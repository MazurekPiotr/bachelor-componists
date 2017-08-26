@extends('layouts.app')

@section('content')
<div id="messages">
    <div class="row">
        <div class="login-form col col s12 m8 offset-m2 l6 offset-l3">
            <h1 class="white-text">Messages with <a href="{{ route('user.profile.index', $recipient) }}">{{ '@' . $recipient->name }}</a></h1>

            <div class="form">

                <div class="panel-body">
                    <messaging recipient="{{ $recipient }}"></messaging>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
