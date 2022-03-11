@extends("layouts.main")

@section("meta")
    <title>Vacancies | Placements.lk</title>
@endsection

@section("main-body")
    
<form novalidate action="{{ route('admin-vacancies') }}" method="GET" onsubmit="$('.page-loader').show()">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">Vacancies By Admin <span class="badge rounded-pill bg-warning text-white ms-3">{{$data->total()}}</span></h1>
        </div>
        <div class="col-md-8">
            <div class="filter-cont">
                <div class="row">
                    <div class="col-md-6 col-6 d-flex search-box">
                        <input type="text"  class="form-control" name="search" value="{{$search}}" placeholder="Search"/>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="col-md-3 col-3">
                        <button type="button" class="btn btn-primary btn-primary-inverse" id="btn-filter">
                            <i class="fas fa-filter"></i> <span>FILTER</span>
                        </button>
                    </div>
                    <div class="col-md-3 col-3">
                        <a href="{{route('create-vacancy-admin')}}" class="btn btn-primary btn-primary-inverse float-end">
                            <i class="fas fa-plus mx-1"></i> <span class="">ADD NEW</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="display:none" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="company" class="form-label">Company</label>
                            <select class="form-control" name="company" id="company">
                                <option value="">Any</option>
                                @foreach ($companies as $obj)
                                    <option {{$companyFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->fname}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-control" name="type">
                                <option value="">Any</option>
                                <option {{$typeFilter == 'Full-time' ? 'selected' : ''}} value="Full-time">Full-time</option>
                                <option {{$typeFilter == 'Part-time' ? 'selected' : ''}} value="Part-time">Part-time</option>
                                <option {{$typeFilter == 'Contract-based' ? 'selected' : ''}} value="Contract-based">Contract-based</option>
                                <option {{$typeFilter == 'Internship' ? 'selected' : ''}} value="Internship">Internship</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="field" class="form-label">Field</label>
                            <select class="form-control" name="field" id="field">
                                <option value="">Any</option>
                                @foreach ($fields as $obj)
                                    <option {{$fieldFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <select class="form-control" name="position" id="position">
                                <option value="">Any</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select class="form-control" name="country" id="country">
                                <option value="">Any</option>
                                @foreach ($countries as $obj)
                                    <option {{$countryFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6" id='region_div' style='display:none'>
                        <div class="mb-3">
                            <label for="region" class="form-label">Region</label>
                            <select class="form-control" name="region">
                                <option value="">Any</option>
                                @foreach ($regions as $obj)
                                    <option {{$regionFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label for="skill" class="form-label">Skill</label>
                            <select class="form-control" name="skill">
                                <option value="">Any</option>
                                @foreach ($skills as $obj)
                                    <option {{$skillFilter == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row border-top-1 m-0 pt-4 mt-2">
                        <div class="col-12 col-md-3 offset-md-9 d-flex">
                            <a type="reset" href="?" class="btn btn-primary-inverse w-100 mx-1">RESET</a>
                            <button type="submit" class="btn btn-primary w-100 mx-1">SUBMIT</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row mt-1">
    <div class="col-12">
        <div class="my-3 p-1 px-3 bg-white rounded shadow-md ">
            <table class="table table-mobile card-row" id="data-table">
                <thead>
                    <tr id="thead-data">
                        <th class="col-md">Title</th>
                        <th class="col-md">Type</th>
                        <th class="col-md">Company Name</th>
                        <!-- <th class="col-md">Field</th> -->
                        <th class="col-md">Position</th>
                        <!-- <th class="col-md">Salary Min</th>
                        <th class="col-md no-sort">Salary Max</th> -->
                        <th class="col-xs">No of Positions</th>
                        <!-- <th class="col-md no-sort">Country</th>
                        <th class="col-md no-sort">Region</th>
                        <th class="col-md no-sort">Cover Img</th>
                        <th class="col-md no-sort">Institute</th>
                        <th class="col-md no-sort">Faculty</th>
                        <th class="col-md no-sort">Department</th>
                        <th class="col-md no-sort">Course</th>
                        <th class="col-md no-sort">Short Description</th>
                        <th class="col-md no-sort">Long Description</th> -->
                        <!-- <th class="col-md no-sort">Skills</th> -->
                        <th class="col-xs no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $obj->title }}</td>
                            <td>{{ $obj->type }}</td>
                            <td>{{ $obj->cname }}</td>
                            <!-- <td>{{ $obj->field }}</td> -->
                            <td>{{ $obj->position }}</td>
                            <!-- <td>{{ $obj->salary_min }}</td>
                            <td>{{ $obj->salary_max }}</td> -->
                            <td>{{ $obj->no_of_positions }}</td>
                            <!-- <td>{{ $obj->country }}</td>
                            <td>{{ $obj->region }}</td>
                            <td><img src="{{asset('storage/Vacancy/CoverImages/'.$obj->cover_img)}}" class="img-thumbnail-app" alt="Gallery image"/></td>
                            <td>{{ $obj->institute }}</td>
                            <td>{{ $obj->faculty }}</td>
                            <td>{{ $obj->department }}</td>
                            <td>{{ $obj->course }}</td>
                            <td>{{ $obj->short_description }}</td>
                            <td>{{ $obj->long_description }}</td> -->
                            <!-- <td>{{ $obj->skills }}</td> -->
                            <td>
                                <div class="d-flex">
                                    @permission(['can-update-admin_vacancies'])
                                    <a href="{{ route('edit-vacancy-admin', $obj->code) }}" title="Edit" class="btn btn-sm btn-primary btn-action"><i class="fas fa-edit"></i></a>
                                    @endpermission()
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center pagintation-cont">
                {!! $data->appends($_GET)->links("others.pagination-tiles") !!}
            </div>
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script>
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

    let positions=@json($positions);

    let selected_field = $("#field option:selected").val();
    changePosition(positions, selected_field);        

    $('#field').on('change', function() {
        let field_id = this.value;
        changePosition(positions, field_id);
    });
});

function changePosition(positions, field_id) {
    $('#position').empty().append('<option value="">Any</option>');
    Object.entries(positions).forEach(([key, value]) => {
        let position_id = value['id'];
        if(field_id == value['field']) {
            $('#position').append('<option ' + ('{{$positionFilter}}' == position_id ? "selected" : "") +' value="'+position_id+'">'+value['name']+'</option>');
        }
    });
}
</script>
@endsection