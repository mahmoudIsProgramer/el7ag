

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(auth()->guard('user_vendor')->user()->image)
                    <img src="{!! asset('public/upload/userVendor/'.auth()->guard('user_vendor')->user()->image) !!}" class="img-circle" alt="User Image">
                @else
                    <img src="{!! asset('public/upload/images/default.png') !!}" class="img-circle" alt="User Image">

                @endif
            </div>
            <div class="pull-left info">
                <p>
                    @if(auth()->guard('user_vendor')->user())
                        {!! auth()->guard('user_vendor')->user()->name !!}

                    @else

                    @endif
                </p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">@lang('admin.MAIN NAVIGATION')</li>

            {{-- <li class="{!! getUrl('user.vendor.home') !!}">
                <a href="{!! route('user.vendor.home') !!}">
                    <i class="fa fa-dashboard"></i> <span>@lang('admin.Dashboard')</span>
                </a>
            </li> --}}



                <li class="treeview {!! getUrl('user.vendor.supervisor.index') !!} {!! getUrl('user.vendor.supervisor.create') !!}" >
                    <a href="#">
                        <i class="fa fa-users"></i> <span>@lang('admin.Supervisors')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        @if(Auth::guard('user_vendor')->user()->hasPermission('read_supervisors'))
                        <li class="{!! getUrl('user.vendor.supervisor.index') !!}"><a href="{{route('user.vendor.supervisor.index')}}"><i class="fa fa-users"></i> @lang('admin.View Supervisors')</a></li>
                        @endif

                        @if(Auth::guard('user_vendor')->user()->hasPermission('create_supervisors'))
                        <li class="{!! getUrl('user.vendor.supervisor.create') !!}"><a href="{{route('user.vendor.supervisor.create')}}"><i class="fa fa-user"></i> @lang('admin.Create Supervisor')</a></li>
                        @endif
                    </ul>

                </li>

                <li class="treeview {!! getUrl('user.vendor.driver.index') !!} {!! getUrl('user.vendor.driver.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-secret"></i> <span>@lang('admin.Drivers')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        @if(Auth::guard('user_vendor')->user()->hasPermission('read_drivers'))
                        <li class="{!! getUrl('user.vendor.driver.index') !!}"><a href="{{route('user.vendor.driver.index')}}"><i class="fa fa-users"></i> @lang('admin.View Drivers')</a></li>
                        @endif

                        @if(Auth::guard('user_vendor')->user()->hasPermission('create_drivers'))
                        <li class="{!! getUrl('user.vendor.driver.create') !!}"><a href="{{route('user.vendor.driver.create')}}"><i class="fa fa-user"></i> @lang('admin.Create Driver')</a></li>
                     @endif
                    </ul>

                </li>

                <li class="treeview {!! getUrl('user.vendor.member.index') !!} {!! getUrl('user.vendor.member.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-circle-o"></i> <span>@lang('admin.Members')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        @if(Auth::guard('user_vendor')->user()->hasPermission('read_members'))
                        <li class="{!! getUrl('user.vendor.member.index') !!}"><a href="{{route('user.vendor.member.index')}}"><i class="fa fa-users"></i> @lang('admin.View Members')</a></li>
                        @endif

                        @if(Auth::guard('user_vendor')->user()->hasPermission('create_members'))
                        <li class="{!! getUrl('user.vendor.member.create') !!}"><a href="{{route('user.vendor.member.create')}}"><i class="fa fa-user"></i> @lang('admin.Create Member')</a></li>
                        @endif
                    </ul>

                </li>

                <li class="treeview {!! getUrl('user.vendor.guide.index') !!} {!! getUrl('user.vendor.guide.create') !!}" >
                    <a href="#">
                        <i class="fa fa-user-o"></i> <span>@lang('admin.Guides')</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" >
                        @if(Auth::guard('user_vendor')->user()->hasPermission('read_guides'))
                        <li class="{!! getUrl('user.vendor.guide.index') !!}"><a href="{{route('user.vendor.guide.index')}}"><i class="fa fa-users"></i> @lang('admin.View Guides')</a></li>
                        @endif

                        @if(Auth::guard('user_vendor')->user()->hasPermission('create_guides'))
                        <li class="{!! getUrl('user.vendor.guide.create') !!}"><a href="{{route('user.vendor.guide.create')}}"><i class="fa fa-user"></i> @lang('admin.Create Guide')</a></li>
                        @endif
                    </ul>

                </li>

            <li class="treeview {!! getUrl('user.vendor.bus.index') !!} {!! getUrl('user.vendor.bus.create') !!}" >
                <a href="#">
                    <i class="fa fa-bus"></i> <span>@lang('admin.Buses')</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" >
                    @if(Auth::guard('user_vendor')->user()->hasPermission('read_buses'))
                    <li class="{!! getUrl('user.vendor.bus.index') !!}"><a href="{{route('user.vendor.bus.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Buses')</a></li>
                    @endif

                    @if(Auth::guard('user_vendor')->user()->hasPermission('create_buses'))
                    <li class="{!! getUrl('user.vendor.bus.create') !!}"><a href="{{route('user.vendor.bus.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Bus')</a></li>
                     @endif
                </ul>

            </li>

            <li class="treeview {!! getUrl('user.vendor.trip.index') !!} {!! getUrl('user.vendor.trip.create') !!}" >
                <a href="#">
                    <i class="fa fa-plane"></i> <span>@lang('admin.Trips')</span>
                    <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu" >
                    @if(Auth::guard('user_vendor')->user()->hasPermission('read_trips'))
                    <li class="{!! getUrl('user.vendor.trip.index') !!}"><a href="{{route('user.vendor.trip.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Trips')</a></li>
                    @endif

                    @if(Auth::guard('user_vendor')->user()->hasPermission('create_trips'))
                    <li class="{!! getUrl('user.vendor.trip.create') !!}"><a href="{{route('user.vendor.trip.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Trip')</a></li>
                    @endif
                </ul>

            </li>

            {{--  <li class="treeview {!! activeMenu('company')[0] !!}" >
                  <a href="#">
                      <i class="fa fa-university"></i> <span>@lang('admin.Companies')</span>
                      <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                  </a>
                  <ul class="treeview-menu" style="{!! activeMenu('company')[1] !!}">
                      @if(Auth::guard('admin')->user()->hasPermission('read_company'))
                          <li><a href="{{route('admin.company.index')}}"><i class="fa fa-list"></i> @lang('admin.View Company')</a></li>
                      @endif
                      @if(Auth::guard('admin')->user()->hasPermission('create_company'))
                          <li><a href="{{route('admin.company.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Company')</a></li>
                      @endif
                  </ul>
              </li>--}}


            <li><a href="{!! route('company.logout') !!}"> <i class="fa fa-lock"></i>  @lang('admin.Sign out')</a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


