@extends('backend.layouts.master')

@section('title',trans('admin.Companies'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Companies')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('company.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View User Vendor')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('admin.View Company')</h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                        <a href="{{route('company.userVendor.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create User Vendor')</a>
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
                                    <th>@lang('admin.IMG')</th>
                                    <th>@lang('admin.Name')</th>
                                    <th>@lang('admin.E-mail')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Action')</th>
                                </tr>
                                </thead>
                              <tbody>
                              @foreach($userVendor as $key => $value)
                              <tr>
                                  <td>{!! $key+1 !!}</td>
                                  <td><img src="{{$value->imagePath}}" style="width: 75px;" class="img-circle" alt="User Image"></td>
                                  <td>{!! $value->name !!}</td>
                                  <td>{!! $value->email !!}</td>
                                  @if($value->status == 1)
                                      <td><span class="label label-success">@lang('admin.Active')</span></td>
                                  @else
                                      <td><span class="label label-warning">@lang('admin.In-Active')</span></td>
                                  @endif
                                  <td>

                                          <a class="active"  data-toggle="modal" href="{{route('company.userVendor.edit',$value->id)}}">
                                              <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                          </a>



                                          <a class="active"  data-toggle="modal" href="#status-vendorUser{{ $value->id }}">
                                              <button class="btn btn-warning btn-xs"><i class="fa fa-bar-chart "></i></button>
                                          </a>

                                          <div class="modal fade" id="status-vendorUser{{ $value->id }}" tabindex="-1"
                                               role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-body">

                                                      </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to change status') {{$value->name}}</p>
                                                      <div class="modal-footer">
                                                          <button data-dismiss="modal" class="btn btn-danger left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                          <a href="{{ route('company.userVendor.status',$value->id) }}">
                                                              <button class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('admin.Edit') }}</button>
                                                          </a>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>

                                      <a class="active"  data-toggle="modal" href="#delete-vendorUser{{ $value->id }}">
                                          <button class="btn btn-danger btn-xs"><i class="fa fa-trash "></i></button>
                                      </a>

                                      <div class="modal fade" id="delete-vendorUser{{ $value->id }}" tabindex="-1"
                                           role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                          <div class="modal-dialog">
                                              <div class="modal-content">
                                                  <div class="modal-body">

                                                  </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to Delete is users') {{$value->name}}</p>
                                                  <div class="modal-footer">
                                                      <button data-dismiss="modal" class="btn btn-primary left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                      <a href="{{ route('company.userVendor.destroy',$value->id) }}">
                                                          <button class="btn btn-danger"><i class="fa fa-trash"></i> {{ trans('admin.Delete') }}</button>
                                                      </a>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>


                                  </td>

                              </tr>
                              @endforeach
                              </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.IMG')</th>
                                    <th>@lang('admin.Name')</th>
                                    <th>@lang('admin.E-mail')</th>
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


