@extends('backend.layouts.master')

@section('title',trans('admin.Buses'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Trips')
            <small>@lang('admin.Control panel')</small>
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.trip.index')}}"><i class="fa fa-plane"></i> @lang('admin.View Trips')</a></li>
                @elseif(Auth::guard('company')->user()->hasPermission('read_trips'))
                    <li ><a href="{{route('company.trip.index')}}"><i class="fa fa-plane"></i> @lang('admin.View Trips')</a></li>

                @else
                <li ><a><i class="fa fa-plane disabled"></i> @lang('admin.View Trips')</a></li>
            @endif
            <li class="active">@lang('admin.Edit trip')</li>
        </ol>
        
    </section>
@endsection()

@section('content')

    @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                @if($trip->driver_id )
                $.ajax({
                    url:'{!! route('company.trip.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{driver_id:'{!! $trip->driver_id !!}',select:'{!! $trip->bus_id !!}'},
                    success: function (data) {
                        $('.state').html(data)
                    }
                });
                @endif
                @if($trip->path_id )
                $.ajax({
                    url:'{!! route('company.trip.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{path_id:'{!! $trip->path_id !!}',select:'{!! $trip->price !!}'},
                    success: function (data) {
                        $('.statePrice').html(data)
                    }
                });
                @endif
                $(document).on('change','.driver_id',function () {
                    var city = $('.driver_id option:selected').val();
                    if (city > 0){
                        $.ajax({
                            url:'{!! route('company.trip.create') !!}',
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
                            url:'{!! route('company.trip.create') !!}',
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
                });
                 
                function confirmDelete() {
                    return confirm('Are you sure you want to delete?');
                }

            }); 
        </script>


    @endpush
    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data trip')</h3>
                </div>
                <div class="box-footer">

                {!! Form::open(['method' => 'DELETE', 'route' => ['company.trip.destroy_by_calendar',$trip->id  ], 'onsubmit' => "return confirm('Are you sure you want to delete?');"]) !!}
                    <button type="submit" name="button" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i> @lang('admin.Delete')
                    </button>
                {!! Form::close() !!}
                </div>
                {!! Form::open(['url'=>route('company.trip.update_by_calendar'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $trip->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" autofocus value="{{$trip->translate($locale)->name}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>
                            @endforeach

                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Number passengers')</label>
                                    <input type="text" class="form-control"  value="{{$trip->number_passenger}}"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           name="number_passenger" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('admin.Flight start time')</label>

                                            <div class="input-group">
                                                <input type="time" value="{!! $trip->start_time !!}"
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

                                            <div class="input-group">
                                                <input type="time" value="{!! $trip->end_time !!}"
                                                       name="end_time"
                                                       class="form-control">

                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('admin.Flight start date')</label>

                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="date" class="form-control"
                                                       name="start_date" value="{!! $trip->start_date !!}">
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
                                                <input type="date" class="form-control"
                                                       name="end_date" value="{!! $trip->end_date !!}">
                                            </div>
                                            <!-- /.input group -->
                                        </div>
                                    </div>


                                </div>


                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Assigned'),2=>trans('admin.Underway'),
                                   3=>trans('admin.hanging'),4=>trans('admin.Canceled'),
                                  // 5=>trans('admin.Closed final'),
                                   6=>trans('admin.Partially closed'),
                                   7=>trans('admin.Scheduled'),
                                   10=>trans('admin.Instant'),

                                   ],
                                   $trip->status,['class'=>'form-control select2','style'=>'width: 100%;',
                                   'placeholder'=>trans('admin.Select Status')]) !!}

                                </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Guide name')</label>
                                <select class="form-control select2" style="width: 100%;" name="guide_id">
                                    <option>@lang('admin.Select guide name')</option>
                                    @foreach($guide as $key => $value)
                                            <option  value="{{$value->id}}" {!! $value->id == $trip->guide_id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Supervisor name')</label>
                                <select class="form-control select2" style="width: 100%;" name="supervisor_id">
                                    <option>@lang('admin.Select supervisor name')</option>
                                    @foreach($supervisor as $key => $value)
                                        <option  value="{{$value->id}}" {!! $trip->supervisor_id == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Driver name')</label>
                                <select class="form-control select2 driver_id" style="width: 100%;" name="driver_id">
                                    <option>@lang('admin.Select driver name')</option>
                                    @foreach($driver as $key => $value)
                                        <option  value="{{$value->id}}" {!! $trip->driver_id == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
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
                                        <option  value="{{$value->id}}" {!! $trip->path_id == $value->id ? 'selected' : '' !!} >
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
                {{--  edit trip --}}
                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>
                {{-- delete trip  --}}
                

                {!! Form::close() !!}

                

            </div>
            <!-- /.box -->



        </div>

    </section>
   
@endsection


