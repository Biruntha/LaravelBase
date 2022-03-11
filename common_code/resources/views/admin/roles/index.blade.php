@extends("layouts.main")

@section("meta")
    <title>Roles | Placements.lk</title>
@endsection

@section("main-body")

<!-- FILTER FORM -->
<form novalidate action="{{ route('roles.index') }}" method="GET">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">Roles <span class="badge rounded-pill bg-warning text-white ms-3">{{sizeOf($data)}}</span></h1>
        </div>
        <div class="col-md-8">
            <div class="filter-cont">
                <div class="row">
                    <div class="col-md-6 col-6 d-flex search-box">
                        <input type="text"  class="form-control" name="search" value="{{$search}}" placeholder="Search"/>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="col-md-3 col-3">
                    </div>
                    <div class="col-md-3 col-3">
                        <a href="{{route('roles.create')}}" class="btn btn-primary btn-primary-inverse float-end">
                            <i class="fas fa-plus mx-1"></i> <span class="">ADD NEW</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- END OF FILTER FORM -->

<!-- SITES TABLE -->
<div class="row mt-1">
    <div class="col-12">
        <div class="my-3 p-1 px-3 bg-white rounded shadow-md ">
            <table class="table table-mobile card-row" id="data-table">
                <thead>
                    <tr id="thead-data">
                        <th class="col-sm">Name</th>
                        <th>Authorities</th>
                        <th class="col-xs no-sort">View</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach($data as $role)
                    <tr>
                            <td class="col-sm">{{ $role->name }}</td>
                            <td><div>
                                @foreach($role->permissions as $permission)
                                    @php
                                        $permission_name = ucfirst(str_replace('-',' ',$permission->name));
                                    @endphp
                                    @if(strpos($permission_name,'delete'))
                                        <span class="badge badge-pill bg-danger"> {{ $permission_name }}</span>
                                    @elseif(strpos($permission_name,'edit'))
                                        <span class="badge badge-pill bg-secondary"> {{ $permission_name }}</span>
                                    @elseif(strpos($permission_name,'add'))
                                        <span class="badge badge-pill bg-success"> {{ $permission_name }}</span>
                                    @else
                                        <span class="badge badge-pill bg-primary"> {{ $permission_name }}</span>
                                    @endif


                                @endforeach
                            </div>
                            </td>
                            <td>
                                <a href="{{ route('roles.show',$role->id) }}"  title="View" class="btn btn-sm btn-success btn-action"><i class="fas fa-eye mx-1"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- END OF SITES -->
@endsection

@section("scripts")
<script>
    $(document).ready(function(){
        buildDataTable(false);
    });
</script>
@endsection