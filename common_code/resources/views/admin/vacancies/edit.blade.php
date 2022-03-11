@extends("layouts.main")

@section("meta")
    <title>Update Vacancy By Admin | Placements.lk</title>
@endsection

@section("main-body")
    <h1 class="page-heading rounded">Update Vacancy By Admin  - {{ $vacancy->title }}</h1>

    <form novalidate action="{{ route('update-vacancy-admin', $vacancy->code) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="row" id="filter-panel">
            <div class="col-12">
                <div class="my-3 p-3 bg-body rounded shadow-sm">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="company" class="form-label">Company <strong style="color:red">*</strong></label>
                                <select class="searchable-select form-control" name="company" id="company" disabled>
                                    <option value="">[Unspecified]</option>
                                    @foreach ($companies as $obj)
                                        <option {{$vacancy->company == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->fname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <strong style="color:red">*</strong></label>
                                <input class="form-control mx-1" value="{{ $vacancy->title }}" placeholder="Title" type="text" name="title" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                                <label for="type" class="form-label">Type <strong style="color:red">*</strong></label>
                                <select class="form-control" name="type">
                                    <option value="">[Unspecified]</option>
                                    <option {{$vacancy->type == 'Full-time' ? 'selected' : ''}} value="Full-time">Full-time</option>
                                    <option {{$vacancy->type == 'Part-time' ? 'selected' : ''}} value="Part-time">Part-time</option>
                                    <option {{$vacancy->type == 'Contract-based' ? 'selected' : ''}} value="Contract-based">Contract-based</option>
                                    <option {{$vacancy->type == 'Internship' ? 'selected' : ''}} value="Internship">Internship</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="field" class="form-label">Field <strong style="color:red">*</strong></label>
                                <select class="searchable-select form-control" name="field" id="field" required>
                                    <option value="">[Unspecified]</option>
                                    @foreach ($fields as $obj)
                                        <option {{old('field') == '' ? ($vacancy->field == $obj->id ? 'selected' : '') : (old('field') == $obj->id ? 'selected' : '') }} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display:none" id="pos_div">
                            <div class="mb-3">
                                <label for="position" class="form-label">Position</label>
                                <select class="searchable-select form-control" name="position" id="position" data-api="/api/positions?field={{$vacancy->field}}">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="skills" class="form-label">Skills</label>
                                <select class="searchable-select js-example-basic-multiple " data-type="multiple" data-api="/api/skills" multiple="multiple" id="skills" name="skills[]">
                                    @foreach ($skills as $obj)
                                        <option {{ in_array($obj->id, $vacancySkills) ? 'selected="selected"' : '' }} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3 text-left">
                                <label for="image" class="form-label">Cover Image</label>
                                @if(isset($vacancy->cover_img))
                                <img src="{{asset('storage/Vacancy/CoverImages/'.$vacancy->cover_img)}}" style="max-width:200px" class="img-thumbnail-app img-fluid" alt="Gallery image"/>
                                @endif
                                <input type='file' name='image' class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="short_description" class="form-label">Short Description</label>
                                <textarea class="form-control mx-1" placeholder="Short Description" type="text" name="short_description">{{ old('short_description', $vacancy->short_description) }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="long_description" class="form-label">Long Description</label>
                                <textarea class="form-control mx-1" style="min-height:400px;border:1px solid #ccc" placeholder="Long Description" type="text" name="long_description" id="long_description">{!! \App\Services\StringUtils::cleanScriptsFromHtmlTags(old('long_description', $vacancy->long_description)) !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="action_type" title="What should we do if an applicant applies for this job?" class="form-label">Application Method</label>
                                <select class="form-control" id="action_type" name="action_type" required>
                                    <option {{$vacancy->action_type == 'SEND_EMAIL' ? 'selected' : ''}} value="SEND_EMAIL">Send to company email</option>
                                    <option {{$vacancy->action_type == 'COLLECT_CV' ? 'selected' : ''}} value="COLLECT_CV">Collect CVs in Placements.lk</option>
                                    <option {{$vacancy->action_type == 'FORWARD_URL' ? 'selected' : ''}} value="FORWARD_URL">Forward to this url</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display:none" id="action_url_div">
                            <div class="mb-3">
                                <label for="action_url" class="form-label">Action URL <strong style="color:red">*</strong></label>
                                <input class="form-control mx-1" value="{{ $vacancy->action_url }}" placeholder="Action URL" type="text" name="action_url" id="action_url" required />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="salary_min" class="form-label d-block">Salary - minimum</label>
                                <input class="form-control mx-1" value="{{ old('salary_min', $vacancy->salary_min) }}" placeholder="Salary - minimum" type="number" min="0" name="salary_min" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="salary_max" class="form-label d-block">Salary - maximum</label>
                                <input class="form-control mx-1" value="{{ old('salary_max', $vacancy->salary_max) }}" placeholder="Salary - maximum" type="number" min="0" name="salary_max" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="no_of_positions" class="form-label d-block">No of Positions</label>
                                <input class="form-control mx-1" value="{{ old('no_of_positions', $vacancy->no_of_positions) }}" placeholder="No of Positions" type="number" min="0" name="no_of_positions" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="searchable-select form-control" name="country" id="country">
                                    <option value="">[Unspecified]</option>
                                    @foreach ($countries as $obj)
                                        <option {{old('country') == '' ? ($vacancy->country == $obj->id ? 'selected' : '') : (old('country') == $obj->id ? 'selected' : '') }} value="{{$obj->id}}">{{$obj->name}}</option>
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
                                        <option {{old('region') == '' ? ($vacancy->region == $obj->id ? 'selected' : '') : (old('region') == $obj->id ? 'selected' : '') }} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="closing_date" class="form-label">Closing Date</label>
                                <input type="date" min="{{date('Y-m-d')}}" value="{{ old('closing_date', $vacancy->closing_date) }}" placeholder="Closing Date" name="closing_date" class="form-control" />
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label for="institute" class="form-label">Institute</label>
                                <select class="searchable-select form-control" name="institute" id="institute">
                                    <option value="">[Unspecified]</option>
                                    @foreach ($institutes as $obj)
                                        <option {{old('institute') == '' ? ($vacancy->institute == $obj->id ? 'selected' : '') : (old('institute') == $obj->id ? 'selected' : '') }} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display:none" id="fac_div">
                            <div class="mb-3">
                                <label for="faculty" class="form-label">Faculty</label>
                                <select class="form-control" name="faculty" id="faculty">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display:none" id="dept_div">
                            <div class="mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select class="form-control" name="department" id="department">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4" style="display:none" id="cou_div">
                            <div class="mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select class="form-control" name="course" id="course">
                                    <option value="">[Unspecified]</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row border-top-1 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <button type="button" onclick="window.location.replace(`{{ route('admin-vacancies') }}`)" class="btn btn-primary-inverse w-100 mx-1">BACK</button>
                            <button type="submit" class="btn btn-primary w-100 mx-1">UPDATE</button>
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

    let action_url=@json($vacancy->action_url);
    console.log(action_url);
    $('#action_type').on('change', function() {
      if ( this.value == "FORWARD_URL") {
        $("#action_url_div").show();
        if (action_url == null || action_url.length == 0) {
            $("#action_url").val('');
        }
      } else {
        $("#action_url_div").hide();
        $("#action_url").val('action_url');
      }
    });
    if ($("#action_type option:selected").val() == 'FORWARD_URL') {
        $("#action_url_div").show();
        if (action_url == null || action_url.length == 0) {
            $("#action_url").val('');
        }
    } else {
        $("#action_url_div").hide();
        $("#action_url").val('action_url');
    }

    let institutes=@json($institutes);
    let faculties=@json($faculties);
    let departments=@json($departments);
    let courses=@json($courses);    

    let selected_institute = $("#institute option:selected").val();
    if (selected_institute != "" && selected_institute != null) {
        $("#fac_div").show();
    } else {
        $("#fac_div").hide();
        $("#dept_div").hide();
        $("#cou_div").hide();
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
        }
        changeFaculty(faculties, institute_id, false);
    });

    let selected_faculty = $("#faculty option:selected").val();
    if (selected_faculty != "" && selected_faculty != null) {
        $("#dept_div").show();
    } else {
        $("#dept_div").hide();
        $("#cou_div").hide();
    }
    changeDepartment(departments, selected_faculty, true);

    $('#faculty').on('change', function() {
        let faculty_id = this.value;
        if ( faculty_id != "" && faculty_id != null) {
            $("#dept_div").show();
        } else {
            $("#dept_div").hide();
            $("#cou_div").hide();
        }
        changeDepartment(departments, faculty_id, false);
    });

    let selected_department = $("#department option:selected").val();
    if (selected_department != "" && selected_department != null) {
        $("#cou_div").show();
    } else {
        $("#cou_div").hide();
    }
    changeCourse(courses, selected_department, true);

    $('#department').on('change', function() {
        let department_id = this.value;
        if ( department_id != "" && department_id != null) {
            $("#cou_div").show();
        } else {
            $("#cou_div").hide();
        }
        changeCourse(courses, department_id, false);
    });

    let selected_field = $("#field option:selected").val();
    changePosition(positions, selected_field, true);        

    $('#field').on('change', function() {
        let field_id = this.value;

        $("#position").attr("data-api", "/api/positions?field=" + field_id);
        $("#position").parent().find(".searchable-select-overlay").attr("data-api", "/api/positions?field=" + field_id);
        
        changePosition(positions, field_id, false);
    });
});

function changeFaculty(faculties, institute_id, flag) {
    $('#faculty').empty().append('<option value="">[Unspecified]</option>');
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(faculties).forEach(([key, value]) => {
        let faculty_id = value['id'];
        if(institute_id == value['institute']) {
            $('#faculty').append('<option ' + ('{{old('faculty')}}' == '' ? (flag && '{{$vacancy->faculty}}' == faculty_id ? 'selected' : '') : ('{{old('faculty')}}' == faculty_id ? 'selected' : '')) +' value="'+faculty_id+'">'+value['name']+'</option>');
        }
    });
}

function changeDepartment(departments, faculty_id, flag) {
    $('#department').empty().append('<option value="">[Unspecified]</option>');
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(departments).forEach(([key, value]) => {
        let department_id = value['id'];
        if(faculty_id == value['faculty']) {
            $('#department').append('<option ' + ('{{old('department')}}' == '' ? (flag && '{{$vacancy->department}}' == department_id ? 'selected' : '') : ('{{old('department')}}' == department_id ? 'selected' : '')) +' value="'+department_id+'">'+value['name']+'</option>');
        }
    });
}

function changeCourse(courses, department_id, flag) {
    $('#course').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(courses).forEach(([key, value]) => {
        let course_id = value['id'];
        if(department_id == value['department']) {
            $('#course').append('<option ' + ('{{old('course')}}' == '' ? (flag && '{{$vacancy->course}}' == course_id ? 'selected' : '') : ('{{old('course')}}' == course_id ? 'selected' : '')) +' value="'+course_id+'">'+value['name']+'</option>');
        }
    });
}

function changePosition(positions, field_id, flag) {
    $('#position').empty().append('<option value="">[Unspecified]</option>');
    Object.entries(positions).forEach(([key, value]) => {
        let position_id = value['id'];
        if(field_id == value['field']) {
            $('#position').append('<option ' + ('{{old('position')}}' == '' ? (flag && '{{$vacancy->position}}' == position_id ? 'selected' : '') : ('{{old('position')}}' == position_id ? 'selected' : '')) +' value="'+position_id+'">'+value['name']+'</option>');
        }
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

function ObjectItemCallback(sourceId, addedData){
    if(sourceId == "position")
    {
        positions.push(addedData);
        console.log("data added");
    }
}
</script>
@endsection