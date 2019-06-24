@extends('backend.layouts.master')

@section('title',trans('admin.Carrier'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Carrier')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.driver.index')}}"><i class="fa fa-university"></i> @lang('admin.View Carrier')</a></li>
                @elseif(Auth::guard('company')->user()->hasPermission('read_drivers'))
                    <li ><a href="{{route('company.driver.index')}}"><i class="fa fa-university"></i> @lang('admin.View Carrier')</a></li>

                @else
                <li ><a><i class="fa fa-university disabled"></i> @lang('admin.View Carrier')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Driver')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data carrier')</h3>
                </div>
                {!! Form::open(['url'=>route('company.carrier.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $carrier->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{$carrier->translate($locale)->name}}"
                                           name="{{$locale}}[name]" id="exampleInputEmail1"
                                           placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                    $carrier->status,['class'=>'form-control select2','style'=>'width: 100%;'
                                    ]) !!}

                                </div>


                        </div>



                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->



        </div>

    </section>

@endsection


