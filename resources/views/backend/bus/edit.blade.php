@extends('backend.layouts.master')

@section('title',trans('admin.Buses'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Buses')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.bus.index')}}"><i class="fa fa-university"></i> @lang('admin.View Buses')</a></li>
                @elseif(Auth::guard('company')->user()->hasPermission('read_buses'))
                    <li ><a href="{{route('company.bus.index')}}"><i class="fa fa-university"></i> @lang('admin.View Buses')</a></li>

                @else
                <li ><a><i class="fa fa-university disabled"></i> @lang('admin.View Buses')</a></li>
            @endif
            <li class="active">@lang('admin.Edit Bus')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data bue')</h3>
                </div>
                {!! Form::open(['url'=>route('company.bus.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $bus->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{$bus->translate($locale)->name}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Number chairs')</label>
                                    <input type="text" class="form-control"  value="{{$bus->number_chairs}}"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           name="number_chairs" id="exampleInputEmail1" placeholder="@lang('admin.Number chairs')">
                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                    $bus->status,['class'=>'form-control select2','style'=>'width: 100%;',
                                    'placeholder'=>trans('admin.Select Status')]) !!}

                                </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Carrier name')</label>
                                <select class="form-control select2" style="width: 100%;" name="carrier_id">
                                    <option>@lang('admin.Select carrier name')</option>
                                    @foreach($carrier as $key => $value)
                                        <option  value="{{$value->id}}" {!! $bus->carrier_id == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Driver name')</label>
                                <select class="form-control select2" style="width: 100%;" name="driver_id">
                                    <option>@lang('admin.Select driver name')</option>
                                    @foreach($driver as $key => $value)
                                        <option  value="{{$value->id}}" {!! $bus->driver_id == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Number bus')</label>
                                <input type="text" class="form-control"  value="{{$bus->number_bus}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="number_bus" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Plate Number')</label>
                                <input type="text" class="form-control"  value="{{$bus->plate_number}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="plate_number" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')">
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


