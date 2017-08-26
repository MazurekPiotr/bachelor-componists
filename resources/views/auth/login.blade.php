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
                    <div class="row">
                        <div class="input-field col s12">
                            <button type="submit" class="waves-effect waves-light btn">
                                Login
                            </button>
                            <a href="/auth/facebook" class="waves-effect waves-light btn ">
                                Login with <i class="fa fa-facebook"></i>
                            </a>
                            <p class="centered">
                                <a href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
