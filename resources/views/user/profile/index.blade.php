@extends('layouts.app')

@section('content')
    <div id="profile">
        <div class="card">
            <div class="card-header row">
                <h3>{{ $user->name }}'s Profile</h3>
            </div>
            <div class="card-content row">
                <div class="image col s8 offset-s2 m6 offset-m3 l4">
                    @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                        <img src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $user->id . '/avatar.jpg'}}" alt="{{ $user->name }}-avatar">
                    @else
                        <img src="{{ Storage::disk('s3')->get('avatars/no-avatar.png') }}" alt="blank-avatar">
                    @endif
                </div>
                <div class="profile-info col s12 m12 l7 centered">
                    @if (Auth::check() && Auth::user()->id === $user->id)
                        <a href="{{ route('user.profile.settings.index', Auth::user()->name) }}" class="right"><span class="fa fa-edit"></span></a>
                    @endif
                    @if (Auth::check() && Auth::user()->isElevated() || Auth::check() && Auth::user()->id === $user->id)
                        <p>Email: <a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
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
@endsection
