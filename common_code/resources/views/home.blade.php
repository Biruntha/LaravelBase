@extends("layouts.main-web")

@section("meta")
    <title>Placements.lk | The #1 candidate-centric Recruitment Ecosystem in Sri Lanka</title>
@endsection

@section("banner")
<div class="banner-cont bg-grad p-2 mb-5">
  <svg class="bubble left" viewBox="0 0 900 1200" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"><g transform="translate(472.583010492509 319.39517466412696)"><path d="M125.9 -180.1C162 -147 189.4 -108.7 204.7 -65.6C220 -22.5 223.2 25.5 215.5 76.4C207.7 127.2 189.1 180.9 151.4 199.9C113.6 218.9 56.8 203.2 9.8 189.7C-37.2 176.2 -74.5 165 -116 147.2C-157.5 129.5 -203.3 105.2 -232.3 64.1C-261.2 23.1 -273.3 -34.8 -258.1 -84.6C-242.9 -134.3 -200.5 -176 -153 -205.4C-105.4 -234.8 -52.7 -251.9 -3.9 -246.5C44.9 -241.1 89.7 -213.2 125.9 -180.1" fill="#ffffff"></path></g></svg>
    
  <img src="/assets/images/student.png" alt="student" class="banner-img" />

  <svg viewbox="0 0 10 2" class="d-none d-md-block">
    <text x="5" y="1" text-anchor="middle" font-size="1" fill="#fff" stroke-width=".008" stroke="none" font-family="poppins" style="font-weight:bolder;opacity:0.1">PLACEMENTS.LK</text>
  </svg>

    <h1 class="text-center text-white px-3">
      The best candidate-centric <br class="d-none d-md-block"/>Recruitment Ecosystem in Sri Lanka
      <br/>
        <a href="/vacancies" class="btn btn-warning text-white ms-2 font-100 px-3 mt-3" style="text-shadow:none">View Jobs <i class="far fa-arrow-right ms-2"></i></a>
        
        @if(Auth::check() and Auth::user()->type == 'Company')
            <a href="/company/vacancies/create" class="btn btn-danger text-white ms-2 font-100 px-3 mt-3" style="text-shadow:none">Post Jobs<i class="far fa-arrow-right ms-2"></i></a>
        @elseif(!Auth::check())
            <a href="/company/signup?rurl=https://placements.lk/company/vacancies/create" class="btn btn-danger text-white ms-2 font-100 px-3 mt-3" style="text-shadow:none">Post Jobs<i class="far fa-arrow-right ms-2"></i></a>
        @endif
    </h1>

  <div class="curve-divider d-none">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="shape-fill"></path>
    </svg>
  </div>
</div>

<div class="curve-divider-mobile d-block d-md-block bg-standard">
</div>
<div class="search-cont bg-standard">
    <form novalidate method="GET" action="/vacancies">
        <div style="position:relative">
            <div class="row search-box-main w-100">
                <div class="col-md-6 offset-md-3 col-12">
                <div class="bg-white rounded p-2 d-flex">
                    <input type="search" name="search" placeholder="Job Title or Keywords" class="form-control rounded placeholder-animation" />
                    <button type="submit" class="btn btn-success text-white ms-2 px-4 px-md-5"><i class="far fa-search"></i></i></button>
                </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section("main-body")

<div class="row mb-5 bg-standard">
    <div class="col-12">
        <h1 class="page-heading rounded text-center mt-3 mt-md-0">Vacancies by Fields</h1>

        <div class="row mb-4 mb-md-5">
            @if(sizeOf($fields) > 0)
                <div class="col-12">
                    <div class="row">
                        @foreach ($fields as $obj)
                            @if($obj->name != "Other")
                                @php ($xlSize = 4)
                                @php ($mdSize = 6)
                                @php ($smSize = 12)
                                @include("tiles.field-tile")
                            @endif
                        @endforeach  

                        @foreach ($fields as $obj)
                            @if($obj->name == "Other")
                                @php ($xlSize = 4)
                                @php ($mdSize = 6)
                                @php ($smSize = 12)
                                @include("tiles.field-tile")
                            @endif
                        @endforeach   
                    </div>    
                </div> 
                <div class="col-12 text-center">
                    <a href="{{ route('vacancies-jobseeker') }}" class="btn btn-success text-white ms-2 font-100 p-3 px-5 mt-3 mx-2" style="text-shadow:none">View all Vacancies<i class="far fa-arrow-right margin-animation"></i></a>
                    <a href="{{ route('vacancies-jobseeker') }}?type=Internship" class="btn btn-info text-white ms-2 font-100 p-3 px-5 mt-3 mx-2" style="text-shadow:none">View all Internships<i class="far fa-arrow-right margin-animation"></i></a>
                </div> 
            @endif   
        </div>
    </div>

    <div class="col-12">
        @if(sizeOf($last_vacancies) > 0)
            <h1 class="page-heading rounded text-center mt-3 mt-md-0">Latest Vacancies</h1>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="row">
                        @foreach ($last_vacancies as $obj)
                            @php ($xlSize = 4)
                            @php ($mdSize = 6)
                            @php ($smSize = 12)
                            @include("tiles.vacancy-tile")
                        @endforeach   

                        <div class="col-12 text-center d-block d-md-none">
                            <a href="{{ route('vacancies-jobseeker') }}" class="btn btn-success text-white ms-2 font-100 p-3 px-5 mt-3 mx-2" style="text-shadow:none">View all Vacancies<i class="far fa-arrow-right margin-animation"></i></a>
                        </div>
                    </div>    
                </div>  
            </div>
        @endif   
    </div>

    <div class="col-12">
        @if(sizeOf($highlighted_companies) > 0)
            <h1 class="page-heading rounded text-center mt-3 mt-md-0">Featured Companies</h1>
            <div class="row mb-4 mb-md-5">
                @foreach ($highlighted_companies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 6)
                    @php ($smSize = 12)
                    @include("tiles.company-tile")
                @endforeach

                <div class="col-12 text-center">
                    <a href="{{ route('companies-jobseeker') }}" class="btn btn-success text-white ms-2 font-100 p-3 px-5 mt-3 mx-2" style="text-shadow:none">View all Companies <i class="far fa-arrow-right margin-animation"></i></a>
                </div>
            </div>
        @endif 
    </div>
</div>

{{--
<div class="row">
    highlighted Field related vacancies
    @if(sizeOf($highlighted_field_vacancies) > 0)
        <div class="col-12">
            <div class="row">
                @foreach ($highlighted_field_vacancies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 4)
                    @php ($smSize = 12)
                    @include("tiles.vacancy-tile")
                @endforeach   
            </div>    
        </div>  
    @endif   
</div>

<div class="row">
    Most viewed vacancies
    @if(sizeOf($top_24_viewed_vacancies) > 0)
        <div class="col-12">
            <div class="row">
                @foreach ($top_24_viewed_vacancies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 4)
                    @php ($smSize = 12)
                    @include("tiles.vacancy-tile")
                @endforeach   
            </div>    
        </div>  
    @endif   
</div>

<div class="row">
    Jobseeker field related 12 vacancies
    @if(sizeOf($top_12_js_field_related_vacancies) > 0)
        <div class="col-12">
            <div class="row">
                @foreach ($top_12_js_field_related_vacancies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 4)
                    @php ($smSize = 12)
                    @include("tiles.vacancy-tile")
                @endforeach   
            </div>    
        </div>  
    @endif   
</div>

<div class="row">
    Jobseeker position related 12 vacancies
    @if(sizeOf($top_12_js_position_related_vacancies) > 0)
        <div class="col-12">
            <div class="row">
                @foreach ($top_12_js_position_related_vacancies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 4)
                    @php ($smSize = 12)
                    @include("tiles.vacancy-tile")
                @endforeach   
            </div>    
        </div>  
    @endif   
</div>
--}}

@endsection

@section("scripts")
<script>
    let closeCount = localStorage.getItem("PLACEMENTS-AD-CLOSE") ? parseInt(localStorage.getItem("PLACEMENTS-AD-CLOSE") + "") : 0;
    function hideAd()
    {
        localStorage.setItem("PLACEMENTS-AD-CLOSE", (closeCount+1));
    }

    if(closeCount <= 3)
    {
        setTimeout(() => {
            //$("#adModal").modal("show");        
        }, 2000);
    }
</script>
@endsection