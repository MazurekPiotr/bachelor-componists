@extends('layouts.app')

@section('content')
    <div id="messages">
        <div class="row">
            <div class="login-form col l8 m8 s12 offset-l2 offset-m2">
                <h1 class="white-text">My messages</h1>
                <div class="row">

                    <div class="form col s12">
                        <h4 class="black-text">Message threads</h4>
                        @if (count($users))
                            @foreach ($users as $user)
                                <div class="row"><a href="{{ route('user.chat.threads.thread.messages.index', $user) }}">View {{ '@' . $user->name }} messages {!! Auth::user()->hasUnreadMessagesFromSender($user) ? '<span class="badge">' . Auth::user()->unreadMessageCountForSender($user). '</span>' : '' !!}</a></div>
                            @endforeach
                        @else
                            <p class="col s12">Your have no conversations.</p>
                        @endif
                    </div>
                </div>
                <div class="row">

                    <form class="form col s12" action="{{ route('user.chat.threads.create') }}" method="post">
                        <h4 class="black-text">Send a new message</h4>
                        <div class="input-field col s10 offset-s1">
                            <input type="text" name="recipient" id="recipient" value="{{ (old('recipient') ? old('recipient') : '@' . Request::get('recipient') ) }}" placeholder="@username">
                            <label for="recipient">Recipient</label>
                            @if ($errors->has('recipient'))
                                <div class="help-block danger">
                                    {{ $errors->first('recipient') }}
                                </div>
                            @endif
                        </div>
                        <div class="col s10 offset-s1 input-field">
                            <input name="content" id="content" type="text" placeholder="Your message" value="{{ (old('content') ? old('content') : '' ) }}">
                            @if ($errors->has('content'))
                                <div class="help-block danger">
                                    {{ $errors->first('content') }}
                                </div>
                            @endif
                        </div>

                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default">Send</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

@endsection
