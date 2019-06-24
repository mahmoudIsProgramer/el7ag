@extends('backend.auth.backend.layouts.master')

@section('title',trans('admin.Reset Password'))


@section('content')

    <div class="register-box-body">
        <p class="login-box-msg">@lang('admin.Reset Password')</p>

        {!! Form::open(['url'=>route('post.reset',$data->token),'method'=>'post']) !!}

            <div class="form-group has-feedback">
                <input type="email" class="form-control" name="email" value="{{$data->email}}" placeholder="@lang('admin.E-mail')">
                @if($errors->has('email'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('admin.We need to know your e-mail address!')
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="password" placeholder="@lang('admin.Password')">
                @if($errors->has('password'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('admin.We need to know your password !')
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input type="password"  class="form-control" name="password_confirmation" placeholder="@lang('admin.Retype password')">
                @if($errors->has('password_confirmation'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('admin.We need to know your password !')
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('admin.Reset')</button>
                </div>
                <!-- /.col -->
            </div>
        {!! Form::close() !!}
    </div>


@endsection

