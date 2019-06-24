@extends('backend.layouts.master')

@section('title',trans('admin.Buses'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Drivers')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('company.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Buses')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('admin.View Buses')</h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                    @if(Auth::guard('company')->user())
                        <a href="{{route('company.bus.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Bus')</a>
                        @elseif(Auth::guard('user_vendor')->user()->hasPermission('red_buses'))
                        <a href="{{route('company.bus.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Bus')</a>
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
                                    <th>@lang('admin.Number bus')</th>
                                    <th>@lang('admin.Plate Number')</th>
                                    <th>@lang('admin.Number chairs')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Name Company')</th>
                                    <th>@lang('admin.UserVendor name')</th>
                                    <th>@lang('admin.Carrier name')</th>
                                    <th>@lang('admin.Driver name')</th>
                                    <th>@lang('admin.Action')</th>
                                </tr>
                                </thead>
                              <tbody>
                              @foreach($bus as $key => $value)
                              <tr>
                                  <td>{!! $key+1 !!}</td>
                                  <td>{!! $value->name !!}</td>
                                  <td>{!! $value->number_bus !!}</td>
                                  <td>{!! $value->plate_number !!}</td>
                                  <td>{!! $value->number_chairs !!}</td>
                                  @if($value->status == 1)
                                      <td><span class="label label-success">@lang('admin.Active')</span></td>
                                  @else
                                      <td><span class="label label-warning">@lang('admin.In-Active')</span></td>
                                  @endif
                                  <td>{!! \App\Company::find($value->company_id)->translate(App::getLocale())->name !!}</td>
                                  <td>
                                      @if( $value->userVendor  != null )
                                      {!! \App\UserVendor::find($value->userVendor)->translate(App::getLocale())->name !!}
                                      @endif
                                    </td>
                                  <td>{!! \App\Carrier::find($value->carrier_id)->name !!}</td>
                                  <td>{!! \App\Driver::find($value->driver_id)->name !!}</td>
                                  <td>
                                      @if(Auth::guard('company')->user())

                                          <a class="active"  data-toggle="modal" href="{{route('company.bus.edit',$value->id)}}">
                                              <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                          </a>
                                          @elseif(Auth::guard('user_vendor')->user()->hasPermission('update_buses'))
                                          <a class="active"  data-toggle="modal" href="{{route('company.bus.edit',$value->id)}}">
                                              <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                          </a>
                                      @else
                                          <a class="active"  data-toggle="modal">
                                              <button class="btn btn-primary btn-xs disabled"><i class="fa fa-edit "></i></button>
                                          </a>
                                      @endif

                                          @if(Auth::guard('company')->user())
                                              <a class="active"  data-toggle="modal" href="#status-bus{{ $value->id }}">
                                                  <button class="btn btn-warning btn-xs"><i class="fa fa-bar-chart "></i></button>
                                              </a>

                                              <div class="modal fade" id="status-bus{{ $value->id }}" tabindex="-1"
                                                   role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog">
                                                      <div class="modal-content">
                                                          <div class="modal-body">

                                                          </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to change status') {{$value->name}}</p>
                                                          <div class="modal-footer">
                                                              <button data-dismiss="modal" class="btn btn-danger left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                              <a href="{{ route('company.bus.status',$value->id) }}">
                                                                  <button class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('admin.Edit') }}</button>
                                                              </a>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                      @elseif(Auth::guard('user_vendor')->user()->hasPermission('delete_buses'))

                                          <a class="active"  data-toggle="modal" href="#status-bus{{ $value->id }}">
                                              <button class="btn btn-warning btn-xs"><i class="fa fa-bar-chart "></i></button>
                                          </a>

                                          <div class="modal fade" id="status-bus{{ $value->id }}" tabindex="-1"
                                               role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-body">

                                                      </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to change status') {{$value->name}}</p>
                                                      <div class="modal-footer">
                                                          <button data-dismiss="modal" class="btn btn-danger left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                          <a href="{{ route('company.bus.status',$value->id) }}">
                                                              <button class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('admin.Edit') }}</button>
                                                          </a>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>

                                      @else
                                          <a class="active"  data-toggle="modal">
                                              <button class="btn btn-warning btn-xs disabled"><i class="fa fa-bar-chart "></i></button>
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
                                    <th>@lang('admin.Number bus')</th>
                                    <th>@lang('admin.Plate Number')</th>
                                    <th>@lang('admin.Number chairs')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Carrier name')</th>
                                    <th>@lang('admin.Driver name')</th>
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


