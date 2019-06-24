@extends('backend.layouts.master')

@section('title',trans('admin.Path'))

@section('content-wrapper')
    <section class="content-header">
        <h1>
            @lang('admin.Path')
            <small>@lang('admin.Control panel')</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.home')}}"><i class="fa fa-dashboard"></i> @lang('admin.Home')</a></li>
            @if(Auth::guard('company')->user())
                <li ><a href="{{route('company.path.index')}}"><i class="fa fa-user-o"></i> @lang('admin.View Path')</a></li>
                @elseif(Auth::guard('company')->user()->hasPermission('read_path'))
                    <li ><a href="{{route('company.path.index')}}"><i class="fa fa-user-o"></i> @lang('admin.View Path')</a></li>

                @else
                <li ><a><i class="fa fa-user-o disabled"></i> @lang('admin.View Path')</a></li>
            @endif
            <li class="active">@lang('admin.Edit path')</li>
        </ol>
    </section>
@endsection()

@section('content')



    {{-- @push('js')
        <script type="text/javascript">
            $(document).ready(function () {
                @if($path->from)
                $.ajax({
                    url:'{!! route('company.path.create') !!}',
                    type:'get',
                    dataType:'html',
                    data:{destination_id:'{!! $path->from !!}',select:'{!! $path->to !!}'},
                    success: function (data) {
                        $('.state').html(data)
                    }
                });
                @endif
                $(document).on('change','.destination_id',function () {
                    var city = $('.destination_id option:selected').val();
                    if (city > 0){
                        $.ajax({
                            url:'{!! route('company.path.create') !!}',
                            type:'get',
                            dataType:'html',
                            data:{destination_id:city,select:''},
                            success: function (data) {
                                $('.state').html(data)
                            }
                        });
                    }
                    else{
                        $('.state').html('')
                    }
                })
            })
        </script>

    @endpush --}}

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data path')</h3>
                </div>
                {!! Form::open(['url'=> route('company.carrier.price.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

                <input name="path_id" value="{!! $path->id !!}" hidden>
                <input name="carrier_id" value="{{ $carrier_id  }}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label >@lang('admin.From Destination')</label>
                                <p>  {{$from}} </p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">@lang('admin.Price')</label>
                                <input type="text"
                                    class="form-control" value="{{$price}}" name="price"
                                    id="exampleInputEmail1" placeholder="@lang('admin.Price')"  required autofocuse >
                            </div>



                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('admin.To Destination')</label>
                                <p>  {{$to}} </p>

                            </div>

                                {{-- <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    <p>
                                        @if( $path->status == 0 )
                                            {{trans('admin.Active')}}
                                        @else 
                                            {{trans('admin.In-Active')}}
                                        @endif
                                    </p>
                                </div> --}}

                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-edit"></i> @lang('admin.Edit')</button>
                </div>

                {!! Form::close() !!}
            </div>
            <!-- /.box -->



        </div>

    </section>

@endsection


