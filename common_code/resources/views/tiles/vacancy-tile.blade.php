
<div class="col-sm-{{$smSize != null ? $smSize : 12}} col-md-{{$mdSize != null ? $mdSize : 6}} col-xl-{{$xlSize != null ? $xlSize : 4}}">
    <div class="mb-2 mb-md-4 p-2 bg-white rounded shadow-md app-card">
        <div class="card-badge bg-theme d-none">VACANCY</div>
        <a href="{{ route('vacancies-view', $obj->code) }}">
            <div class="d-flex">
                <div class="img-cont">
                    @if($obj->logo == null)
                    <div class="text-dp">{{ $obj->cname[0] }}</div>
                    @else
                    <img alt="vacancy-img" class="lazy-load d-none" src="{{asset('storage/Company/LogoImages/small/'.$obj->logo)}}" />
                    <img alt="vacancy-img" class="placeholder-img" src="/assets/images/company.png" />
                    @endif
                </div>
                <div class="details">
                    <h4>{{ $obj->title }}</h4>
                    <p>{{ $obj->cname }}</p>
                    <p class="field">{{ $obj->field }}</p>
                </div>
            </div>
            <div class="d-flex more-details">
                <div class="detail-cont"><i class="far fa-star"></i> {{ $obj->type }}</div>

                @if(isset($obj->closing_date) && date('Y-m-d') > $obj->closing_date)
                    <div class="detail-cont text-danger fw-bold"><i class="far fa-calendar-times"></i> Expired</div>  
                @else
                    <div class="detail-cont"><i class="far fa-flag"></i> {{ $obj->country }}</div>  
                @endif    
            </div>
        </a>
        <div class="d-flex actions">
            <div class="detail-cont w-50 share-link" data-title="Vacancy: {{$obj->title}} at {{ $obj->cname }} via Placements LK " data-link="{{ route('vacancies-view', $obj->code) }}"><i class="far fa-share-square"></i> Share</div>
            <a class="w-50 d-block" href="{{ route('vacancies-view', $obj->code) }}">
                @if(Auth::check() && Auth::user()->type != 'JobSeeker')
                    <div class="action-cont bg-grad text-white"><i class="far fa-arrow-right"></i> View Details</div> 
                @else
                    @if(isset($obj->closing_date) && date('Y-m-d') > $obj->closing_date)
                        <div class="action-cont bg-grad text-white"><i class="far fa-arrow-right"></i> View Details</div>  
                    @else
                        <div class="action-cont bg-grad text-white"><i class="far fa-arrow-right"></i> {{in_array($obj->id, $applied_vacancies) ? 'Applied' : 'Apply Now'}}</div> 
                    @endif 
                @endif 
            </a>     
        </div>
    </div>
</div>