@extends("layouts.main-web")

@section("meta")
    <title>Vacancy: {{ $vacancy->title }} at {{ $vacancy->cname }} | Placements.lk</title>

    <script type="application/ld+json">
    {
      "@context" : "https://schema.org/",
      "@type" : "JobPosting",
      "title" : "{{ $vacancy->title }}",
      "description" : "{!! \App\Services\StringUtils::cleanScriptsFromHtmlTags($vacancy->long_description) !!}",
      "identifier": {
        "@type": "PropertyValue",
        "name": "Google",
        "value": "{{$vacancy->code}}"
      },
      "datePosted" : "{{explode(" ",$vacancy->created_at)[0]}}",
      "validThrough" : "{{$vacancy->closing_date}}",
      "applicantLocationRequirements": {
        "@type": "Country",
        "name": "{{ $vacancy->country }}"
      },
      "jobLocationType": "TELECOMMUTE",
      "employmentType": "{{ $vacancy->type == "Full-time" ? "FULL_TIME" : "INTERN" }}",
      "hiringOrganization" : {
        "@type" : "Organization",
        "name" : "{{ $vacancy->cname }}",
        "sameAs" : "{{$company->website}}",
        "logo" : "{{$company->logo == null ? asset('assets/images/company.png') : asset('storage/Company/LogoImages/'.$company->logo)}}"
      },
      "baseSalary": {
        "@type": "MonetaryAmount",
        "currency": "LKR",
        "value": {
          "@type": "QuantitativeValue",
          "value": {{$vacancy->salary_max}},
          "unitText": "MONTH"
        }
      }
    }
    </script>
    
    <meta property="og:title" content="{{ $vacancy->title }} at {{ $vacancy->cname }} | Placements.lk" />
    <meta property="og:description" content="{{ $vacancy->cname }} is looking for {{ $vacancy->title }}. Apply via Placements.lk" />
    <meta property="og:url" content="{{ route('vacancies-view', $vacancy->code) }}" />

    @if($company->logo != null)
        <meta property="og:image" content="{{asset('storage/Company/LogoImages/'.$company->logo)}}" />
        <meta property="og:image:secure_url" content="{{asset('storage/Company/LogoImages/'.$company->logo)}}" />
    @endif
@endsection

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    {{ $vacancy->title }} at {{ $vacancy->cname }}
</h1>
@endsection

@section("main-body")
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
@if(Auth::check() && Auth::user()->type == 'JobSeeker' && isset($jobSeeker) && (is_null($jobSeeker->cv) or empty($jobSeeker->cv)))
<a href="{{ route('edit-profile') }}#cv-cont"><div class="alert alert-danger dont-hide">You haven't uploaded your CV yet <input type="button" value="Upload now" class="ms-2 btn btn-danger" /> </div></a>
@endif

<div class="row mt-1">
    <div class="col-lg-8 col-md-6">
        <div class="bg-white rounded shadow-md p-3 py-4">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($company->logo == null)
                        <img alt="profile-img" class="img-thumbnail-app profile-img img-fluid" src="{{asset('assets/images/company.png')}}" />
                    @else
                        <img alt="profile-img" class="img-thumbnail-app profile-img img-fluid" src="{{asset('storage/Company/LogoImages/'.$company->logo)}}"/>
                    @endif
                </div>
                <div class="col-md-8 ps-md-4 text-center text-md-start">
                    <h1 class="m-0 p-0 fw-bolder mt-2 mt-md-1">{{ $vacancy->title }} </h1>
                    <a href="/companies/{{$company->code}}"><Strong class="text-theme">{{ $vacancy->cname }}</strong></a>
                    <p class="my-1">
                        <a href="/vacancies?field={{ $vacancy->field_id}}" title="View other vacancies in {{ $vacancy->field }}">{{ $vacancy->field }}</a> 
                        @if($vacancy->position_id != null)
                            <strong class="text-warning"><i class="fas fa-chevron-right"></i></strong> <a href="/vacancies?field={{ $vacancy->field_id}}&position={{ $vacancy->position_id}}" title="View other {{ $vacancy->position }} vacancies">{{ $vacancy->position }}</a>
                        @endif
                    </p>
                    {!! $vacancy->no_of_positions > 1 ? "<p class='my-1'>No of positions: ".$vacancy->no_of_positions."</p>" : "" !!}
                    <strong>
                        {{ $vacancy->salary_min == null ? "[Unspecified Salary Range]" :  $vacancy->salary_min." LKR ".($vacancy->salary_max == null ? "" : "- ".$vacancy->salary_max." LKR") }} 
                    </strong>
                </div>
            </div>
            <div class="row mt-4 mt-md-2">
                <div class="col-xl-3 col-lg-6">
                    <div class="detail-cont m-0 mt-1 py-2"><i class="far fa-star"></i> 
                    <a href="/vacancies?type={{ $vacancy->type}}&field={{ $vacancy->field_id}}" title="View other {{ $vacancy->type }} vacancies in {{ $vacancy->field }}">{{ $vacancy->type }}</a>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="detail-cont m-0 mt-1 py-2"><i class="far fa-flag"></i> 
                        <a href="/vacancies?country={{ $vacancy->country_id}}" title="View other vacancies in {{ $vacancy->country }}">{{ $vacancy->country }}</a>
                        @if($vacancy->region != null)
                            <strong class="text-warning"><i class="fas fa-chevron-right"></i></strong> <a href="/vacancies?country={{ $vacancy->country_id}}&region={{ $vacancy->region_id}}" title="View other vacancies in {{ $vacancy->region }}, {{ $vacancy->country }}">{{ $vacancy->region }}</a>
                        @endif
                    </div>   
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="detail-cont m-0 mt-1 py-2"><i class="far fa-clock"></i> Posted on {{explode(" ",$vacancy->created_at)[0]}}</div>
                </div>
                <div class="col-xl-3 col-lg-6">
                    <div class="detail-cont m-0 mt-1 py-2 share-link" style="cursor:pointer" data-title="Vacancy: {{$vacancy->title}} at {{ $vacancy->cname }} via Placements LK " data-link="{{ route('vacancies-view', $vacancy->code) }}"><i class="far fa-share-square"></i> Share</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4  col-md-6 mt-2 mt-md-0">
        <div class="p-3 bg-white rounded shadow-md">
            @if(Auth::check())
                @if(Auth::user()->type == 'JobSeeker')
                    @if(isset($vacancy->closing_date) && date('Y-m-d') > $vacancy->closing_date)
                        <button class="w-100 btn btn-danger py-3 mb-2"><i class="far fa-calendar-times"></i> Vacancy Expired</button>                                       
                    @else
                        <button {{ $isApplied ? 'style=display:none' : '' }} id="apply" onclick='$("#applyModal").modal("show")' class="w-100 btn btn-success py-3"><i class="fas fa-arrow-right margin-animation"></i> Apply to this Job</button> 
                    @endif

                    <a {{ $isApplied ? '' : 'style=display:none' }} id="applied" class="w-100 btn btn-primary-inverse py-3" href="/my-applications"><i class="fas fa-check"></i> Applied to this Job</a>

                    <button {{ $isWishList ? '' : 'style=display:none' }} id="remove-from-wishlist" class="added w-100 btn btn-primary-inverse py-3 mt-2" onclick='addToWishlist(this, {{$vacancy->id}}, false)'><i class="fas fa-heart"></i> Remove from your Wishlist</button>
                    <button {{ $isWishList ? 'style=display:none' : '' }} id="add-to-wishlist" class="open w-100 btn btn-primary-inverse py-3 mt-2" onclick='addToWishlist(this, {{$vacancy->id}})'><i class="far fa-heart"></i> Add to your Wishlist</button>
                @endif
            @else
                @if(isset($vacancy->closing_date) && date('Y-m-d') > $vacancy->closing_date)
                    <button class="w-100 btn btn-danger py-3"><i class="far fa-calendar-times"></i> Vacancy Expired</button>                                       
                @else
                    <a href="/login?rurl={{ route('vacancies-view', $vacancy->code) }}" class="w-100 btn btn-success py-3"><i class="fas fa-arrow-right margin-animation"></i> Apply to this Job</a>
                @endif
                                                       
                <a href="/login?rurl={{ route('vacancies-view', $vacancy->code) }}" class="w-100 btn btn-primary-inverse py-3 mt-2"><i class="fas fa-heart"></i> Add to Wishlist</a>
            @endif

            <a class="d-block w-100 btn btn-primary-inverse py-3 mt-2" href="/companies/{{$company->code}}"><i class="far fa-building"></i> View Company Profile</a>
        </div>
    </div> 
</div>

<div class="row mt-1">  
    <div class="col-lg-8  col-md-8">
        <div class="my-3 p-3 bg-white rounded shadow-md links">            
            @if($vacancy->skills != null && !empty($vacancy->skills))
                <div class="my-3">
                    @foreach(explode("||", $vacancy->skills) as $skill)
                        @if(!empty($skill))
                            <a title="View all {{explode('---', $skill)[0]}} vacancies" target="_blank" href="/vacancies?skill={{explode('---', $skill)[1]}}" class="p-2 px-3 bg-light shadow-sm rounded me-2">#{{explode("---", $skill)[0]}}</a>
                        @endif
                    @endforeach
                </div>
            @endif

            <p>{{ empty($vacancy->short_description)  ? "-" : $vacancy->short_description }}</p>
            <p>{!! \App\Services\StringUtils::cleanScriptsFromHtmlTags($vacancy->long_description) !!}</p>

            @if(isset($vacancy->cover_img))
                <img src="{{asset('storage/Vacancy/CoverImages/'.$vacancy->cover_img)}}" class="img-thumbnail-app mt-3 img-fluid" alt="Gallery image"/>
            @endif
        </div>
    </div>  
    <div class="col-lg-4  col-md-4">
        <div class="my-3 p-3 bg-white rounded shadow-md">
            <strong class="d-block w-100 text-center mb-4">Company Contact Details</strong>
            
            @if(!empty($company->website))
            <a href="{{str_contains(strtolower($company->website), 'http') ? '' : '//'}}{{$company->website}}?from=placements.lk" target="_blank">
                <div class="detail-btn m-0 mt-2 py-3 d-flex justify-content-center">
                    <h1 class="m-0 me-2"><i class="far fa-globe"></i> </h2>
                    <div>
                        {{$company->website}}
                    </div>
                </div>
            </a>
            @endif

            @if(!empty($company->email))
            <a href="mailto://{{$company->email}}">
                <div class="detail-btn m-0 mt-2 py-3 d-flex justify-content-center">
                    <h1 class="m-0 me-2"><i class="far fa-envelope"></i> </h2>
                    <div>
                        {{$company->email}}
                    </div>
                </div>
            </a>
            @endif

            <div class="d-flex justify-content-center">
                <a href="https://www.google.com/search?q={{$vacancy->cname}}&from=placements.lk" target="_blank" class="w-100 mx-1" title="Google search results">
                    <div class="detail-btn m-0 mt-2 py-3 text-center">
                        <h1 class="m-0"><i class="fab fa-google"></i> </h2>
                    </div>
                </a>

                @if($company->facebook != null)
                <a href="{{$company->facebook}}" target="_blank" class="w-100 mx-1" title="Facebook">
                    <div class="detail-btn m-0 mt-2 py-3 text-center">
                        <h1 class="m-0"><i class="fab fa-facebook"></i> </h2>
                    </div>
                </a>
                @endif

                @if($company->instagram != null)
                <a href="{{$company->instagram}}" target="_blank" class="w-100 mx-1" title="Instagram">
                    <div class="detail-btn m-0 mt-2 py-3 text-center">
                        <h1 class="m-0"><i class="fab fa-instagram"></i> </h2>
                    </div>
                </a>
                @endif

                @if($company->linkedin != null)
                <a href="{{$company->linkedin}}" target="_blank" class="w-100 mx-1" title="LinkedIn">
                    <div class="detail-btn m-0 mt-2 py-3 text-center">
                        <h1 class="m-0"><i class="fab fa-linkedin"></i> </h2>
                    </div>
                </a>
                @endif

                @if($company->twitter != null)
                <a href="{{$company->twitter}}" target="_blank" class="w-100 mx-1" title="Twitter">
                    <div class="detail-btn m-0 mt-2 py-3 text-center">
                        <h1 class="m-0"><i class="fab fa-twitter"></i> </h2>
                    </div>
                </a>
                @endif
            </div>
        </div>

        <div class="my-3 p-3 bg-white rounded shadow-md">
            <strong class="d-block w-100 text-center">Total Views</strong>
            <Strong style="color:#20c997;font-size:4rem" class="d-block w-100 text-center mt-n2">{{$totalViewsCount}}</strong>
            <p class="text-center m-0 mt-n2">{{$totalUniqueViewsCount}} unique users</p>
        </div>

        @if($totalApplicationsCount > 0)
        <div class="my-3 p-3 bg-white rounded shadow-md">
            <strong class="d-block w-100 text-center mb-4">Total Applicants: {{ $totalApplicationsCount }}</strong>
            
            <div id="application-chart"></div>
        </div>
        @endif
    </div>
</div>

<div class="row">
    @if(sizeOf($last_10_vacancies) > 0)
        <div class="col-12">
            <div class="my-3 mt-5 p-3 bg-white rounded">
                <strong class="d-block w-100 text-center">Other Vacancies from {{$company->com_name}}</strong>            
            </div>       
        </div>

        <div class="col-12">
            <div class="row">
                @foreach ($last_10_vacancies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 6)
                    @php ($smSize = 12)
                    @include("tiles.vacancy-tile")
                @endforeach   
            </div>    
        </div>  
    @endif   


    @if(sizeOf($field_related_vacancies) > 0)
        <div class="col-12">
            <div class="my-3 mt-5 p-3 bg-white rounded">
                <strong class="d-block w-100 text-center">Similar Vacancies from other companies</strong>            
            </div>          
        </div>

        <div class="col-12">
            <div class="row">
                @foreach ($field_related_vacancies as $obj)
                    @php ($xlSize = 4)
                    @php ($mdSize = 6)
                    @php ($smSize = 12)
                    @include("tiles.vacancy-tile")
                @endforeach 
            </div>    
        </div>  
    @endif  
</div>
@endsection

@section("scripts")
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
    @if(sizeOf($vacancy_applications_daywise_count) > 0)
        Morris.Bar({
            element: 'application-chart',
            data: [
                @foreach ($vacancy_applications_daywise_count as $obj)
                    { y: "{{ explode(" ",$obj->created_at)[0] }}", a: {{$obj->count}} },  
                @endforeach
            ],
            xkey: 'y',
            ykeys: ['a'],
            yLabelFormat: function(y){return y != Math.round(y)?'':y;},
            labels: ['Applicants'],
            barColors: ["#20c997"],
            grid: false,
            hideHover: 'always'
        });

        setTimeout(() => {
            $('#application-chart svg text').attr("font-family", "poppins");    
            $('#application-chart svg text').css("font", "14px poppins");
        }, 500);
    @endif
</script>  

<!-- Apply Job MODAL ===================================================== -->
<div class="modal fade hide" id="applyModal" tabindex="-1" aria-labelledby="applyModal" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content" style="margin-top:80px">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
            <div class="alert alert-success text-center dont-hide m-0">We will share your contact details and CV/Resume with {{$vacancy->cname}}. Please confirm your application. </br><b>Wish you all the best.</b></div>
          @if($vacancy->action_type == "FORWARD_URL")
            <div class="alert alert-warning text-center dont-hide m-0 mt-3">You will be redirected to {{$vacancy->cname}} careers portal. </br>You may have to apply there as well.</div>
          @endif
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn bg-light p-2 px-3"  data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn bg-success p-2 px-3 text-white" data-bs-dismiss="modal" onclick='applyJob({{$vacancy->id}})'>Confirm <i class="fas fa-arrow-right margin-animation"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection