@extends('layouts.app')

@section('content')
    <div id="login">
        <div class="row">
            <div class="login-form col l4 m6 s12 offset-l4 offset-m3">
                <h1 class="white-text">Login</h1>
                <form method="POST" action="{{ url('/login') }}"class="col s12">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="email" type="email" class="validate" autofocus name="email" value="{{ old('email') }}" required>
                            <label for="email">Email</label>
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="password" type="password" name="password" class="validate" required>
                            <label for="password">Password</label>
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> Remember Me
                        </label>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <div>
                                <button type="submit" class="waves-effect waves-light btn">Login</button>
                            </div>
                            <div>
                                <a href="/auth/facebook" class="waves-effect waves-light btn" id="fb-btn">Login with <i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                            </div>
                            <a href="http://piotrmazurek.webhosting.be/password/reset" class="center-align forgot-pw"> Forgot Your Password? </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
