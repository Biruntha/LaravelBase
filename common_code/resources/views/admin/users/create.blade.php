@extends("layouts.main")

@section("meta")
    <title>Create User | Placements.lk</title>
@endsection

@section("main-body")
    
<h1 class="page-heading rounded">Create User</h1>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
        <div class="alert alert-danger fade show">{{ $error }}</div>
        @endforeach
    @endif

    <form novalidate action="{{ route('users.store') }}" method="POST" files="true" enctype="multipart/form-data">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    @csrf
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="firstname" class="form-label">First Name <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1" placeholder="First Name" type="text" value="{{old('firstname')}}" name="firstname" required/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="lastname" class="form-label">Last Name <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1" placeholder="Last Name" type="text" value="{{old('lastname')}}" name="lastname" required/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="othername" class="form-label">Other Name</label>
                        <input class="form-control mx-1" placeholder="Other Name" type="text" value="{{old('othername')}}" name="othername"/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="type" class="form-label">Type <strong style="color:red">*</strong></label>
                        <select class="form-control" name="type" id="type">
                            <option value="">[Unspecified]</option>
                            <option {{old('type') == 'University Staff' ? 'selected' : ''}} value="University Staff">University Staff</option>
                            <option {{old('type') == 'Internal User' ? 'selected' : ''}} value="Internal User">Internal User</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="email" class="form-label">Email <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1" placeholder="Email" type="email" value="{{old('email')}}" name="email" required/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="contact" class="form-label">Primary Contact Number <strong style="color:red">*</strong></label>
                        <input class="form-control mx-1 telephone" placeholder="Contact Number" type="text" value="{{old('contact')}}" name="contact" required/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="contact_secondary" class="form-label">Secondary Contact Number</label>
                        <input class="form-control mx-1 telephone" placeholder="Contact Number" type="text" value="{{old('contact_secondary')}}" name="contact_secondary"/>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                            <label for="password" class="form-label">Password <strong style="color:red">*</strong></label>
                            <div class="pos-relative">
                                <input class="form-control" placeholder="Password" type="password" name="password" id="password" required/>
                                <span toggle="#password-field" class="fa fa-fw fa-eye toggle-password icon-password"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" name="gender">
                            <option value="">[Unspecified]</option>
                            <option {{old('gender') == 'Male' ? 'selected' : ''}} value="Male">Male</option>
                            <option {{old('gender') == 'Female' ? 'selected' : ''}} value="Female">Female</option>
                        </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" id="role" style="display:none">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" name="role">
                                @foreach ($roles as $obj)
                                    <option value="{{$obj->id}}" {{old('role') == $obj->id ? 'selected' : ''}}>{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea class="form-control mx-1" placeholder="Remarks" type="text" name="remarks"></textarea>
                        </div>
                    </div>


                    <div class="col-12 col-md-4" id="institute_div" style="display:none">
                        <div class="mb-3">
                            <label for="institute" class="form-label">Institute <strong style="color:red">*</strong></label>
                            <select class="form-control" name="institute" id="institute">
                                <option value="">[Unspecified]</option>
                                @foreach ($institutes as $obj)
                                    <option {{old('institute') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" id="faculty_div" style="display:none">
                        <div class="mb-3">
                            <label for="faculty" class="form-label">Faculty</label>
                            <select class="form-control" name="faculty" id="faculty">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" id="department_div" style="display:none">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" name="department" id="department">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" id="course_div" style="display:none">
                        <div class="mb-3">
                            <label for="course" class="form-label">Course Name</label>
                            <select class="form-control" name="course" id="course">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row border-top-1 m-0 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-3 d-flex">
                            <a type="reset" href="?" class="btn btn-primary-inverse w-100 mx-1">RESET</a>
                            <button type="button" class="btn btn-info w-100 mx-1" onclick="window.location.replace(`{{ route('users.index') }}`)">Cancel</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section("scripts")
<script>
$(document).ready(function() {
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

    $('#type').on('change', function() {
    if ( this.value == "University Staff") {
        $("#institute_div").show();
        $("#faculty_div").show();
        $("#department_div").show();
        $("#course_div").show();            
    } else {
        $("#institute_div").hide();
        $("#faculty_div").hide();
        $("#department_div").hide();
        $("#course_div").hide();
    }
    });
    if ($("#type option:selected").val() == 'University Staff') {
        $("#institute_div").show();
        $("#faculty_div").show();
        $("#department_div").show();
        $("#course_div").show();    
    } else {
        $("#institute_div").hide();
        $("#faculty_div").hide();
        $("#department_div").hide();
        $("#course_div").hide();
    }

    let institutes=@json($institutes);
    let faculties=@json($faculties);
    let departments=@json($departments);
    let courses=@json($courses);

    let selected_institute = $("#institute option:selected").val();
    changeFaculty(faculties, selected_institute);

    let selected_faculty = $("#faculty option:selected").val();
    changeDepartment(departments, selected_faculty);

    let selected_department = $("#department option:selected").val();
    changeCourse(courses, selected_department);

    $('#institute').on('change', function() {
        let institute_id = this.value;
        changeFaculty(faculties, institute_id);
    });

    $('#faculty').on('change', function() {
        let faculty_id = this.value;
        changeDepartment(departments, faculty_id);
    });

    $('#department').on('change', function() {
        let department_id = this.value;
        changeCourse(courses, department_id);
    }); 
});

function changeFaculty(faculties, institute_id) {
    $('#faculty').empty().append('<option value="">[Unspecified]</option>');
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(faculties).forEach(([key, value]) => {
        let faculty_id = value['id'];
        if(institute_id == value['institute']) {
            $('#faculty').append('<option ' + ('{{old('faculty')}}' == faculty_id ? "selected" : "") +' value="'+faculty_id+'">'+value['name']+'</option>');
        }
    });
}

function changeDepartment(departments, faculty_id) {
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(departments).forEach(([key, value]) => {
        let department_id = value['id'];
        if(faculty_id == value['faculty']) {
            $('#department').append('<option ' + ('{{old('department')}}' == department_id ? "selected" : "") +' value="'+department_id+'">'+value['name']+'</option>');
        }
    });
}

function changeCourse(courses, department_id) {
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(courses).forEach(([key, value]) => {
        let course_id = value['id'];
        if(department_id == value['department']) {
            $('#course').append('<option ' + ('{{old('course')}}' == course_id ? "selected" : "") +' value="'+course_id+'">'+value['name']+'</option>');
        }
    });
}
</script>  
@endsection