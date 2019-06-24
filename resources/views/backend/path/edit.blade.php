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



    @push('js')
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

    @endpush

    <section class="content">

        <div class="row">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">@lang('admin.Edit data path')</h3>
                </div>
                {!! Form::open(['url'=>route('company.path.update'),'method'=>'post','files'=>true,'role'=>'form','id'=>'formABC']) !!}

               <input name="id" value="{!! $path->id !!}" hidden>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">

                            <div class="form-group">
                                <label >@lang('admin.From Destination')</label>
                                <select class="form-control select2 destination_id" style="width: 100%;" name="from">
                                    <option>@lang('admin.Select from destination')</option>
                                    @foreach($destination as $key => $value)
                                            <option  value="{{$value->id}}" {!! $path->from == $value->id ? 'selected' : '' !!} > {{$value->translate(App::getLocale())->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">@lang('admin.Price')</label>
                                        <input type="text"
                                               oninput="this.value = this.value.replace(/[^0-9.]/g, '');
                                       this.value = this.value.replace(/(\..*)\./g, '$1');"
                                               class="form-control" value="{{$path->price}}" name="price"
                                               id="exampleInputEmail1" placeholder="@lang('admin.Price')">
                                    </div>



                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('admin.To Destination')</label>
                                <span class="state"></span>

                            </div>

                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">@lang('Status')</label>
                                    {!! Form::select('status',[1=>trans('admin.Active'),2=>trans('admin.In-Active')],
                                    $path->status,['class'=>'form-control select2','style'=>'width: 100%;' ]) !!}

                                </div>

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


