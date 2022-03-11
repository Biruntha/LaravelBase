@extends("layouts.main-web")

@section("meta")
    <title>Pricing | Placements.lk</title>
    <meta property="og:title" content="Placements.lk Pricing" />
    <meta property="og:description" content="It is completely free to create a Placements.lk account and enjoy the basic features."/>
    <meta property="og:url" content="https://placements.lk/pricing" />
@endsection

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Pricing
</h1>
@endsection

@section("main-body")
    <div class="">
        <div class="row mt-3 font-120 bg-white">
            <div class="col-md-12 col-lg-8 col-xl-6 offset-lg-2 offset-xl-3">
                <div class="row mb-5 bg-white rounded p-3 p-md-5">
                    <div class="col-12">
                        <h2 class="fw-bold text-center">Absolutely Free for the basics </br> <span class="font-120">and affordable pricing on the premium features</span> </h2>
                    </div>
                    <div class="col-md-12">
                        <p class="mt-4 text-center">It is completely <b class='text-success'>FREE</b> to create a Placements.lk account and enjoy the basic features. Below is the pricing chart of all of the product. <a href="/contact">Contact us</a>, if you need any additional info. We are happy to receive new feature requests and product improvements suggestions from you. </p> 
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3 mt-md-4 font-100">
            <div class="col-12">
                <div class="bg-white rounded text-center shadow-md p-4 mt-3">
                    <select class="form-control w-auto px-5 m-auto font-150 fw-bold" onchange="$('.pamount').hide();$('.' + this.value).show()">
                        <option value="annually">Pay annually</option>
                        <option value="monthly">Pay monthly</option>
                    </select> 
                </div>
            </div>
        </div>


        <div class="row mt-3 mt-md-4 font-100">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="bg-white rounded shadow-md p-3 py-4 mt-3">
                    <h4 class="fw-bold text-center m-0"> SEEK</h4>
                    <p class="m-0 text-center">For Job Seekers</p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold">FREE<p>

                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> View Vacancies</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Apply for Vacancies</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Attend Public Careers</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Manage Applications</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Enroll for Graduate Programs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Skill-set Analytics</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Industry Analytics</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Public workshops by Experts</p>
                    <p class="text-center mt-3"><a class="btn btn-success p-3 px-4" href="/jobseeker/signup">Signup Now <i class="fas fa-arrow-right ms-2"></i></a></p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="bg-white rounded shadow-md p-3 py-4 mt-3">
                    <h4 class="fw-bold text-center m-0"> BASIC</h4>
                    <p class="m-0 text-center">For Companies</p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold">FREE<p>

                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Post Free Vacancies - 2 per month</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-info"></i> Additional Vacancies - $15 per vacancy</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Embed Vacancies outside</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Collect Applicant CVs</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Schedule Interviews</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Interview Gradings</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Manage Applicants</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Send/Receive Email Notifications</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Enroll for Graduate Programs</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Basic Analytics</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Advanced Analytics</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Multi-user/department Support</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Conduct Career Fairs</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> 24 x 7 Support</p>
                    <p class="text-center mt-3"><a class="btn btn-success p-3 px-4" href="/company/signup">Signup Now <i class="fas fa-arrow-right ms-2"></i></a></p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="bg-white rounded shadow-md p-3 py-4 mt-3" style="border:2px solid green">
                    <h4 class="fw-bold text-center m-0"> STANDARD</h4>
                    <p class="m-0 text-center">For Companies</p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold">FREE <span class="text-danger font-80">(Only for the first 500 companies)</span><p>

                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Post Free Vacancies - 5 per month</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-info"></i> Additional Vacancies - $10 per vacancy</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Embed Vacancies outside</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Collect Applicant CVs</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Schedule Interviews</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Interview Gradings</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Manage Applicants</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Send/Receive Email Notifications</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Enroll for Graduate Programs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Basic Analytics</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Advanced Analytics</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Multi-user/department Support</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Conduct Career Fairs</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> 24 x 7 Support</p>
                    <p class="text-center mt-3"><a class="btn btn-success p-3 px-4" href="/company/signup">Signup Now <i class="fas fa-arrow-right ms-2"></i></a></p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 d-none">
                <div class="bg-white rounded shadow-md p-3 py-4 mt-3">
                    <h4 class="fw-bold text-center m-0"> PLUS </h4>
                    <p class="m-0 text-center">For Companies</p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold annually pamount">$17 per month </p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold monthly pamount" style="display:none">$34 per month</p>

                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Post Free Vacancies - 10 per month</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-info"></i> Additional Vacancies - $10 per vacancy</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Embed Vacancies outside</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Collect Applicant CVs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Schedule Interviews</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Interview Gradings</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Manage Applicants</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Send/Receive Email Notifications</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Enroll for Graduate Programs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Basic Analytics</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Advanced Analytics</p>
                    <p class="text-center"><i class="fas fa-times me-2 text-danger"></i> Multi-user/department Support</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Conduct Career Fairs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> 24 x 7 Support</p>
                    <p class="text-center mt-3"><a class="btn btn-success p-3 px-4" href="/contact?t=pkg-plus">Talk to Sales Team <i class="fas fa-arrow-right ms-2"></i></a></p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="bg-white rounded shadow-md p-3 py-4 mt-3">
                    <h4 class="fw-bold text-center m-0"> PRO </h4>
                    <p class="m-0 text-center">For Companies</p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold annually pamount">$32 per month</p>
                    <p class="text-center text-success font-150 mb-2 pb-md-4 fw-bold monthly pamount" style="display:none">$64 per month</p>

                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Post Free Vacancies - 20 per month</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-info"></i> Additional Vacancies - $7 per vacancy</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Embed Vacancies outside</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Collect Applicant CVs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Schedule Interviews</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Interview Gradings</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Manage Applicants</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Send/Receive Email Notifications</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Enroll for Graduate Programs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Basic Analytics</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Advanced Analytics</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Multi-user/department Support</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> Conduct Career Fairs</p>
                    <p class="text-center"><i class="fas fa-check me-2 text-success"></i> 24 x 7 Support</p>
                    <p class="text-center mt-3"><a class="btn btn-success p-3 px-4" href="/contact?t=pkg-pro">Talk to Sales Team <i class="fas fa-arrow-right ms-2"></i></a></p>
                </div>
            </div>
        </div>  
    </div>  
@endsection
