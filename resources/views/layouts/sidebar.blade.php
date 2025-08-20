<div class="scrollbar-sidebar ps ps--active-y">

    <div class="app-sidebar__inner">

        <ul class="vertical-nav-menu metismenu">
            <li class="app-sidebar__heading">Dashbaord Overview</li>
            <li>
                <a href="{{URL('/dashboard')}}" aria-expanded="true" class="@yield('dashboard')">
                    <i class="metismenu-icon pe-7s-home"></i>
                    Dashbaord
                </a>
            </li>
            {{-- <li class="app-sidebar__heading">Driver Information</li>
            <li>
                <a href="{{URL('/driver-registration')}}" aria-expanded="true" class="@yield('driver-registration')">
                    <i class="metismenu-icon pe-7s-note"></i>
                    Driver Registration
                </a>
            </li>
            <li>
                <a href="{{URL('/registered-drivers')}}" aria-expanded="true" class="@yield('registered-drivers')">
                    <i class="metismenu-icon pe-7s-note2"></i>
                    Registered Drivers
                </a>
            </li> --}}
            <li class="app-sidebar__heading">Package Info</li>
            <li>
                <a href="{{URL('/packages')}}" aria-expanded="true" class="@yield('packages')">
                    <i class="metismenu-icon pe-7s-network"></i>
                    Add Mobile Package
                </a>
            </li>
            <li>
                <a href="{{URL('/active-packages')}}" aria-expanded="true" class="@yield('active-packages')">
                    <i class="metismenu-icon pe-7s-note"></i>
                    Active Packages
                </a>
            </li>
            <li>
                <a href="{{URL('/most-selling-packages')}}" aria-expanded="true"
                    class="@yield('most-selling-packages')">
                    <i class="metismenu-icon pe-7s-way"></i>
                    Most Selling Packages
                </a>
            </li>
            </li>
            <li class="app-sidebar__heading">Order Info</li>

            <li>
                <a href="{{URL('/order-list')}}" aria-expanded="true" class="@yield('order-list')">
                    <i class="metismenu-icon pe-7s-pin"></i>
                    Order List
                </a>

            </li>
            <li>
                <a href="{{URL('/pending-order-list')}}" aria-expanded="true" class="@yield('pending-order-list')">
                    <i class="metismenu-icon pe-7s-note2"></i>
                    Order Pending
                </a>

            </li>
            <li>
                <a href="{{URL('/order-completed')}}" aria-expanded="true" class="@yield('order-completed')">
                    <i class="metismenu-icon pe-7s-info"></i>
                    Order Complete
                </a>

            </li>
            <li class="app-sidebar__heading">Payment Info</li>

            <li>
                <a href="{{URL('/assgin-bus')}}" aria-expanded="true" class="@yield('assgin-bus')">
                    <i class="metismenu-icon pe-7s-pin"></i>
                    Add Money Request
                </a>

            </li>
            <li>
                <a href="{{URL('/assign-route-list')}}" aria-expanded="true" class="@yield('assign-route-list')">
                    <i class="metismenu-icon pe-7s-note2"></i>
                    Request Completed
                </a>

            </li>
            <li class="app-sidebar__heading">Refund Info</li>
            <li>
                <a href="{{URL('/assgin-bus')}}" aria-expanded="true" class="@yield('assgin-bus')">
                    <i class="metismenu-icon pe-7s-pin"></i>
                    Refund Request
                </a>

            </li>
            <li>
                <a href="{{URL('/assign-route-list')}}" aria-expanded="true" class="@yield('assign-route-list')">
                    <i class="metismenu-icon pe-7s-note2"></i>
                    Refund Completed
                </a>

            </li>
            <li class="app-sidebar__heading">App User Info</li>
            <li>
                <a href="{{URL('/registered-app-users')}}" aria-expanded="true" class="@yield('registered-app-users')">
                    <i class="metismenu-icon pe-7s-users"></i>
                    App Users
                </a>
            </li>


        </ul>
    </div>
    <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
    </div>

    <div class="ps__rail-y" style="top: 0px; height: 196px; right: 0px;">
        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 35px;"></div>
    </div>

</div>



</div>
