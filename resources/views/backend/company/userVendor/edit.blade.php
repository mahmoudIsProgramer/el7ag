@extends('backend.layouts.master')

@section('title',trans('admin.Companies'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Companies')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('company.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
                <li ><a href="{{route('company.userVendor.index')}}"><i class="fa fa-university"></i> @lang('admin.View user vendor')</a></li>
            <li class="active">@lang('admin.Edit user vendor')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data company')</h3>
                </div>
                {!! Form::open(['url'=>route('company.userVendor.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $userVendor->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{$userVendor->translate($locale)->name}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <img src="{{$userVendor->imagePath}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Image')</label>
                                    <input type="file" class="form-control image" name="image" >
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputPassword1">@lang('admin.Permission')</label>
                                    <div class="nav-tabs-custom">
                                        @php
                                            $models = ['guides','drivers','supervisors','members','buses','trips','path','carrier','destination'];
                                           $maps = ['create','read','update','delete']
                                        @endphp
                                        <ul class="nav nav-tabs">
                                            @foreach($models as $key =>$model)
                                                <li class="{{$key == 0 ? 'active' : ''}}"><a href="#{{$model}}" data-toggle="tab">{{$model}}</a></li>
                                            @endforeach
                                        </ul>
                                        <div class="tab-content">
                                            @foreach($models as $key =>$model)
                                                <div class="tab-pane {{$key == 0 ? 'active' : ''}}" id="{{$model}}">
                                                    @foreach($maps as $index => $map)
                                                        <div class="checkbox">
                                                            <label>
                                                                <input type="checkbox" name="permission[]" {{$userVendor->hasPermission($map.'_'.$model) ? 'checked' : ''}} value="{{$map.'_'.$model}}"> {{$map}}
                                                            </label>
                                                        </div>
                                                    @endforeach

                                                </div>

                                            @endforeach
                                        </div>
                                        <!-- /.tab-content -->
                                    </div>

                                </div>


                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputNumber">@lang('admin.number')</label>
                                <input type="text" class="form-control" name="number" value="{!!$userVendor->number !!}" id="exampleInputNumber" placeholder="@lang('admin.number')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.E-mail')</label>
                                <input type="email" class="form-control" name="email" value="{!! $userVendor->email !!}" id="exampleInputEmail1" placeholder="@lang('admin.E-mail')">
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-2 control-label">@lang('Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $userVendor->status,['class'=>'form-control select2','style'=>'width: 100%;',
                                'placeholder'=>trans('admin.Select Status')]) !!}

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Address')</label>
                                <input type="text" class="form-control"  value="{{$userVendor->address}}" name="address" id="exampleInputEmail1" placeholder="@lang('admin.Address')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Phone')</label>
                                <input type="text" class="form-control"  value="{{$userVendor->phone}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="phone" id="exampleInputEmail1" placeholder="@lang('admin.Phone')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Mobile')</label>
                                <input type="text" class="form-control"  value="{{$userVendor->mobile}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="mobile" id="exampleInputEmail1" placeholder="@lang('admin.Mobile')">
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


