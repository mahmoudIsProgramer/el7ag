<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>@lang('admin.Al7ag') | @yield('title')</title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="csrf-token" content="{{ csrf_token() }}" />


<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('backend/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{asset('backend/bower_components/font-awesome/css/font-awesome.min.css')}}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('backend/bower_components/Ionicons/css/ionicons.min.css')}}">
<link rel="stylesheet" href="{{asset('backend/dist/css/jquery.calendars.picker.css')}}">

<!-- Theme style -->
@if( LaravelLocalization::getCurrentLocaleDirection() == 'ltr')
    <link rel="stylesheet" href="{{asset('backend/dist/css/AdminLTE.min.css')}}">

@else

    <link rel="stylesheet" href="{{asset('backend/dist/css/rtl/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/dist/css/rtl/bootstrap-rtl.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/dist/css/rtl/rtl.css')}}">

    {{-- <link rel="stylesheet" href="{{asset('backend/dist/css/fonts/fonts-fa.css')}}">--}}

    <link href="https://fonts.googleapis.com/css?family=Cairo:300,400&amp;subset=arabic,latin-ext" rel="stylesheet">
    <style type="text/css">
        html,body,h1,h2,h3,h4,h5,.alert{
            font-family: 'Cairo', sans-serif;
        }
    </style>

@endif
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{asset('backend/dist/css/skins/_all-skins.min.css')}}">
<!-- Morris chart -->
<link rel="stylesheet" href="{{asset('backend/bower_components/morris.js/morris.css')}}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{asset('backend/bower_components/jvectormap/jquery-jvectormap.css')}}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">


<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<!-- Google Font -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

<script src="{{asset('backend/bower_components/jquery/dist/jquery.min.js')}}"></script>
<script src="{{asset('backend/dist/js/jquery.calendars.js')}}"></script>
<script src="{{asset('backend/dist/js/jquery.calendars.plus.min.js')}}"></script>
<script src="{{asset('backend/dist/js/jquery.plugin.min.js')}}"></script>
<script src="{{asset('backend/dist/js/jquery.calendars.picker.js')}}"></script>
<script src="{{asset('backend/dist/js/jquery.calendars.islamic.min.js')}}"></script>

<!-- Select2 -->
<link rel="stylesheet" href="{{asset('backend/bower_components/select2/dist/css/select2.min.css')}}">
<!-- jstree -->
<link rel="stylesheet" href="{{asset('backend/jstree/themes/default/style.css')}}">

<link rel="stylesheet" href="{!! asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') !!}">

<link rel="stylesheet" href="{!! asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') !!}">

<link rel="stylesheet" href="{!! asset('backend/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') !!}">
<!-- Bootstrap time Picker -->
<link rel="stylesheet" href="{!! asset('backend/plugins/timepicker/bootstrap-timepicker.min.css') !!}">
{{-- full calendar  --}}
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
