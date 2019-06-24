@extends('backend.layouts.master')

@section('title',trans('admin.Destination'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Destination')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.destination.index')}}"><i class="fa fa-user-o"></i> @lang('admin.View Destination')</a></li>

                @elseif(Auth::guard('user_vendor')->user()->hasPermission('read_destinations'))
                    <li ><a href="{{route('company.destination.index')}}"><i class="fa fa-user-o"></i> @lang('admin.View Destination')</a></li>

                @else
                <li ><a><i class="fa fa-user-o disabled"></i> @lang('admin.View Destination')</a></li>
            @endif
            <li class="active">@lang('admin.Create Guide')</li>
        </ol>
    </section>
@endsection()

@section('content')

    @push('css')
        <style>
            #map{
                width: 100% !important;
                height: 500px !important;
            }
        </style>
    @endpush

    @push('js')

        <script>
            // Initialize the map.

            <?php
            $lat = !empty(old('lat')) ? old('lat') : 30.05806302883548;
            $lng = !empty(old('lng')) ? old('lng') : 31.20761839389786;
            ?>
            function initMap() {
                var map = new google.maps.Map(document.getElementById('map'), {
                    zoom: 15,
                    center: {
                        lat: {!! $lat !!},
                        lng: {!! $lng !!}
                    }
                });
                var markerOne = new google.maps.Marker({
                    position: {
                        lat: {!! $lat !!},
                        lng: {!! $lng !!}
                    },
                    map: map,
                    draggable: true
                });


                var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));

                google.maps.event.addListener(searchBox, 'places_changed', function () {
                    var places = searchBox.getPlaces();
                    var boundsOne = new google.maps.LatLngBounds();
                    var i, place;

                    for (i = 0; place = places[i]; i++) {
                        boundsOne.extend(place.geometry.location);
                        marker.setPosition(place.geometry.location);
                    }
                    map.fitBounds(boundsOne);
                    map.setZoom(15);
                });

                google.maps.event.addListener(markerOne, 'position_changed', function () {

                    var lat = markerOne.getPosition().lat();
                    var lng = markerOne.getPosition().lng();
                    $('#lat').val(lat);
                    $('#lng').val(lng);
                });


            }

        </script>
        <script async defer
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl4ojvZ2izKtlCvqX14FQCWAzCBmq4Wgk&callback=initMap&libraries=places">
        </script>

        <script type="text/javascript"    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAl4ojvZ2izKtlCvqX14FQCWAzCBmq4Wgk&callback=initMap&libraries=places"></script>
        <script type="text/javascript" src='{!! asset('backend/dist/js/locationpicker.jquery.js') !!}'></script>

    @endpush

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Create data destination')</h3>
                </div>
                {!! Form::open(['url'=>route('company.destination.store'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">

                            @foreach(config('translatable.locales') as $locale)
                                <div class="form-group">
                                    <label for="exampleInputEmail1">@lang('admin.'.$locale.'.nameDes')</label>
                                    <input type="text" class="form-control" autofocus value="{{old($locale.'.name')}}" name="{{$locale}}[name]"
                                           id="exampleInputEmail1" placeholder="@lang('admin.'.$locale.'.nameDes')">
                                </div>

                            @endforeach

                        </div>


                       <div class="col-lg-6 col-lg-offset-3">

                           <div class="form-group">
                               <label  class="col-sm-2 control-label">@lang('Status')</label>
                               {!! Form::select('status',[1=>trans('admin.Active'),0=>trans('admin.In-Active')],
                               old('status'),['class'=>'form-control select2','style'=>'width: 100%;' ]) !!}

                           </div>

                           <div class="form-group">
                               <label >@lang('admin.Search Map')</label>
                               <input type="text" class="form-control" id="searchmap">
                           </div>

                           <div class="form-group">
                               @if($errors->has('start_lat') || $errors->has('start_lng') || $errors->has('end_lat') || $errors->has('end_lng') )
                                   <div class="alert alert-warning alert-dismissible">
                                       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                       <h4><i class="icon fa fa-warning"></i> @lang('admin.Warning') !</h4>
                                       @lang('admin.Please move mark in maps')
                                   </div>
                               @endif
                               <div id="map"></div>
                           </div>
                       </div>

                        <div class="row">

                            <div class="col-md-6">

                                <input type="hidden"  class="form-control" id="lat"
                                        name="lat" value="{!! $lat !!}">



                                <input type="hidden"  class="form-control" id="lng"
                                       name="lng" value="{!! $lng !!}" >


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


