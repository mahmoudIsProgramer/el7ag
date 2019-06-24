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
            @if(Auth::guard('admin')->user()->hasPermission('read_admins'))
            <li ><a href="{{route('admin.admin.index')}}"><i class="fa fa-users"></i> @lang('admin.View Admin')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Admin')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data admin')</h3>
                </div>
                {!! Form::open(['url'=>route('admin.admin.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="row">
                        <input hidden name="id" value="{!! $admin->id !!}">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.First name')</label>
                                <input type="text" class="form-control" autofocus value="{{$admin->firstName}}"
                                       name="firstName" id="exampleInputEmail1" placeholder="@lang('admin.First name')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.E-mail')</label>
                                <input type="email" class="form-control" value="{{$admin->email}}" name="email" id="exampleInputEmail1" placeholder="@lang('admin.E-mail')">
                            </div>



                            @if($admin->image)
                            <div class="form-group">
                                <img src="{{$admin->imagePath}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                            </div>
                            @else
                                <div class="form-group">
                                    <img src="{{asset('upload/images/default.png')}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
                                </div>
                                @endif
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Avatar')</label>
                                <input type="file" class="form-control image" name="image" >
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Address')</label>
                                <input type="text" class="form-control"  value="{{$admin->address}}" name="address" id="exampleInputEmail1" placeholder="@lang('admin.Address')">
                            </div>


                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Last name')</label>
                                <input type="text" class="form-control"  value="{{$admin->lastName}}" name="lastName" id="exampleInputEmail1" placeholder="@lang('admin.Last name')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Phone')</label>
                                <input type="text" class="form-control"  value="{{$admin->phone}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="phone" id="exampleInputEmail1" placeholder="@lang('admin.Phone')">
                            </div>


                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Status')</label>
                                {!! Form::select('status',['1'=>trans('admin.Active'),'2'=>trans('admin.In-Active')],
                                $admin->status,['class'=>'form-control select2','style'=>'width: 100%;','placeholder'=>trans('admin.Select status')]) !!}

                            </div>



                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('admin.Permission')</label>
                                <div class="nav-tabs-custom">
                                    @php
                                        $models = ['admins','company'];
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
                                                @foreach($maps as  $map)
                                                    <div class="checkbox">
                                                        <label>
                                                            <input type="checkbox"  name="permission[]" {{$admin->hasPermission($map.'_'.$model) ? 'checked' : ''}}  value="{{$map.'_'.$model}}"> {{$map}}
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


