@extends('backend.layouts.master')

@section('title',trans('admin.Trips'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Trips')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('company.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Trips')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('admin.View Trips')</h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                    @if(Auth::guard('company')->user())
                        <a href="{{route('company.trip.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Trip')</a>
                        @elseif(Auth::guard('user_vendor')->user()->hasPermission('red_trips'))
                        <a href="{{route('company.trip.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Trip')</a>
                    @else
                        <a  class="btn btn-primary disabled"><i class="fa fa-plus-circle"> </i> @lang('admin.Add Bus')</a>
                    @endif
                </div>

            </div>
        </div>

        <br>

    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('admin.Name')</th>
                            <th>@lang('admin.Number Passenger')</th>
                            <th>@lang('admin.Status')</th>
                            <th>@lang('admin.Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trip as $key => $value)
                        <tr>
                            <td>{!! $key+1 !!}</td>
                            <td>{!! $value->name !!}</td>
                            <td>{!! $value->number_passenger !!}</td>
                            @if($value->status == 1)
                                <td><span class="label label-success">@lang('admin.Assigned')</span></td>
                            @elseif($value->status == 2)
                                <td><span class="label label-warning">@lang('admin.Underway')</span></td>
                            @elseif($value->status == 3)
                                <td><span class="label label-info">@lang('admin.hanging')</span></td>
                            @elseif($value->status == 4)
                                <td><span class="label label-primary">@lang('admin.hanging')</span></td>
                            @elseif($value->status == 5)
                                <td><span class="label label-danger">@lang('admin.Closed final')</span></td>
                            @elseif($value->status == 6)
                                <td><span class="label label-default">@lang('admin.Partially closed')</span></td>
                            @elseif($value->status == 7)
                                <td><span class="label label-primary">@lang('admin.Scheduled')</span></td>
                            @elseif($value->status == 10)
                                <td><span class="label label-info">@lang('admin.Instant')</span></td>
                            @endif
                            <td>
                                @if(Auth::guard('company')->user())

                                    <a class="active"  data-toggle="modal" href="{{route('company.trip.edit',$value->id)}}">
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                    </a>

                                    <a href="" data-toggle="modal" data-target="#modal-trips{{$value->id}}">
                                        <button class="btn btn-success btn-xs"><i class="fa fa-eye "></i></button>
                                    </a>

                                    <div class="modal fade" id="modal-trips{{$value->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">@lang('admin.Info Trip') : {{$value->name}}</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="box box-primary">
                                                        <div class="box-body box-profile">
                                                            <h3 class="profile-username text-center">{{$value->name}}</h3>

                                                            <p class="text-muted text-center">@lang('admin.Create since') {{date('M-Y',strtotime($value->created_at))}}</p>

                                                            <ul class="list-group list-group-unbordered">
                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-user margin-r-5" aria-hidden="true"></i>@lang('admin.Name trip')</b>
                                                                    <a class="pull-right">
                                                                        {{$value->name}}
                                                                    </a>
                                                                </li>
                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-user margin-r-5" aria-hidden="true"></i>@lang('admin.Guide name')</b>
                                                                    <a class="pull-right">
                                                                    {!! \App\Guide::find($value->guide_id)->translate(App::getLocale())->name !!}
                                                                    </a>
                                                                </li>

                                                                {{-- <li class="list-group-item">
                                                                    <b><i class="fa fa-user margin-r-5" aria-hidden="true"></i>@lang('admin.Member name')</b>
                                                                    <a class="pull-right">
                                                                        {!! \App\Member::find($value->member_id)->translate(App::getLocale())->name !!}
                                                                    </a>
                                                                </li>--}}

                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-user margin-r-5" aria-hidden="true"></i>@lang('admin.Driver name')</b>
                                                                    <a class="pull-right">
                                                                        {!! \App\Driver::find($value->driver_id)->translate(App::getLocale())->name !!}
                                                                    </a>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Bus name')</b>
                                                                    <a class="pull-right">
                                                                        {!! \App\Bus::find($value->bus_id)->translate(App::getLocale())->name !!}
                                                                    </a>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-money margin-r-5" aria-hidden="true"></i>@lang('admin.Price')</b>
                                                                    <a class="pull-right">
                                                                        {!! $value->price !!}
                                                                    </a>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-users margin-r-5" aria-hidden="true"></i>@lang('admin.Number Passenger')</b>
                                                                    <a class="pull-right">
                                                                        {!! $value->number_passenger !!}
                                                                    </a>
                                                                </li>
                                                                {{-- <li class="list-group-item">
                                                                    <b><i class="fa fa-times-circle margin-r-5" aria-hidden="true"></i>@lang('admin.Flight start time')</b>
                                                                    <a class="pull-right">
                                                                        {!! date('h:i:A',strtotime($value->start_time)) !!}
                                                                    </a>
                                                                </li> --}}

                                                                {{-- <li class="list-group-item">
                                                                    <b><i class="fa fa-times margin-r-5" aria-hidden="true"></i>@lang('admin.Flight end time')</b>
                                                                    <a class="pull-right">
                                                                        {!! date('h:i:A',strtotime($value->end_time)) !!}
                                                                    </a>
                                                                </li> --}}

                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-times-rectangle margin-r-5" aria-hidden="true"></i>@lang('admin.Flight start date')</b>
                                                                    <a class="pull-right">
                                                                        {!! date('d-m-Y H:i:s',strtotime($value->start_date)) !!}
                                                                    </a>
                                                                </li>

                                                                <li class="list-group-item">
                                                                    <b><i class="fa fa-times-circle-o margin-r-5" aria-hidden="true"></i>@lang('admin.Flight end date')</b>
                                                                    <a class="pull-right">
                                                                        {!! date('d-m-Y H:i:s',strtotime($value->end_date)) !!}
                                                                    </a>
                                                                </li>

                                                            </ul>
                                                        </div>


                                                        <!-- /.box-body -->
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">@lang('admin.Close')</button>
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                                @elseif(Auth::guard('user_vendor')->user()->hasPermission('update_trips'))
                                    <a class="active"  data-toggle="modal" href="{{route('company.trip.edit',$value->id)}}">
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                    </a>
                                @else
                                    <a class="active"  data-toggle="modal">
                                        <button class="btn btn-primary btn-xs disabled"><i class="fa fa-edit "></i></button>
                                    </a>
                                @endif

                                @if(Auth::guard('company')->user())
                                    <a class="active"  data-toggle="modal" href="#status-trip{{ $value->id }}">
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-trash "></i></button>
                                    </a>

                                    <div class="modal fade" id="status-trip{{ $value->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to delete this trip') {{$value->name}}</p>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-primary left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                    <a href="{{ route('company.trip.destroy',$value->id) }}">
                                                        <button class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('admin.Delete') }}</button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @elseif(Auth::guard('user_vendor')->user()->hasPermission('delete_trips'))

                                    <a class="active"  data-toggle="modal" href="#status-trip{{ $value->id }}">
                                        <button class="btn btn-danger btn-xs"><i class="fa fa-trash "></i></button>
                                    </a>

                                    <div class="modal fade" id="status-trip{{ $value->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">

                                                </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to delete this trip') {{$value->name}}</p>
                                                <div class="modal-footer">
                                                    <button data-dismiss="modal" class="btn btn-primary left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                    <a href="{{ route('company.trip.destroy',$value->id) }}">
                                                        <button class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('admin.Delete') }}</button>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @else
                                    <a class="active"  data-toggle="modal">
                                        <button class="btn btn-warning btn-xs disabled"><i class="fa fa-trash "></i></button>
                                    </a>
                                @endif

                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>#</th>
                            <th>@lang('admin.Name')</th>
                            <th>@lang('admin.Number Passenger')</th>
                            <th>@lang('admin.Status')</th>
                            <th>@lang('admin.Action')</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
        <!-- /.col -->
    </div>


@endsection


