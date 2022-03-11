<div class="mobile-footer bg-white shadow d-none row m-0">
  <div class="col-3">
    <a class="btn footer-icon" href="">
      <i class="far fa-home"></i>
      <span>Home</span>
    </a>
  </div>
  <div class="col-3">
    <a class="btn footer-icon" href="">
      <i class="far fa-briefcase"></i>
      <span>Vacancies</span>
    </a>
  </div>
  <div class="col-3">
    @if(Auth::check())
      <a class="btn footer-icon pos-relative" href="{{ route('notifications') }}">
        <i class="far fa-bell m-0"></i>
        <span class="badge bg-danger text-white">{{isset($notificationCount) ? $notificationCount : 0}}</span>
      </a>
    @else
      <a class="btn footer-icon" href="">
        <i class="far fa-building"></i>
        <span>Companies</span>
      </a>
    @endif
  </div>
  <div class="col-3">
    @if(Auth::check() and Auth::user()->role == null)
      <a class="btn footer-icon" href="{{ route('dashboard') }}">
        <i class="far fa-th-large"></i>
        <span>Dashboard</span>
      </a>
    @else
      <a class="btn footer-icon" href="{{url('/login')}}">
        <i class="far fa-sign-in-alt"></i>
        <span>Login</span>
      </a>
    @endif
  </div>
</div>  