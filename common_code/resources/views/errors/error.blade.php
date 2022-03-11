@extends("layouts.main-web")

@section("meta")
    <title>Placements.lk</title>
@endsection

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Sorry, something went wrong
</h1>
@endsection

@section("main-body")
<div class="app-container p-2">
<div class="row">
    <div class="col-md-8 col-sm-12 offset-md-2">
        <div class="bg-white shadow-md rounded p-3 p-md-5 mb-5 mt-4" style="align-items: center;position:relative">
        <div class="row">
            <div class="col-12 text-center">
                <h1 class="text-center fw-bold">Sorry, something went wrong</h1>
                <img src="/assets/images/pages/500.jpg" alt="500" class="img-fluid m-auto"  style="max-width:400px"/>
                <a href="/" class="btn btn-success p-4 px-5 mt-4 d-block">Go to home page</a>
            </div>
        </div>
        </div>
    </div>
</div>
</div>
@endsection