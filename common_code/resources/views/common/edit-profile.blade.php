@extends("layouts.main")

@section("meta")
    <title>Edit Profile | Placements.lk</title>
@endsection

@section("main-body")
    
<h1 class="page-heading rounded">Edit Profile</h1>

    <form novalidate action="{{ route('update-profile') }}" method="POST" enctype='multipart/form-data'>
    
    <div class="row">
        <div class="col-md-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <!-- Nav tabs -->
                <ul class="nav nav-pills" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic-cont" type="button" role="tab" aria-controls="home" aria-selected="true">Basic Info</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password-cont" type="button" role="tab" aria-controls="home" aria-selected="true">Password</button>
                    </li>
                    @if($userType == "JobSeeker")
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link" id="qualifications-tab" data-bs-toggle="tab" data-bs-target="#qualifications-cont" type="button" role="tab" aria-controls="profile" aria-selected="false">Qualifications</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link" id="experience-tab" data-bs-toggle="tab" data-bs-target="#experience-cont" type="button" role="tab" aria-controls="messages" aria-selected="false">Experience</button>
                    </li>
                    @endif

                    @if($userType == "JobSeeker" or $userType == "Company")
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link" id="fields-tab" data-bs-toggle="tab" data-bs-target="#fields-cont" type="button" role="tab" aria-controls="settings" aria-selected="false">Fields</button>
                    </li>
                    @endif
                    
                    @if($userType == "JobSeeker")
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link" id="cv-tab" data-bs-toggle="tab" data-bs-target="#cv-cont" type="button" role="tab" aria-controls="settings" aria-selected="false">CV</button>
                    </li>
                    @endif
                    <li class="nav-item" role="presentation">
                        <button class="px-3 text-dark nav-link" id="terminate-tab" data-bs-toggle="tab" data-bs-target="#terminate-cont" type="button" role="tab" aria-controls="terminate" aria-selected="false">Delete My Account</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>


        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="my-0 p-3 bg-body rounded shadow-sm mb-2">

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <input type="hidden" name="active_tab" value="basic-cont" id="active_tab" name="active_tab"/>
                        <!-- =================== BASIC DETAILS ============================ -->
                        <div class="tab-pane active" id="basic-cont" role="tabpanel" aria-labelledby="basic-cont">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="my-3 p-3 bg-body rounded shadow-sm text-center">
                                        <h6 class="mb-4">@if(Auth::user()->type == 'Company') Logo @else Display Picture @endif</h6>
                                        <div class="nav-item user-info mt-4 mb-4 text-center">
                                            @if($user->image == null or $user->image == "")
                                                @if(Auth::user()->type == 'Company')
                                                    <img class="border img-responsive" id="img-dp" src="/assets/images/company.png">
                                                @elseif($user->gender == 'Female')
                                                    <img class="border img-responsive" id="img-dp" src="/assets/images/default-female-dp.png">
                                                @else
                                                    <img class="border img-responsive" id="img-dp" src="/assets/images/default-male-dp.png">
                                                @endif
                                            @else
                                                <img class="border img-responsive" id="img-dp" src="{{asset('storage/UserImages/'.$user->image)}}"/>
                                            @endif
                                        </div>
                                        <input type='file' name='profile_image' class="form-control"/>
                                        <button type="button" class="btn btn-light mt-2 m-auto" onclick="deleteDp()">DELETE</button>
                                        <input type="hidden" name="delete-dp" value="0" id="delete-dp"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="fname" class="form-label">{{$userType == "Company" ? 'Company Name' : 'First Name'}} <strong style="color:red">*</strong></label>
                                        <input class="form-control mx-1" value="{{ $user->fname }}" placeholder="First Name" type="text" name="fname" />
                                    </div>
                                </div>
                                @if($userType != "Company")
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="lname" class="form-label">Last Name <strong style="color:red">*</strong></label>
                                        <input class="form-control mx-1" value="{{ $user->lname }}" placeholder="Last Name" type="text" name="lname" />
                                    </div>
                                </div>
                                @endif
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="oname" class="form-label">Other Name</label>
                                        <input class="form-control mx-1" value="{{ $user->oname }}" placeholder="Other Name" type="text" name="oname" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <strong style="color:red">*</strong>
                                            <!-- @if(Auth::check() and !(Auth::user()->type == 'University Staff' or Auth::user()->type == 'Company'))
                                            <input class="ms-3" type="checkbox" name="is_to_notify" id="is_to_notify-noti" value="1" name="notifications" @if(isset($user->is_to_notify) && $user->is_to_notify == '1') checked @endif/> 
                                            Email me when jobs are posted
                                            @endif -->
                                        </label>
                                        <input class="form-control mx-1" disabled value="{{ $user->email }}" placeholder="Email" type="text" name="email" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="contact" class="form-label">Primary Contact Number <strong style="color:red">*</strong></label>
                                        <input class="form-control mx-1 telephone" value="{{ $user->contact }}" placeholder="Primary Contact Number" type="text" name="contact" />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_secondary" class="form-label">Secondary Contact Number</label>
                                        <input class="form-control mx-1 telephone" value="{{ $user->contact_secondary }}" placeholder="Secondary Contact Number" type="text" name="contact_secondary" />
                                    </div>
                                </div>
                                @if(Auth::check() and Auth::user()->type == 'JobSeeker')
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-control" name="gender" id="gender">
                                            <option value="">[Unspecified]</option>
                                            <option @if($user->gender == 'Male') selected @endif value="Male">Male</option>
                                            <option @if($user->gender == 'Female') selected @endif value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Introductory Text</label>
                                        <textarea class="form-control mx-1" placeholder="Anything about you in few words" type="text" name="remarks">{{ $user->remarks }}</textarea>
                                    </div>
                                </div>
                                @if(Auth::check() and Auth::user()->type == 'University Staff')
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="staff_institute" class="form-label">Institute <strong style="color:red">*</strong></label>
                                        <select class="searchable-select form-control" name="staff_institute" id="staff_institute">
                                            <option value="">[Unspecified]</option>
                                            @foreach ($institutes as $obj)
                                                <option @if($user->institute == $obj->id) selected @endif value="{{$obj->id}}">{{$obj->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6" style="display:none" id="staff_fac_div">
                                    <div class="mb-3">
                                        <label for="staff_faculty" class="form-label">Faculty</label>
                                        <select class="form-control" name="staff_faculty" id="staff_faculty">
                                            <option value="">[Unspecified]</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6" style="display:none" id="staff_dept_div">
                                    <div class="mb-3">
                                        <label for="staff_department" class="form-label">Department</label>
                                        <select class="form-control" name="staff_department" id="staff_department">
                                            <option value="">[Unspecified]</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6" style="display:none" id="staff_course_div">
                                    <div class="mb-3">
                                        <label for="staff_course" class="form-label">Course</label>
                                        <select class="form-control" name="staff_course" id="staff_course">
                                            <option value="">[Unspecified]</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                                @if(Auth::check() and Auth::user()->type == 'JobSeeker')
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="found_mode" class="form-label">How did you find us?</label>
                                        <select class="form-control" name="found_mode" id="found_mode">
                                            <option value="">[Unspecified]</option>
                                            <option @if($user->found_mode == 'Facebook/Instagram Ads') selected @endif value="Facebook/Instagram Ads">Facebook/Instagram Ads</option>
                                            <option @if($user->found_mode == 'Google Ads') selected @endif value="Google Ads">Google Ads</option>
                                            <option @if($user->found_mode == 'Heard from colleague') selected @endif value="Heard from colleague">Heard from colleague</option>
                                            <option @if($user->found_mode == 'Heard from University') selected @endif value="Heard from University">Heard from University</option>
                                            <option @if($user->found_mode == 'Other') selected @endif value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                @endif
                                @if($userType == "JobSeeker")
                                        
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="looking_for" class="form-label">What are you looking for?</label>
                                            <select class="form-control" name="looking_for" id="looking_for">
                                                <option value="">[Unspecified]</option>
                                                <option @if($user->looking_for == 'Full-time') selected @endif value="Full-time">Full-time</option>
                                                <option @if($user->looking_for == 'Part-time') selected @endif value="Part-time">Part-time</option>
                                                <option @if($user->looking_for == 'Contract-based') selected @endif value="Contract-based">Contract-based</option>
                                                <option @if($user->looking_for == 'Internship') selected @endif value="Internship">Internship</option>
                                                <option @if($user->looking_for == 'None') selected @endif value="None">None</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="linkedin" class="form-label">Linkedin Profile Link</label>
                                            <input class="form-control mx-1" value="{{ $user->linkedin }}" placeholder="Linkedin" type="text" name="linkedin" />
                                        </div>
                                    </div>
                                    
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Resident Country <strong style="color:red">*</strong></label>
                                            <select class="searchable-select form-control" id="sel-country" name="country">
                                                <option value="">[Unspecified]</option>
                                                @foreach ($countries as $obj)
                                                    <option {{$user->country == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12  col-md-6 d-none">
                                        <div class="mb-3">
                                            <label for="latitute" class="form-label">Latitute</label>
                                            <input class="form-control mx-1" value="{{ $user->latitute }}" placeholder="Latitute" type="text" name="latitute" />
                                        </div>
                                    </div>
                                    <div class="col-12  col-md-6 d-none">
                                        <div class="mb-3">
                                            <label for="longitute" class="form-label">Longitute</label>
                                            <input class="form-control mx-1" value="{{ $user->longitute }}" placeholder="Longitute" type="text" name="longitute" />
                                        </div>
                                    </div>
                                @endif

                                @if($userType == "Company")
                                    <h6 class="mb-4">Company Information</h6>
                                    <!-- <div class="col-12  col-md-6">
                                        
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="logo" class="form-label">Logo</label>
                                                @if(isset($user->logo))
                                                <img src="{{asset('storage/Company/LogoImages/'.$user->logo)}}" style="max-width:250px;" class="d-block img-thumbnail-app img-fluid" alt="Gallery image"/>
                                                @endif
                                                <input type='file' name='logo' class="form-control">
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3 text-left">
                                            <label for="cover_image" class="form-label">Cover Image</label>
                                            @if(isset($user->cover_image))
                                            <img src="{{asset('storage/Company/CoverImages/'.$user->cover_image)}}" style="max-width:250px;" class="d-block img-thumbnail-app img-fluid" alt="Gallery image"/>
                                            @endif
                                            <input type='file' name='cover_image' class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="website" class="form-label">Website</label>
                                            <input class="form-control mx-1" value="{{ $user->website }}" placeholder="Website" type="text" name="website" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="facebook" class="form-label">Facebook</label>
                                            <input class="form-control mx-1" value="{{ $user->facebook }}" placeholder="Facebook" type="text" name="facebook" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="linkedin" class="form-label">Linkedin</label>
                                            <input class="form-control mx-1" value="{{ $user->linkedin }}" placeholder="Linkedin" type="text" name="linkedin" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="twitter" class="form-label">Twitter</label>
                                            <input class="form-control mx-1" value="{{ $user->twitter }}" placeholder="Twitter" type="text" name="twitter" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="instagram" class="form-label">Instagram</label>
                                            <input class="form-control mx-1" value="{{ $user->instagram }}" placeholder="Instagram" type="text" name="instagram" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country <strong style="color:red">*</strong></label>
                                            <select class="searchable-select form-control" id="sel-country2" name="country">
                                                <option value="">[Unspecified]</option>
                                                @foreach ($countries as $obj)
                                                    <option {{$user->country == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="short_description" class="form-label">Short Description</label>
                                            <textarea class="form-control mx-1" placeholder="Short Description" type="text" name="short_description">{{ $user->short_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="long_description" class="form-label">Long Description</label>
                                            <textarea class="form-control mx-1" placeholder="Long Description" type="text" name="long_description">{{ $user->long_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 d-none">
                                        <div class="mb-3">
                                            <label for="latitute" class="form-label">Latitute</label>
                                            <input class="form-control mx-1" value="{{ $user->latitute }}" placeholder="Latitute" type="text" name="latitute" />
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 d-none">
                                        <div class="mb-3">
                                            <label for="longitute" class="form-label">Longitute</label>
                                            <input class="form-control mx-1" value="{{ $user->longitute }}" placeholder="Longitute" type="text" name="longitute" />
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="col-md-4 offset-md-8 col-12 mt-4">
                                <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPDATE</button>
                            </div>
                        </div>

                        <div class="tab-pane" id="password-cont" role="tabpanel" aria-labelledby="password-cont">
                            <div class="row">
                                <div class="col-12 d-flex">
                                    <h4 class="mb-4 mt-5 fw-bold">Change Password</h4>
                                    <!-- <div class="py-5 px-3">
                                        <span toggle="#password-field" class="fa fa-fw fa-eye toggle-password"></span>
                                    </div> -->
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Current Password</label>
                                        <div class="pos-relative">
                                            <input class="form-control mx-1"  autocomplete="off" placeholder="Current Password" type="password" name="cpassword" id="cpassword" />
                                            <span toggle="#password-field" class="fa fa-fw fa-eye c-toggle-password icon-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="pos-relative">
                                            <input class="form-control mx-1"  autocomplete="off" placeholder="New Password" type="password" name="npassword" id="npassword" />
                                            <span toggle="#password-field" class="fa fa-fw fa-eye n-toggle-password icon-password"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Confirm Password</label>
                                        <div class="pos-relative">
                                            <input class="form-control mx-1"  autocomplete="off" placeholder="Confirm Password" type="password" name="npassword2" id="npassword2" />
                                            <span toggle="#password-field" class="fa fa-fw fa-eye n2-toggle-password icon-password"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 offset-md-8 col-12 mt-4">
                                <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPDATE</button>
                            </div>
                        </div>

                        <!-- =================== EDUCATION, EXPERIENCE AND CV DETAILS ============================ -->
                        @if($userType == "JobSeeker")
                            <div class="tab-pane" id="qualifications-cont" role="tabpanel" aria-labelledby="qualifications-cont">
                                <div class="row">
                                    <div class="col-12"><h4 class="mb-4 mt-5 fw-bold">Primary Education Details</h4></div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="jobseeker_type" class="form-label">Are you currently a University student? <strong style="color:red">*</strong></label>
                                            <select class="form-control" name="jobseeker_type" id="jobseeker_type">
                                                <option @if($user->jobseeker_type == 'No/Not Now') selected @endif value="No/Not Now">No/Not Now</option>
                                                <option @if($user->jobseeker_type == "Yes I'm a University Student") selected @endif value="Yes I'm a University Student">Yes I'm a University Student</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <div class="mb-3">
                                            <label for="institute" class="form-label">Institute</label>
                                            <select class="searchable-select form-control" name="institute" id="institute">
                                                <option value="">[Unspecified]</option>
                                                @foreach ($institutes as $obj)
                                                    <option @if($user->institute == $obj->id) selected @endif value="{{$obj->id}}">{{$obj->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6" style="display:none" id="fac_div">
                                        <div class="mb-3">
                                            <label for="faculty" class="form-label">Faculty</label>
                                            <select class="form-control" name="faculty" id="faculty">
                                                <option value="">[Unspecified]</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6" style="display:none" id="dept_div">
                                        <div class="mb-3">
                                            <label for="department" class="form-label">Department</label>
                                            <select class="form-control" name="department" id="department">
                                                <option value="">[Unspecified]</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6" style="display:none" id="cou_div">
                                        <div class="mb-3">
                                            <label for="course" class="form-label">Course</label>
                                            <select class="form-control" name="course" id="course">
                                                <option value="">[Unspecified]</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6" style="display:none" id="bat_div">
                                        <div class="mb-3">
                                            <label for="batch" class="form-label">Batch</label>
                                            <select class="form-control" name="batch" id="batch">
                                                <option value="">[Unspecified]</option>
                                                <!-- @foreach ($batches as $obj)
                                                    <option @if($user->batch == $obj->id) selected @endif value="{{$obj->id}}">{{$obj->name}}</option>
                                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 offset-md-8 col-12 mt-4">
                                        <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPDATE</button>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-9 d-flex"><h4 class="mb-4 mt-5 fw-bold">Other Educational Qualifications</h4></div>
                                    <div class="col-3"><button type="button" class="btn btn-primary btn-primary-inverse float-end mb-4 mt-5" onclick="$('#education-modal').modal('show')"><i class="fas fa-plus mx-1"></i></button></div>
                                    
                                    @foreach($educations as $obj)
                                        @php ($xlSize = 6)
                                        @php ($mdSize = 12)
                                        @php ($smSize = 12)
                                        @include("tiles.education-tile")
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="experience-cont" role="tabpanel" aria-labelledby="experience-cont">
                                <div class="row">
                                    <div class="col-9 d-flex"><h4 class="mb-4 mt-5 fw-bold">Work Experience</h4></div>
                                    <div class="col-3"><button type="button" class="btn btn-primary btn-primary-inverse float-end mb-4 mt-5" onclick="$('#experience-modal').modal('show')"><i class="fas fa-plus mx-1"></i></button></div>
                                    
                                    @foreach($experiences as $obj)
                                        @php ($xlSize = 6)
                                        @php ($mdSize = 12)
                                        @php ($smSize = 12)
                                        @include("tiles.experience-tile")
                                    @endforeach
                                </div>    
                            </div>
                            <div class="tab-pane" id="cv-cont" role="tabpanel" aria-labelledby="cv-cont">
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <label for="cv" class="form-label">Upload your latest Curriculum Vitae <strong style="color:red">*</strong></label> (max: 10 MB)
                                        <input type='file' name='cv' class="form-control mb-4">
                                    </div>
                                    <div class="col-md-4 offset-md-8 col-12 mt-4">
                                        <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPLOAD</button>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3 text-left">
                                            @if(isset($user->cv))
                                                <object data="{{asset('storage/CurriculamVitaes/'.$user->cv)}}" type="application/pdf" class="w-100" height="1000">
                                                    <iframe src="{{asset('storage/CurriculamVitaes/'.$user->cv)}}">
                                                    <p>This browser does not support PDF!</p>
                                                    </iframe>
                                                </object>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- =================== FIELD DETAILS ============================ -->
                        @if($userType == "JobSeeker" or $userType == "Company")
                            <div class="tab-pane" id="fields-cont" role="tabpanel" aria-labelledby="fields-cont">
                                <div class="row">
                                    <div class="col-12">
                                        <h1 class="page-heading rounded">Select Preferred Fields and Positions</h1>
                                        <table class="table w-100 table-mobile" id="data-table">
                                            <tbody id="tbody-data">
                                                @foreach ($fields as $field)
                                                    <tr style="cursor:pointer">
                                                        <td class="fw-bold">
                                                            <input onchange="checkAll(this, {{$field->id}})" class="me-2 field_{{$field->id}}" type="checkbox" {{ in_array('field_'.$field->id, $selectedFields) ? 'checked' : '' }} id="field_{{$field->id}}" name="field_{{$field->id}}"> 
                                                            <span onclick="$('.children-{{$field->id}}').toggleClass('d-none')">{{ $field->name }}</span>
                                                        </td>
                                                    </tr>
                                                    @foreach ($positions as $position)
                                                        @if ($field->id == $position->field)
                                                        <tr class="children-{{$field->id}} d-none">
                                                            <td style="padding-left: 100px !important">
                                                                <input onchange="checkPosition(this, '{{$position->id}}','{{$field->id}}')" class="me-2 position_{{$field->id}}" type="checkbox" {{ (in_array('position_'.$field->id.'_'.$position->id, $selectedPositions) || in_array('field_'.$field->id, $selectedFields)) ? 'checked' : '' }} id="position_{{$field->id}}_{{$position->id}}" name="position_{{$field->id}}_{{$position->id}}">
                                                                {{ $position->name }}
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-4 offset-md-8 col-12 mt-4">
                                        <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPDATE</button>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane" id="terminate-cont" role="tabpanel" aria-labelledby="terminate-cont">
                            <div class="row">
                                <div class="col-12">
                                    <h4 class="mb-4 mt-5 fw-bold">Delete (Terminate) My Account</h4>
                                    <p>Please be noted that once you delete your account, you will not be able to recover it. Are you sure you want to do this?</p>
                                </div>
                                <div class="col-md-2 col-12 mt-4">
                                    <button href="{{ url('terminate-account')}}" type="button" class="btn p-3 btn-danger w-100 mx-1 mb-4 confirm-action-link">Confirm</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- <div class="col-md-4 offset-md-8 col-12 mt-4">
                <button type="submit" class="btn p-3 btn-primary w-100 mx-1 mb-4">UPDATE</button>
            </div> -->
        </div>
    </form>
@endsection

@section('modal')
<!-- MODALS ======================================================== -->
<form novalidate method="get">
<div class="modal fade hide" id="education-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" >Add Education</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="education-modal-body">
        <div class="row">
          <div class="col-12">
            <div class="alert alert-danger" role="alert" id="education-alert" style="display:none">
              
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="mb-3">
                <label for="edu_institute" class="form-label">Institute <strong style="color:red">*</strong></label>
                <select class="js-example-basic-single form-control" name="edu_institute" id="edu_institute">
                    <option value="">[My Institute is not listed here]</option>
                    @foreach ($institutes as $obj)
                        <option value="{{$obj->id}}">{{$obj->name}}</option>
                    @endforeach
                </select>
              </div>
          </div>
          <div class="col-12 col-md-6" id="institutename_div">
              <div class="mb-3">
                  <label for="institute_name" class="form-label">Institute Name</label>
                  <input class="form-control mx-1" placeholder="Institute Name" type="text" name="institute_name" id="institute_name"/>
              </div>
          </div>
          <div class="col-12 col-md-6" style="display:none" id="faculty_div">
              <div class="mb-3">
                  <label for="edu_faculty" class="form-label">Faculty</label>
                  <select class="form-control" name="edu_faculty" id="edu_faculty">
                      <option value="">[Unspecified]</option>
                  </select>
              </div>
          </div>
          <div class="col-12 col-md-6" style="display:none" id="department_div">
              <div class="mb-3">
                  <label for="edu_department" class="form-label">Department</label>
                  <select class="form-control" name="edu_department" id="edu_department">
                      <option value="">[Unspecified]</option>
                  </select>
              </div>
          </div>
          <div class="col-12 col-md-6" style="display:none" id="course_div">
              <div class="mb-3">
                  <label for="edu_course" class="form-label">Course</label>
                  <select class="form-control" name="edu_course" id="edu_course">
                      <option value="">[My Course is not listed here]</option>
                  </select>
              </div>
          </div>
          <div class="col-12 col-md-6" id="coursename_div">
              <div class="mb-3">
                  <label for="course_name" class="form-label">Course Name</label>
                  <input class="form-control mx-1" placeholder="Course Name" type="text" name="course_name" id="course_name"/>
              </div>
          </div>
          <div class="col-12 col-md-6" id="grade">
            <div class="mb-3">
                <label for="grade" class="form-label">Grade</label>
                <input class="form-control mx-1" placeholder="Grade" type="text" name="grade" id="grade"/>
            </div>
          </div>
          <div class="col-12">
            <div class="mb-3">
                <input type="checkbox" checked value="1" onclick="$(this).attr('value', this.checked ? 1 : 0)" name="currently_studying" id="currently_studying" name="currently_working"/> 
                <label for="currently_studying">I am currently studying</label>
            </div>
          </div>
          <div class="col-12 col-md-6">
              <div class="mb-3">
                  <label for="year_from" class="form-label">Start Year</label>
                  <select class="form-control" name="year_from" id="year_from">
                      <option value="">Year</option>
                  </select>
              </div>
          </div>
          <div class="col-12 col-md-6" style="display:none" id="edu_end_year">
              <div class="mb-3">
                  <label for="year_to" class="form-label">End Year</label>
                  <select class="form-control" name="year_to" id="year_to">
                      <option value="">Year</option>
                  </select>
              </div>
          </div>
          <div class="col-12 col-md-6">
              <div class="mb-3">
                  <label for="edu_notes" class="form-label">Notes</label>
                  <textarea class="form-control mx-1" placeholder="Notes" type="text" name="edu_notes" id="edu_notes"></textarea>
              </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light btn-lg px-4 rounded" data-bs-dismiss="modal">CLOSE</button>
        <button type="button" class="btn btn-primary px-4 rounded text-white" onclick="addEducation()">SAVE</button>
      </div>
    </div>
  </div>
</div>
</form>

<form novalidate method="get">
<div class="modal fade hide" id="experience-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" >Add Experience</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="experience-modal-body">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-danger" role="alert" id="experience-alert" style="display:none">
                        
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1" placeholder="Title" type="text" name="title" id="title"/>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="pro_company" class="form-label">Company <strong style="color:red">*</strong></label>
                        <select class="js-example-basic-single form-control" name="pro_company" id="pro_company">
                            <option value="">[My Company is not listed here]</option>
                            @foreach ($companies as $obj)
                                <option value="{{$obj->id}}">{{$obj->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6" id="companyname_div">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Company Name</label>
                        <input class="form-control mx-1" placeholder="Company Name" type="text" name="company_name" id="company_name"/>
                    </div>
                </div>
                <div class="col-12 col-md-6" id="position_div">
                    <div class="mb-3">
                        <label for="pro_position" class="form-label">Position <strong style="color:red">*</strong></label>
                        <select class="form-control" name="pro_position" id="pro_position">
                            <option value="">[My Position is not listed here]</option>
                            @foreach ($positions as $obj)
                                <option value="{{$obj->id}}">{{$obj->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6" id="positionname_div">
                    <div class="mb-3">
                        <label for="position_name" class="form-label">Position Name</label>
                        <input class="form-control mx-1" placeholder="Position Name" type="text" name="position_name" id="position_name"/>
                    </div>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <input type="checkbox" checked value="1" onclick="$(this).attr('value', this.checked ? 1 : 0)" name="currently_working" id="currently_working" name="currently_working"/> 
                        <label for="currently_working">I am currently working in this role</label>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="row mb-3">
                        <label for="pro_year_from" class="form-label">Start Date</label>
                        <div class="col-12 col-md-6">
                            <select class="form-control" name="pro_year_from" id="pro_year_from">
                                <option value="">Year</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <select class="form-control" name="month_from" id="month_from">
                                <option value="">Month</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6" style="display:none" id="end_date">
                    <div class="row mb-3">
                        <label for="pro_year_to" class="form-label">End Date</label>
                        <div class="col-12 col-md-6">
                            <select class="form-control" name="pro_year_to" id="pro_year_to">
                                <option value="">Year</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <select class="form-control" name="month_to" id="month_to">
                                <option value="">Month</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="job_type" class="form-label">Employment type</label>
                        <select class="form-control" name="job_type" id="job_type">
                            <option value="">[Unspecified]</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract-based">Contract-based</option>
                            <option value="Internship">Internship</option>
                            <option value="None">None</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="mb-3">
                        <label for="pro_notes" class="form-label">Notes</label>
                        <textarea class="form-control mx-1" placeholder="Notes" type="text" name="pro_notes" id="pro_notes"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light btn-lg px-4 rounded" data-bs-dismiss="modal">CLOSE</button>
            <button type="button" class="btn btn-primary btn-lg px-4 rounded text-white" onclick="addExperience()">SAVE</button>
        </div>
    </div>
  </div>
</div>
</form>
@endsection

@section("scripts")

@if ($userType == "JobSeeker")
    @include("jobseeker.profile.scripts-education")
    @include("jobseeker.profile.scripts-experience")
@endif

<script>
$(document).ready(function() {
    $('#myTab button').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-pills > li > button").on("shown.bs.tab", function(e) {
        var id = $(e.target).attr("data-bs-target").substr(1);
        window.location.hash = id;
        $('#active_tab').val(id);
    });

    // on load of the page: switch to the currently selected tab
    var hash = window.location.hash;
    $('#myTab button[data-bs-target="' + hash + '"]').tab('show');

    $("body").on('click', '.c-toggle-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#cpassword");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $("body").on('click', '.n-toggle-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#npassword");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $("body").on('click', '.n2-toggle-password', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#npassword2");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    let institutes=@json($institutes);
    let faculties=@json($faculties);
    let departments=@json($departments);
    let courses=@json($courses);
    let batches=@json($batches);
    let userType=@json($userType);

    if (userType == "JobSeeker") {
        let selected_institute = $("#institute option:selected").val();
        if (selected_institute != "" && selected_institute != null) {
            $("#fac_div").show();
        } else {
            $("#fac_div").hide();
            $("#dept_div").hide();
            $("#cou_div").hide();
            $("#bat_div").hide();
        }
        changeFaculty(faculties, selected_institute, true);

        $('#institute').on('change', function() {
            let institute_id = this.value;
            if ( institute_id != "" && institute_id != null) {
                $("#fac_div").show();
            } else {
                $("#fac_div").hide();
                $("#dept_div").hide();
                $("#cou_div").hide();
                $("#bat_div").hide();
            }
            changeFaculty(faculties, institute_id, false);
        });

        let selected_faculty = $("#faculty option:selected").val();
        if (selected_faculty != "" && selected_faculty != null) {
            $("#dept_div").show();
        } else {
            $("#dept_div").hide();
            $("#cou_div").hide();
            $("#bat_div").hide();
        }
        changeDepartment(departments, selected_faculty, true);

        $('#faculty').on('change', function() {
            let faculty_id = this.value;
            if ( faculty_id != "" && faculty_id != null) {
                $("#dept_div").show();
            } else {
                $("#dept_div").hide();
                $("#cou_div").hide();
                $("#bat_div").hide();
            }
            changeDepartment(departments, faculty_id, false);
        });

        let selected_department = $("#department option:selected").val();
        if (selected_department != "" && selected_department != null) {
            $("#cou_div").show();
        } else {
            $("#cou_div").hide();
            $("#bat_div").hide();
        }
        changeCourse(courses, selected_department, true);

        $('#department').on('change', function() {
            let department_id = this.value;
            if ( department_id != "" && department_id != null) {
                $("#cou_div").show();
            } else {
                $("#cou_div").hide();
                $("#bat_div").hide();
            }
            changeCourse(courses, department_id, false);
        });

        let selected_course = $("#course option:selected").val();
        if (selected_course != "" && selected_course != null) {
            $("#bat_div").show();
        } else {
            $("#bat_div").hide();
        }
        changeBatch(batches, selected_course, true);    

        $('#course').on('change', function() {
            let batch_id = this.value;
            if ( batch_id != "" && batch_id != null) {
                $("#bat_div").show();
            } else {
                $("#bat_div").hide();
            }
            changeBatch(batches, batch_id, false);
        }); 
    } else if (userType == "University Staff") {

        //University Staff
        let selected_institute = $("#staff_institute option:selected").val();
        if (selected_institute != "" && selected_institute != null) {
            $("#staff_fac_div").show();
        } else {
            $("#staff_fac_div").hide();
            $("#staff_dept_div").hide();
            $("#staff_cou_div").hide();
        }
        changeStaffFaculty(faculties, selected_institute, true);

        $('#staff_institute').on('change', function() {
            let institute_id = this.value;
            if ( institute_id != "" && institute_id != null) {
                $("#staff_fac_div").show();
            } else {
                $("#staff_fac_div").hide();
                $("#staff_dept_div").hide();
                $("#staff_cou_div").hide();
            }
            changeStaffFaculty(faculties, institute_id, false);
        });

        let selected_faculty = $("#staff_faculty option:selected").val();
        if (selected_faculty != "" && selected_faculty != null) {
            $("#staff_dept_div").show();
        } else {
            $("#staff_dept_div").hide();
            $("#staff_cou_div").hide();
        }
        changeStaffDepartment(departments, selected_faculty, true);

        $('#staff_faculty').on('change', function() {
            let faculty_id = this.value;
            if ( faculty_id != "" && faculty_id != null) {
                $("#staff_dept_div").show();
            } else {
                $("#staff_dept_div").hide();
                $("#staff_cou_div").hide();
            }
            changeStaffDepartment(departments, faculty_id, false);
        });

        let selected_department = $("#staff_department option:selected").val();
        if (selected_department != "" && selected_department != null) {
            $("#staff_cou_div").show();
        } else {
            $("#staff_cou_div").hide();
        }
        changeStaffCourse(courses, selected_department, true);

        $('#staff_department').on('change', function() {
            let department_id = this.value;
            if ( department_id != "" && department_id != null) {
                $("#staff_cou_div").show();
            } else {
                $("#staff_cou_div").hide();
            }
            changeStaffCourse(courses, department_id, false);
        });
    }
});

function changeFaculty(faculties, institute_id, flag) {
    $('#faculty').empty().append('<option value="">[Unspecified]</option>');
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    $('#batch').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(faculties).forEach(([key, value]) => {
        let faculty_id = value['id'];
        if(institute_id == value['institute']) {
            $('#faculty').append('<option ' + (flag && '{{isset($user->faculty) ? $user->faculty : ''}}' == faculty_id ? "selected" : "") +' value="'+faculty_id+'">'+value['name']+'</option>');
        }
    });
}

function changeStaffFaculty(faculties, institute_id, flag) {
    $('#staff_faculty').empty().append('<option value="">[Unspecified]</option>');
    $('#staff_department').empty().append('<option value="">[Unspecified]</option>');
    $('#staff_course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(faculties).forEach(([key, value]) => {
        let faculty_id = value['id'];
        if(institute_id == value['institute']) {
            $('#staff_faculty').append('<option ' + (flag && '{{isset($user->faculty) ? $user->faculty : ''}}' == faculty_id ? "selected" : "") +' value="'+faculty_id+'">'+value['name']+'</option>');
        }
    });
}

function changeDepartment(departments, faculty_id, flag) {
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    $('#batch').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(departments).forEach(([key, value]) => {
        let department_id = value['id'];
        if(faculty_id == value['faculty']) {
            $('#department').append('<option ' + (flag && '{{isset($user->department) ? $user->department : ''}}' == department_id ? "selected" : "") +' value="'+department_id+'">'+value['name']+'</option>');
        }
    });
}

function changeStaffDepartment(departments, faculty_id, flag) {
    $('#staff_department').empty().append('<option value="">[Unspecified]</option>');
    $('#staff_course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(departments).forEach(([key, value]) => {
        let department_id = value['id'];
        if(faculty_id == value['faculty']) {
            $('#staff_department').append('<option ' + (flag && '{{isset($user->department) ? $user->department : ''}}' == department_id ? "selected" : "") +' value="'+department_id+'">'+value['name']+'</option>');
        }
    });
}

function changeCourse(courses, department_id, flag) {
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    $('#batch').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(courses).forEach(([key, value]) => {
        let course_id = value['id'];
        if(department_id == value['department']) {
            $('#course').append('<option ' + (flag && '{{isset($user->course) ? $user->course : ''}}' == course_id ? "selected" : "") +' value="'+course_id+'">'+value['name']+'</option>');
        }
    });
}

function changeStaffCourse(courses, department_id, flag) {
    $('#staff_course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(courses).forEach(([key, value]) => {
        let course_id = value['id'];
        if(department_id == value['department']) {
            $('#staff_course').append('<option ' + (flag && '{{isset($user->course) ? $user->course : ''}}' == course_id ? "selected" : "") +' value="'+course_id+'">'+value['name']+'</option>');
        }
    });
}

function changeBatch(batches, course_id, flag) {
    $('#batch').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(batches).forEach(([key, value]) => {
        let batch_id = value['id'];
        if(course_id == value['course']) {
            $('#batch').append('<option ' + (flag && '{{isset($user->batch) ? $user->batch : ''}}' == batch_id ? "selected" : "") +' value="'+batch_id+'">'+value['name']+'</option>');
        }
    });
}

function deleteDp()
{
    $("#delete-dp").val(1);
    if ('{{$gender}}' == "Female")
        $("#img-dp").attr("src", "/assets/images/default-female-dp.png");
    else 
        $("#img-dp").attr("src", "/assets/images/default-male-dp.png");
}

function checkAll(ele, fid)
{
    if($(ele).prop("checked"))
    {
        $(".position_" + fid).prop("checked", true);
    }
    else
    {
        $(".position_" + fid).prop("checked", false);
    }
}

function checkPosition(ele, position, field)
{
    $('#field_' + field).prop('checked', false);

    if($(ele).prop("checked"))
    {
        if($(".position_" + field).length == $(".position_" + field + ":checked").length)
        {
            $('#field_' + field).prop('checked', true);
        }
    }
}
</script>
@endsection
