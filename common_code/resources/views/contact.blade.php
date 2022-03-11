@extends("layouts.main-web")

@section("meta")
    <title>Contact Placements.lk | The #1 candidate-centric Recruitment Ecosystem in Sri Lanka</title>
    <meta property="og:title" content="Contact Placements.lk" />
    <meta property="og:description" content="Feel free to contact us for any sorts of inquiries/complains/suggestions." />
    <meta property="og:url" content="https://placements.lk/contact" />
@endsection

@section("title")
<h1 class="main-page-heading p-2 p-md-3 bg-grad m-0 text-center">
    Contact us
</h1>
@endsection

@section("main-body")
    <form novalidate action="{{ route('save-inquiry') }}" method="POST" id="frm-cart">
        @csrf
        <div class="row p-2">
            <div class="col-12 col-md-6 offset-md-3 p-2 p-md-5 bg-white rounded shadow-sm">
                <div class="row p-2">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="" class="form-label d-block">Your Name <strong style="color:red">*</strong></label>
                            <input type="text" class="form-control" name="name"  id="" value="{{Auth::check() ? Auth::user()->fname . ' ' .Auth::user()->lname : ''}}" required />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="" class="form-label d-block">Email <strong style="color:red">*</strong></label>
                            <input type="email" class="form-control"  value="{{Auth::check() ? Auth::user()->email : ''}}" name="email" id="" required />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="" class="form-label d-block">Message <strong style="color:red">*</strong></label>
                            <textarea rows="3" class="form-control" name="message" id="message" required></textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3 mt-3 text-right">
                            <button type="submit" class="btn btn-success float-end p-3 px-5 mt-2">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection