@extends('backend.layouts.master')

@section('title',trans('admin.Supervisors'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Supervisors')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.supervisor.index')}}"><i class="fa fa-users"></i> @lang('admin.View Supervisors')</a></li>
                @elseif(Auth::guard('company')->user()->hasPermission('read_supervisors'))
                    <li ><a href="{{route('company.supervisor.index')}}"><i class="fa fa-users"></i> @lang('admin.View Supervisors')</a></li>

                @else
                <li ><a><i class="fa fa-users disabled"></i> @lang('admin.View Supervisors')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Supervisor')</li>
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
                {!! Form::open(['url'=>route('company.supervisor.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $supervisor->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{$supervisor->translate($locale)->name}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <img src="{{$supervisor->imagePath}}" style="width: 100px;" class="img-thumbnail image-preview" alt="">
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
                                               name="birthday" value="{!! $supervisor->birthday !!}">
                                    </div>
                                    <!-- /.input group -->
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Mobile')</label>
                                    <input type="text" class="form-control"  value="{{$supervisor->mobile}}"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           name="mobile" id="exampleInputEmail1" placeholder="@lang('admin.Mobile')">
                                </div>


                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Member name')</label>
                                <select class="form-control select2" style="width: 100%;" name="member_id">
                                    <option>@lang('admin.Select member name')</option>
                                    @foreach($member as $key => $value)
                                        <option  value="{{$value->id}}" {!! $supervisor->member_id == $value->id ? 'selected' : '' !!}> {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.SSN')</label>
                                <input type="text" class="form-control"  value="{{$supervisor->ssn}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="ssn" id="exampleInputEmail1" placeholder="@lang('admin.SSN')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Nationality')</label>
                                <input type="text" class="form-control" name="nationality"
                                       value="{!! $supervisor->nationality !!}" id="exampleInputEmail1"
                                       placeholder="@lang('admin.Nationality')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.E-mail')</label>
                                <input type="email" class="form-control" name="email" value="{!! $supervisor->email !!}" id="exampleInputEmail1" placeholder="@lang('admin.E-mail')">
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-2 control-label">@lang('Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                $supervisor->status,['class'=>'form-control select2','style'=>'width: 100%;',
                                'placeholder'=>trans('admin.Select Status')]) !!}

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Address')</label>
                                <input type="text" class="form-control"  value="{{$supervisor->address}}" name="address" id="exampleInputEmail1" placeholder="@lang('admin.Address')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Phone')</label>
                                <input type="text" class="form-control"  value="{{$supervisor->phone}}"
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


