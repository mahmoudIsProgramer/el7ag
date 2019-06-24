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
                <li ><a href="{{route('company.driver.index')}}"><i class="fa fa-user-secret"></i> @lang('admin.View Carrier')</a></li>

                @elseif(Auth::guard('user_vendor')->user()->hasPermission('read_drivers'))
                    <li ><a href="{{route('company.driver.index')}}"><i class="fa fa-user-secret"></i> @lang('admin.View Carrier')</a></li>

                @else
                <li ><a><i class="fa fa-user-secret disabled"></i> @lang('admin.View Carrier')</a></li>
            @endif
            <li class="active">@lang('admin.Create Carrier')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data carrier')</h3>
                </div>
                {!! Form::open(['url'=>route('company.carrier.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{old($locale.'.name')}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                    old('status'),['class'=>'form-control select2','style'=>'width: 100%;' ]) !!}

                                </div>

                        </div>
                    </div>
                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fa fa-save"></i> @lang('admin.Save')</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </section>

@endsection


