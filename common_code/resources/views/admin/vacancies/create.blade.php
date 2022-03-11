@extends("layouts.main")

@section("meta")
    <title>Post Vacancy | Placements.lk</title>
@endsection

@section("main-body")
<h1 class="page-heading rounded">Post Vacancy</h1>

<form novalidate action="{{ route('store-vacancy-admin') }}" method="POST" enctype='multipart/form-data'>
    @csrf
    <div class="row" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="company" class="form-label">Company <strong style="color:red">*</strong></label>
                            <select class="searchable-select form-control" name="company" id="company" required>
                                <option value="">[Unspecified]</option>
                                @foreach ($companies as $obj)
                                    <option {{old('company') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->fname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" value="{{old('title')}}" placeholder="Title" type="text" name="title" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                            <label for="type" class="form-label">Type <strong style="color:red">*</strong></label>
                            <select class="form-control" name="type">
                                <option {{old('type') == 'Full-time' ? 'selected' : ''}} value="Full-time">Full-time</option>
                                <option {{old('type') == 'Part-time' ? 'selected' : ''}} value="Part-time">Part-time</option>
                                <option {{old('type') == 'Contract-based' ? 'selected' : ''}} value="Contract-based">Contract-based</option>
                                <option {{old('type') == 'Internship' ? 'selected' : ''}} value="Internship">Internship</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="field" class="form-label">Field <strong style="color:red">*</strong></label>
                            <select class="searchable-select form-control" name="field" id="field" required>
                                <option value="">[Unspecified]</option>
                                @foreach ($fields as $obj)
                                    <option {{old('field') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display:none" id="pos_div">
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <select class="searchable-select form-control" name="position" id="position" data-api="">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3 text-left">
                            <label for="image" class="form-label">Cover Image <span class='font-80 fw-normal'>(Recommended size: 1500 X 1000px)</span></label>
                            <input type='file' name='image' class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="skills" class="form-label">Skills needed</label>
                            <select class="searchable-select js-example-basic-multiple " data-type="multiple" data-api="/api/skills" multiple="multiple" id="skills" name="skills[]">
                                @foreach ($skills as $obj)
                                    <option {{(is_array(old('skills')) and in_array($obj->id, old('skills'))) ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mt-2">
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea class="form-control mx-1" placeholder="Short Description" type="text" name="short_description">{{old('short_description')}}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="long_description" class="form-label">Long Description</label>
                            <textarea class="form-control mx-1" style="min-height:400px;border:1px solid #ccc" placeholder="Long Description" type="text" name="long_description" id="long_description">{!! \App\Services\StringUtils::cleanScriptsFromHtmlTags(old('long_description'))!!}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-warning my-2" type="button" onclick="$('#more-details').slideToggle()"><i class="fas fa-plus me-2"></i> Show more info</button>
                    </div>
                </div>
                <div class="row mt-2" style="display:none" id="more-details">
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="action_type"  title="What should we do if an applicant applies for this job?" class="form-label">Application Method</label>
                            <select class="form-control" id="action_type" name="action_type" required>
                                <option {{old('action_type') == 'SEND_EMAIL' ? 'selected' : ''}} value="SEND_EMAIL">Send to company email</option>
                                <option {{old('action_type') == 'COLLECT_CV' ? 'selected' : ''}} value="COLLECT_CV">Collect CVs in Placements.lk</option>
                                <option {{old('action_type') == 'FORWARD_URL' ? 'selected' : ''}} value="FORWARD_URL">Forward to this url</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display:none" id="action_url_div">
                        <div class="mb-3">
                            <label for="action_url" class="form-label">Action URL <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" value="action_url" placeholder="Action URL" type="text" name="action_url" id="action_url" required />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="salary_min" class="form-label d-block">Salary - minimum</label>
                            <input class="form-control mx-1" value="{{old('salary_min')}}" placeholder="Salary - minimum" type="number" min="0" name="salary_min" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="salary_max" class="form-label d-block">Salary - maximum</label>
                            <input class="form-control mx-1" value="{{old('salary_max')}}" placeholder="Salary - maximum" type="number" min="0" name="salary_max" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="no_of_positions" class="form-label d-block">No of Positions</label>
                            <input class="form-control mx-1" value="{{old('no_of_positions')}}" placeholder="No of Positions" type="number" min="0" name="no_of_positions" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="searchable-select form-control" name="country" id="country">
                                <option value="">[Unspecified]</option>
                                @foreach ($countries as $obj)
                                    <option value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" id='region_div' style='display:none'>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <select class="searchable-select form-control" name="region" id="sel-region2">
                                <option value="">[Unspecified]</option>
                                @foreach ($regions as $obj)
                                    <option {{old('region') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="closing_date" class="form-label">Closing Date</label>
                            <input type="date" min="{{date('Y-m-d')}}" value="{{old('closing_date')}}" placeholder="Closing Date" name="closing_date" class="form-control" />
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display:none" id="institute_div">
                        <div class="mb-3">
                            <label for="institute" class="form-label">Institute <span class="fw-normal font-80">(If you want it to be a targetted Vacancy)</span></label>
                            <select class="searchable-select form-control" name="institute" id="institute">
                                <option value="">[Unspecified]</option>
                                @foreach ($institutes as $obj)
                                    <option {{old('institute') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display:none" id="faculty_div">
                        <div class="mb-3">
                            <label for="faculty" class="form-label">Faculty</label>
                            <select class="form-control" name="faculty" id="faculty">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display:none" id="department_div">
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" name="department" id="department">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4" style="display:none" id="course_div">
                        <div class="mb-3">
                            <label for="course" class="form-label">Course</label>
                            <select class="form-control" name="course" id="course">
                                <option value="">[Unspecified]</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row border-top-1 m-0 pt-4 mt-2">
                    <div class="col-12 col-md-3 offset-md-9 d-flex">
                        <button type="button" onclick="window.location.replace(`{{ route('vacancies-admin') }}`)" class="btn btn-primary-inverse w-100 mx-1">BACK</button>
                        <button type="submit" class="btn btn-primary w-100 mx-1">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
                    
@endsection

@section("scripts")
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<script>
let positions=@json($positions);
$(document).ready(function() {
    $('#country').on('change', function() {
      if ( this.value == "213") {
        $("#region_div").show();
      } else {
        $("#region_div").hide();
      }
    });
    if ($("#country option:selected").val() == '213') {
        $("#region_div").show();
    } else {
        $("#region_div").hide();
    }

    $('#action_type').on('change', function() {
      if ( this.value == "FORWARD_URL") {
        $("#action_url_div").show();
        $("#action_url").val('');
      } else {
        $("#action_url_div").hide();
        $("#action_url").val('action_url');
      }
    });
    if ($("#action_type option:selected").val() == 'FORWARD_URL') {
        $("#action_url_div").show();
        $("#action_url").val('');
    } else {
        $("#action_url_div").hide();
        $("#action_url").val('action_url');
    }

    let selected_field = $("#field option:selected").val();
    changePosition(positions, selected_field, true);        

    $('#field').on('change', function() {
        let field_id = this.value;
        $("#position").attr("data-api", "/api/positions?field=" + field_id);
        $("#position").parent().find(".searchable-select-overlay").attr("data-api", "/api/positions?field=" + field_id);
        
        changePosition(positions, field_id, false);
    });
    
    let careerfair_code = $("#careerfair_code").val();
    if (careerfair_code == null || careerfair_code.length == 0) {
        $("#institute_div").show();
        $("#faculty_div").show();
        $("#department_div").show();
        $("#course_div").show();

        let institutes=@json($institutes);
        let faculties=@json($faculties);
        let departments=@json($departments);
        let courses=@json($courses);

        let selected_institute = $("#institute option:selected").val();
        if (selected_institute != "" && selected_institute != null) {
            $("#faculty_div").show();
        } else {
            $("#faculty_div").hide();
            $("#department_div").hide();
            $("#course_div").hide();
        }
        changeFaculty(faculties, selected_institute, true);

        $('#institute').on('change', function() {
            let institute_id = this.value;
            if (institute_id != "" && institute_id != null) {
                $("#faculty_div").show();
            } else {
                $("#faculty_div").hide();
                $("#department_div").hide();
                $("#course_div").hide();
            }
            changeFaculty(faculties, institute_id, false);
        });

        let selected_faculty = $("#faculty option:selected").val();
        if (selected_faculty != "" && selected_faculty != null) {
            $("#department_div").show();
        } else {
            $("#department_div").hide();
            $("#course_div").hide();
        }
        changeDepartment(departments, selected_faculty, true);

        $('#faculty').on('change', function() {
            let faculty_id = this.value;
            if (faculty_id != "" && faculty_id != null) {
                $("#department_div").show();
            } else {
                $("#department_div").hide();
                $("#course_div").hide();
            }
            changeDepartment(departments, faculty_id, false);
        });

        let selected_department = $("#department option:selected").val();
        if (selected_department != "" && selected_department != null) {
            $("#course_div").show();
        } else {
            $("#course_div").hide();
        }
        changeCourse(courses, selected_department, true);        

        $('#department').on('change', function() {
            let department_id = this.value;
            if (department_id != "" && department_id != null) {
                $("#course_div").show();
            } else {
                $("#course_div").hide();
            }
            changeCourse(courses, department_id, false);
        });
    }

    //CK Editor =====================================================
    CKEDITOR.editorConfig = function( config ) {
        config.toolbarGroups = [
            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
            { name: 'forms', groups: [ 'forms' ] },
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
            { name: 'links', groups: [ 'links' ] },
            { name: 'insert', groups: [ 'insert' ] },
            { name: 'styles', groups: [ 'styles' ] },
            { name: 'colors', groups: [ 'colors' ] },
            { name: 'tools', groups: [ 'tools' ] },
            { name: 'others', groups: [ 'others' ] },
            { name: 'about', groups: [ 'about' ] }
        ];

        config.removeButtons = 'ExportPdf,Save,NewPage,Preview,Print,Templates,Cut,Copy,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,RemoveFormat,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Image,Table,SpecialChar,PageBreak,Iframe,ShowBlocks,About';
    };
    CKEDITOR.replace( 'long_description');
});

function changeFaculty(faculties, institute_id, flag) {
    $('#faculty').empty().append('<option value="">[Unspecified]</option>');
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(faculties).forEach(([key, value]) => {
        let faculty_id = value['id'];
        if(institute_id == value['institute']) {
            $('#faculty').append('<option ' + (flag && '{{old('faculty')}}' == faculty_id ? "selected" : "") +' value="'+faculty_id+'">'+value['name']+'</option>');
        }
    });
}

function changeDepartment(departments, faculty_id, flag) {
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(departments).forEach(([key, value]) => {
        let department_id = value['id'];
        if(faculty_id == value['faculty']) {
            $('#department').append('<option ' + (flag && '{{old('department')}}' == department_id ? "selected" : "") +' value="'+department_id+'">'+value['name']+'</option>');
        }
    });
}

function changeCourse(courses, department_id, flag) {
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(courses).forEach(([key, value]) => {
        let course_id = value['id'];
        if(department_id == value['department']) {
            $('#course').append('<option ' + (flag && '{{old('course')}}' == course_id ? "selected" : "") +' value="'+course_id+'">'+value['name']+'</option>');
        }
    });
}

function changePosition(positions, field_id, flag) {
    $('#position').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(positions).forEach(([key, value]) => {
        let position_id = value['id'];
        if(field_id == value['field']) {
            $('#position').append('<option ' + (flag && '{{old('position')}}' == position_id ? "selected" : "") +' value="'+position_id+'">'+value['name']+'</option>');
        }
    });
}

function ObjectItemCallback(sourceId, addedData){
    if(sourceId == "position")
    {
        positions.push(addedData);
        console.log("data added");
    }
}
</script>  
@endsection
