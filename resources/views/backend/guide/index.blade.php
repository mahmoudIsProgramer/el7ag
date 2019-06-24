@extends('backend.layouts.master')

@section('title',trans('admin.Guides'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Guides')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('company.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            <li class="active">@lang('admin.View Guides')</li>
        </ol>
    </section>
@endsection()

@section('content')


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">@lang('admin.View Guides')</h3>
            <div class="box-tools pull-right">
                <div class="form-group">
                    @if(Auth::guard('company')->user())
                        <a href="{{route('company.guide.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Guide')</a>
                        @elseif(Auth::guard('user_vendor')->user()->hasPermission('red_guides'))
                        <a href="{{route('company.guide.create')}}" class="btn btn-primary"><i class="fa fa-plus-circle"> </i> @lang('admin.Create Guide')</a>
                    @else
                        <a  class="btn btn-primary disabled"><i class="fa fa-plus-circle"> </i> @lang('admin.Add Guide')</a>
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
                                    <th>@lang('admin.IMG')</th>
                                    <th>@lang('admin.SSN')</th>
                                    <th>@lang('admin.number')</th>
                                    <th>@lang('admin.Name')</th>
                                    <th>@lang('admin.E-mail')</th>
                                    <th>@lang('admin.Age')</th>
                                    <th>@lang('admin.Nationality')</th>
                                    <th>@lang('admin.Status')</th>
                                    <th>@lang('admin.Action')</th>
                                </tr>
                                </thead>
                              <tbody>
                              @foreach($guide as $key => $value)
                              <tr>
                                  <td>{!! $key+1 !!}</td>
                                  <td><img src="{{$value->imagePath}}" style="width: 75px;" class="img-circle" alt="User Image"></td>
                                  <td>{!! $value->ssn !!}</td>
                                  <td>{!! $value->number !!}</td>
                                  <td>{!! $value->name !!}</td>
                                  <td>{!! $value->email !!}</td>
                                  <td>{!! getAge($value->birthday) !!}</td>
                                  <td>{!! $value->nationality !!}</td>
                                  @if($value->status == 1)
                                      <td><span class="label label-success">@lang('admin.Active')</span></td>
                                  @else
                                      <td><span class="label label-warning">@lang('admin.In-Active')</span></td>
                                  @endif
                                  <td>
                                      @if(Auth::guard('company')->user())

                                          <a class="active"  data-toggle="modal" href="{{route('company.guide.edit',$value->id)}}">
                                              <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                          </a>
                                          @elseif(Auth::guard('user_vendor')->user()->hasPermission('update_guides'))
                                          <a class="active"  data-toggle="modal" href="{{route('company.guide.edit',$value->id)}}">
                                              <button class="btn btn-primary btn-xs"><i class="fa fa-edit "></i></button>
                                          </a>
                                      @else
                                          <a class="active"  data-toggle="modal">
                                              <button class="btn btn-primary btn-xs disabled"><i class="fa fa-edit "></i></button>
                                          </a>
                                      @endif

                                          @if(Auth::guard('company')->user())
                                              <a class="active"  data-toggle="modal" href="#status-guide{{ $value->id }}">
                                                  <button class="btn btn-warning btn-xs"><i class="fa fa-bar-chart "></i></button>
                                              </a>

                                              <div class="modal fade" id="status-guide{{ $value->id }}" tabindex="-1"
                                                   role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog">
                                                      <div class="modal-content">
                                                          <div class="modal-body">

                                                          </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to change status') {{$value->name}}</p>
                                                          <div class="modal-footer">
                                                              <button data-dismiss="modal" class="btn btn-danger left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                              <a href="{{ route('company.guide.status',$value->id) }}">
                                                                  <button class="btn btn-success"><i class="fa fa-edit"></i> {{ trans('admin.Edit') }}</button>
                                                              </a>
                                                          </div>
                                                      </div>
                                                  </div>
                                              </div>

                                      @elseif(Auth::guard('user_vendor')->user()->hasPermission('delete_guide'))

                                          <a class="active"  data-toggle="modal" href="#status-guide{{ $value->id }}">
                                              <button class="btn btn-warning btn-xs"><i class="fa fa-bar-chart "></i></button>
                                          </a>

                                          <div class="modal fade" id="status-guide{{ $value->id }}" tabindex="-1"
                                               role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                              <div class="modal-dialog">
                                                  <div class="modal-content">
                                                      <div class="modal-body">

                                                      </div><p style='margin:auto;width:80%'>  @lang('admin.Are you sure you want to change status') {{$value->name}}</p>
                                                      <div class="modal-footer">
                                                          <button data-dismiss="modal" class="btn btn-danger left" type="button"><i class="fa fa-close"></i> @lang('admin.Close')</button>
                                                          <a href="{{ route('company.guide.status',$value->id) }}">
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

                                        {{-- start view  user  --}}
                                        @if(Auth::guard('company')->user()   || Auth::guard('user_vendor')->user()->hasPermission('read_guides') )
                                    
                                            <a href="" data-toggle="modal" data-target="#modal-trips{{$value->id}}">
                                                <button class="btn btn-success btn-xs"><i class="fa fa-eye "></i></button>
                                            </a>

                                            <div class="modal fade" id="modal-trips{{$value->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">@lang('admin.Info Supervisor') : {{$value->name}}</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="box box-primary">
                                                                <div class="box-body box-profile">

                                                                    <div class = 'text-center'>
                                                                        <img src="{{$value->imagePath}}" style="width: 75px;" class="img-circle" alt="User Image">
                                                                    </div>

                                                                    {{-- <img src="{{$value->imagePath}}" class="rounded mx-auto d-block" alt="..."> --}}

                                                                    <h3 class="profile-username text-center"> {!! \App\Guide::find($value->id)->translate(App::getLocale())->name !!}</h3>

                                                                    <p class="text-muted text-center">@lang('admin.Create since') {{date('M-Y',strtotime($value->created_at))}}</p>

                                                                    <ul class="list-group list-group-unbordered">
                                                                        
                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Name Company')</b>
                                                                            <a class="pull-right">
                                                                                {!! \App\Company::find($value->company_id)->translate(App::getLocale())->name !!}
                                                                            </a>
                                                                        </li>

                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.User Vendor')</b>
                                                                            <a class="pull-right">
                                                                                @if( $value->userVendor != null)
                                                                                    {!! \App\UserVendor::find($value->userVendor)->translate(App::getLocale())->name !!}
                                                                                @endif
                                                                            </a>
                                                                        </li>

                                                                        @if( $value->user_vendor)
                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.User Vendor')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->user_vendor !!}
                                                                            </a>
                                                                        </li>
                                                                        @endif 

                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.E-mail')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->email !!}
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Number')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->number !!}
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Phone')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->phone !!}
                                                                            </a>
                                                                        </li>
                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Mobile')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->mobile !!}
                                                                            </a>
                                                                        </li>

                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Address')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->address !!}
                                                                            </a>
                                                                        </li>

                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.SSN')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->ssn !!}
                                                                            </a>
                                                                        </li>


                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Nationality')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->nationality !!}
                                                                            </a>
                                                                        </li>

                                                                        <li class="list-group-item">
                                                                            <b><i class="fa fa-bus margin-r-5" aria-hidden="true"></i>@lang('admin.Birthday')</b>
                                                                            <a class="pull-right">
                                                                                {!! $value->birthday !!}
                                                                            </a>
                                                                        </li>

                                                                        @php 
                                                                            $attachments =  \DB::table('attachments')->where('type','guide')->where('user_id',$value->id)->pluck('file_name')->toArray();
                                                                        @endphp
                                                                        <div class="container">
                                                                            @foreach( $attachments   as $file)
                                                                                <div class="row">
                                                                                    <div class='col-md-12 m-auto b-3 '>
                                                                                        <img class="img-responsive" alt="" src="{{ image_path('guide', $file) }}" />
                                                                                    </div> 
                                                                                </div>
                                                                            @endforeach
                                                                        </div>


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
                                        
                                        @else
                                            <a class="active"  data-toggle="modal">
                                                <button class="btn btn-warning btn-xs disabled"><i class="fa fa-eye "></i></button>
                                            </a>
                                        @endif
                                        {{-- end view user  --}}

                                        {{-- start delete  --}}
                                        @if(Auth::guard('company')->user()   || Auth::guard('user_vendor')->user()->hasPermission('delete_guides') )
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
                                                            <a href="{{ route('company.guide.destroy',$value->id) }}">
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
                                        {{-- end delete  --}}


                                  </td>
                              </tr>
                              @endforeach
                              </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('admin.IMG')</th>
                                    <th>@lang('admin.SSN')</th>
                                    <th>@lang('admin.number')</th>
                                    <th>@lang('admin.Name')</th>
                                    <th>@lang('admin.E-mail')</th>
                                    <th>@lang('admin.Age')</th>
                                    <th>@lang('admin.Nationality')</th>
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


