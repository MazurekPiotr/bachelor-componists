@extends('layouts.app')

@section('content')
<div id="message-thread" class="full">
<div class="container">
    <div class="row under-nav">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Messages with <a href="{{ route('user.profile.index', $recipient) }}">{{ '@' . $recipient->name }}</a> @if($recipient->name === 'Admin'): any questions? Just ask! @endif</h2></div>
                <div class="divider"></div>
                <div class="panel-body">
                    @if($recipient->name === 'Admin')<p>We will respond to your questions as quick as possible!</p> @endif
                    <messaging recipient="{{ $recipient }}"></messaging>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
