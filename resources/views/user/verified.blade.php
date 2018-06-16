@extends('layouts.app')
@section('content')
<div id="login">
    <div class="row">
            <div class="login-form col l4 m6 s12 offset-l4 offset-m3">
            <div class="panel panel-default white-text">
            <div class=”panel panel-default”>
                <div class=”panel-heading”>Registration Confirmed</div>
                <div class=”panel-body”>
                    Your Email is successfully verified. Click here to <a href="{{url('/login')}}">login</a>
                </div>
            </div>
        </div>
    </div>
@endsection
