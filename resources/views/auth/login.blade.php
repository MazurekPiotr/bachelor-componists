@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 form-box">
            <div class="form-bottom">
                <form class="login-form" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" >E-Mail Address</label>
                        <input autofocus="autofocus" id="email" type="email" class="form-control form-username" name="email" value="{{ old('email') }}" required >

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" >Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            Login
                        </button>

                        <a  href="{{ url('/password/reset') }}">
                            Forgot Your Password?
                        </a>
                    </div>
                </form>
            </div>
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 social-login">
                    <h3>...or login with:</h3>
                    <div class="social-login-buttons">
                        <a class="btn btn-link-2" href="/auth/facebook">
                            <i class="fa fa-facebook"></i> Facebook
                        </a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
