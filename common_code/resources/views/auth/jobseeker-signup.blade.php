@extends("layouts.main-web")

@section("meta")
    <title>Signup for Placements.lk | The #1 candidate-centric Recruitment Ecosystem in Sri Lanka</title>
@endsection

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Create a Job Seeker account
</h1>
@endsection

@section("main-body")

<style>
    .alert{
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    
    footer{
        display:none;
    }
</style>
      <form novalidate method="POST" action="{{ route('jobseeker-register') }}?rurl={{$rurl}}" enctype='multipart/form-data'>
      {{ csrf_field() }}
         <div class="app-container p-2">
            <div class="row">
               <div class="col-md-8 col-sm-12 offset-md-2">
                  <div class="bg-white shadow-md rounded p-3 p-md-5 mb-5 mt-4" style="align-items: center;position:relative">
                    <div class="row">
                        <!-- <div class="col-12">
                            @if ($message = Session::get('error'))
                            <div class="alert alert-danger alert-block mb-3" style="max-width:430px">
                                <strong style="font-size: 0.9rem;">{{ $message }}</strong>
                            </div>
                            @endif
                        </div> -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                            <label for="firstname" class="form-label">First Name <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" placeholder="First Name" type="text" value="{{old('firstname')}}" name="firstname" required/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                            <label for="lastname" class="form-label">Last Name <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" placeholder="Last Name" type="text" value="{{old('lastname')}}" name="lastname" required/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                            <label for="email" class="form-label">Email <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1" placeholder="Email" type="email" value="{{old('email')}}" name="email" required/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                            <label for="contact" class="form-label">Contact Number <strong style="color:red">*</strong></label>
                            <input class="form-control mx-1 telephone" placeholder="Contact Number" type="text" value="{{old('contact')}}" name="contact" required/>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 d-none">
                            <div class="mb-3">
                                <label for="country" class="form-label">Resident Country <strong style="color:red">*</strong></label>
                                <select class="searchable-select form-control" name="country" id="sel-country">
                                    <option value="">[Unspecified]</option>
                                    @foreach ($countriesForFilter as $obj)
                                        <option {{old('country') == $obj->id ? 'selected' : ''}} value="{{$obj->id}}">{{$obj->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                            <label for="type" class="form-label">Are you currently a University student? <strong style="color:red">*</strong></label>
                            <select class="form-control" name="type">
                                <option {{old('type') == "No/Not Now" ? 'selected' : ''}} value="No/Not Now">No/Not Now</option>
                                <option {{old('type') == "Yes I'm a University Student" ? 'selected' : ''}} value="Yes I'm a University Student">Yes I'm a University Student</option>
                            </select>
                            </div>
                        </div>
                        <!-- <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                                <label for="cv" class="form-label">Resume/CV <strong style="color:red">*</strong></label> (max: 10 MB)
                                <input type='file' name='cv' class="form-control" required>
                            </div>
                        </div> -->
                        <div class="col-12 col-md-6">
                            <div class="mb-3 text-left">
                                <label for="password" class="form-label">Password <strong style="color:red">*</strong></label>
                                <div class="pos-relative">
                                    <input class="form-control" placeholder="Password" type="password" name="password" id="password" required/>
                                    <span toggle="#password-field" class="fa fa-fw fa-eye toggle-password icon-password"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="mb-1 text-center">
                                <strong><input type="checkbox" class="me-2" required id="chk-privacy"/> <label for="chk-privacy">I agree to the placements.lk's <a class="text-theme" href="/privacy" target="_blank"> privacy policy</a></lael></strong>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="row clearfix px-3">
                              <div class="col-12 col-md-6 offset-md-3 mt-4">
                                 <button type="submit" class="btn btn-primary w-100 mx-1  p-2">Register</button>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                            <div class="row px-3 mt-2">
                              <div class="col-12 col-md-6 offset-md-3">
                                 <a class="btn btn-light w-100 mx-1  p-2" href="/login">Already Have An Account?</a>
                              </div>
                           </div>
                        </div>
                        <div class="col-12">
                            <div class="row px-3 mt-2">
                              <div class="col-12 col-md-6 offset-md-3 mt-4">
                                 <a class="btn btn-primary-inverse w-100 mx-1  p-2" href="/company/signup">I'm a Job Provider</a>
                              </div>
                           </div>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
         </div>
      </form>
@endsection

@section("scripts")
<script>
    if(!$("#sel-country").val())
        $("#sel-country").val(213);
</script>  
@endsection