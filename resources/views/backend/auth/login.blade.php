@extends('backend.auth.backend.layouts.master')

@section('title',trans('admin.Sign In'))


@section('content')

    <div class="login-box-body">
        <p class="login-box-msg">@lang('admin.Sign in to start your session')</p>

        {!! Form::open(['route'=>'post.login','method'=>'post','id'=>'formLock']) !!}

            <div class="form-group has-feedback">
                <input type="email" name="email" value="{!! old('email') !!}" class="form-control" placeholder="@lang('admin.E-mail')">
                @if($errors->has('email'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                       @lang('admin.We need to know your e-mail address!')
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">

                <input type="password" name="password" id="password-field"  class="form-control" placeholder="@lang('admin.Password')">
                @if($errors->has('password'))
                    <div class="alert alert-warning alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        @lang('admin.Please confirmation password !')
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input type="checkbox" name="remember" value="{!! old('remember') !!}"> @lang('admin.Remember Me')
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat " id="btnLock">@lang('admin.Sign In')</button>
                </div>
                <!-- /.col -->
            </div>
       {!! Form::close() !!}


        <!-- /.social-auth-links -->

        <a href="{!! route('get.reset.password') !!}">@lang('admin.I forgot my password')</a><br>


    </div>


    @endsection


