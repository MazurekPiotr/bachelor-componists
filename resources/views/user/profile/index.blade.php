@extends('layouts.app')

@section('content')
<div id="profile">
    <div class="container">
        <div class="row">
            <div class="col s12 m10 l10 offset-m1 offset-l1">
                <div class="card">
                    <div class="card-header">
                        <h1 class="center-align">{{ $user->name }}</h1>
                        <p class="centered"><a href="{{ route('user.chat.threads.thread.messages.index', $user) }}"><i class="fa fa-edit"></i> Message {{ $user->name }}</a></p>
                    </div>
                    <div class="card-content">
                        <div class="image col s12 m6 l6">
                            @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                            <img src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $user->id . '/avatar.jpg'}}" alt="{{ $user->name }}-avatar">
                            @else
                            <img style="width:100%" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                            @if (Auth::check() && Auth::user()->id === $user->id)<p class="text-center">Change your profile picture!</p>@endif
                            @endif
                        </div>
                        <div class="profile-info col s12 m6 l6 center-align">
                            @if (Auth::check() && Auth::user()->id === $user->id)
                              <a class="edit-profile" href="{{ route('user.profile.settings.index', Auth::user()->name) }}"><i class="fa fa-edit"></i> Edit profile</a>
                            @endif
                            @if (Auth::check() && Auth::user()->id === $user->id && $user->country == null)
                              <p class="bold">Let people know which country you are from! Edit your profile!</p>
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
        @if ($user->projects->count() !== 0)
        <div class="row">
            <h2 class="center-align white-text">Most recent projects of {{ $user->name }}</h2>
        </div>
        <div class="row">
        @foreach($user->projects as $project)
          <div class="col l4 m12 s12">
            <div class="card">
              <div class="card-content project-info">
                <h3>{{ $project->title}}</h3>
                <p>{{ $project->description }}</p>
                <p>Created {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>
                <p>by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{$project->user->name}}</a></p>
                <p>Last added track {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }} </p>
                <div class="chip">{{ $project->fragmentCount() }} track(s)</div>
              </div>
              <div class="card-action">
                  <a href="/projects/{{ $project->slug }}">See project</a>
              </div>
            </div>
          </div>
        @endforeach
        </div>
        @endif

      <div class="row">
          <h2 class="center-align white-text">Contributions of {{ $user->name }}</h2>
      </div>
      <div class="row">
        @foreach($user->fragments as $fragment)
          @if($fragment->user_id != $fragment->project->user_id)
          <div class="col l4 m12 s12">
            <div class="card">
              <div class="card-content project-info">
                <h3>{{ $fragment->name }}</h3>
                <h3>added to:{{ $fragment->project->title }}</h3>
                <p>{{ $project->description }}</p>
                <p>by <a href="/user/profile/{{'@' . App\User::findOrFail($fragment->user_id)->name }}">{{$fragment->user->name}}</a></p>
              </div>
              <div class="card-action">
                  <a href="/projects/{{ $project->slug }}">See project</a>
              </div>
            </div>
          </div>
          @endif
        @endforeach
      </div>
    </div>
</div>
@endsection
