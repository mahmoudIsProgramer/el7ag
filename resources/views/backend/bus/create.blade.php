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
                <li ><a href="{{route('company.bus.index')}}"><i class="fa fa-university"></i> @lang('admin.View Drivers')</a></li>

                @elseif(Auth::guard('user_vendor')->user()->hasPermission('read_buses'))
                    <li ><a href="{{route('company.bus.index')}}"><i class="fa fa-university"></i> @lang('admin.View Drivers')</a></li>

                @else
                <li ><a><i class="fa fa-university disabled"></i> @lang('admin.View Drivers')</a></li>
            @endif
            <li class="active">@lang('admin.Create Driver')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data bus')</h3>
                </div>
                {!! Form::open(['url'=>route('company.bus.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
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
                                <label for="exampleInputEmail1">@lang('admin.Number chairs')</label>
                                <input type="text" class="form-control"  value="{{old('phone')}}"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                        name="number_chairs" id="exampleInputEmail1" placeholder="@lang('admin.Number chairs')">
                            </div>

                            <div class="form-group">
                                <label  class="col-sm-2 control-label">@lang('Status')</label>
                                {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                                old('status'),['class'=>'form-control select2','style'=>'width: 100%;',
                                'placeholder'=>trans('admin.Select Status')]) !!}
                            </div>

                        </div>


                        <div class="col-md-6">
                            <div class="form-group"  >
                                <label for="exampleInputEmail1">@lang('admin.Guide name')</label>
                                <select class="form-control" style="width: 100%;" id="guide_id" name="guide_id">
                                    <option value ="">@lang('admin.Select guide name')</option>
                                    @foreach($guides as $key => $value)
                                        <option  value="{{$value->id}}" {!! old('guide_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Driver name')</label>
                                <select class="form-control select2" style="width: 100%;" name="driver_id" id="driver_id">
                                    <option>@lang('admin.Select driver name')</option>
                                    @foreach($driver as $key => $value)
                                        <option  value="{{$value->id}}" {!! old('driver_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" value="" id="driver_id">

                            

                            <div class="form-group" id="carr" >
                                <label for="exampleInputEmail1">@lang('admin.Carrier name')</label>
                                <select class="form-control select2" style="width: 100%;" id="carrier_id" name="carrier_id">
                                    <option value="">@lang('admin.Select carrier name')</option>
                                    @foreach($carrier as $key => $value)
                                        <option  value="{{$value->id}}" {!! old('carrier_id') == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="carrier_id2" style="display: none;">
                                <label for="exampleInputEmail1">@lang('admin.Carrier name')</label>
                                <select class="form-control select2" style="width: 100%;" id="carrier_i" name="carrier_id">
                                    <option value="">@lang('admin.Select carrier name')</option>

                                </select>
                            </div>



                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Number bus')</label>
                                <input type="text" class="form-control"  value="{{old('number_bus')}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="number_bus" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Plate Number')</label>
                                <input type="text" class="form-control"  value="{{old('plate_number')}}"
                                       oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                       name="plate_number" id="exampleInputEmail1" placeholder="@lang('admin.Number bus')">
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
    <script>
        $(document).ready(function(){
            $("#driver_id").change(function(){
                // alert("The text has been changed."); carrier_id2  $value->translate(App::getLocale())->name
                var getValue=$(this).val();

                $.ajax({
                    type: "get",
                    url: "{{url('vendor/getcarrier')}}"+"/"+getValue,
                    success: function(response){
                        console.log(response);
                        if(response == ""){
                            $('.carrier_id2').css('display','block');
                            $('#carr').show();

                        }else {
                            $('#carr').hide();
                            $('#carrier_id2').show();
                            var select = $('#carrier_i');
                            select.empty();
                            // $.each(response, function (index, value) {
                            //     alert(response[0]);
                                $('#carrier_i').append('<option value="' + response[0] + '" selected>' + response[1] + '</option>');

                                // alert( index + ": " + value );
                            // });
                        }
                    }
                });

            });
        });
    </script>

@endsection


