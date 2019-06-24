@extends('backend.layouts.master')

@section('title',trans('admin.Admins'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Admins')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Admin')</li>
        </ol>
    </section>
@endsection()

@section('content')



       <div class="row">
           <div class="col-xs-12">
               <div class="box">
                   <div class="box-body">
                       @if(Auth::guard('admin')->user()->hasPermission('delete_admins'))
                       {!! Form::open(['id'=>'form_data','url'=>route('admin.admin.delete.all'),'method'=>'delete']) !!}
                       @endif

                           @if(Auth::guard('admin')->user()->hasPermission('read_admins'))
                       {!! $dataTable->table(['class'=>'dataTable table table-bordered table-hover'],true) !!}
                           @endif

                           @if(Auth::guard('admin')->user()->hasPermission('delete_admins'))
                       {!! Form::close() !!}
                           @endif
                   </div>
               </div>

           </div>
           <!-- /.col -->
       </div>

   <!-- The Modal -->
       @if(Auth::guard('admin')->user()->hasPermission('delete_admins'))
   <div class="modal" id="multipleDelete">
       <div class="modal-dialog">
           <div class="modal-content">

               <!-- Modal Header -->
               <div class="modal-header">
                   <h4 class="modal-title">@lang('admin.Delete')</h4>
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
               </div>

               <!-- Modal body -->
               <div class="modal-body">

                   <div class="alert alert-danger">

                       <div class="empty_record hidden">
                           <h3>@lang('admin.Please select some records to delete')  </h3>
                       </div>


                       <div class="not_empty_record hidden">
                           <h3>@lang('admin.Are you sure delete all')  <span class="record_count"></span> ? </h3>
                       </div>


                   </div>

               </div>

               <!-- Modal footer -->
               <div class="empty_record hidden">
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                   </div>
               </div>
               <div class="not_empty_record hidden">
                   <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.No')</button>
                       <input type="submit" name="del_all" value="@lang('admin.Yes')" class="btn btn-danger del_all">
                   </div>
               </div>


           </div>
       </div>
   </div>
   @endif


   @push('js')
       <script>
           delete_all();
       </script>
       {!! $dataTable->scripts() !!}

   @endpush

@endsection


