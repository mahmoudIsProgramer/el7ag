

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(auth()->guard('supervisor')->user()->image)
                    <img src="{!! asset('public/upload/supervisor/'.auth()->guard('supervisor')->user()->image) !!}" class="img-circle" alt="User Image">
                @else
                    <img src="{!! asset('public/upload/images/default.png') !!}" class="img-circle" alt="User Image">

                @endif
            </div>
            <div class="pull-left info">
                <p>
                    @if(auth()->guard('supervisor')->user())
                        {!! auth()->guard('supervisor')->user()->name !!}

                    @else

                    @endif
                </p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">@lang('admin.MAIN NAVIGATION')</li>

            {{-- <li class="{!! getUrl('supervisor.home') !!}">
                <a href="{!! route('supervisor.home') !!}">
                    <i class="fa fa-dashboard"></i> <span>@lang('admin.Dashboard')</span>
                </a>
            </li> --}}


          @if(Auth::guard('supervisor')->user())
            <li class="treeview {!! getUrl('supervisor.trip.index') !!} {!! getUrl('supervisor.trip.create') !!}" >
              <a href="#">
                <i class="fa fa-plane"></i> <span>@lang('admin.Trips')</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
              </a>
              <ul class="treeview-menu" >
                <li class="{!! getUrl('supervisor.trip.index') !!}"><a href="{{route('supervisor.trip.index')}}"><i class="fa fa-eye"></i> @lang('admin.View Trips')</a></li>
                <li class="{!! getUrl('supervisor.trip.create') !!}"><a href="{{route('supervisor.trip.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Trip')</a></li>
              </ul>

            </li>
          @endif


            <li><a href="{!! route('supervisor.logout') !!}"> <i class="fa fa-lock"></i> </i> @lang('admin.Sign out')</a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


