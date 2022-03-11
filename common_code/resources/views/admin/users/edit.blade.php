@extends("layouts.main")

@section("meta")
    <title>Edit User | Placements.lk</title>
@endsection

@section("main-body")
    
<h1 class="page-heading rounded">Update User  - {{ $user->fname }} {{ $user->lname }}</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade show">{{ $error }}</div>
        @endforeach
    @endif

    <form novalidate action="{{ route('users.update', $user->id) }}" method="POST" files="true" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="fname" class="form-label">{{$user->type == 'Company' ? 'Company Name' : 'First Name'}} <strong style="color:red">*</strong></label>
                                <input class="form-control mx-1" value="{{ old('fname', $user->fname) }}" placeholder="First Name" type="text" name="fname" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4 {{$user->type == 'Company' ? 'd-none' : ''}}">
                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name <strong style="color:red">*</strong></label>
                                <input class="form-control mx-1" value="{{ old('lname', $user->lname) }}" placeholder="Last Name" type="text" name="lname" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                            <label for="oname" class="form-label">Other Name</label>
                            <input class="form-control mx-1" placeholder="Other Name" type="text" value="{{ old('oname', $user->oname) }}" name="oname"/>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                            <label for="type" class="form-label">Type <strong style="color:red">*</strong></label>
                            <select class="form-control" name="type" id="type" style="pointer-events: none">
                                <option value="">[Unspecified]</option>
                                <option {{$user->type == 'University Staff' ? 'selected' : ''}} value="University Staff">University Staff</option>
                                <option {{$user->type == 'Internal User' ? 'selected' : ''}} value="Internal User">Internal User</option>
                                <option {{$user->type == 'Company' ? 'selected' : ''}} value="Company">Company</option>
                                <option {{$user->type == 'JobSeeker' ? 'selected' : ''}} value="JobSeeker">JobSeeker</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <strong style="color:red">*</strong></label>
                                <input class="form-control mx-1" placeholder="Email" type="text" name="email" value="{{old('email', $user->email)}}" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                            <label for="contact" class="form-label">Primary Contact Number <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1 telephone" placeholder="Contact Number" type="text" value="{{old('contact', $user->contact)}}" name="contact" required/>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                            <label for="contact_secondary" class="form-label">Secondary Contact Number</label>
                            <input class="form-control mx-1 telephone" placeholder="Contact Number" type="text" value="{{old('contact_secondary', $user->contact_secondary)}}" name="contact_secondary"/>
                            </div>
                        </div>
                        <!-- <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                            <label for="password" class="form-label">Password</label>
                            <input class="form-control mx-1" placeholder="Password" type="password" name="password"/>
                            </div>
                        </div> -->

                        <div class="col-12 col-md-4 {{$user->type == 'Company' ? 'd-none' : ''}}">
                            <div class="mb-3 text-left">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" name="gender">
                                <option value="">[Unspecified]</option>
                                <option {{$user->gender == 'Male' ? 'selected' : ''}} value="Male">Male</option>
                                <option {{$user->gender == 'Female' ? 'selected' : ''}} value="Female">Female</option>
                            </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Introductory Text</label>
                                <textarea class="form-control mx-1" placeholder="Remarks" type="text" name="remarks">{{$user->remarks}}</textarea>
                            </div>
                        </div>
                        @if($user->type == 'Company')
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input class="form-control mx-1" value="{{ $user->website }}" placeholder="Website" type="text" name="website" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input class="form-control mx-1" value="{{ $user->facebook }}" placeholder="Facebook" type="text" name="facebook" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="linkedin" class="form-label">Linkedin</label>
                                <input class="form-control mx-1" value="{{ $user->linkedin }}" placeholder="Linkedin" type="text" name="linkedin" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input class="form-control mx-1" value="{{ $user->twitter }}" placeholder="Twitter" type="text" name="twitter" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input class="form-control mx-1" value="{{ $user->instagram }}" placeholder="Instagram" type="text" name="instagram" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
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
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control mx-1" placeholder="Short Description" type="text" name="short_description">{{ $user->short_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea class="form-control mx-1" placeholder="Long Description" type="text" name="long_description">{{ $user->long_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">  
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="logo" class="form-label">Logo</label>
                                    @if(isset($user->logo))
                                    <img src="{{asset('storage/Company/LogoImages/'.$user->logo)}}" style="max-width:250px;" class="d-block img-thumbnail-app img-fluid" alt="Gallery image"/>
                                    @endif
                                    <input type='file' name='logo' class="form-control">
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-12 col-md-4" id="role" style="display:none">
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" name="role" id="role">
                                    @foreach ($roles as $obj)
                                        <option value="{{$obj->id}}" {{old('role') == '' ? ($user->role == $obj->id ? 'selected' : '') : (old('role') == $obj->id ? 'selected' : '') }}>{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                       
                        @if($user->type == 'JobSeeker' or $user->type == 'University Staff')
                        <div class="col-12 col-md-4" id="inst_se_div">
                            <div class="mb-3">
                                <label for="institute" class="form-label">Institute <strong style="color:red" class="{{$user->type == 'JobSeeker' ? 'd-none' : ''}}">*</strong></label>
                                <select class="form-control" name="institute" id="institute">
                                    <option value="">[Unspecified]</option>
                                    @foreach ($institutes as $obj)
                                        <option {{$user->institute == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" id="fac_se_div">
                            <div class="mb-3">
                                <label for="faculty" class="form-label">Faculty</label>
                                <select class="form-control" name="faculty" id="faculty">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" id="dept_se_div">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-control" name="department" id="department">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" id="cou_se_div">
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-control" name="course" id="course">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        @endif
                        @if($user->type == 'JobSeeker')
                        <div class="col-12 col-md-4" id="bat_se_div">
                            <div class="mb-3">
                                <label for="batch" class="form-label">Batch</label>
                                <select class="form-control" name="batch" id="batch">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        @endif

                        @if($user->role != null)
                        <div class="col-12 col-md-12">
                            <div class="mb-3">
                                <label for="description" class="form-label">Authorities</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <roleset>
                                    <legend></legend>
                                    <label class="checkboxLabel"><label>Check All Authorities</label>
                                        <input type="checkbox" id="check-all" onchange="onChangeClickCheckAll('all')">
                                        <span class="checkmark"></span>
                                    </label>
                                </roleset>
                            </div>
                        </div>
                        <div class="row" id="">
                            @foreach($types as $per_type)
                            <div class="col-md-3">
                                <div class="form-group">
                                    <roleset>
                                        <legend><label>{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</legend>
                                        </label>
                                        <label class="checkboxLabel"><label>Check All Authorities For
                                                {{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}</label>
                                            <input type="checkbox" class="check-all-permission"
                                                id='check-{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}'
                                                onchange="onChangeClickCheckAll('{{ucwords(strtolower(str_replace('-',' ',$per_type->type)))}}')">
                                            <span class="checkmark"></span>
                                        </label>
                                        @foreach($permissions as $permission)
                                        @if($per_type->type == $permission->type)
                                        <label
                                            class="checkboxLabel">{{ucwords(strtolower(str_replace('-',' ',str_replace('CAN',' ',str_replace($per_type->type,' ',$permission->name)))))}}
                                            <input type="checkbox"
                                                class="check-all-permission check-box-click-{{$per_type->type}}"
                                                name="permission[]" value="{{$permission->id}}" @if(is_array(old('permission'))
                                                && in_array($permission->id,old('permission')) ||
                                            $user->permissions->contains($permission->id)) checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </roleset>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="row border-top-1 m-0 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-3 d-flex">
                            <a type="reset" href="?" class="btn btn-primary-inverse w-100 mx-1">RESET</a>
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('users.show',$user->code) }}`)">Cancel</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">UPDATE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('scripts')
<script>
    function disableButton() {
        setTimeout(() => {
            $('#role-update-button').attr('disabled', 'disabled');
        }, 1500);
    }

    function onChangeClickCheckAll(val) {
        id = `check-${val}`;
        if (val == 'all') {
            if ($(`#${id}`).is(':checked')) {
                $('.check-all-permission').prop('checked', true);
            } else {
                $('.check-all-permission').prop('checked', false);
            }
        } else {
            if ($(`#${id}`).is(':checked')) {
                $(`.check-box-click-${val}`).prop('checked', true);
            } else {
                $(`.check-box-click-${val}`).prop('checked', false);
            }
        }
    }
</script>
<script>
$(document).ready(function() {
    let institutes=@json($institutes);
    let faculties=@json($faculties);
    let departments=@json($departments);
    let courses=@json($courses);
    let batches=@json($batches);
    
    $('#type').on('change', function() {
    if ( this.value == "Internal User") {
        $("#role").show();
    } else {
        $("#role").hide();
    }
    });
    if ($("#type option:selected").val() == 'Internal User') {
        $("#role").show();
    } else {
        $("#role").hide();
    }

    let selected_institute = $("#institute option:selected").val();
    changeFaculty(faculties, selected_institute, true);

    $('#institute').on('change', function() {
        let institute_id = this.value;
        changeFaculty(faculties, institute_id, false);
    });

    let selected_faculty = $("#faculty option:selected").val();
    changeDepartment(departments, selected_faculty, true);

    $('#faculty').on('change', function() {
        let faculty_id = this.value;
        changeDepartment(departments, faculty_id, false);
    });

    let selected_department = $("#department option:selected").val();
    changeCourse(courses, selected_department, true);

    $('#department').on('change', function() {
        let department_id = this.value;
        changeCourse(courses, department_id, false);
    });

    let selected_course = $("#course option:selected").val();
    changeBatch(batches, selected_course, true);    

    $('#course').on('change', function() {
        let course_id = this.value;
        changeBatch(batches, course_id, false);
    });    
});

function changeFaculty(faculties, institute_id, flag) {
    $('#faculty').empty().append('<option value="">[Unspecified]</option>');
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(faculties).forEach(([key, value]) => {
        let faculty_id = value['id'];
        if(institute_id == value['institute']) {
            $('#faculty').append('<option ' + ('{{old('faculty')}}' == '' ? (flag && '{{isset($user->faculty) ? $user->faculty : ''}}' == faculty_id ? 'selected' : '') : ('{{old('faculty')}}' == faculty_id ? 'selected' : '')) +' value="'+faculty_id+'">'+value['name']+'</option>');
        }
    });
}

function changeDepartment(departments, faculty_id, flag) {
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(departments).forEach(([key, value]) => {
        let department_id = value['id'];
        if(faculty_id == value['faculty']) {
            $('#department').append('<option ' + ('{{old('department')}}' == '' ? (flag && '{{isset($user->department) ? $user->department : ''}}' == department_id ? 'selected' : '') : ('{{old('department')}}' == department_id ? 'selected' : '')) +' value="'+department_id+'">'+value['name']+'</option>');
        }
    });
}

function changeCourse(courses, department_id, flag) {
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(courses).forEach(([key, value]) => {
        let course_id = value['id'];
        if(department_id == value['department']) {
            $('#course').append('<option ' + ('{{old('course')}}' == '' ? (flag && '{{isset($user->course) ? $user->course : ''}}' == course_id ? 'selected' : '') : ('{{old('course')}}' == course_id ? 'selected' : '')) +' value="'+course_id+'">'+value['name']+'</option>');
        }
    });
}

function changeBatch(batches, course_id, flag) {
    $('#batch').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(batches).forEach(([key, value]) => {
        let batch_id = value['id'];
        if(course_id == value['course']) {
            $('#batch').append('<option ' + ('{{old('batch')}}' == '' ? (flag && '{{isset($user->batch) ? $user->batch : ''}}' == batch_id ? 'selected' : '') : ('{{old('batch')}}' == batch_id ? 'selected' : '')) +' value="'+batch_id+'">'+value['name']+'</option>');
        }
    });
}
</script>
@endsection
