

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if(auth()->guard('admin')->user()->image)
                    <img src="{!! asset('public/upload/admin/'.auth()->guard('admin')->user()->image) !!}" class="img-circle" alt="User Image">
                @else
                    <img src="{!! asset('public/upload/images/default.png') !!}" class="img-circle" alt="User Image">

                @endif
            </div>
            <div class="pull-left info">
                <p>
                    @if(auth()->guard('admin')->user())
                        {!! auth()->guard('admin')->user()->firstName .' '.auth()->guard('admin')->user()->lastName !!}

                    @else

                    @endif
                </p>
            </div>
        </div>

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">@lang('admin.MAIN NAVIGATION')</li>

            {{-- <li class="{!! getUrl('admin.home') !!}">
                <a href="{!! route('admin.home') !!}">
                    <i class="fa fa-dashboard"></i> <span>@lang('admin.Dashboard')</span>
                </a>
            </li> --}}


            <li class="treeview {!! getUrl('admin.admin.index') !!} {!! getUrl('admin.admin.create') !!}" >
                <a href="#">
                    <i class="fa fa-user-o"></i> <span>@lang('admin.Admins')</span>
                    <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
            </span>
                </a>
                <ul class="treeview-menu">
                    @if(Auth::guard('admin')->user()->hasPermission('read_admins'))
                    <li class="{!! getUrl('admin.admin.index') !!}"><a href="{{route('admin.admin.index')}}"><i class="fa fa-users"></i> @lang('admin.View Admin')</a></li>
                    @endif
                    @if(Auth::guard('admin')->user()->hasPermission('create_admins'))
                    <li class="{!! getUrl('admin.admin.create') !!}"><a href="{{route('admin.admin.create')}}"><i class="fa fa-user"></i> @lang('admin.Create Admin')</a></li>
                    @endif
                </ul>
            </li>

            <li class="treeview {!! getUrl('admin.company.index') !!} {!! getUrl('admin.company.create') !!}" >
                <a href="#">
                    <i class="fa fa-university"></i> <span>@lang('admin.Companies')</span>
                    <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" >
                    @if(Auth::guard('admin')->user()->hasPermission('read_company'))
                        <li class="{!! getUrl('admin.company.index') !!}"><a href="{{route('admin.company.index')}}"><i class="fa fa-list"></i> @lang('admin.View Company')</a></li>
                    @endif
                    @if(Auth::guard('admin')->user()->hasPermission('create_company'))
                        <li class="{!! getUrl('admin.company.create') !!}"><a href="{{route('admin.company.create')}}"><i class="fa fa-plus"></i> @lang('admin.Create Company')</a></li>
                    @endif
                </ul>
            </li>
    
            <li><a href="{!! route('admin.logout') !!}"> <i class="fa fa-lock"></i> </i> @lang('admin.Sign out')</a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>


