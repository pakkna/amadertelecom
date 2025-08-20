@section('Applications-li', 'mm-active')
@section('Applications-ul', 'mm-show')
@section('driver-registration', 'mm-active')
@include('layouts.header')

@include('layouts.sidebar')

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block pr-2">
                                <i class="lnr-users opacity-6"></i>
                            </span>
                            <span class="d-inline-block">Add New Driver</span>
                        </div>
                        <div class="page-title-subheading opacity-10">
                            <nav class="" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a>
                                            <i aria-hidden="true" class="fa fa-users-cog"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a>Dashboards</a>
                                    </li>
                                    <li class="active breadcrumb-item" aria-current="page">
                                        Driver Registration
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Dashboard Row section -->
        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3 card">

                    <form method="post" action="{{ route('driver.store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include("layouts.includes.flash")

                        <div class="card-body ">
                            <div class="section">
                                <h5 class="card-title" style="margin-top: 7px;">Fill Up Driver
                                    Details : </h5>
                                <div class="form-row">
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Driver Name <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="name" placeholder="Full name"
                                            value="{{ old('name') }}" required>

                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Mobile Number ( As Username ) <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="mobile"
                                            placeholder="Mobile Number " value="{{ old('mobile') }}" required>
                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Password <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="password "
                                            placeholder="Enter Driver Password " required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Driver Email </label>
                                        <input class="form-control" type="email" name="email"
                                            placeholder="Email address" value="{{ old('email') }}">

                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Driver Address <span
                                                class="important">*</span></label>
                                        <input class="form-control" type="textarea" name="address"
                                            placeholder="Present Address" value="{{ old('address') }}" required>
                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Date of Birth <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="date" data-toggle="datepicker" name="dob"
                                            placeholder="Date of Birth" value="{{ old('dob') }}" required>
                                    </div>

                                </div>
                                <div class="form-row">
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">License No <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="license_number"
                                            placeholder="License  Number " value="{{ old('license_number') }}" required>
                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">National ID / Passport<span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="textarea" name="nid_no"
                                            placeholder="National ID/ Passport" value="{{ old('nid_no') }}">
                                    </div>
                                    <div class="custom-file form-line-height col-lg-4 col-md-12 mt-4">
                                        <input type="file" class="custom-file-input" name="nid_image"
                                            id="validatedCustomFile">
                                        <label class="custom-file-label" for="validatedCustomFile">Choose
                                            NID Photo...</label>
                                    </div>
                                    <script>
                                        // Add the following code if you want the name of the file appear on select
                                        $(".custom-file-input").on("change", function() {
                                          var fileName = $(this).val().split("\\").pop();
                                          $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                                        });
                                    </script>
                                </div>

                            </div>

                        </div>

                        <div class="form-row text-center">
                            <div class="offset-4 col-md-4">
                                <div class="position-relative form-group" id="submit_button">

                                    <input value="Submit" id="date"
                                        class="btn-wide mb-2 mr-2 mt-3 btn btn-shadow btn-gradient-info btn-lg"
                                        type="submit">
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

</div>

@include('layouts.footer')
