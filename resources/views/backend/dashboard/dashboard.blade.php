@extends('backend.layouts.master')

@section('title',trans('admin.Dashboard'))


@section('content-wrapper')

    <section class="content-header">
               <h1>
                   @lang('admin.Dashboard')
                   <small>@lang('admin.Control panel')</small>
               </h1>
               <ol class="breadcrumb">
                   <li><a href="{!! route('admin.home') !!}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
                   <li class="active">@lang('admin.Dashboard')</li>
               </ol>
           </section>
 @endsection

@section('content')

   <!-- Main row -->
   <div class="row">
    <!-- Left col -->
    <h1 class = 'text-center p-m:4'> @lang('admin.smart_guide')</h1>
    <section class="col-lg-12 connectedSortable">
        <img src = "{{asset('public/upload/photos_maca.jpg')}}"  style = 'height:700px;font-weight: bold;'>
    </section>
    <!-- /.Left col -->

</div>
<!-- /.row (main row) -->


    @endsection


