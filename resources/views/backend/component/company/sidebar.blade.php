

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(auth()->guard('company')->user()->image)
                    <img src="{!! asset('public/upload/company/'.auth()->guard('company')->user()->image) !!}" class="img-circle" alt="User Image">
                @else
                    <img src="{!! asset('public/upload/images/default.png') !!}" class="img-circle" alt="User Image">

                @endif
            </div>
            <div class="pull-left info">
                <p>
                    @if(auth()->guard('company')->user())
                        {!! auth()->guard('company')->user()->name !!}

                    @else

                    @endif
                </p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">@lang('admin.MAIN NAVIGATION')</li>

            {{-- <li class="{!! getUrl('company.home') !!}">
                <a href="{!! route('company.home') !!}">
                    <i class="fa fa-dashboard"></i> <span>@lang('admin.Dashboard')</span>
                </a>
            </li> --}}


            @if(Auth::guard('company')->user())
            <li class="treeview  {!! getUrl('company.userVendor.index') !!} {!! getUrl('company.userVendor.create') !!}" >
                <a href="#">
                    <i class="fa fa-user-o"></i> <span>@lang('admin.User Vendor')</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" >
                    <li class="{!! getUrl('company.userVendor.index') !!}"><a href="{{route('company.userVendor.index')}}"><i class="fa fa-eye"></i> @lang('admin.View User Vendor')</a></li>
                    <li class="{!! getUrl('company.userVendor.create') !!}"><a href="{{route('company.userVendor.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create User Vendor')</a></li>
                </ul>
            </li>
            @endif


            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.member.index') !!} {!! getUrl('company.member.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-circle-o"></i> <span>@lang('admin.Members')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.member.index') !!}"><a href="{{route('company.member.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Members')</a></li>
                        <li class="{!! getUrl('company.member.create') !!}"><a href="{{route('company.member.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Member')</a></li>
                    </ul>

                </li>
            @endif

            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.supervisor.index') !!} {!! getUrl('company.supervisor.create') !!}" >
                    <a href="#">
                        <i class="fa fa-users"></i> <span>@lang('admin.Supervisors')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.supervisor.index') !!}"><a href="{{route('company.supervisor.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Supervisors')</a></li>
                        <li class="{!! getUrl('company.supervisor.create') !!}"><a href="{{route('company.supervisor.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Supervisor')</a></li>
                    </ul>

                </li>
            @endif


            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.carrier.index') !!} {!! getUrl('company.carrier.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-secret"></i> <span>@lang('admin.Carrier')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.carrier.index') !!}"><a href="{{route('company.carrier.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Carrier')</a></li>
                        <li class="{!! getUrl('company.carrier.create') !!}"><a href="{{route('company.carrier.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Carrier')</a></li>
                    </ul>

                </li>
            @endif

            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.driver.index') !!} {!! getUrl('company.driver.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-secret"></i> <span>@lang('admin.Drivers')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.driver.index') !!}"><a href="{{route('company.driver.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Drivers')</a></li>
                        <li class="{!! getUrl('company.driver.create') !!}"><a href="{{route('company.driver.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Driver')</a></li>
                    </ul>

                </li>
            @endif

            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.bus.index') !!} {!! getUrl('company.bus.create') !!}" >
                    <a href="#">
                        <i class="fa fa-bus"></i> <span>@lang('admin.Buses')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.bus.index') !!}"><a href="{{route('company.bus.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Buses')</a></li>
                        <li class="{!! getUrl('company.bus.create') !!}"><a href="{{route('company.bus.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Bus')</a></li>
                    </ul>

                </li>
            @endif


            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.guide.index') !!} {!! getUrl('company.guide.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-o"></i> <span>@lang('admin.Guides')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.guide.index') !!}"><a href="{{route('company.guide.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Guides')</a></li>
                        <li class="{!! getUrl('company.guide.create') !!}"><a href="{{route('company.guide.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Guide')</a></li>
                    </ul>

                </li>
            @endif



            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.destination.index') !!} {!! getUrl('company.destination.create') !!}" >
                    <a href="#">
                        <i class="fa fa-location-arrow"></i> <span>@lang('admin.Destination')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.destination.index') !!}"><a href="{{route('company.destination.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Destination')</a></li>
                        <li class="{!! getUrl('company.destination.create') !!}"><a href="{{route('company.destination.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Destination')</a></li>
                    </ul>

                </li>
            @endif

            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.path.index') !!} {!! getUrl('company.path.create') !!}" >
                    <a href="#">
                        <i class="fa fa-location-arrow"></i> <span>@lang('admin.Path')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.path.index') !!}"><a href="{{route('company.path.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Path')</a></li>
                        <li class="{!! getUrl('company.path.create') !!}"><a href="{{route('company.path.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Path')</a></li>
                    </ul>

                </li>
            @endif

            @if(Auth::guard('company')->user())
                <li class="treeview {!! getUrl('company.trip.index') !!} {!! getUrl('company.trip.create') !!}" >
                    <a href="#">
                        <i class="fa fa-plane"></i> <span>@lang('admin.Trips')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        <li class="{!! getUrl('company.trip.index') !!}"><a href="{{route('company.trip.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Trips')</a></li>
                        <li class="{!! getUrl('company.trip.create') !!}"><a href="{{route('company.trip.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Trip')</a></li>
                    </ul>

                </li>
            @endif


            <li class="{!! getUrl('admin.company.index') !!}"><a href="{{route('company.trip.management')}}"><i class="fa fa-plane"></i> @lang('admin.Trip Managment')</a></li>

            <li><a href="{!! route('company.logout') !!}"> <i class="fa fa-lock"></i> </i> @lang('admin.Sign out')</a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


