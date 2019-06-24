<!DOCTYPE html>
<html>
<head>
   @include('backend.auth.backend.component.head')
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href=""><b>@lang('admin.Al7ag')</b></a>
    </div>
    <!-- /.login-logo -->
   @yield('content')
@include('backend.auth.backend.component.messages')

<!-- /.login-box-body -->
</div>
<!-- /.login-box -->

@include('backend.auth.backend.component.script')
</body>
</html>
