@extends('layouts.app')

@section('content')
<div id="profile">
    <div class="container">
        <div class="row">
            <div class="col s12 m8 offset-m2">
                <div class="card">
                    <div class="card-header">
                        <h1 class="center-align">{{ $user->name }}</h1>
                    </div>
                    <div class="card-content">
                        <div class="image col s12">
                            @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                            <img src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $user->id . '/avatar.jpg'}}" alt="{{ $user->name }}-avatar">
                            @else
                            <img style="width:100%" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                            <p>Change your profile picture!</p>
                            @endif
                        </div>
                        <div class="profile-info col s12 center-align">
                            @if (Auth::check() && Auth::user()->id === $user->id)
                              <a class="edit-profile" href="{{ route('user.profile.settings.index', Auth::user()->name) }}"><i class="fa fa-edit"></i> Edit profile</a>
                            @endif
                            @if ($user->country == null)
                              <p>Let people know which country you are from! Edit your profile!</p>
                            @endif
                            @if (Auth::check() && Auth::user()->isElevated() || Auth::check() && Auth::user()->id === $user->id)
                              <p>Email: <a class="email" href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                              @if (Auth::check() && Auth::user()->id === $user->id)
                                <span class="text-muted"><span class="glyphicon glyphicon-exclamation-sign" title="Your email address is only visible to you and elevated account users."></span></span>
                              @endif
                            @endif
                            <p>Registered: {{ Carbon\Carbon::createFromTimeStamp(strtotime($user->created_at))->diffForHumans() }}</p>
                            <p>Last Activity: {{ Carbon\Carbon::createFromTimeStamp(strtotime($user->last_activity))->diffForHumans() }}</p>
                            <h4>Contributions</h4>
                            <p>Started {{ count($user->projects) }} project{{ (count($user->projects) > 1) ? 's' : '' }}.</p>
                            <p>Added {{ count($user->fragments) }} track{{ (count($user->fragments) > 1) ? 's' : '' }}.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
