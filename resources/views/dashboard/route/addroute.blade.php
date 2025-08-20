@section('Applications-li', 'mm-active')
@section('Applications-ul', 'mm-show')
@section('route-create', 'mm-active')
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
                            <span class="d-inline-block">Add New Bus Route</span>
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
                                        Add New Route
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

                    <form method="post" action="{{ route('route.store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include("layouts.includes.flash")

                        <div class="card-body ">
                            <div class="section">
                                <h5 class="card-title" style="margin-top: 7px;">Fill Up Route
                                    Details : </h5>
                                <div class="form-row">
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Route Name <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="route_name"
                                            placeholder="Route Name" value="{{ old('route_name') }}" required>

                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Route Code <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="route_code"
                                            placeholder="Route Code" value="{{ old('route_code') }}" required>
                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Route Details <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" name="route_details"
                                            placeholder="Route Details" value="{{ old('route_details') }}" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label" for="start_slot">Start Time Slot <span
                                                class="important text-danger">*</span></label>
                                        <select id="start_slot" class="js-example-basic-multiple js-states form-control"
                                            name="start_time_slot[]" multiple="multiple">
                                            <option value="7.00 AM">7.00AM</option>
                                            <option value="7.30 AM">7.30AM</option>
                                            <option value="8.00 AM">8.00AM</option>
                                            <option value="8.30 AM">8.30AM</option>
                                            <option value="9.00 AM">9.00AM</option>
                                            <option value="9.30 AM">9.30AM</option>
                                            <option value="10.00 AM">10.00AM</option>
                                            <option value="10.30 AM">10.30AM</option>
                                            <option value="11.00 AM">11.00AM</option>
                                            <option value="11.30 AM">11.30AM</option>
                                            <option value="12.00 PM">12.00PM</option>
                                            <option value="12.30 PM">12.30PM</option>
                                            <option value="1.00 PM">1.00PM</option>
                                            <option value="1.30 PM">1.30PM</option>
                                            <option value="2.00 PM">2.00PM</option>
                                            <option value="2.30 PM">2.30PM</option>
                                            <option value="3.00 PM">3.00PM</option>
                                            <option value="3.30 PM">3.30PM</option>
                                            <option value="4.00 PM">4.00PM</option>
                                            <option value="4.30 PM">4.30PM</option>
                                            <option value="5.00 PM">5.00PM</option>
                                            <option value="5.30 PM">5.30PM</option>
                                            <option value="6.00 PM">6.00PM</option>
                                            <option value="6.30 PM">6.30PM</option>
                                            <option value="7.00 PM">7.00PM</option>
                                            <option value="7.30 PM">7.30PM</option>
                                            <option value="8.00 PM">8.00PM</option>
                                            <option value="8.30 PM">8.30PM</option>
                                            <option value="9.00 PM">9.00PM</option>
                                            <option value="9.30 PM">9.30PM</option>
                                        </select>

                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label" for="dp_slot">Departure Time Slot <span
                                                class="important text-danger">*</span></label>
                                        <select id="dp_slot" class="js-example-basic-multiple js-states form-control"
                                            name="departure_time_slot[]" multiple="multiple">
                                            <option value="7.00 AM">7.00AM</option>
                                            <option value="7.30 AM">7.30AM</option>
                                            <option value="8.00 AM">8.00AM</option>
                                            <option value="8.30 AM">8.30AM</option>
                                            <option value="9.00 AM">9.00AM</option>
                                            <option value="9.30 AM">9.30AM</option>
                                            <option value="10.00 AM">10.00AM</option>
                                            <option value="10.30 AM">10.30AM</option>
                                            <option value="11.00 AM">11.00AM</option>
                                            <option value="11.30 AM">11.30AM</option>
                                            <option value="12.00 PM">12.00PM</option>
                                            <option value="12.30 PM">12.30PM</option>
                                            <option value="1.00 PM">1.00PM</option>
                                            <option value="1.30 PM">1.30PM</option>
                                            <option value="2.00 PM">2.00PM</option>
                                            <option value="2.30 PM">2.30PM</option>
                                            <option value="3.00 PM">3.00PM</option>
                                            <option value="3.30 PM">3.30PM</option>
                                            <option value="4.00 PM">4.00PM</option>
                                            <option value="4.30 PM">4.30PM</option>
                                            <option value="5.00 PM">5.00PM</option>
                                            <option value="5.30 PM">5.30PM</option>
                                            <option value="6.00 PM">6.00PM</option>
                                            <option value="6.30 PM">6.30PM</option>
                                            <option value="7.00 PM">7.00PM</option>
                                            <option value="7.30 PM">7.30PM</option>
                                            <option value="8.00 PM">8.00PM</option>
                                            <option value="8.30 PM">8.30PM</option>
                                            <option value="9.00 PM">9.00PM</option>
                                            <option value="9.30 PM">9.30PM</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-line-height col-lg-4 col-md-12">
                                        <label class="input-label">Route Map Url <span
                                                class="important text-danger">*</span></label>
                                        <input class="form-control" type="text" data-toggle="datepicker"
                                            name="route_map_url" placeholder="Route Map Url"
                                            value="{{ old('route_map_url') }}" required>
                                    </div>

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
<script type="text/javascript">
    $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
</script>
@include('layouts.footer')
