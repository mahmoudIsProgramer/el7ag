@extends('backend.auth.backend.layouts.master')

@section('title',trans('admin.Rest Password'))


@section('content')


    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">@lang('admin.reset your password')</p>

        {!! Form::open(['url'=>route('post.reset.password'),'method'=>'post']) !!}

        <div class="form-group has-feedback">
            <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="@lang('admin.E-mail')">
            @if($errors->has('email'))
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    @lang('admin.We need to know your e-mail address!')
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-xs-6">
                <a href="{{route('get.login')}}" class="btn btn-info btn-block btn-flat">@lang('admin.Sing in')</a>
            </div>
            <!-- /.col -->
            <div class="col-xs-6">
                <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('admin.Reset')</button>
            </div>
            <!-- /.col -->
        </div>

        {!! Form::close() !!}



    </div>
    <!-- /.login-box-body -->


@endsection

