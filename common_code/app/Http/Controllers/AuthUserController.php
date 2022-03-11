<?php

namespace App\Http\Controllers;

use App\Jobs\ForgotPasswordEmailJob;
use App\Jobs\SignUpEmailJob;
use App\Models\User;
use App\Models\Country;
use App\Models\JobSeeker;
use App\Models\Company;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatersUsers;
use Illuminate\Support\Str;
use Validator;
use Session;
use Redirect;
use DB;
use Storage;
use Image;
use App\Models\LoginHistory;

class AuthUserController extends Controller
{
    function loginPage(Request $request)
    {
        $rurl = $request->rurl;
        return view('auth/login')->with("rurl", $rurl);
    }

    function login(Request $request)
    {
        $checkUser = User::where('email',$request->email);

        if ($request->is('api*')) {
            $checkUser = $checkUser->where('type','JobSeeker');
        }

        $checkUser = $checkUser->first();
        $rurl = $request->rurl;

        if($checkUser == null){

            $msg = "Invalid credentials. Please try again.";

            $val = [
                'email' => [
                    '0' =>$msg
                    ]
                ];

            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => $msg,
                    "error" => array('login'=>$msg)
                ],422);
            }
            return Redirect::to('/login?rurl='.$rurl)->with('error',$msg);
        }


        if($checkUser->status == 0) {
            $msg = "Your account hasn't been activated yet. Please check the email that was sent to you during your account creation. If you don't have that email, please use 'Forgot Password' functionality.";

            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => $msg,
                    "error" => array('login'=>$msg)
                ],422);
            }

            return Redirect::to('/login?rurl='.$rurl)->with('error', $msg);
        }

        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) 
        {
            $user = Auth::user();
            $newToken = (string) Str::uuid();
            $user->remember_token = $newToken;
            $user->save();

            if ($request->is('api*')) {
                $user->image_url = url('/storage/UserImages/').'/'.$user->image;
                $datas = $user;
                $datas['token'] =  $user->createToken('MyApp')->accessToken; 

                return response()->json([
                    "success" => true,
                    "message" => "Operation successful.",
                    "data" => $datas,
                ],200);
            }

            session(['loginedIn' => Auth::user()->id]);

            $uid = "";
            if(Session::has("PLACEMENTS-UID")) {
                $uid = Session::get("PLACEMENTS-UID");
            } else {
                $uid = (string) Str::orderedUuid();
                Session::put("PLACEMENTS-UID", $uid);
            }

            $login_history = new LoginHistory();
            $login_history->user = $user->id;
            $login_history->ip = $request->ip;
            $login_history->uid = $uid;
            $login_history->date = date('Y-m-d H:i:s');
            $login_history->save();

            if(empty($rurl))
                return Redirect::to('/dashboard');
            else
                return Redirect::to($rurl);
        }
        else{
            $msg= 'Invalid credentials. Please try again.';
            if ($request->is('api*')) {
                return response()->json([
                    "success" => false,
                    "message" => $msg,
                    "error" => array('login'=>$msg)
                ],422);
            }

            return Redirect::to('/login?rurl='.$rurl)->with('error',$msg);
        }
    }


    function emailOTP(Request $request)
    {
        if($request->token == null)
            return Redirect::to('/login')->with('error','You need to login first to continue doing the requested operation');
        else{
            $user = User::where("remember_token", $request->token)->first();

            if($user == null){
                return Redirect::to('/login')->with('error','You need to login first to continue doing the requested operation');
            }
            else{
                return view('auth.email-otp')->with("token",  $request->token);
            }
        }        
    }
    
    function logout()
    {
        $user = Auth::user();
        $response["msg"] = 'Successfully logout into the system';
        $response["status"] = "success";
        $response["is_success"] = true;
        if ($user->save()) {
            $response["msg"] = "Logout in successfully";
            $response["status"] = "Success";
            $response["is_success"] = true;
        }
        session()->flush();
        return Redirect::to('/login');
    }

    public function jobseekerSignup(Request $request) {
        $countriesForFilter = Country::all();
        $rurl = $request->rurl;
        return view('auth.jobseeker-signup', compact('countriesForFilter'))->with("rurl", $rurl);
    }

    public function companySignup(Request $request) {
        $countriesForFilter = Country::all();
        $rurl = $request->rurl;
        return view('auth.company-signup', compact('countriesForFilter'))->with("rurl", $rurl);
    }

    public function jobseekerRegister(Request $request) {
        $rules = array(
            'firstname' => 'required',
            'lastname' => 'required',
            'contact' => ['required', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users'],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'type' => 'required',
            'country' => 'required',
            // 'cv' => 'required|mimes:pdf|max:10240',
        );

        $customMessages = [
            'firstname.required' => 'First Name cannot be empty',
            'lastname.required' => 'Last Name cannot be empty',
            'contact.required' => 'Contact Number cannot be empty',
            'contact.unique' => 'Contact Number has been already taken. Please try with another one',
            'email.required' => 'Email cannot be empty',
            'email.unique' => 'Email has been already taken. Please try with another one',
            'password.required' => 'Password cannot be empty',
            'type.required' => 'Type need to be selected',
            'country.required' => 'Country need to be selected',
            // 'cv.required' => 'CV need to be uploaded',
            // 'cv.mimes' => 'CV should be a pdf',
            // 'cv.max' => 'CV can be of 10MB or lesser',
            'contact.regex' => 'Invalid Contact Number',
        ];
       
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
            return redirect()->back()
                    ->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = new User();
                $user->type = 'JobSeeker';
                // $user->code = Str::random(20);
                $user->fname = $request->firstname;
                $user->lname = $request->lastname;
                $user->contact = $request->contact;
                $user->email = $request->email;
                $user->remember_token = Str::random(100);
                $user->password = Hash::make($request->password);     
                $user->save();

                $stringUtil = new \App\Services\StringUtils();
                $user->code = $stringUtil->randomStringGenerator($user->fname, $user->id);
                $user->save();

                $jobSeeker = new JobSeeker();
                $jobSeeker->user = $user->id;
                $jobSeeker->jobseeker_type = $request->type;
                $jobSeeker->country = $request->country;

                // if ($request->hasfile('cv')) {
                //     try {
                //         $cv = $request->file('cv');
                //         $fileName = time() . '_' . $cv->getClientOriginalName();
                        
                //         $cv->move(storage_path().'/app/public/CurriculamVitaes'.'/', $fileName);
                //         $jobSeeker->cv = $fileName;
                //     } catch(\Exception $e) {
                //         Session::flash('error', 'Error while uploading CV');
                //     } catch(\Throwable $e) {
                //         Session::flash('error', 'Error while uploading CV');
                //     }
                // } else {
                //     Session::flash('error', 'CV need to be uploaded');
                // }
                
                $jobSeeker->save();
                
                $rurl = $request->rurl;
                $details = [
                    'title' => 'Welcome to Placements.lk',
                    'token' => 'https://placements.lk/verify?token='.$user->remember_token.'&rurl='.$rurl,
                    'name' => $user->fname." ".$user->lname
                ];
                
                try {
                    // \Mail::to($user->email)->send(new \App\Mail\SignUpEmail($details));
                    
                    dispatch(new SignUpEmailJob($user->email,  $details))->delay(now()->addSeconds(2));
                } catch(\Exception $e) {

                    if ($request->is('api*')) {
                        return response()->json([
                            "success" => false,
                            "message" => "Operation failed.",
                            "error" => 'Error while sending email.'
                        ],500);
                    }

                    return redirect()->back()->with('error', 'Error while sending email.');
                }

                $msg = 'Successfully registered. Please check your email inbox or spam box for activating your account.';
                if ($request->is('api*')) {
                    return response()->json([
                        "success" => true,
                        "message" => $msg,
                        "data" => "Successfully registered. Please check your email inbox for further instructions. Please check your SPAM box, if you couldn't find the email in the inbox.",
                    ],200);
                }
                
                Session::flash('message', "Successfully registered. Please check your email inbox for further instructions.");
                Session::flash('warning', "Please check your <b>SPAM box</b>, if you couldn't find the email in the inbox.");
            } catch(\Exception $e) {

                if ($request->is('api*')) {
                    return response()->json([
                        "success" => false,
                        "message" => "Operation failed.",
                        "error" => 'Operation failed. Please try again.'
                    ],500);
                }

                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            } catch(\Throwable $e) {

                if ($request->is('api*')) {
                    return response()->json([
                        "success" => false,
                        "message" => "Operation failed.",
                        "error" => 'Operation failed. Please try again.'
                    ],500);
                }
                
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            }
            return redirect()->route('login');
        }
    }

    public function verifyEmail(Request $request){
        $rules = array(
            'token' => 'required'
        );

        $customMessages = [
            'token.required' => 'Token cannot be empty'
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput($request->all());
        } else {
            $reqToken = $request->token;
            $user = User::where('remember_token', $reqToken)->first();
            if ($user) {
                $user->status = 1;
                $user->remember_token = null;
                $user->save();
                Session::flush();
                Session::flash('message', 'Your account has been activated successfully. Please login to continue.');

                $rurl = $request->rurl;
                
                return Redirect::to('/login?rurl='.$rurl);
                //return redirect()->route('login');
            } else {
                Session::flash('error', "This is an expired link OR your account is already activated. Otherwise, please use 'Forgot Password' functionality to reset the password.");
                return redirect()->route('login');
            }
        }
    }

    public function companyRegister(Request $request) {
        $rules = array(
            'companyname' => 'required',
            'contact' => ['required', 'regex:/^(?:7|0|(?:\+94))[0-9]{7,15}$/', 'max:15', 'unique:users'],
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'country' => 'required',
        );

        $customMessages = [
            'companyname.required' => 'Company Name cannot be empty',
            'contact.required' => 'Contact Number cannot be empty',
            'contact.unique' => 'Contact Number has been already taken. Please try with another one',
            'email.required' => 'Email cannot be empty',
            'email.unique' => 'Email has been already taken. Please try with another one',
            'password.required' => 'Password cannot be empty',
            'country.required' => 'Country need to be selected',
            'contact.regex' => 'Invalid Contact Number',
        ];
       
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back()
                    ->withErrors($validator)->withInput($request->all());
        } else {
            try {
                $user = new User();
                $user->type = 'Company';
                // $user->code = Str::random(20);
                $user->fname = $request->companyname;
                $user->contact = $request->contact;
                $user->email = $request->email;
                $user->remember_token = Str::random(100);
                $user->password = Hash::make($request->password);
                $user->save();

                $stringUtil = new \App\Services\StringUtils();
                $user->code = $stringUtil->randomStringGenerator($user->fname, $user->id);
                $user->save();

                $company = new Company();
                $company->user = $user->id;
                $company->country = $request->country;
                $company->save();
                
                $rurl = $request->rurl;
                $details = [
                    'title' => 'Welcome to Placements.lk',
                    'token' => 'https://placements.lk/verify?token='.$user->remember_token.'&rurl='.$rurl,
                    'name' => $user->fname
                ];
                
                try {
                    // \Mail::to($user->email)->send(new \App\Mail\SignUpEmail($details));
                    dispatch(new SignUpEmailJob($user->email,  $details))->delay(now()->addSeconds(2));

                } catch(\Exception $e) {
                    return redirect()->back()->with('error', 'Error while sending email.');
                }
                
                Session::flash('message', "Successfully registered. Please check your email inbox for further instructions.");
                Session::flash('warning', "Please check your <b>SPAM box</b>, if you couldn't find the email in the inbox.");
            } catch(\Exception $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            } catch(\Throwable $e) {
                return redirect()->back()->with('error', 'Operation failed. Please try again.');
            }
            return redirect()->route('login');
        }
    }

    public function passwordRequest(Request $request)
    {
        if($request->token == null){
            return view("auth.forgot-password",['token' => null]);
        }else{
            return view("auth.forgot-password",['token' => $request->token]);
        }
    }

    public function forgotPassword(Request $request)
    {
        if($request->email){
            $user = User::where('email',$request->email)->first();
            if($user != null){
                $user->remember_token = Str::random(100);
                $user->save();
                $details = [
                    'title' => 'Password Reset Email',
                    'name' => $user->fname." ".$user->lname,
                    'email' => $user->email,
                    'token' => 'https://placements.lk/forgot-password?token='.$user->remember_token
                ];

                try {
                    // \Mail::to($user->email)->send(new \App\Mail\forgotPasswordEmail($details));

                    dispatch(new ForgotPasswordEmailJob($user->email,  $details))->delay(now()->addSeconds(2));

                } catch(\Exception $e) {
                    return redirect()->back()->with('error', 'Error while sending email.');
                }
                return redirect()->route('login')->with('message', "We have sent you an email. Please check your email inbox for further instructions.")
                                    ->with('warning', "Please check your <b>SPAM box</b>, if you couldn't find the email in the inbox.");
            }
            Session::flash('message', "We have sent you an email. Please check your email inbox for further instructions.");
            Session::flash('warning', "Please check your <b>SPAM box</b>, if you couldn't find the email in the inbox.");
            
            return view('auth.forgot-password');
        } else {
            $rules = array(
                'password' => 'required|min:6',
            );
    
            $customMessages = [
                'password.required' => 'Password cannot be empty',
            ];
            $validator = Validator::make($request->all(), $rules, $customMessages);

            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                return  view("auth.forgot-password",['token' => $request->token]);
            } else {
                $user = User::where('remember_token', $request->token)->first();
                if (!is_null($user) && !empty($user)) {
                    $user->password = Hash::make($request->password);
                    $user->status = 1;
                    $user->remember_token = null;
                    $user->save();
                    return redirect()->route('login')->with('message', 'Your password has been successfully updated');
                } else {
                    Session::flash('error', 'It seems that you have clicked on a wrong or an expired link. Please try again. Thanks.');
                    return redirect()->route('login');
                }
            }
        }
    }

    public function forgotPasswordMobile(Request $request)
    {
        if($request->email){
            $user = User::where('email',$request->email)->first();
            if($user != null){
                $user->remember_token = Str::random(100);
                $user->save();
                $details = [
                    'title' => 'Password Reset Email',
                    'name' => $user->fname." ".$user->lname,
                    'email' => $user->email,
                    'token' => 'https://placements.lk/forgot-password?token='.$user->remember_token
                ];

                try {
                    // \Mail::to($user->email)->send(new \App\Mail\forgotPasswordEmail($details));

                    dispatch(new ForgotPasswordEmailJob($user->email,  $details))->delay(now()->addSeconds(2));

                } catch(\Exception $e) {

                    if ($request->is('api*')) {
                        return response()->json([
                            "success" => false,
                            "message" => "Error while sending email.",
                            "error" => array('email'=>'Error while sending email.')
                        ],500);
                    }
                    return redirect()->back()->with('error', 'Error while sending email.');
                }
                if ($request->is('api*')) {
                    return response()->json([
                        "success" => true,
                        "message" => "We have sent you an email. Please check your email inbox for further instructions. Please check your SPAM box, if you couldn't find the email in the inbox.",
                        "data" => "We have sent you an email. Please check your email inbox for further instructions. Please check your SPAM box, if you couldn't find the email in the inbox.",
                    ],200);
                }
                return redirect()->route('login')->with('message', "We have sent you an email. Please check your email inbox for further instructions.")
                                    ->with('warning', "Please check your <b>SPAM box</b>, if you couldn't find the email in the inbox.");
            }
           
                return response()->json([
                    "success" => false,
                    "message" => "Cannot find the user id.",
                    "error" => array('email'=>'Cannot find the user id.')
                ],400);
       
           
        } else {
            
                return response()->json([
                    "success" => false,
                    "message" => "Email cannot be empty",
                    "error" => array('email'=>'Email cannot be empty')
                ],422);
          
        }
    }
}
