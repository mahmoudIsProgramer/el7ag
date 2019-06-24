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
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.trip.index')}}"><i class="fa fa-plane"></i> @lang('admin.View Trips')</a></li>

                @elseif(Auth::guard('user_vendor')->user()->hasPermission('read_trips'))
                    <li ><a href="{{route('company.trip.index')}}"><i class="fa fa-plane"></i> @lang('admin.View Trips')</a></li>

                @else
                <li ><a><i class="fa fa-plane disabled"></i> @lang('admin.View Trips')</a></li>
            @endif
            <li class="active">@lang('admin.Create Trips')</li>
        </ol>
    </section>
@endsection()

@section('content')

    @push('js')

        <script>

            $("#datetimepicker5").calendarsPicker({
                calendar: $.calendars.instance('islamic'),
                // format: "dd MM yyyy - hh:ii",
                    onSelect: function (date) {
                        alert('You picked ' + date[0].formatDate());
                    }

            });
            $("#end_date").calendarsPicker({
                calendar: $.calendars.instance('islamic'),
                // format: "dd MM yyyy - hh:ii",
                    onSelect: function (date) {
                        alert('You picked ' + date[0].formatDate());
                    }

            });
          $("#start_date").calendarsPicker({
                        calendar: $.calendars.instance('islamic'),
                        // format: "dd MM yyyy - hh:ii",
                            onSelect: function (date) {
                                alert('You picked ' + date[0].formatDate());
                            }

                    });

        </script>

        <script type="text/javascript">
            $(document).ready(function () {
                @if(old('driver_id'))
                $.ajax({
                    url:'{!! route('company.trip.create') !!}',
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
                    url:'{!! route('company.trip.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{path_id:'{!! old('path_id') !!}',select:'{!! old('price') !!}'},
                    success: function (data) {
                        $('.statePrice').html(data)
                    }
                });
                @endif

                $(document).on('change','.bus_id',function () {

                    var bus_id = $('.bus_id option:selected').val();
                    var token = $("input[name='_token']").val();
                    $('.price').html('') ;

                    if ( bus_id > 0 ){

                        // drivers
                        $.ajax({

                            url:"{!! route('company.trip.create') !!}",
                            type:'get',
                            dataType:'',
                            data:{ bus_id:bus_id,get_users:'drivers',select:''  },
                            success: function (data) {

                                $('.drivers').html(data) ;
                                console.log('drivers');
                            }
                        });

                        // guides
                        $.ajax({

                            url:"{!! route('company.trip.create') !!}",
                            type:'get',
                            dataType:'',
                            data:{ bus_id:bus_id,get_users:'guides',select:''  },
                            success: function (data) {
                                $('.guides').html(data) ;
                                console.log('guides');
                            }
                        });

                        // carriers
                        $.ajax({

                            url:"{!! route('company.trip.create') !!}",
                            type:'get',
                            dataType:'',
                            // data:{ driver_id:$('.driver_id option:selected').val() ,get_users:'carriers',select:''  },
                            data:{ bus_id:bus_id ,get_users:'carriers',select:''  },
                            success: function (data) {
                                console.log('carriers');
                                $('.carriers').html(data) ;
                            }

                        });

                        // supervisors
                        $.ajax({

                            url:"{!! route('company.trip.create') !!}",
                            type:'get',
                            dataType:'',
                            // data:{  guide_id:$('.guide_id option:selected').val() , get_users:'supervisors',select:''  },
                            data:{  bus_id:bus_id , get_users:'supervisors',select:''  },
                            success: function (data) {
                                console.log('supervisors');
                                $('.supervisors').html(data) ;
                            }
                        });

                        // paths
                        $.ajax({
                            url:"{!! route('company.trip.create') !!}",
                            type:'get',
                            dataType:'',
                            data:{  bus_id:bus_id , get_users:'paths',select:''  },
                            success: function (data) {
                                console.log('paths');
                                $('.paths').html(data) ;
                            }
                        });

                    }
                    else{

                    }
                });




                $(document).on('change','.path_id',function () {
                    var path_id = $('.path_id option:selected').val();
                    var carrier_id = $('.carrier_id option:selected').val();
                    if ( typeof path_id !== 'undefined'    &&  typeof carrier_id !== 'undefined'    ){
                        $.ajax({
                            url:'{!! route('company.trip.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{path_id:path_id,carrier_id:carrier_id,get_users:'price' ,select:''},
                            success: function (data) {
                                $('.price').html(data)
                            }
                        });
                    }
                    else{

                        $('.price').html('')
                    }
                });

                //  change driver and get the carrier beloing to it
                $(document).on('change','.driver_id',function () {
                    var driver_id = $('.driver_id option:selected').val();
                    if ( typeof driver_id !== 'undefined'      ){
                        $.ajax({
                            url:'{!! route('company.trip.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{driver_id:driver_id,get_users:'carrier' ,select:''},
                            success: function (data) {
                                $('.carriers').html(data)
                            }
                        });
                    }
                    else{

                        $('.carriers').html('')
                        // $('.price').html('')
                    }
                });

                //  change guides and get the suprvisor  beloing to it
                $(document).on('change','.guide_id',function () {
                    var guide_id = $('.guide_id option:selected').val();
                    if ( typeof guide_id !== 'undefined'      ){

                        $.ajax({
                            url:'{!! route('company.trip.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{guide_id:guide_id,get_users:'supervisor' ,select:''},
                            success: function (data) {
                                $('.supervisors').html(data)
                                console.log('guied changes') ;
                            }
                        });
                    }
                    else{

                        $('.supervisors').html('')
                    }
                });


            })
        </script>


        @endpush
    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data trip')</h3>
                </div>
                {!! Form::open(['url'=>route('company.trip.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.companyName')</label>
                                    <input type="text" class="form-control" required  autofocus value="{{old($locale.'.name')}}" name="{{$locale}}[name]" id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.companyName')">
                                </div>

                            @endforeach


                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.Number passengers')</label>
                                    <input type="text" class="form-control"  value="{{old('number_passenger')}}"
                                           oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                           name="number_passenger" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')" required  autofocus>
                                </div>




                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>@lang('admin.Flight start date')</label>

                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type = "text" name = "start_date" id="start_date"
                                                      value="{!! old('start_date') !!}" class="form-control" required  autofocus><br>

                                                {{-- <input type="date" class="form-control"
                                                       name="start_date" value="{!! old('start_date') !!}"> --}}
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
                                                <input type = "text" name = "end_date" id="end_date"
                                                     value="{!! old('end_date') !!}" class="form-control" required  autofocus ><br>

                                                {{-- <input type="date" class="form-control"
                                                       name="end_date" value="{!! old('end_date') !!}"> --}}
                                            </div>
                                        </div>
                                    </div>
{{--                                    <div class='col-sm-6'>--}}
{{--                                        <div class="form-group">--}}
{{--                                            <div class='input-group date' >--}}
{{--                                                <input type='text' class="form-control" id='datetimepicker5' />--}}
{{--                                                <span class="input-group-addon">--}}
{{--                                                    <span class="glyphicon glyphicon-calendar"></span>--}}
{{--                                                </span>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


                                </div>
                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Assigned'),

                                   7=>trans('admin.Scheduled'),
                                   10=>trans('admin.Instant'),
                                   ],
                                   old('status'),['required' => true,'class'=>'form-control select2','style'=>'width: 100%;',
                                   'placeholder'=>trans('admin.Select Status')]) !!}

                                </div>

                        </div>


                        <div class="col-md-6">




                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Bus name')</label>
                                <select class="form-control select2 bus_id" style="width: 100%;" name="bus_id" required  autofocus >
                                    <option>@lang('admin.Select bus name')</option>
                                    @foreach($bus as $key => $value)
                                            <option  value="{{$value->id}}" {!! old('bus_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">  @lang('admin.Guide name')  </label>
                                <span class="guides"></span>

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1"> @lang('admin.Supervisor name') </label>
                                <span class="supervisors"></span>

                            </div>



                            <div class="form-group">
                                <label for="exampleInputEmail1"> @lang('admin.Driver name') </label>
                                <span class="drivers"></span>

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">  @lang('admin.Carrier name') </label>
                                <span class="carriers"></span>

                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">  @lang('admin.Path')  </label>
                                <span class="paths"></span>

                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Price')  </label>
                                <span class="price"></span>

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


