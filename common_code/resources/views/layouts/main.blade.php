<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Placements.lk is the Sri Lanka's #1 Recruitment and Job Searching platform with structured candidate data and end-to-end applicants management system.">
    <meta name="keywords" content="Job Portal, Job Board, Job Seeking, Recruitment, Vacancies, Vacancies in Sri Lanka, Jobs, Job, Career, COmpanies in Sri Lanka, Work, Opportunities, Universities, HR System, Placements,">
    <meta name="author" content="Placements LK (Pvt) Ltd.">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @yield("meta")

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Placements.lk | The #1 candidate-centric Recruitment Ecosystem in Sri Lanka" />
    <meta property="og:description" content="Placements.lk is the Sri Lanka's #1 Recruitment and Job Searching platform with structured candidate data and end-to-end applicants management system." />
    <meta property="og:url" content="https://www.placements.lk" />
    <meta property="og:site_name" content="Placements.lk" />
    <meta property="og:image" content="https://www.placements.lk/assets/images/og banner.png" />
    <meta property="og:image:secure_url" content="https://www.placements.lk/assets/images/og banner.png" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />

    <link rel="icon" href="/assets/images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/fa-all.css"/>

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.min.css?v=15" rel="stylesheet">
    <link href="/assets/css/countrySelect.css" rel="stylesheet">
    <link href="/assets/css/file-upload.css" rel="stylesheet">
    <link href="/assets/libs/apex-charts/apexcharts.css?v=15" rel="stylesheet">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <link rel="apple-touch-icon" href="/assets/res/icon/ios/icon-1024.png">
    <meta name="apple-mobile-web-app-status-bar" content="#06c6b5">
    <meta name="theme-color" content="#06c6b5">
    
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Placements">
    <link href="/assets/res/screen/ios/iphone5_splash.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/iphone6_splash.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/iphoneplus_splash.png" media="(device-width: 621px) and (device-height: 1104px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/iphonex_splash.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/iphonexr_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/iphonexsmax_splash.png" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/ipad_splash.png" media="(device-width: 768px) and (device-height: 1024px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/ipadpro1_splash.png" media="(device-width: 834px) and (device-height: 1112px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/ipadpro3_splash.png" media="(device-width: 834px) and (device-height: 1194px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />
    <link href="/assets/res/screen/ios/ipadpro2_splash.png" media="(device-width: 1024px) and (device-height: 1366px) and (-webkit-device-pixel-ratio: 2)" rel="apple-touch-startup-image" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-212446031-1"></script>
    <script>
      try
      {
        if(window.location.host == "placements.lk")
        {
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-212446031-1');
        }
        else
        {
          console.log("gtag unused: " + window.location.host);
        }
      }
      catch(ex)
      {
        console.log("gtag error: " + window.location.host)
      }
    </script>
  </head>
  <body class="body-with-sidebar">
        <div class="page-loader" id="page-loader" >
          <img src="/assets/images/loader.gif" class="m-auto" />
        </div>
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" aria-label="Main navigation">
  <div class="container-fluid-2 w-100">
    <a class="navbar-brand" href="{{route('dashboard')}}">
        <img src="/assets/images/logo.png" class="d-none d-md-inline-block"/>
        <img src="/assets/images/logo-white.png" class="d-inline-block d-md-none"/>
    </a>

    <button class="navbar-toggler p-0 border-0 float-end mt-2 me-2" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </button>
    <div class="header-icon-cont float-end d-none d-md-inline-block">
      @if(Auth::check() and Auth::user()->type == 'Company')
      <a href="/company/vacancies/create" class="btn btn-danger text-white header-icon p-2 px-3 me-2">
          <span class="far fa-plus"></span> Post a Vacancy
      </a>
      @endif

      <a href="/" class="btn btn-warning text-white header-icon p-2 px-3 me-3">
        <span class="far fa-home"></span> Home
      </a>
      <!-- <a class="btn header-icon" href="">
        <i class="fas fa-expand me-1"></i>
      </a> -->
      <a class="btn float-end header-icon" href="{{ route('notifications') }}">
       <i class="far fa-bell"></i>
        @if(isset($notificationCount))
          <span class="notification-cont">
            {{ $notificationCount }}
          </span>
        @endif
      </a>
      <a class="btn float-end header-icon d-none" href="{{ route('messages') }}">
       <i class="far fa-comment"></i>
        @if(isset($messageCount))
          <span class="notification-cont">
            {{ $messageCount }}
          </span>
        @endif
      </a>
      <a href="{{ route('edit-profile') }}" class="btn header-icon">
        <i class="far fa-user"></i>
      </a>
    </div>
  </div>
</nav>

<div class="sidebar-default mini-scrollbar shadow-sm" id="navbarsExampleDefault">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

    @if(Auth::check())
    <li class="nav-item user-info my-2 text-center">
      <a href="{{ route('edit-profile') }}">
        @if(isset(Auth::user()->image))
          <img src="{{'/storage/UserImages/'.Auth::user()->image}}" />
        @elseif(Auth::user()->type == 'Company')
          <img class="border img-responsive" id="img-dp" src="/assets/images/company.png">
        @elseif(Auth::user()->gender == 'Female')
          <img src='/assets/images/default-female-dp.png' />
        @else
          <img src='/assets/images/default-male-dp.png' />
        @endif
        <strong class="mt-2 d-block">Hello, {{Auth::user()->fname}}</strong>
        <p class=""><button ype="button" class="btn btn-success p-1 px-3 font-80"><span class="far fa-edit me-1"></span> Edit Profile</button></p>
      </a>
    </li>
    @endif

    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('dashboard')}}"><i class="fas fa-th"></i> Dashboard</a></li>

    <!-- *************************************************************** -->
    <!-- PUBLIC MENU =================================================== -->
    @if(!Auth::check())
        <!-- Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('vacancies-jobseeker') }}"><i class="fas fa-briefcase"></i> Vacancies</a></li>

        <!-- Companies -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('companies-jobseeker') }}"><i class="far fa-building"></i> Companies</a></li>

        
    @endif


    <!-- *************************************************************** -->
    <!-- JOB SEEKER MENU =============================================== -->
    @if(Auth::check() and Auth::user()->type == 'JobSeeker')

        <!-- Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('vacancies-jobseeker') }}"><i class="fas fa-briefcase"></i> Vacancies</a></li>

        <!-- Companies -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('companies-jobseeker') }}"><i class="far fa-building"></i> Companies</a></li>
    
        <!-- My applications -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('my-applications') }}"><i class="fas fa-users"></i> My Applications</a></li>

        <!-- Careerfairs -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('all-career-fairs') }}"><i class="fas fa-business-time"></i> Career Fairs</a></li>
        
        <!-- Following Companies -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('my-company-follows') }}"><i class="far fa-building"></i> Following Companies</a></li>
        
        <!-- Wishlist -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('wishlist') }}"><i class="fas fa-heart"></i> My Wishlist</a></li>
    @endif


    <!-- *************************************************************** -->
    <!-- COMPANY MENU ================================================== -->
    @if(Auth::check() and Auth::user()->type == 'Company')
        <!-- Post Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('create-vacancy') }}"><i class="fas fa-plus"></i> Post a Vacancy</a></li>
        
        <!-- Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('vacancies-company') }}"><i class="fas fa-briefcase"></i> Vacancies</a></li>
    
        <!-- Applications -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('applications-company') }}"><i class="fas fa-users"></i> Applicants</a></li>
        
        <!-- Inquiries -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('get-job-inquiries') }}"><i class="fas fa-comment-dots"></i> Inquiries</a></li>

        <!-- Careerfairs -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('career-fairs') }}"><i class="fas fa-business-time"></i> Career Fairs</a></li>
    @endif

    <!-- University Staff MENU ================================================== -->
    @if(Auth::check() and Auth::user()->type == 'University Staff')
        <!-- Batches -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('batches-index') }}"><i class="fas fa-award"></i> Batches</a></li>
        <!-- Students -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('my-students') }}"><i class="fas fa-user-graduate"></i> Students</a></li>
    
        <!-- Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('vacancies-jobseeker') }}"><i class="fas fa-briefcase"></i> Vacancies</a></li>

        <!-- Companies -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('companies-jobseeker') }}"><i class="far fa-building"></i> Companies</a></li>
    
        <!-- Careerfairs -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('all-career-fairs') }}"><i class="fas fa-business-time"></i> Career Fairs</a></li>
        
    @endif
    
    <!-- *************************************************************** -->
    <!-- ADMIN MENU ==================================================== -->
    @if(Auth::check())

        <!-- Vacancy -->
        @permission(['can-view-vacancy'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('vacancies-admin') }}"><i class="fas fa-briefcase"></i> Vacancies</a></li>
        @endpermission()

        <!-- Company -->
        @permission(['can-view-company'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('company-index') }}"><i class="far fa-building"></i> Companies</a></li>
        @endpermission()

        <!-- Applications -->
        @permission(['can-view-application'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('applications-admin') }}"><i class="fas fa-users"></i> Applications</a></li>
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('applicants-admin') }}"><i class="fas fa-users"></i> Applicants</a></li>
        @endpermission()

        <!-- Careerfairs -->
        @permission(['can-view-careerfair'])
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('admin-career-fairs') }}"><i class="fas fa-business-time"></i> Career Fairs</a></li>
        @endpermission()

        <!-- Others -->
        @if(Auth::user()->role == 1)
        <li class="nav-item">
          <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i> Others</a>
          <ul class="dropdown-menu" aria-labelledby="dropdown02">      
            
            <!-- USERS MENU ================================================== -->
            @permission(['can-view-users'])
              <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('users.index')}}">Users</a></li>
            @endpermission()

            <!-- ROLES MENU ================================================== -->
            @permission(['can-view-roles'])
              <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('roles.index')}}">Roles</a></li>
            @endpermission()

            @permission(['can-view-admin_companies'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('companies-admin') }}">Admin Companies</a></li>
            @endpermission()
            @permission(['can-view-admin_vacancies'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('admin-vacancies') }}">Admin Vacancies</a></li>
            @endpermission()

            <!-- Institute MENU ================================================== -->
            @permission(['can-view-institute'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('institutes.index') }}">Institutes</a></li>
            @endpermission()
            <!-- Faculty MENU ================================================== -->
            @permission(['can-view-faculty'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('faculties.index') }}">Faculties</a></li>
            @endpermission()
            <!-- Department MENU ================================================== -->
            @permission(['can-view-department'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('departments.index') }}">Departments</a></li>
            @endpermission()
            <!-- Course MENU ================================================== -->
            @permission(['can-view-course'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('courses.index') }}">Courses</a></li>
            @endpermission()
            <!-- Batch MENU ================================================== -->
            @permission(['can-view-batch'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('batches.index') }}">Batches</a></li>
            @endpermission()
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('generate-edu-details-view') }}">Generate Primary Edu Details</a></li>
            <!-- Field MENU ================================================== -->
            @permission(['can-view-field'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('fields.index') }}">Fields</a></li>
            @endpermission()
            <!-- Position MENU ================================================== -->
            @permission(['can-view-position'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('positions.index') }}">Positions</a></li>
            @endpermission()
            <!-- Skill MENU ================================================== -->
            @permission(['can-view-skill'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('skills.index') }}">Skills</a></li>
            @endpermission()
            @permission(['can-view-inquiry'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('inquiries') }}">Inquiries</a></li>
            @endpermission()
            @permission(['can-view-package'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('packages.index') }}">Packages</a></li>
            @endpermission()
            @permission(['can-view-feature'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('features.index') }}">Features</a></li>
            @endpermission()
            @permission(['can-view-package_feature'])
            <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('package-features.index') }}">Package Feature</a></li>
            @endpermission()
          </ul>
        </li>
        @endif
    @endif

    
    <!-- *************************************************************** -->
    <!-- COMMON AUTHENTICATED USERS MENU =============================== -->
    @if(Auth::check())
    <!-- <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('edit-profile') }}"><i class="fas fa-user-edit"></i> Edit Profile</a></li> -->
    
    <!-- LOGOUT MENU ================================================== -->
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="#" onclick="$('#frm-logout').submit()"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

    <form novalidate method="POST" action="{{ route('logout') }}" id="frm-logout">
      @csrf
    </form>
    @endif

    </ul>
</div>

<main class="container position-relative">
  @if (Session::has('alert-unauthorized-access-class'))
      <div class="alert alert-danger fade show mt-2 common-alert">{!! \App\Services\StringUtils::cleanScriptsFromHtmlTags(Session::get('alert-unauthorized-access-class')) !!}</div>
      @php
          Session::forget('alert-unauthorized-access-class')
      @endphp
  @endif

  @if ($message = Session::get('message'))
      <div class="mt-2 alert alert-success common-alert">
          <b>{{ $message }}</b>
      </div>
  @endif
  @if ($error = Session::get('error'))
      <div class="mt-2 alert alert-danger common-alert">
          <b>{{ $error }}</b>
      </div>
  @endif

  @yield("main-body")
</main>
<div class="bg-overlay bg-grad d-none"></div>

<!-- TOASTS ======================================================== -->
<div class="position-fixed end-0 p-3" style="z-index: 1020;top:70px">
  <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header icon-success text-center">

      <strong class="me-auto"><i class="far fa-smile-wink mx-2" style="font-size:20px"></i> Success!</strong>
      <small></small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Successfully added
    </div>
  </div>
</div>

<div class="position-fixed end-0 p-3" style="z-index: 1020;top:70px">
  <div id="failedToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header icon-danger text-center">

      <strong class="me-auto"><i class="far fa-frown mx-2"  style="font-size:20px"></i> Sorry!</strong>
      <small></small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
    </div>
  </div>
</div>
<!-- END OF TOASTS ======================================================== -->

@include("layouts.modals")   
@include("tiles.mobile-footer") 
<!-- END OF MODALS ======================================================== -->    
      

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="{{ URL::asset('assets/scripts/scripts.js?v=15') }}"></script>
<script src="{{ URL::asset('assets/scripts/sweetalert.min.js') }}"></script>

<!-- Custom scripts for this template -->
<script src="/assets/js/request_handler.js"></script>
<script src="/assets/js/scripts.min.js?v=15"></script>

<script src="/assets/libs/apex-charts/apexcharts.min.js?v=15"></script>


<script>
(function () {
  'use strict'

  document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
    document.querySelector('.sidebar-default').classList.toggle('open');
    document.querySelector('body').classList.toggle('overflow-hidden');
  })
})();

$(document).ready(function() {
    $('.js-example-basic-multiple').select2();
});
</script>

@yield('scripts')


  </body>
</html>
