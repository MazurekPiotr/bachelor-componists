@extends('layouts.app')

@section('content')
<div id="messages" class="full">
    <div class="container">
        <div class="row under-nav">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>Message Threads</h1></div>

                    <div class="panel-body row">

                        @if (count($users))
                        @foreach ($users as $user)
                        <div class="col s6 m4 l3">
                            <div class="card">
                                <div class="card-content">
                                  {!! Auth::user()->hasUnreadMessagesFromSender($user) ? '<span class="badge" style="position:absolute; top:10px; right:10px;">' . Auth::user()->unreadMessageCountForSender($user). '</span>' : '' !!}
                                    @if( Storage::disk('s3')->exists('avatars/'. $user . '/avatar.jpg')  )
                                      <img class="circle" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $user . '/avatar.jpg'}}" alt="avatar">
                                    @else
                                      <img class="circle" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                                    @endif
                                    <br>
                                    <a href="{{ route('user.chat.threads.thread.messages.index', $user) }}">Chat with {{ $user->name }} </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <p>Your have no conversations.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Send a Message</div>

                    <div class="panel-body">

                        <form action="{{ route('user.chat.threads.create') }}" method="post">
                            <div class="form-group{{ $errors->has('recipient') ? ' has-error' : '' }}">
                                <label for="recipient" class="control-label">Recipient</label>
                                <input type="text" name="recipient" id="recipient" class="form-control" value="{{ (old('recipient') ? old('recipient') : '@' . Request::get('recipient') ) }}" placeholder="@username">
                                @if ($errors->has('recipient'))
                                <div class="help-block danger">
                                    {{ $errors->first('recipient') }}
                                </div>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label for="content" class="control-label">Your Message</label>
                                <textarea name="content" id="content" class="form-control" placeholder="Your message" rows="8">{{ (old('content') ? old('content') : '' ) }}</textarea>
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
    </div>
</div>
@endsection
