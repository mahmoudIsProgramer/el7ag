<!DOCTYPE html>
<html dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    @include('backend.component.head')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    @if(Auth::guard('admin')->user())

    @include('backend.component.header')

    @elseif(Auth::guard('company')->user())
        @include('backend.component.company.header')

        @elseif(Auth::guard('user_vendor')->user())

        @include('backend.component.userVendor.header')

    @elseif(Auth::guard('supervisor')->user())
        @include('backend.component.supervisor.header')
    @endif
    <!-- Left side column. contains the logo and sidebar -->

    @if(Auth::guard('admin')->user())

         @include('backend.component.sidebar')

    @elseif(Auth::guard('company')->user())
        @include('backend.component.company.sidebar')

    @elseif(Auth::guard('user_vendor')->user())
        @include('backend.component.userVendor.sidebar')

    @elseif(Auth::guard('supervisor')->user())
        @include('backend.component.supervisor.sidebar')

    @endif
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        @yield('content-wrapper')


        <!-- Main content -->
        <section class="content">

            @include('backend.component.messages')

            @yield('content')


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
   @include('backend.component.footer')

    <!-- Control Sidebar -->

   @include('backend.component.control-sidebar')
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->

</div>
<!-- ./wrapper -->


@include('backend.component.script')
</body>
</html>
