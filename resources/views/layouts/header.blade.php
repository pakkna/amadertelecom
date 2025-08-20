<!doctype html>
<html lang="{{ app()->getLocale() }}">


<!-- Mirrored from demo.dashboardpack.com/architectui-html-pro/dashboards-minimal-1.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 26 Aug 2019 06:48:46 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Diu Transport') }}</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="Examples of just how powerful ArchitectUI really is!">

    <!-- Disable tap highlight on IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="icon" type="image/png" href="{!! asset('assets/images/favicon.png') !!}" sizes="16x16">
    <link rel="icon" type="image/png" href="{!! asset('assets/images/favicon.png') !!}" sizes="32x32">
    <link href="{!! asset('/main.css') !!}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{--
    <link href="{!! asset('/css/bootstrap-datetimepicker.min.css') !!}" rel="stylesheet" type="text/css"> --}}
    <link href="{!! asset('/css/daterangepicker.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
    <script src="{!! asset('js/jquery.min.js') !!}"></script>


    <!--  <script src="https://cdn.ckeditor.com/4.12.1/basic/ckeditor.js"></script> -->
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9d7;
            min-width: 130px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            padding: 10px 12px;
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .swal2-cancel {
            background-color: #cc0a00 !important;
        }
    </style>


</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">
        <div class="app-header header-shadow">
            <div class="app-header__logo" style="padding-left: 40px !important;">
                <a href="{{ route('dashboard') }}">
                    <div class="logo-src"> </div>
                </a>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button"
                        class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">

                <div class="app-header-right">

                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="dropdown">
                                        <a class="p-0 btn">
                                            @if(Auth::user()->profile_photo)
                                            <img class="rounded-circle"
                                                src="assets/images/avatars/{{ Auth::user()->profile_photo }}" alt=""
                                                width="42">
                                            @else
                                            <img class="rounded-circle"
                                                src="{!! asset('assets/images/avatars/1.jpg') !!}" alt="" width="42">
                                            @endif

                                            <i class="fa fa-angle-down ml-2 opacity-8"></i>
                                        </a>
                                        <div tabindex="-1" role="menu" aria-hidden="true"
                                            class="dropdown-content dropdown-menu-xl dropdown-menu dropdown-menu-right">
                                            <div class="dropdown-menu-header mt-1">
                                                <div class="dropdown-menu-header-inner bg-info">
                                                    <div class="menu-header-image opacity-4"></div>
                                                    <div class="menu-header-content text-left">
                                                        <div class="widget-content p-0">
                                                            <div class="widget-content-wrapper">
                                                                <div class="widget-content-left mr-3">
                                                                    @if(Auth::user()->profile_photo)
                                                                    <img class="rounded-circle"
                                                                        src="assets/images/avatars/{{ Auth::user()->profile_photo }}"
                                                                        alt="" width="42">
                                                                    @else
                                                                    <img class="rounded-circle"
                                                                        src="{!! asset('assets/images/avatars/1.jpg') !!}"
                                                                        alt="" width="42">
                                                                    @endif
                                                                </div>
                                                                <div class="widget-content-left">
                                                                    <div class="widget-heading">{{
                                                                        Auth::user()->name}}
                                                                    </div>
                                                                    <div class="widget-subheading opacity-8">{{
                                                                        \App\Models\User::UserType() }}
                                                                    </div>
                                                                </div>
                                                                <div class="widget-content-right mr-2">
                                                                    <a class="btn-pill btn-shadow btn-shine btn btn-focus"
                                                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                                        Logout
                                                                    </a>

                                                                    <form id="logout-form"
                                                                        action="{{ route('logout') }}" method="POST"
                                                                        style="display: none;">
                                                                        {{ csrf_field() }}
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="scroll-area-xs" style="height: 140px;">
                                                <div class="scrollbar-container ps">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item-header nav-item">MY Account
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="{{url('edit-profile')}}" class="nav-link"><i
                                                                    class="fa fa-edit" style="margin-right: 5px"></i>
                                                                Edit Profile
                                                            </a>
                                                        </li>

                                                        <!--  @if(Auth::user()->user_type=='Admin' || Auth::user()->user_type=='Super Admin')
                                                        <li class="nav-item">
                                                            <a href="{{url('adduser')}}" class="nav-link"><i class="fa fa-user" style="margin-right: 5px"></i> Add Users
                                                            </a>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a href="{{url('user-list')}}" class="nav-link"><i class="fa fa-cogs" style="margin-right: 5px"></i> User Management
                                                            </a>
                                                        </li>
                                                        @endif -->
                                                    </ul>
                                                    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                                                        <div class="ps__thumb-x" tabindex="0"
                                                            style="left: 0px; width: 0px;"></div>
                                                    </div>
                                                    <div class="ps__rail-y" style="top: 0px; right: 0px;">
                                                        <div class="ps__thumb-y" tabindex="0"
                                                            style="top: 0px; height: 0px;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-content-left  ml-3 header-user-info">
                                    <div class="widget-heading">
                                        {{ Auth::user()->name}}
                                    </div>
                                    <div class="widget-subheading">
                                        ({{ \App\Models\User::UserType() }})
                                    </div>
                                </div>
                                <div class="widget-content-right header-user-info ml-3">
                                    <button type="button" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                        class="btn-shadow p-1 btn btn-success btn-sm show-toastr-example">
                                        <i class="fas text-white fa-sign-out-alt pr-1 pl-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
