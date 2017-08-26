@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <div id="login">
        <div class="row">
            <div class="login-form col l4 m6 s12 offset-l4 offset-m3">
                <h1 class="white-text">Reset password</h1>
                @if (session('status'))
                    <div style="color: #ffffff;">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ url('/password/email') }}" class="col s12">
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
                    <button type="submit" class="btn btn-primary">
                        Send Reset Link
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
