@extends('backend.layouts.master')

@section('title',trans('admin.Drivers'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Drivers')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.driver.index')}}"><i class="fa fa-university"></i> @lang('admin.View Drivers')</a></li>
                @elseif(Auth::guard('company')->user()->hasPermission('read_drivers'))
                    <li ><a href="{{route('company.driver.index')}}"><i class="fa fa-university"></i> @lang('admin.View Drivers')</a></li>

                @else
                <li ><a><i class="fa fa-university disabled"></i> @lang('admin.View Drivers')</a></li>
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
                    <h3 class="box-title">@lang('admin.Edit data driver')</h3>
                </div>
                {!! Form::open(['url'=>route('company.driver.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $driver->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{$driver->translate($locale)->name}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <img src="{{$driver->imagePath}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Image')</label>
                                    <input type="file" class="form-control image" name="image" >
                                </div>

                                <div class="form-group">
                                    <label>@lang('admin.Birthday')</label>

                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="date" class="form-control"
                                               name="birthday" value="{!! $driver->birthday !!}">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Mobile')</label>
                                    <input type="text" class="form-control"  value="{{$driver->mobile}}"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           name="mobile" id="exampleInputEmail1" placeholder="@lang('admin.Mobile')">
                                </div>
                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.SSN')</label>
                                <input type="text" class="form-control"  value="{{$driver->ssn}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="ssn" id="exampleInputEmail1" placeholder="@lang('admin.SSN')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Nationality')</label>
                                <input type="text" class="form-control" name="nationality"
                                       value="{!! $driver->nationality !!}" id="exampleInputEmail1"
                                       placeholder="@lang('admin.Nationality')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.E-mail')</label>
                                <input type="email" class="form-control" name="email" value="{!! $driver->email !!}" id="exampleInputEmail1" placeholder="@lang('admin.E-mail')">
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-2 control-label">@lang('Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $driver->status,['class'=>'form-control select2','style'=>'width: 100%;',
                                'placeholder'=>trans('admin.Select Status')]) !!}

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Address')</label>
                                <input type="text" class="form-control"  value="{{$driver->address}}" name="address" id="exampleInputEmail1" placeholder="@lang('admin.Address')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Phone')</label>
                                <input type="text" class="form-control"  value="{{$driver->phone}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="phone" id="exampleInputEmail1" placeholder="@lang('admin.Phone')">
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


