<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\CompanyField;
use App\Models\Institute;
use App\Models\Batch;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\JobSeekerFieldPosition;
use App\Models\Course;
use App\Models\Vacancy;
use App\Models\Permission;
use App\Models\UserPermission;
use App\Models\PermissionRole;
use App\Models\UniversityStaff;
use App\Models\JobSeeker;
use App\Models\Country;
use App\Models\Company;
use Exception;
use Illuminate\Http\Request;
use stdClass;
use Redirect;
use Validator;
use Session;
use Storage;
use Image;
use View;
use Str;
use Auth;
use DB;
use DateTime;
use App\Helpers\ConfigUserStatusHelper;
use App\Jobs\SignUpEmailJob;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $search = $request->search;
            $roleFilter = $request->role;
            $typeFilter = $request->type;
            $statusFilter = $request->status;

            $roles = Role::get(['id','name']);
            $data =  User::leftJoin('roles','roles.id','=','users.role')
                    ->select('users.*','roles.name as role_name');
                    // ->whereNull('users.deleted_at');
            if(isset($roleFilter) && $roleFilter != "") {
                $data->where('users.role','=',$roleFilter);
            }

            if(isset($typeFilter) && $typeFilter != "") {
                $data->where('users.type','=',$typeFilter);
            } 
            // else {
            //     $data = $data->where(function ($query) use($typeFilter) {
            //         $query->where('users.type', 'Internal User')
            //                 ->orWhere('users.type', 'University Staff');
            //     });
            // }

            if(isset($statusFilter) && $statusFilter != "") {
                $data->where('users.status','=',$statusFilter);
            }

            if(isset($search) && $search != "")
            {
                $data->where(function ($q) use($search) {
                    $q->where('users.fname', 'LIKE', '%' . $search . '%' )
                        ->orWhere('users.lname', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.oname', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.email', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.gender', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.contact', 'LIKE', '%'.$search.'%')
                        ->orWhere('users.contact_secondary', 'LIKE', '%'.$search.'%');
                });
            }

            if(isset($request->fdate) && $request->fdate != "") {
                $data->whereDate('users.created_at','>=',$request->fdate);
            }

            if(isset($request->tdate) && $request->tdate != "") {
                $data->whereDate('users.created_at','<=',$request->tdate);
            }

            $data = $data->orderBy("users.id", "DESC")->paginate(20);

            return view('admin.users.index',compact('search','roleFilter', 'typeFilter', 'statusFilter','roles'))->with(['data'=>$data]);
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $roles = Role::get(['id','name']);
            $countries = Country::all();
            $institutes = Institute::all();
            $faculties = Faculty::all();
            $departments = Department::all();
            $courses = Course::all();

            return view('admin.users.create', compact('countries', 'departments', 'institutes', 'faculties', 'courses'))->with(['roles'=>$roles]);
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function store(Request $request) {
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'contact' => ['required', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users'],
            'email' => 'required|unique:users',
            'password' => 'required',
            'type' => 'required',
        );

        $customMessages = [
            'firstname.required' => 'First Name cannot be empty',
            'lastname.required' => 'Last Name cannot be empty',
            'contact.required' => 'Primary Contact Number cannot be empty',
            'contact.unique' => 'Primary Contact Number has been already taken. Please try with another one',
            'email.required' => 'Email cannot be empty',
            'email.unique' => 'Email has been already taken. Please try with another one',
            'password.required' => 'Password cannot be empty',
            'type.required' => 'Type need to be selected',
            'contact.regex' => 'Invalid Primary Contact Number',
        ];
       
        if (!is_null($request->contact_secondary)) {
            $rules['contact_secondary'] = ['regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15'];
            $customMessages['contact_secondary.regex'] = 'Invalid Secondary Contact Number';
        }

        if ($request->type == "University Staff") {
            $rules['institute'] = 'required';
            $customMessages['institute.required'] = 'Institute need to be selected';
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = new User();
                $user->type = $request->type;
                // $user->code = Str::random(20);
                $user->fname = $request->firstname;
                $user->lname = $request->lastname;
                $user->oname = $request->othername;
                $user->contact = $request->contact;
                $user->contact_secondary = $request->contact_secondary;
                $user->email = $request->email;
                
                if ($request->type == "Internal User") {
                    $user->role = $request->role;
                } else {
                    $user->role = null;
                }
                $user->password = Hash::make($request->password);
                $user->gender = $request->gender;
                $user->remarks = $request->remarks;
                $user->registered_mode = 'Manual';
                $user->remember_token = Str::random(100);
                $user->save();

                $stringUtil = new \App\Services\StringUtils();
                $user->code = $stringUtil->randomStringGenerator($user->fname, $user->id);
                $user->save();

                if ($request->type == "Internal User") {
                    $permissions = PermissionRole::where('role_id','=',$request->role)->get();
                    foreach ($permissions as $key => $value) {
                        $userPermission = new UserPermission();
                        $userPermission->user_id = $user->id;
                        $userPermission->permission_id = $value->permission_id;
                        $userPermission->save();
                    }
                }

                if ($request->type == "University Staff") {
                    $universityStaff = new UniversityStaff();
                    $universityStaff->user = $user->id;
                    $universityStaff->institute = $request->institute;
                    $universityStaff->faculty = $request->faculty;
                    $universityStaff->department = $request->department;
                    $universityStaff->course = $request->course;
                    $universityStaff->save();
                }

                $details = [
                    'title' => 'Welcome to Placements.lk',
                    'token' => 'https://placements.lk/verify?token='.$user->remember_token,
                    'name' => $user->fname." ".$user->lname
                ];

                try {
                    // \Mail::to($user->email)->send(new \App\Mail\SignUpEmail($details));
                    dispatch(new SignUpEmailJob($user->email,  $details))->delay(now()->addSeconds(2));

                } catch(\Exception $e) {
                    return redirect()->back()->with('error', 'Error while sending email');
                }

                return redirect()->route('users.index')->with('message', 'User has been added successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while creating User');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while creating User');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $code)
    {
        try {
            $sm_op = 0;
            if($request->get('sm_op')) {
                $sm_op = 1;
            }
            $user = DB::table('users as u')->where('u.code', $code)->first();
            if($user->type == "JobSeeker") {
                $jobSeeker = DB::table('job_seekers as j')
                            ->select('j.linkedin', 'j.looking_for', 'j.cv', 'i.name as iname', 'fa.name as fname', 'd.name as dname', 'co.name as cname', 'b.name as bname')
                            ->leftjoin('institutes as i', 'i.id', '=', 'j.institute')
                            ->leftjoin('faculties as fa', 'fa.id', '=', 'j.faculty')
                            ->leftjoin('departments as d', 'd.id', '=', 'j.department')
                            ->leftjoin('courses as co', 'co.id', '=', 'j.course')
                            ->leftjoin('batches as b', 'b.id', '=', 'j.batch')
                            ->where('j.user', $user->id)
                            ->first();
                $user->institute = $jobSeeker->iname;
                $user->faculty = $jobSeeker->fname;
                $user->department = $jobSeeker->dname;
                $user->course = $jobSeeker->cname;
                $user->batch = $jobSeeker->bname;
                $user->linkedin = $jobSeeker->linkedin;
                $user->looking_for = $jobSeeker->looking_for;
                $user->looking_for = $jobSeeker->looking_for;
                $user->cv = $jobSeeker->cv;
            }

            $types = Permission::select('type')->groupBy('type')->get();
            $permissions = Permission::all();
            
            if (!is_null($user->role)) {
                $user->permissions = UserPermission::where('user_id','=', $user->id)->leftJoin('permissions','permissions.id','=','user_permissions.permission_id')->select('permissions.*')->get();
            }

            return View::make('admin.users.show', compact('user','types','permissions','sm_op'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function edit($code)
    {
        try {
            $user_obj = User::where('code', $code)->first();
            if (is_null($user_obj) or empty($user_obj)) {
                return redirect()->route('dashboard')->with('error', 'Invalid User');
            }
            $userType = $user_obj->type;

            $selectArray = array('u.id', 'u.code', 'u.type', 'u.fname', 'u.lname', 'u.oname', 'u.email', 
            'u.contact', 'u.contact_secondary', 'u.role', 'u.gender', 'u.image', 'u.status', 'u.remarks');
            
            $types = Permission::select('type')->groupBy('type')->get();
            $roles = Role::get(['id','name']);
            $permissions = Permission::all();

            $user = DB::table('users as u');
            if ($userType == "University Staff") {
                $user = $user->leftjoin('university_staff as us', 'u.id', '=', 'us.user');
                array_push($selectArray, 'us.institute', 'us.faculty', 'us.department', 'us.course');
            } else if ($userType == "Company") {
                $user = $user->leftjoin('companies as c', 'u.id', '=', 'c.user');
                array_push($selectArray, 'c.logo', 'c.short_description', 'c.long_description', 'c.website', 'c.facebook', 'c.linkedin', 'c.twitter', 'c.instagram', 'c.country');
            } else if ($userType == "JobSeeker") {
                $user = $user->leftjoin('job_seekers as j', 'u.id', '=', 'j.user');
                array_push($selectArray, 'j.institute', 'j.faculty', 'j.department', 'j.course', 'j.batch', 'j.linkedin');
            }
            
            $user = $user->select($selectArray)->where('u.code', $code)->first();

            if (!is_null($user->role)) {
                $user->permissions = UserPermission::where('user_id','=',$user->id)->leftJoin('permissions','permissions.id','=','user_permissions.permission_id')->select('permissions.*')->get();
            }
            $roles = Role::get(['id','name']);
            $countries = Country::all();
            $institutes = Institute::all();
            $faculties = Faculty::all();
            $departments = Department::all();
            $courses = Course::all();
            $batches = Batch::all();

            return View::make('admin.users.edit', compact('user', 'userType', 'types','permissions','roles', 'batches', 'countries', 'departments', 'institutes', 'faculties', 'courses'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function update(Request $request, $id)
    {
        $userType = $request->type;

        $rules = array(
            'fname' => 'required',
            'contact' => ['required', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users,contact,' . $id],
            'email' => 'required|unique:users,email,' . $id,
            'type' => 'required',
        );

        $customMessages = [
            'fname.required' => 'First Name cannot be empty',
            'contact.required' => 'Primary Contact Number cannot be empty',
            'contact.unique' => 'Primary Contact Number has been already taken. Please try with another one',
            'email.required' => 'Email cannot be empty',
            'email.unique' => 'Email has been already taken. Please try with another one',
            'type.required' => 'Type need to be selected',
            'contact.regex' => 'Invalid Primary Contact Number',
        ];

        if (!is_null($request->contact_secondary)) {
            $rules['contact_secondary'] = ['regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15'];
            $customMessages['contact_secondary.regex'] = 'Invalid Secondary Contact Number';
        }
        
        if ($userType != "Company") {
            $rules['lname'] = 'required';
            $customMessages['lname.required'] = 'Last Name cannot be empty';
        }
        if ($userType == "University Staff") {
            $rules['institute'] = 'required';
            $customMessages['institute.required'] = 'Institute need to be selected';
        } else if ($userType == "Company") {
            $rules['country'] = 'required';
            $customMessages['country.required'] = 'Country need to be selected';
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = User::find($id);
                $user->fname = $request->fname;
                $user->lname = $request->lname;
                $user->oname = $request->oname;
                $user->contact = $request->contact;
                $user->contact_secondary = $request->contact_secondary;
                $user->email = $request->email;
                
                if ($userType == "Internal User") {
                    $user->role = $request->role;
                } else {
                    $user->role = null;
                }
                if($request->password != '') {
                    $user->password = Hash::make($request->password);
                }
                $user->gender = $request->gender;
                $user->remarks = $request->remarks;
                $user->save();

                $de = UserPermission::where('user_id','=', $id)->delete();
                $permissions = $request->permission;
                if (!is_null($permissions) && !empty($permissions)) {
                    foreach ($permissions as $key => $value) {
                        $userPermission = new UserPermission();
                        $userPermission->user_id = $id;
                        $userPermission->permission_id = $value;
                        $userPermission->save();
                    }
                }

                if ($userType == "University Staff") {
                    $universityStaff = UniversityStaff::where('user', $id)->first();
                    $universityStaff->institute = $request->institute;
                    $universityStaff->faculty = $request->faculty;
                    $universityStaff->department = $request->department;
                    $universityStaff->course = $request->course;
                    $universityStaff->save();
                } else if ($userType == "Company") {
                    $company = Company::where('user', $id)->first();
                    $company->short_description = $request->short_description;
                    $company->long_description = $request->long_description;
                    $company->website = $request->website;
                    $company->facebook = $request->facebook;
                    $company->linkedin = $request->linkedin;
                    $company->twitter = $request->twitter;
                    $company->instagram = $request->instagram;
                    $company->country = $request->country;
                    //Logo
                    if ($request->hasFile('logo')) {
                        $image      = $request->file('logo');
                        $fileName   = time() . '.' . $image->getClientOriginalExtension();
                        
                        $img = Image::make($image->getRealPath());
                        $img->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();                 
                        });
                        $img->stream();
                        Storage::disk('local')->put('/UserImages'.'/'.$fileName, $img, 'public');
                        $user->image = $fileName;
                        $user->save();

                        Storage::disk('local')->put('/Company/LogoImages'.'/'.$fileName, $img, 'public');
                        $img->resize(100, null, function ($constraint) {
                            $constraint->aspectRatio();                 
                        });
                        $img->stream();
                        Storage::disk('local')->put('/Company/LogoImages/small'.'/'.$fileName, $img, 'public');
                        $company->logo = $fileName;
                    }
                    $company->save();
                } else if ($userType == "JobSeeker") {
                    $jobSeeker = JobSeeker::where('user', $id)->first();
                    $jobSeeker->institute = $request->institute;
                    $jobSeeker->faculty = $request->faculty;
                    $jobSeeker->department = $request->department;
                    $jobSeeker->course = $request->course;
                    $jobSeeker->batch = $request->batch;
                    $jobSeeker->save();
                }

                Session::flash('message', 'User has been updated successfully');
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Error while updating User');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Error while updating User');
            }
            return redirect()->route('users.index');
        }
    }


    public function updateProfileBasic(Request $request)
    {

        DB::beginTransaction();

        try {
            $id = Auth::user()->id;
           
            $rules = array(
                'profile_image' => 'mimes:jpeg,jpg,png,gif|max:2048',
                'fname'=>'required',
                'contact'=>['required', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users,contact,' . $id],
                'contact_secondary'=>['nullable', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users,contact_secondary,' . $id],
                'fname'=>'required',
            );
            $customMessages = [
                'profile_image.mimes' => 'Profile Image can be a type of jpeg/jpg/png/gif',
                'profile_image.max' => 'Profile Image can be of 2MB or lesser',
            ];
    
            $customMessages['contact_secondary.regex'] = 'Invalid Secondary Contact Number';
            $customMessages['fname.required'] = 'First Name cannot be empty';
            $customMessages['contact.required'] = 'Primary Contact Number cannot be empty';
            $customMessages['contact.unique'] = 'Primary Contact Number has been already taken. Please try with another one';
            $customMessages['contact.regex'] = 'Invalid Primary Contact Number';
            $rules['country'] = 'required';
            $customMessages['country.required'] = 'Country need to be selected';
            $rules['lname'] = 'required';
            // $rules['jobseeker_type'] = 'required';
            $customMessages['lname.required'] = 'Last Name cannot be empty';
            // $customMessages['jobseeker_type.required'] = 'Please select whether you are university student';
    
    
    
            $validator = Validator::make($request->all(), $rules, $customMessages);
            if ($validator->fails()) {
                    return response()->json([
                        "success" => false,
                        "message" => 'Requested',
                        "error" => $validator->messages()
                    ],422);
            }
    
    
            $user = User::find($id);
            // $user->type = $request->type;
            $user->fname = $request->fname;
            $user->lname = $request->lname;
            $user->oname = $request->oname;
            $user->contact = $request->contact;
            $user->contact_secondary = $request->contact_secondary;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->remarks = $request->remarks;
            $user->found_mode = $request->found_mode;
            
            //Profile Image
            if ($request->hasFile('profile_image')) {
                $image      = $request->file('profile_image');
                $fileName   = time() . '.' . $image->getClientOriginalExtension();
                
                $img = Image::make($image->getRealPath());
                $img->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();                 
                });
                $img->stream();
    
                Storage::disk('local')->put('/UserImages'.'/'.$fileName, $img, 'public');
                $user->image = $fileName;
            } else {
                $is_delete = $request['delete-dp'];
                if (!is_null($is_delete) && $is_delete == '1') {
                    $user->image = null;
                }
            }
    
    
            $user->save();
    
            $jobSeeker = JobSeeker::where('user', $id)->first();
            $jobSeeker->looking_for = $request->looking_for;
            $jobSeeker->linkedin = $request->linkedin;
            $jobSeeker->country = $request->country;
            $jobSeeker->latitute = $request->latitute;
            $jobSeeker->longitute = $request->longitute;
            $jobSeeker->is_to_notify = $request->is_to_notify;
            $jobSeeker->save();

            DB::commit();

            return response()->json([
                "success" => true,
                "message" => "Operation successful.",
                "data" => "Operation successful.",
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
                return response()->json([
                    "success" => false,
                    "message" => "Operation failed.",
                    "error" => $e->getMessage()
                ],500);
        }
    }

    public function updateProfilePassword(Request $request)
    {
            $rules = [
                'cpassword' => ['required',new MatchOldPassword],
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required',
            ];
            $customMessages['cpassword.required'] = "Current password can't be empty";
            $customMessages['password.required'] = "New password can't be empty";
            $customMessages['password.min'] = "New password must be at least 6 characters";
            $customMessages['password_confirmation.required'] = "Confirm Password can't be empty";


            
            
            $validator = Validator::make($request->all(), $rules, $customMessages);
            
            
            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "message" => 'Requested',
                    "error" => $validator->messages()
                ],422);
            }
            

            $new_pass = $request->password;
            $newPass = Hash::make($new_pass);
            $user = Auth::user();

            $user = User::find($user->id);
            $user->password = $newPass;
            $user->save();

            return response()->json([
                "success" => true,
                "message" => "Operation successful.",
                "data" => "Operation successful.",
            ],200);
    }

    public function updateProfilePrimaryEducation(Request $request)
    {
        $rules['jobseeker_type'] = 'required';
        $customMessages['jobseeker_type.required'] = 'Please select whether you are university student';

        $validator = Validator::make($request->all(), $rules, $customMessages);

        $id = Auth::user()->id;
        $jobSeeker = JobSeeker::where('user', $id)->first();
        $jobSeeker->jobseeker_type = $request->jobseeker_type;
        $jobSeeker->institute = $request->institute;
        $jobSeeker->faculty = $request->faculty;
        $jobSeeker->department = $request->department;
        $jobSeeker->course = $request->course;
        $jobSeeker->batch = $request->batch;
        $jobSeeker->save();

        return response()->json([
            "success" => true,
            "message" => "Operation successful.",
            "data" => "Operation successful.",
        ],200);

    }

    public function editProfile(Request $request) {
        try {
            $user_id = Auth::user()->id;
            $selectArray = array('u.fname', 'u.type', 'u.lname', 'u.oname', 'u.email', 'u.contact', 'u.contact_secondary', 'u.gender', 'u.image', 'u.remarks', 'u.registered_mode', 'u.found_mode', 'u.timezone',
            DB::raw("CONCAT('".url('/storage/UserImages')."/', u.image) AS image_url")
        );
            $userDetails = DB::table('users as u')
                ->where('u.id', $user_id);
            $userType = $userDetails->first()->type;
            $gender = $userDetails->first()->gender;
            $job_seeker = null;
            if ($userType == "JobSeeker") {
                $job_seeker = JobSeeker::where('user', $user_id)->first();
                if (is_null($job_seeker) or empty($job_seeker)) {

                    if ($request->is('api*')) {
                        return response()->json([
                            "success" => false,
                            "message" => "You are not a Job Seeker.",
                            "error" => "You are not a Job Seeker."
                        ],404);
                    }

                    return redirect()->route('dashboard')->with('error', 'You are not a Job Seeker');
                }
                array_push($selectArray, 'js.institute', 'js.faculty', 'js.department', 'js.course', 'js.batch', 'js.jobseeker_type', 'js.looking_for', 'js.linkedin', 'js.cv', 'js.country', 'js.latitute', 'js.longitute', 'js.is_to_notify',
                DB::raw("CONCAT('".url('/storage/CurriculamVitaes')."/', js.cv) AS cv_url")
                );
                $userDetails = $userDetails->leftjoin('job_seekers as js', 'js.user', '=', 'u.id');
            } else if ($userType == "Company") {
                $company = Company::where('user', $user_id)->first();
                if (is_null($company) or empty($company)) {

                    if ($request->is('api*')) {
                        return response()->json([
                            "success" => false,
                            "message" => "You are not a Job Company.",
                            "error" => "You are not a Company."
                        ],404);
                    }

                    return redirect()->route('dashboard')->with('error', 'You are not a Company');
                }
                array_push($selectArray, 'c.logo', 'c.cover_image', 'c.short_description', 'c.long_description', 'c.website', 'c.facebook', 'c.linkedin', 'c.twitter', 'c.instagram', 'c.country', 'c.latitute', 'c.longitute', 'c.is_to_notify');
                $userDetails = $userDetails->leftjoin('companies as c', 'c.user', '=', 'u.id');
            } else if ($userType == "University Staff") {
                $university_staff = UniversityStaff::where('user', $user_id)->first();
                if (is_null($university_staff) or empty($university_staff)) {

                    if ($request->is('api*')) {
                        return response()->json([
                            "success" => false,
                            "message" => "You are not a Job University Staff.",
                            "error" => "You are not a University Staff."
                        ],404);
                    }

                    return redirect()->route('dashboard')->with('error', 'You are not a University Staff');
                }
                array_push($selectArray, 'us.institute', 'us.faculty', 'us.department', 'us.course');
                $userDetails = $userDetails->leftjoin('university_staff as us', 'us.user', '=', 'u.id');
            }

            $userDetails = $userDetails->select($selectArray)->first();

            $countries = Country::all();

            //Jobseeker fields and positions
            $fields = array();
            $positions = array();
            $selectedFields = array();
            $selectedPositions = array();

            $fields = DB::table('fields')->get();
            $positions = DB::table('positions')->get();
            if ($userType == "JobSeeker") {
                $jobSeeker = JobSeeker::where('user', $user_id)->first();
                $jobSeekerFieldPositions = JobSeekerFieldPosition::where('job_seeker', $jobSeeker->id)->get();
                
                foreach($jobSeekerFieldPositions as $jobSeekerFieldPosition) {
                    $position = $jobSeekerFieldPosition->position;
                    $field = $jobSeekerFieldPosition->field;
                    if (is_null($position) or empty($position)) {
                        array_push($selectedFields, "field_".$field);
                    } else {
                        array_push($selectedPositions, "position_".$field."_".$position);
                    }
                }
            } else if($userType == "Company") {
                $company = Company::where('user', Auth::user()->id)->first();
                $companyFields = CompanyField::where('company', $company->id)->get();
                
                foreach($companyFields as $companyField) {
                    if (is_null($companyField->position) or empty($companyField->position)) {
                        array_push($selectedFields, "field_".$companyField->field);
                    } else {
                        array_push($selectedPositions, "position_".$companyField->field."_".$companyField->position);
                    }
                }
            }

            $institutes = Institute::all();
            $faculties = Faculty::all();
            $departments = Department::all();
            $courses = Course::all();
            $batches = Batch::all();
            $companies = DB::table('companies as com')
                            ->select('com.id as id', 'u.fname as name')
                            ->leftjoin('users as u', 'u.id', '=', 'com.user')
                            ->get();

            $experience_medias = null;
            $education_medias = null;
            $experiences = array();
            $educations = array();

            if ($userType == "JobSeeker") {
                $educations = DB::table('job_seeker_qualifications as jsq')
                        ->select('jsq.*', 'i.logo', 'i.name as institute', 'fa.name as faculty', 'd.name as department', 'co.name as course')
                        ->leftjoin('institutes as i', 'i.id', '=', 'jsq.institute')
                        ->leftjoin('faculties as fa', 'fa.id', '=', 'jsq.faculty')
                        ->leftjoin('departments as d', 'd.id', '=', 'jsq.department')
                        ->leftjoin('courses as co', 'co.id', '=', 'jsq.course')
                        ->where('jsq.job_seeker', $job_seeker->id)->get();
                foreach($educations as $education) {
                    if(is_null($education->institute) or empty($education->institute)) {
                        $education->institute = $education->institute_name;
                    }
                    if(is_null($education->course) or empty($education->course)) {
                        $education->course = $education->course_name;
                    }
                }

                $experiences = DB::table('job_seeker_experiences as jse')
                    ->select('jse.*', 'c.logo', 'u.fname as company', 'p.name as position')
                    ->leftjoin('companies as c', 'c.id', '=', 'jse.company')
                    ->leftjoin('users as u', 'u.id', '=', 'c.user')
                    ->leftjoin('positions as p', 'p.id', '=', 'jse.position')
                    ->where('jse.job_seeker', $job_seeker->id)->get();
                
                $monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

                foreach($experiences as $experience) {
                    if(is_null($experience->company) or empty($experience->company)) {
                        $experience->company = $experience->company_name;
                    }
                    if(is_null($experience->position) or empty($experience->position)) {
                        $experience->position = $experience->position_name;
                    }
                    if(!is_null($experience->month_from)) {
                        $experience->month_from = $monthNames[$experience->month_from];
                    }
                    if(!is_null($experience->month_to)) {
                        $experience->month_to = $monthNames[$experience->month_to];
                    }
                }

                $experience_medias = DB::table('job_seeker_experience_media as em')
                ->select('em.*', 
                        DB::raw("CONCAT('".url('/storage/Experience/Medias')."/', em.file_url) AS file_url_mobile"))
                        ->leftjoin('job_seeker_experiences as e', 'e.id', 'em.job_seeker_experience')
                        ->where('e.job_seeker', $job_seeker->id)->get();
                $education_medias = DB::table('job_seeker_qualification_media as qm')
                        ->select('qm.*', 
                        DB::raw("CONCAT('".url('/storage/Education/Medias')."/', qm.file_url) AS file_url_mobile"))
                        ->leftjoin('job_seeker_qualifications as q', 'q.id', 'qm.job_seeker_qualification')
                        ->where('q.job_seeker', $job_seeker->id)->get();
            }

            $datas = compact('gender', 'experience_medias', 'education_medias', 'experiences', 'educations', 'companies', 'departments', 'institutes', 'faculties', 'courses', 'batches', 'countries', 'userType', 'fields', 'positions', 'selectedFields', 'selectedPositions');

            if ($request->is('api*')) {
                $datas['userDetails'] = $userDetails;
                return response()->json([
                    "success" => true,
                    "message" => "Operation successful.",
                    "data" => $datas,
                ],200);
            }

            return view('common.edit-profile', $datas)->with('user', $userDetails);
        } catch(\Exception $e) {
            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => "Operation failed.",
                    "error" => $e->getMessage()
                ],500);
            }
            return redirect()->route('error');
        } catch(\Throwable $e) {
            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => "Operation failed.",
                    "error" => $e->getMessage()
                ],500);
            }
            return redirect()->route('error');
        }
    }

    public function updateProfile(Request $request) {
        $user_id = Auth::user()->id;
        $userType = DB::table('users as u')->where('u.id', $user_id)->first()->type;
        $active_tab = $request->active_tab;

        $rules = array(
            'profile_image' => 'mimes:jpeg,jpg,png,gif|max:2048',
        );
        $customMessages = [
            'profile_image.mimes' => 'Profile Image can be a type of jpeg/jpg/png/gif',
            'profile_image.max' => 'Profile Image can be of 2MB or lesser',
        ];

        if ($active_tab == 'basic-cont') {
            $rules['fname'] = 'required';
            $rules['contact'] = ['required', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users,contact,' . $user_id];
            if (!is_null($request->contact_secondary)) {
                $rules['contact_secondary'] = ['regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15'];
                $customMessages['contact_secondary.regex'] = 'Invalid Secondary Contact Number';
            }
            $customMessages['fname.required'] = 'First Name cannot be empty';
            $customMessages['contact.required'] = 'Primary Contact Number cannot be empty';
            $customMessages['contact.unique'] = 'Primary Contact Number has been already taken. Please try with another one';
            $customMessages['contact.regex'] = 'Invalid Primary Contact Number';
            if ($userType == "JobSeeker" or $userType == "Company") {
                $rules['country'] = 'required';
                $customMessages['country.required'] = 'Country need to be selected';
            }
            if ($userType == "Company") {
                $rules['cover_image'] = 'mimes:jpeg,jpg,png,gif';
                $rules['logo'] = 'mimes:jpeg,jpg,png,gif|max:2048';
                $customMessages['cover_image.mimes'] = 'Cover Image can be a type of jpeg/jpg/png/gif';
                $customMessages['logo.mimes'] = 'Logo Image can be a type of jpeg/jpg/png/gif';
                $customMessages['logo.max'] = 'Logo Image can be of 2MB or lesser';
            }
            if ($userType == "JobSeeker") {
                $rules['lname'] = 'required';
                $rules['jobseeker_type'] = 'required';
                $customMessages['lname.required'] = 'Last Name cannot be empty';
                $customMessages['jobseeker_type.required'] = 'Please select whether you are university student';
            } else if ($userType == "University Staff") {
                $rules['lname'] = 'required';
                $rules['staff_institute'] = 'required';
                $customMessages['lname.required'] = 'Last Name cannot be empty';
                $customMessages['staff_institute.required'] = 'Institute need to be selected';
            }
        } else if ($active_tab == 'password-cont') {
            $rules['cpassword'] = 'required';
            $rules['npassword'] = 'required|min:6';
            $rules['npassword2'] = 'required|min:6';
            $customMessages['cpassword.required'] = "Current password can't be empty";
            $customMessages['npassword.required'] = "New password can't be empty";
            $customMessages['npassword.min'] = "New password must be at least 6 characters";
            $customMessages['npassword2.required'] = "Confirm Password can't be empty";
            $customMessages['npassword2.min'] = "Confirm password must be at least 6 characters";
        } else if ($active_tab == 'qualifications-cont' && $userType == "JobSeeker") {
            $rules['jobseeker_type'] = 'required';
            $customMessages['jobseeker_type.required'] = 'Please select whether you are university student';
        } else if ($active_tab == 'cv-cont' && $userType == "JobSeeker") {
            $rules['cv'] = 'required|mimes:pdf|max:10240';
            $customMessages['cv.required'] = 'CV need to be uploaded';
            $customMessages['cv.mimes'] = 'CV should be a pdf';
            $customMessages['cv.max'] = 'CV can be of 10MB or lesser';
        }

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {

            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => 'Requested',
                    "error" => $validator->messages()
                ],422);
            }

            Session::flash('error', $validator->messages()->first());
            return redirect('/edit-profile#'.$active_tab)->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = User::find($user_id);
                if ($active_tab == 'basic-cont') {
                    $user->fname = $request->fname;
                    if ($userType != "Company") {
                        $user->lname = $request->lname;
                    }
                    $user->oname = $request->oname;
                    $user->contact = $request->contact;
                    $user->contact_secondary = $request->contact_secondary;
                    // $user->email = $request->email;
                    $user->gender = $request->gender;
                    $user->remarks = $request->remarks;
                    $user->found_mode = $request->found_mode;
                
                    //Profile Image
                    if ($request->hasFile('profile_image')) {
                        $image      = $request->file('profile_image');
                        $fileName   = time() . '.' . $image->getClientOriginalExtension();
                        
                        $img = Image::make($image->getRealPath());
                        $img->resize(500, null, function ($constraint) {
                            $constraint->aspectRatio();                 
                        });
                        $img->stream();
            
                        Storage::disk('local')->put('/UserImages'.'/'.$fileName, $img, 'public');
                        $user->image = $fileName;

                        if ($userType == "Company") {
                            $company = Company::where('user', $user_id)->first();
                            Storage::disk('local')->put('/Company/LogoImages'.'/'.$fileName, $img, 'public');

                            $img->resize(100, null, function ($constraint) {
                                $constraint->aspectRatio();                 
                            });
                            $img->stream();
                
                            Storage::disk('local')->put('/Company/LogoImages/small'.'/'.$fileName, $img, 'public');

                            $company->logo = $fileName;
                            $company->save();
                        }
                    } else {
                        $is_delete = $request['delete-dp'];
                        if (!is_null($is_delete) && $is_delete == '1') {
                            $user->image = null;
                        }
                    }
                } else if ($active_tab == 'password-cont') {
                    $current_pass = $request->cpassword;
                    $new_pass = $request->npassword;
                    $confirm_pass = $request->npassword2;
                    if (!is_null($current_pass) && !empty($current_pass)) {
                        if (!is_null($new_pass) && !empty($new_pass) && !is_null($confirm_pass) && !empty($confirm_pass)) {
                            if ($new_pass == $confirm_pass) {
                                $newPass = Hash::make($new_pass);
                                $exist_pass = $user->password;
                                if (Hash::check($current_pass, $exist_pass)) {
                                    if ($new_pass != $current_pass) {
                                        $user->password = $newPass;
                                    } else {
                                        
                                        if ($request->is('api*')) {
                                            return response()->json([
                                                "success" => false,
                                                "message" => 'Current password and New password cannot be same',
                                                "error" => []
                                            ],422);
                                        }

                                        return redirect('/edit-profile#'.$active_tab)->with('error', 'Current password and New password cannot be same'); 
                                    }
                                } else {

                                    if ($request->is('api*')) {
                                        return response()->json([
                                            "success" => false,
                                            "message" => 'Invalid Current password provided',
                                            "error" => []
                                        ],422);
                                    }

                                    return redirect('/edit-profile#'.$active_tab)->with('error', 'Invalid Current password provided'); 
                                }
                            } else {

                                if ($request->is('api*')) {
                                    return response()->json([
                                        "success" => false,
                                        "message" => 'New password and confirm password need to be same',
                                        "error" => []
                                    ],422);
                                }

                                return redirect('/edit-profile#'.$active_tab)->with('error', 'New password and confirm password need to be same');
                            }
                        } else {

                            if ($request->is('api*')) {
                                return response()->json([
                                    "success" => false,
                                    "message" => 'Please provide new/confirm password',
                                    "error" => []
                                ],422);
                            }

                            return redirect('/edit-profile#'.$active_tab)->with('error', 'Please provide new/confirm password');
                        }
                    }
                }
                $user->save();

                if ($userType == "JobSeeker") {
                    $jobSeeker = JobSeeker::where('user', $user_id)->first();
                    if ($active_tab == 'basic-cont') {
                        $jobSeeker->looking_for = $request->looking_for;
                        $jobSeeker->linkedin = $request->linkedin;
                        $jobSeeker->country = $request->country;
                        $jobSeeker->latitute = $request->latitute;
                        $jobSeeker->longitute = $request->longitute;
                        $jobSeeker->is_to_notify = $request->is_to_notify;
                    } else if ($active_tab == 'qualifications-cont') {
                        $jobSeeker->jobseeker_type = $request->jobseeker_type;
                        $jobSeeker->institute = $request->institute;
                        $jobSeeker->faculty = $request->faculty;
                        $jobSeeker->department = $request->department;
                        $jobSeeker->course = $request->course;
                        $jobSeeker->batch = $request->batch;
                    } else if ($active_tab == 'cv-cont') {
                        if ($request->hasfile('cv')) {
                            try {
                                $cv = $request->file('cv');
                                $fileName = time() . '_' . $cv->getClientOriginalName();
                                
                                $cv->move(storage_path().'/app/public/CurriculamVitaes'.'/', $fileName);
                                $jobSeeker->cv = $fileName;
                            } catch(\Exception $e) {
                                Session::flash('error', 'Error while uploading CV');
                                return redirect('/edit-profile#'.$active_tab);
                            } catch(\Throwable $e) {
                                Session::flash('error', 'Error while uploading CV');
                                return redirect('/edit-profile#'.$active_tab);
                            }
                        }
                    }
                    $jobSeeker->save();

                    if ($active_tab == 'fields-cont') {
                        $datas = $request->all();
                        $jobSeekerFieldPosition = JobSeekerFieldPosition::where('job_seeker', $jobSeeker->id)->delete();

                        foreach ($datas as $key => $value) {
                            if (str_contains($key, 'position') or str_contains($key, 'field')) {
                                $x = explode('_', $key);
                                $jobSeekerFieldPosition = new JobSeekerFieldPosition();
                                $jobSeekerFieldPosition->job_seeker = $jobSeeker->id;
                                if (str_contains($key, 'field')) {
                                    $fieldId = $x[1];
                                    $jobSeekerFieldPosition->field = $fieldId;
                                    $jobSeekerFieldPosition->save();
                                } else if (str_contains($key, 'position')) {
                                    $fieldId = $x[1];
                                    $jbfieldPosition = JobSeekerFieldPosition::where('job_seeker', $jobSeeker->id)
                                                    ->where('field', $fieldId)
                                                    ->whereNull('position')->first();
                                    if (is_null($jbfieldPosition) or empty($jbfieldPosition)) {
                                        $positionId = $x[2];
                                        $jobSeekerFieldPosition->field = $fieldId;
                                        $jobSeekerFieldPosition->position = $positionId;
                                        $jobSeekerFieldPosition->save();
                                    }
                                }
                            }
                        }
                    }
                } else if ($userType == "Company") {
                    $company = Company::where('user', $user_id)->first();
                    if ($active_tab == 'basic-cont') {
                        $company->short_description = $request->short_description;
                        $company->long_description = $request->long_description;
                        $company->website = $request->website;
                        $company->facebook = $request->facebook;
                        $company->linkedin = $request->linkedin;
                        $company->twitter = $request->twitter;
                        $company->instagram = $request->instagram;
                        $company->country = $request->country;
                        $company->latitute = $request->latitute;
                        $company->longitute = $request->longitute;
                        $company->is_to_notify = $request->is_to_notify;
                    
                        //Logo
                        // if ($request->hasFile('logo')) {
                        //     $image      = $request->file('logo');
                        //     $fileName   = time() . '.' . $image->getClientOriginalExtension();
                            
                        //     $img = Image::make($image->getRealPath());
                        //     $img->resize(1000, null, function ($constraint) {
                        //         $constraint->aspectRatio();                 
                        //     });
                        //     $img->stream();
                
                        //     Storage::disk('local')->put('/Company/LogoImages'.'/'.$fileName, $img, 'public');
                        //     $company->logo = $fileName;
                        // }

                        //Cover Image
                        if ($request->hasFile('cover_image')) {
                            $image      = $request->file('cover_image');
                            $fileName   = time() . '.' . $image->getClientOriginalExtension();
                            
                            $img = Image::make($image->getRealPath());
                            $img->resize(1000, null, function ($constraint) {
                                $constraint->aspectRatio();                 
                            });
                            $img->stream();
                
                            Storage::disk('local')->put('/Company/CoverImages'.'/'.$fileName, $img, 'public');
                            $company->cover_image = $fileName;
                        }
                        $company->save();
                    }

                    if ($active_tab == 'fields-cont') {
                        $datas = $request->all();
                        $companyField = CompanyField::where('company', $company->id)->delete();

                        foreach ($datas as $key => $value) {
                            if (!str_contains($key, 'token')) {
                                $x = explode('_', $key);
                                $companyField = new CompanyField();
                                $companyField->company = $company->id;
                                if (str_contains($key, 'field')) {
                                    $fieldId = $x[1];
                                    $companyField->field = $fieldId;
                                    $companyField->save();
                                } else if (str_contains($key, 'position')) {
                                    $fieldId = $x[1];
                                    $fieldPosition = CompanyField::where('company', $company->id)
                                                    ->where('field', $fieldId)
                                                    ->whereNull('position')->first();
                                    if (is_null($fieldPosition) or empty($fieldPosition)) {
                                        $positionId = $x[2];
                                        $companyField->field = $fieldId;
                                        $companyField->position = $positionId;
                                        $companyField->save();
                                    }
                                }
                            }
                        }
                    }
                } else if ($userType == "University Staff") {
                    $university_staff = UniversityStaff::where('user', $user_id)->first();
                    $university_staff->institute = $request->staff_institute;
                    $university_staff->faculty = $request->staff_faculty;
                    $university_staff->department = $request->staff_department;
                    $university_staff->course = $request->staff_course;
                    $university_staff->save();
                }

                if ($request->is('api*')) {
                    return response()->json([
                        "success" => true,
                        "message" => $msg,
                        "data" => 'Profile has been updated successfully.',
                    ],200);
                }

                Session::flash('message', 'Profile has been updated successfully.');
            } catch(\Exception $e) {

                if ($request->is('api*')) {
                    return response()->json([
                        "success" => false,
                        "message" => 'Operation failed. Please try again.',
                        "error" => []
                    ],500);
                }

                Session::flash('error', 'Operation failed. Please try again.');
                return redirect('/edit-profile#'.$active_tab);
            } catch(\Throwable $e) {

                if ($request->is('api*')) {
                    return response()->json([
                        "success" => false,
                        "message" => 'Operation failed. Please try again.',
                        "error" => []
                    ],500);
                }

                Session::flash('error', 'Operation failed. Please try again.');
                return redirect('/edit-profile#'.$active_tab);
            }
            return redirect('/edit-profile#'.$active_tab);
        }
    }


    public function updateCV(Request $request)
    {
        try {
            $rules = [];
            $customMessages = [];
            $rules['cv'] = 'required|mimes:pdf|max:10240';
            $customMessages['cv.required'] = 'CV need to be uploaded';
            $customMessages['cv.mimes'] = 'CV should be a pdf';
            $customMessages['cv.max'] = 'CV can be of 10MB or lesser';
            $validator = Validator::make($request->all(), $rules, $customMessages);

            $id = Auth::user()->id;
            $jobSeeker = JobSeeker::where('user', $id)->first();
    
            $cv = $request->file('cv');
            $fileName = time() . '_' . $cv->getClientOriginalName();
            
            $cv->move(storage_path().'/app/public/CurriculamVitaes'.'/', $fileName);
            $jobSeeker->cv = $fileName;
            $jobSeeker->save();

            return response()->json([
                "success" => true,
                "message" => 'success',
                "data" => 'success',
            ],200);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Operation failed.",
                "error" => $e->getMessage()
            ],500);
        } catch(\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Operation failed.",
                "error" => $e->getMessage()
            ],500);
        }
    }


    public function downloadCv(Request $request)
    {
        $filename = Auth::user()->cv;
        // Check if file exists in app/storage/file folder
        $file_path = asset('storage/CurriculamVitaes/'.$filename);
        $headers = array(
            'Content-Type: csv',
            'Content-Disposition: attachment; filename='.$filename,
        );
        if ( file_exists( $file_path ) ) {
            // Send Download
            return  response()->download( $file_path, $filename, $headers );
        } else {
            // Error
            return response()->json([
                "success" => false,
                "message" => "Requested file does not exist on our server!",
                "error" => "Requested file does not exist on our server!"
            ],500);
        }
    }

    public function updateFields(Request $request)
    {
        try {
            $user_id = Auth::user()->id;
            $jobSeeker = JobSeeker::where('user', $user_id)->first();
            $datas = $request->selected;
            $jobSeekerFieldPosition = JobSeekerFieldPosition::where('job_seeker', $jobSeeker->id)->delete();
            foreach ($datas as $key => $value) {
                if (str_contains($value, 'position') or str_contains($value, 'field')) {
                    $x = explode('_', $value);
                    $jobSeekerFieldPosition = new JobSeekerFieldPosition();
                    $jobSeekerFieldPosition->job_seeker = $jobSeeker->id;
                    if (str_contains($value, 'field')) {
                        
                        $fieldId = $x[1];
                        $jobSeekerFieldPosition->field = $fieldId;
                        $jobSeekerFieldPosition->save();
                    } else if (str_contains($value, 'position')) {
                        $fieldId = $x[1];
                        $jbfieldPosition = JobSeekerFieldPosition::where('job_seeker', $jobSeeker->id)
                                        ->where('field', $fieldId)
                                        ->whereNull('position')->first();
                        if (is_null($jbfieldPosition) or empty($jbfieldPosition)) {
                            $positionId = $x[2];
                            $jobSeekerFieldPosition->field = $fieldId;
                            $jobSeekerFieldPosition->position = $positionId;
                            $jobSeekerFieldPosition->save();
                        }
                    }
                }
            }
            
            return response()->json([
                "success" => true,
                "message" => 'success',
                "data" => 'success',
            ],200);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Operation failed.",
                "error" => $e->getMessage()
            ],500);
        } catch(\Throwable $e) {
            return response()->json([
                "success" => false,
                "message" => "Operation failed.",
                "error" => $e->getMessage()
            ],500);
        }
    }
    
    public function terminateMyAccount(Request $request)
    {
        try{
            $active_tab = 'terminate-cont';
            $user_id = Auth::user()->id;
            $user = User::where('id', $user_id)->whereNull('deleted_at')->first();
            if (is_null($user) or empty($user)) {
                session()->flush();
                if ($request->is('api*')) {
                    return response()->json([
                        "success" => true,
                        "message" => 'success',
                        "data" => 'success',
                    ],200);
                }
                return Redirect::to('/login');
            }
            $userType = $user->type;
            $user->email = "del_". time() . "_" .$user->email;
            $user->status = 0;
            $user->deleted_at = new DateTime();
            $user->save();

            if ($userType == "JobSeeker") {
                JobSeeker::where('user', $user_id)->delete();
            } else if ($userType == "Company") {
                $company = Company::where('user', $user_id)->first();
                Vacancy::where('company', $company->id)->update(['deleted_at' => new DateTime()]);
                Company::where('user', $user_id)->delete();
            } else if ($userType == "University Staff") {
                UniversityStaff::where('user', $user_id)->delete();
            }

            session()->flush();
            if ($request->is('api*')) {
                return response()->json([
                    "success" => true,
                    "message" => 'success',
                    "data" => 'success',
                ],200);
            }
            return Redirect::to('/login');
        } catch(\Exception $ex) {
            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => "Operation failed.",
                    "error" => $e->getMessage()
                ],500);
            }
            return redirect('/edit-profile#'.$active_tab)->with('error','Error while terminating the account');
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $user = User::where('code', $request->user_code)->first();
            $user->status = $request->status;
            $user->save();

            Session::flash('message', 'User status has been updated successfully');
            return redirect()->route('users.show',$request->user_code);
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function viewJobSeekerProfile($code) {
        try {
            if(is_null($code) or empty($code)) {
                return redirect()->route('dashboard')->with('error', 'Invalid JobSeeker Profile');
            }

            $jobseeker = DB::table('job_seekers as j')
                ->select('j.id', 'u.fname', 'u.lname', 'u.oname', 'u.code', 'j.cv','j.linkedin', 'u.email', 'u.contact', 'u.contact_secondary', 'u.image', 'u.status', 'u.remarks', 'u.gender', 'j.looking_for',
                    'i.name as institute', 'fa.name as faculty', 'd.name as department', 'co.name as course', 'b.name as batch')
                ->leftjoin('users as u', 'u.id', '=', 'j.user')
                ->leftjoin('institutes as i', 'i.id', '=', 'j.institute')
                ->leftjoin('faculties as fa', 'fa.id', '=', 'j.faculty')
                ->leftjoin('departments as d', 'd.id', '=', 'j.department')
                ->leftjoin('courses as co', 'co.id', '=', 'j.course')
                ->leftjoin('batches as b', 'b.id', '=', 'j.batch')
                ->where('u.code', $code)
                ->where('u.status', 1)
                ->whereNull('j.deleted_at')->first();

            if(is_null($jobseeker) or empty($jobseeker)) {
                return redirect()->route('dashboard')->with('error', 'Invalid JobSeeker Profile');
            }

            $educations = DB::table('job_seeker_qualifications as jsq')
                    ->select('jsq.*', 'i.name as institute','i.logo', 'fa.name as faculty', 'd.name as department', 'co.name as course')
                    ->leftjoin('institutes as i', 'i.id', '=', 'jsq.institute')
                    ->leftjoin('faculties as fa', 'fa.id', '=', 'jsq.faculty')
                    ->leftjoin('departments as d', 'd.id', '=', 'jsq.department')
                    ->leftjoin('courses as co', 'co.id', '=', 'jsq.course')
                    ->where('jsq.job_seeker', $jobseeker->id)->get();
            foreach($educations as $education) {
                if(is_null($education->institute) or empty($education->institute)) {
                    $education->institute = $education->institute_name;
                }
                if(is_null($education->course) or empty($education->course)) {
                    $education->course = $education->course_name;
                }
            }

            $experiences = DB::table('job_seeker_experiences as jse')
                ->select('jse.*', 'u.fname as company', 'p.name as position', 'c.logo')
                ->leftjoin('companies as c', 'c.id', '=', 'jse.company')
                ->leftjoin('users as u', 'u.id', '=', 'c.user')
                ->leftjoin('positions as p', 'p.id', '=', 'jse.position')
                ->where('jse.job_seeker', $jobseeker->id)->get();
            
            $monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

            foreach($experiences as $experience) {
                if(is_null($experience->company) or empty($experience->company)) {
                    $experience->company = $experience->company_name;
                }
                if(is_null($experience->position) or empty($experience->position)) {
                    $experience->position = $experience->position_name;
                }
                if(!is_null($experience->month_from)) {
                    $experience->month_from = $monthNames[$experience->month_from];
                }
                if(!is_null($experience->month_to)) {
                    $experience->month_to = $monthNames[$experience->month_to];
                }
            }
            
            $experience_medias = DB::table('job_seeker_experience_media as em')
                    ->leftjoin('job_seeker_experiences as e', 'e.id', 'em.job_seeker_experience')
                    ->where('e.job_seeker', $jobseeker->id)->get();
            $education_medias = DB::table('job_seeker_qualification_media as qm')
                    ->leftjoin('job_seeker_qualifications as q', 'q.id', 'qm.job_seeker_qualification')
                    ->where('q.job_seeker', $jobseeker->id)->get();
                    
            return view('common.jobseeker.profile', compact('jobseeker', 'educations', 'experiences', 'experience_medias', 'education_medias'));
        } catch(\Exception $e) {
            return redirect()->route('error');
        } catch(\Throwable $e) {
            return redirect()->route('error');
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if (is_null($user) or empty($user)) {
                return redirect()->back()->with('error', 'Invalid User');
            }

            $userType = $user->type;
            $user->email = "del_".$user->email;
            $user->status = 0;
            $user->deleted_at = new DateTime();
            $user->save();

            if ($userType == "JobSeeker") {
                JobSeeker::where('user', $id)->delete();
            } else if ($userType == "Company") {
                $company = Company::where('user', $id)->first();
                Vacancy::where('company', $company->id)->update(['deleted_at' => new DateTime()]);
                Company::where('user', $id)->delete();
            } else if ($userType == "University Staff") {
                UniversityStaff::where('user', $id)->delete();
            }

            return redirect()->back()->with('message', 'User deleted successfully');
        } catch(\Exception $e) {
            return redirect()->back()->with('error', 'Error while deleting the User');
        } catch(\Throwable $e) {
            return redirect()->back()->with('error', 'Error while deleting the User');
        }
    }
}