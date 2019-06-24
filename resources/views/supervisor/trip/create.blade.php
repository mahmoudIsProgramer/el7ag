@extends('backend.layouts.master')

@section('title',trans('admin.Trips'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Trips')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('supervisor')->user())
                <li ><a href="{{route('supervisor.trip.index')}}"><i class="fa fa-plane"></i> @lang('admin.View Trips')</a></li>
                @else
                <li ><a><i class="fa fa-plane disabled"></i> @lang('admin.View Trips')</a></li>
            @endif
            <li class="active">@lang('admin.Create Trips')</li>
        </ol>
    </section>
@endsection

@section('content')

    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                @if(old('driver_id'))
                $.ajax({
                    url:'{!! route('supervisor.trip.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{driver_id:'{!! old('driver_id') !!}',select:'{!! old('bus_id') !!}'},
                    success: function (data) {
                        $('.state').html(data)
                    }
                });
                @endif
                @if(old('path_id'))
                $.ajax({
                    url:'{!! route('supervisor.trip.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{path_id:'{!! old('path_id') !!}',select:'{!! old('price') !!}'},
                    success: function (data) {
                        $('.statePrice').html(data)
                    }
                });
                @endif
                $(document).on('change','.driver_id',function () {
                    var city = $('.driver_id option:selected').val();
                    if (city > 0){
                        $.ajax({
                            url:'{!! route('supervisor.trip.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{driver_id:city,select:''},
                            success: function (data) {
                                $('.state').html(data)
                            }
                        });
                    }
                    else{
                        $('.state').html('')
                    }
                });

                $(document).on('change','.path_id',function () {
                    var city = $('.path_id option:selected').val();
                    if (city > 0){
                        $.ajax({
                            url:'{!! route('supervisor.trip.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{path_id:city,select:''},
                            success: function (data) {
                                $('.statePrice').html(data)
                            }
                        });
                    }
                    else{
                        $('.statePrice').html('')
                    }
                })
            })
        </script>


        @endpush
    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data trip')</h3>
                </div>
                {!! Form::open(['url'=>route('supervisor.trip.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{old($locale.'.name')}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach


                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Number passengers')</label>
                                    <input type="text" class="form-control"  value="{{old('number_passenger')}}"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           name="number_passenger" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')">
                                </div>


                                {{-- <div class="row">
                                   <div class="col-md-6">
                                       <div class="form-group">
                                           <label>@lang('admin.Flight start time')</label>
                                           <input type="text" name="start_time" value="{{old('start_time')}}" class="form-control"
                                                   data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                           <div class="input-group">
                                               <input type="time" value="{!! old('start_time') !!}"
                                                      name="start_time"
                                                      class="form-control">

                                               <div class="input-group-addon">
                                                   <i class="fa fa-clock-o"></i>
                                               </div>
                                           </div>
                                       </div>

                                   </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('admin.Flight end time')</label>
                                            <input type="text" name="start_time" value="{{old('start_time')}}" class="form-control"
                                                    data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>
                                            <div class="input-group">
                                                <input type="time" value="{!! old('end_time') !!}"
                                                       name="end_time"
                                                       class="form-control">

                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('admin.Flight start date')</label>

                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>

                                                <input type = "datetime-local" name = "start_date"  
                                                    value="{!! old('start_date') !!}" class="form-control"><br>

                                                
                                            </div>
                                            <!-- /.input group -->
                                        </div>

                                    </div>
                                    <div class="col-md-6">

                                        <div class="form-group">
                                            <label>@lang('admin.Flight end date')</label>

                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type = "datetime-local" name = "end_date"  
                                                    value="{!! old('end_date') !!}" class="form-control"><br>
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>


                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Assigned'),2=>trans('admin.Underway'),
                                    3=>trans('admin.hanging'),4=>trans('admin.Canceled'),
                                    7=>trans('admin.Scheduled'),
                                    10=>trans('admin.Instant'),
                                    ],
                                    old('status'),['class'=>'form-control select2','style'=>'width: 100%;',
                                    'placeholder'=>trans('admin.Select Status')]) !!}

                                </div>


                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Guide name')</label>
                                <select class="form-control select2" style="width: 100%;" name="guide_id">
                                    <option>@lang('admin.Select guide name')</option>
                                    @foreach($guide as $key => $value)
                                            <option  value="{{$value->id}}" {!! old('guide_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Driver name')</label>
                                <select class="form-control select2 driver_id" style="width: 100%;" name="driver_id">
                                    <option>@lang('admin.Select driver name')</option>
                                    @foreach($driver as $key => $value)
                                            <option  value="{{$value->id}}" {!! old('driver_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>@lang('admin.Bus name')</label>
                                <span class="state"></span>

                            </div>

                             <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Path')</label>
                                <select class="form-control select2 path_id" style="width: 100%;" name="path_id">
                                    <option>@lang('admin.Select path')</option>
                                    @foreach($path as $key => $value)
                                        <option  value="{{$value->id}}" {!! old('path_id') == $value->id ? 'selected' : '' !!} >
                                            {{\App\Destination::find($value->from)->name . ' | '.\App\Destination::find($value->to)->name}}
                                        </option>

                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Price path')</label>
                                <span class="statePrice"></span>

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
            <!-- /.box -->



        </div>

    </section>

@endsection


