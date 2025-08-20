@include('layouts.header')

@include('layouts.sidebar')
<style type="text/css">
    .avatar-upload {
        position: relative;
        /*max-width: 205px;*/
        margin: 15px auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit input+label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        font-weight: normal;
        transition: all 0.2s ease-in-out;
    }

    .avatar-upload .avatar-edit input+label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    .avatar-upload .avatar-edit input+label:after {
        color: #757575;
        position: absolute;
        top: 10px;
        left: 10px;
        right: 0;
        text-align: center;
        margin: auto;
    }

    .avatar-upload .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload .avatar-preview>div {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        position: absolute;
    }
</style>
<!-- Dashboard Header  section -->

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title app-page-title-simple">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div>
                        <div class="page-title-head center-elem">
                            <span class="d-inline-block pr-2">
                                <i class="lnr-apartment opacity-6"></i>
                            </span>
                            <span class="d-inline-block">Manage Your Profile Information</span>
                        </div>
                        <div class="page-title-subheading opacity-10">
                            <nav class="" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a>
                                            <i aria-hidden="true" class="fa fa-home"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a>Dashboards</a>
                                    </li>
                                    <li class="active breadcrumb-item" aria-current="page">
                                        Profile update
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
            <div class="offset-1 col-md-10">
                <div class="main-card mb-3 card">

                    <div class="card-body ">
                        <h5 class="card-title">Update Your Profile</h5>
                        <form method="post" action="{{route('edit-data')}}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include("layouts.includes.flash")
                            <div class="form-row">
                                <div class="offset-4 col-md-4 text-center">
                                    <label for="title" class="card-title">Change Profile Picture</label>
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload" name="image"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload"><i class="fa fa-edit fads"></i></label>

                                        </div>
                                        <div class="avatar-preview story">
                                            <div id="imagePreview"
                                                style="background-image: url('{!! asset('assets/images/avatars') !!}/{{ Auth::user()->profile_photo }}'); background-size: contain;">
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        function readURL(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                reader.onload = function(e) {
                                                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                                                    $('#imagePreview').hide();
                                                    $('#imagePreview').fadeIn(650);
                                                }
                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                        $("#imageUpload").change(function() {
                                            readURL(this);
                                        });
                                    </script>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="name" class="">User
                                            Name</label>
                                        <input name="name" id="email" placeholder="name"
                                            value="{{ Auth::user()->name }}" type="text" class="form-control" required>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="email" class="">Email</label>
                                        <input name="email" id="email" placeholder=" email"
                                            value="{{ Auth::user()->email }}" type="email" class="form-control"
                                            desabled>
                                    </div>
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="twitter"
                                            class="">Mobile</label>
                                        <input name="mobile" id="mobile" placeholder="Mobile Number"
                                            value="{{ Auth::user()->mobile }}" type="text" class="form-control"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group"><label for="password" class="">Password
                                            (If not to change leave it blank)</label>
                                        <input name="password" id="password" placeholder="Password" type=text value=""
                                            class="form-control">
                                    </div>
                                </div>

                            </div>
                            <div class="form-row text-center">
                                <input name="id" value="{{ Auth::user()->id }}" type="text" hidden>
                            </div>
                            <div class="offset-5 col-md-2">
                                <div class="position-relative form-group"><input value="Update" id="date"
                                        class="btn-wide mb-2 mr-2 mt-3 btn btn-shadow btn-gradient-success btn-lg"
                                        type="submit"></div>
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
