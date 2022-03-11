<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Placements.lk is the Sri Lanka's #1 Recruitment and Job Searching platform with structured candidate data and end-to-end applicants management system.">
    <meta name="keywords" content="Job Portal, Job Board, Job Seeking, Recruitment, Vacancies, Vacancies in Sri Lanka, Jobs, Job, Career, COmpanies in Sri Lanka, Work, Opportunities, Universities, HR System, Placements,">
    <meta name="author" content="Placements LK (Pvt) Ltd.">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Placements.lk" />
    
    @if(View::hasSection('meta'))
      @yield("meta")
    @else
      <meta property="og:title" content="Placements.lk | The #1 candidate-centric Recruitment Ecosystem in Sri Lanka" />
      <meta property="og:description" content="Placements.lk is the Sri Lanka's #1 Recruitment and Job Searching platform with structured candidate data and end-to-end applicants management system." />
      <meta property="og:url" content="https://www.placements.lk" />
    @endif
    
    <meta property="og:image" content="https://www.placements.lk/assets/images/og banner.png" />
    <meta property="og:image:secure_url" content="https://www.placements.lk/assets/images/og banner.png" />
    <meta property="og:image:width" content="500" />
    <meta property="og:image:height" content="500" />


    <link rel="icon" href="/assets/images/favicon.png">
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/fa-all.css"/>

    <!-- Custom styles for this template -->
    <link href="/assets/css/style.min.css?v=15" rel="stylesheet">

    @if(Request::path() != '/')
      <link href="/assets/css/file-upload.css" rel="stylesheet">
      <link href="/assets/css/countrySelect.css" rel="stylesheet">
      <link href="/assets/css/select2.min.css" rel="stylesheet" />
    @endif

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
  <body class="web-body">
        <div class="page-loader" id="page-loader" >
          <img src="/assets/images/loader.gif" class="m-auto" />
        </div>
<nav class="navbar navbar-expand-lg fixed-top shadow-sm" aria-label="Main navigation">
  <div class="container-fluid-2 w-100">
    <a class="navbar-brand ms-md-5" href="">
        <img src="/assets/images/logo.png" alt="" class="d-none d-md-inline-block"/>
        <img src="/assets/images/logo-white.png" alt="" class="d-inline-block d-md-none"/>
    </a>

    <button class="navbar-toggler p-0 border-0 float-end mt-2 me-2" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
        <div class="hamburger">
            <span class="line"></span><span class="line"></span><span class="line"></span>
        </div>
    </button>
    
    <div class="header-icon-cont float-end d-none d-lg-block">
      <a class="btn header-icon ms-2" href="">
        Home
      </a>
      <a href="" class="btn header-icon ms-2">
        Vacancies
      </a>
      <a href="" class="btn header-icon ms-2">
        Career Fairs
      </a>
      <a href="" class="btn header-icon ms-2">
        Companies
      </a>
      <a href="" class="btn header-icon ms-2">
        About us
      </a>
      <a href="" class="btn header-icon ms-2">
        Pricing
      </a>
      @if(Auth::check() and Auth::user()->type == 'Company')
      <a href="/company/vacancies/create" class="btn btn-danger text-white header-icon p-2 px-3 me-2">
          <span class="far fa-plus"></span> Post a Vacancy
      </a>
      @endif
      @if(Auth::check())
      <a class="btn bg-grad text-white header-icon ms-2 p-2 px-3" href="{{ route('dashboard') }}">
       <span class="fas fa-th-large fw-normal"></span> Dashboard
      </a>
      @else
      <a class="btn btn-danger text-white header-icon ms-2 p-2 px-3" href="{{ route('login') }}">
       <span class="far fa-sign-in-alt"></span> Login/Register
      </a>
      @endif
    </div>
  </div>
</nav>

<div class="sidebar-default mini-scrollbar shadow-sm d-block d-lg-none" id="navbarsExampleDefault">
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

    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{route('dashboard')}}"><i class="fas fa-th"></i> Dashboard</a></li>
    @else
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="/login"><i class="fas fa-sign-in-alt"></i> Login/Register</a></li>
    @endif

    <!-- Home -->
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="/"><i class="fas fa-home"></i> Home</a></li>

    @if(Auth::check() and Auth::user()->type == 'Company')
        <!-- Post Vacancy -->
        <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="{{ route('create-vacancy') }}"><i class="fas fa-plus"></i> Post a Vacancy</a></li>
    @endif

    <!-- Vacancy -->
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href=""><i class="fas fa-briefcase"></i> Vacancies</a></li>

    <!-- CareerFair -->
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href=""><i class="fas fa-business-time"></i> Career Fairs</a></li>

    <!-- Companies -->
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href=""><i class="far fa-building"></i> Companies</a></li>


    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="/about"><i class="fas fa-address-card"></i> About us</a></li>
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="/pricing"><i class="fas fa-dollar-sign"></i> Pricing</a></li>
    <li class="nav-item"><a class="nav-link rounded" aria-current="page" href="/contact"><i class="fas fa-phone"></i> Contact</a></li>


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

@yield("title")
@yield("banner")

<main class="container position-relative web  bg-standard">
  @if (Session::has('alert-unauthorized-access-class'))
      <div class="alert alert-danger fade show mt-2 common-alert">{!! \App\Services\StringUtils::cleanScriptsFromHtmlTags(Session::get('alert-unauthorized-access-class')) !!}</div>
      @php
          Session::forget('alert-unauthorized-access-class')
      @endphp
  @endif

  @if ($message = Session::get('message'))
      <div class="mt-2 alert alert-success common-alert">
          <b>{!! $message !!}</b>
      </div>
  @endif
  @if ($warning = Session::get('warning'))
      <div class="mt-2 alert alert-warning common-alert">
          <span>{!! $warning !!}</span>
      </div>
  @endif
  @if ($error = Session::get('error'))
      <div class="mt-2 alert alert-danger common-alert">
          <b>{!! $error !!}</b>
      </div>
  @endif

  @yield("main-body")
</main>
<div class="bg-overlay bg-grad d-none"></div>

@include("tiles.footer")   
@include("tiles.mobile-footer")    
      

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
<script src="/assets/js/jquery.min.js"></script>
<script src="/assets/js/bootstrap.bundle.min.js"></script>

<script src="{{ URL::asset('assets/scripts/scripts.js?v=15') }}"></script>

<!-- Custom scripts for this template -->
<script src="/assets/js/request_handler.js"></script>
<script src="/assets/js/scripts.min.js?v=15"></script>

@if(Request::path() != '/')
  <script src="/assets/js/select2.min.js"></script>
@endif

<script>
(function () {
  'use strict'

  document.querySelector('#navbarSideCollapse').addEventListener('click', function () {
    document.querySelector('.sidebar-default').classList.toggle('open');
    document.querySelector('body').classList.toggle('overflow-hidden');
  })
})();
</script>

@yield('scripts')




  </body>
</html>
