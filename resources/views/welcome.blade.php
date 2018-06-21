@extends('layouts.app')

@section('content')
<div id="intro">
    <div id="intro-message" class="center-div">
        <div>
            <h1>Componists</h1>
            <h3 class="centered white-text">For musicians all over the world!</h3>
        </div>
    </div>
    <div class="more" id="scroll-arrow">
        <span >What is Componists about?</span>
        <span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
    </div>
</div>

<div id="about-link">
    <div class="center-div">
        <h2>Errr what?!</h2>
        <h3>For all musicians?</h3>
        <p>
            Of course! Everybody is welcome to join us! <br>
            This platform is made for everyone who likes to experiment together with totally random people! <br>
            Are you a novice or even a professional musician, we bet you will find something here worth your attention!
        </p>

        <h4>So how does this work, you ask?</h4>
        <p>In short, there are two options:</p>
        <ul>
          <li><span><i class="fa fa-chevron-right"></i></span>  create a project yourself with a track that you made: Then you are the project owner and you can edit the tracks!</li><br>
          <li><span><i class="fa fa-chevron-right"></i></span>  contribute to a project you like: download the mix and record you track in your favourite recording program!</li>
        </ul>

    </div>
    <div class="more" id="scroll-arrow-2">
        <span class="black-text">Let's create awesome stuff!</span>
        <span><i class="fa fa-chevron-down black-text" aria-hidden="true"></i></span>
    </div>
</div>
<div id="join" class="row">
      <div class="center-div">
        <h2>Start composing now!</h2>
        <div class="center-align col s12 icons">
            <div class="left col m3 s6 offset-m3">
                <a class= "white-text" href="{{ route('componists.projects.create.form') }}">
                    <i class="fa fa-user fa-4x" aria-hidden="true"></i>
                    <p>Create your own project</p>
                </a>
            </div>
            <div class="left col m3 s6">
                <a class= "white-text" href="{{ route('componists.projects.index') }}">
                    <i class="fa fa-users fa-4x" aria-hidden="true"></i>
                    <p>Contribute to another</p>
                </a>
            </div>
        </div>
      </div>
      <div class="more" id="scroll-arrow-3">
          <span class="white-text">See the most recent projects!</span>
          <span><i class="fa fa-chevron-down white-text" aria-hidden="true"></i></span>
      </div>
</div>
<div id="recent-projects">
    <div class="container">
        <div class="row">
            <h2 class="center-align">Most recent projects</h2>
        </div>
        <div class="row">
          @foreach($projects as $project)
          <div class="col l4 m12 s12">
            <div class="card">
              <div class="card-content project-info">
                <h3>{{ $project->title}}</h3>
                @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                  <img class="circle" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $project->user_id . '/avatar.jpg'}}" alt="avatar">
                @else
                  <img class="circle" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                @endif
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
</div>
</div>
@endsection
