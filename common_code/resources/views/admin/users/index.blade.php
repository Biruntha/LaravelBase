@extends("layouts.main")

@section("meta")
    <title>Users | Placements.lk</title>
@endsection

@section("main-body")

<!-- FILTER FORM -->
<form novalidate action="{{ route('users.index') }}" method="GET">
    <div class="row">
        <div class="col-md-4">
            <h1 class="page-heading rounded">Users <span class="badge rounded-pill bg-warning text-white ms-3">{{$data->total()}}</span></h1>
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
                        <a href="{{route('users.create')}}" class="btn btn-primary btn-primary-inverse float-end">
                            <i class="fas fa-plus mx-1"></i> <span class="">ADD NEW</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END OF FILTER FORM -->
    <div class="row" style="display:none" id="filter-panel">
        <div class="col-12">
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" name="role" id="role">
                                <option value="">Any</option>
                                @foreach ($roles as $obj)
                                    <option value="{{$obj->id}}" {{$roleFilter == $obj->id ? 'selected' : ''}}>{{$obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-control" name="type" id="type">
                                <option value="">Any</option>
                                <option {{$typeFilter == 'Company' ? 'selected' : ''}} value="Company">Company</option>
                                <option {{$typeFilter == 'JobSeeker' ? 'selected' : ''}} value="JobSeeker">JobSeeker</option>
                                <option {{$typeFilter == 'University Staff' ? 'selected' : ''}} value="University Staff">University Staff</option>
                                <option {{$typeFilter == 'Internal User' ? 'selected' : ''}} value="Internal User">Internal User</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" id="status">
                                <option value="">Any</option>
                                <option {{$statusFilter == '1' ? 'selected' : ''}} value="1">Active</option>
                                <option {{$statusFilter == '0' ? 'selected' : ''}} value="0">Inactive</option>
                            </select>
                        </div>
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
</form>

<div class="row mt-1">
    <div class="col-12">
        <div class="my-3 p-1 px-3 bg-white rounded shadow-md ">
            <table class="table table-mobile card-row" id="data-table">
                <thead>
                    <tr id="thead-data">
                        <th class="col-md">Name</th>
                        <th class="col-sm">Type</th>
                        <th class="col-sm">Email</th>
                        <th class="col-sm">Joined Date</th>
                        <th class="col-md">Contact Numbers</th>
                        <th class="col-md">Status</th>
                        <th class="col-xs no-sort">Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-data">
                    @foreach($data as $user)
                    <tr>
                            <td>{{ $user->fname }} {{ $user->lname }}</td>
                            <td>{{ $user->type }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ explode(" ",$user->created_at)[0] ? explode(" ",$user->created_at)[0] : '-' }}</td>
                            <td>{{ $user->contact }} {{ $user->contact_secondary }}</td>
                            <td>
                                <div>
                                    @if($user->status == 1)
                                    <a href="{{ route('users.show',[$user->code, 'sm_op'=>1]) }}"><span class="badge badge-pill bg-success">Active</span></a>
                                    @else
                                    <a href="{{ route('users.show',[$user->code, 'sm_op'=>1]) }}"><span class="badge badge-pill bg-danger">Inactive</span></a>
                                    @endif
                                </div>
                            </td>
                            <td class="d-flex">
                                @permission(['can-view-users'])
                                    <a href="{{ route('users.show', $user->code) }}"  title="View" class="btn btn-sm btn-success btn-action"><i class="fas fa-eye mx-1"></i></a>
                                @endpermission()
                                @permission(['can-delete-users'])
                                    <form novalidate action="{{ route('users.destroy', $user->id) }}" title="Delete" class="confirm-action" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger  mx-1 btn-action"><i class="fas fa-trash"></i></button>
                                    </form>
                                @endpermission()
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
