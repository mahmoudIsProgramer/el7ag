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
            <li class="active">@lang('admin.View Admin')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('admin.View Company')</h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                    @if(Auth::guard('admin')->user()->hasPermission('create_company'))
                        <a href="{{route('admin.company.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Company')</a>
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
                    @if(Auth::guard('admin')->user()->hasPermission('read_company'))
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.IMG')</th>
                                    <th>@lang('admin.Name Company')</th>
                                    <th>@lang('admin.E-mail')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Action')</th>
                                </tr>
                                </thead>
                              <tbody>
                              @foreach($company as $key => $value)
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
                                      @if(Auth::guard('admin')->user()->hasPermission('update_company'))

                                          <a class="active"  data-toggle="modal" href="{{route('admin.company.edit',$value->id)}}">
                                              <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                          </a>

                                      @else
                                          <a class="active"  data-toggle="modal">
                                              <button class="btn btn-primary btn-xs disabled"><i class="fa fa-edit "></i></button>
                                          </a>
                                      @endif

                                      @if(Auth::guard('admin')->user()->hasPermission('delete_company'))

                                          <a class="active"  data-toggle="modal" href="#status-company{{ $value->id }}">
                                              <button class="btn btn-warning btn-xs"><i class="fa fa-bar-chart "></i></button>
                                          </a>

                                          <div class="modal fade" id="status-company{{ $value->id }}" tabindex="-1"
                                               role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-body">

                                                      </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to change status') {{$value->name}}</p>
                                                      <div class="modal-footer">
                                                          <button data-dismiss="modal" class="btn btn-danger left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                          <a href="{{ route('admin.company.destroy',$value->id) }}">
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
                                    <th>@lang('admin.IMG')</th>
                                    <th>@lang('admin.Name Company')</th>
                                    <th>@lang('admin.E-mail')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Action')</th>
                                </tr>
                                </tfoot>
                            </table>
                    @endif

                </div>
            </div>

        </div>
        <!-- /.col -->
    </div>


@endsection


