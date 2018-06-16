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
                        <div class="col s12">
                            By clicking "Register" you accept our <a href="/privacy-policy">Privacy policy</a> and our <a href="/terms-of-use">Terms of use</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <div>
                                <button type="submit" class="waves-effect waves-light btn">Register</button>
                            </div>
                            <div>
                                <a href="/auth/facebook" class="waves-effect waves-light btn" id="fb-btn">Login with <i class="fa fa-facebook-official" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
