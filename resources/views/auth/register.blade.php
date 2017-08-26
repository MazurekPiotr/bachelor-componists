@extends('layouts.app')

@section('content')
    <div id="register">
        <div class="row">
            <div class="login-form col l4 m6 s12 offset-l4 offset-m3">
                <h1 class="white-text">Register</h1>
                <form method="POST" action="{{ url('/register') }}" role="form" class="col s12">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="name" type="text" name="name" value="{{ old('name') }}" class="validate">
                            <label for="name">Your name</label>
                        </div>
                        @if ($errors->has('name'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="validate">
                            <label for="email">Your email</label>
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="password" name="password" class="validate">
                            <label for="password">Your password</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password-confirm" type="password" name="password_confirmation" class="validate">
                            <label for="password-confirm">Confirm password</label>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <button type="submit" class="waves-effect waves-light btn">
                                Register
                            </button>
                            or
                                <button class="waves-effect waves-light btn" href="/auth/facebook">
                                    Login with <i class="fa fa-facebook"></i>
                                </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <div class="col-sm-6 col-sm-offset-3 form-box">
            <div class="form-bottom">
                <form class="login-form" role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" >Username</label>

                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email">E-Mail Address</label>

                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password">Password</label>

                            <input id="password" type="password" class="form-control" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" >Confirm Password</label>

                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <input type="hidden" name="code" value="{{ (Request::get('code') ? Request::get('code') : '') }}">

                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary btn-block">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
