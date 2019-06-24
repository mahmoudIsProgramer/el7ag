@extends('backend.layouts.master')

@section('title',trans('admin.Path'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Path')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('company.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Path')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('admin.View Path')</h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                    @if(Auth::guard('company')->user())
                        <a href="{{route('company.path.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Path')</a>
                        @elseif(Auth::guard('user_vendor')->user()->hasPermission('red_paths'))
                        <a href="{{route('company.path.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Path')</a>
                    @else
                        <a  class="btn btn-primary disabled"><i class="fa fa-plus-circle"> </i> @lang('admin.Add Path')</a>
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
                                    <th>@lang('admin.From Name')</th>
                                    <th>@lang('admin.To Name')</th>
                                    <th>@lang('admin.Price')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Action')</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($path as $key => $value)

                                    <tr>
                                        <td>{!! $key+1 !!}</td>
                                        <td>{!! \App\Destination::find($value->from)->name !!}</td>
                                        <td>{!!\App\Destination::find($value->to)->name !!}</td>
                                        <td>
                                            @php
                                                $carrierPath =  App\CarrierPath::where('carrier_id',$carrier_id)->
                                                    where('path_id', $value->id )->first(['carrier_id','path_id','price']);
                                            @endphp

                                            @if($carrierPath)
                                                {!! $carrierPath->price !!} S.R
                                            @endif

                                        </td>
                                        @if($value->status == 1)
                                            <td><span class="label label-success">@lang('admin.Active')</span></td>
                                        @else
                                            <td><span class="label label-warning">@lang('admin.In-Active')</span></td>
                                        @endif
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="{{route('company.carrier.set_price_view', [$carrier_id , $value->id] )}}" >
                                                <i class="fa fa-edit "></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.From Name')</th>
                                    <th>@lang('admin.To Name')</th>
                                    <th>@lang('admin.Price')</th>
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


