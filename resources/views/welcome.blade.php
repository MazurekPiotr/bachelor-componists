@extends('layouts.app')

@section('content')
<div id="intro">
    <div id="intro-message" class="center-div">
        <div>
            <h1>Componists</h1>
            <h3>For musicians all over the world!</h3>
        </div>
    </div>
    <div class="more" id="scroll-arrow">
        <span >See more!</span>
        <span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
    </div>
</div>

<div id="about-link">
    <div class="container">
        <h1>Errr what?!</h1>
        <h3>For all musicians?</h3>
        <p>
            Of course! Everybody is welcome to join us!
        </p>
        <p>
            This platform is made for everyone who likes to experiment together with totally random people!
        </p>
        <p>
            Are you a novice or even a professional musician, we bet you will find something here worth your attention!
        </p>

        <h3>So how does this work, you ask?</h3>

        <div id="about-btn">
            <button class="btn-large" href="#">Read more!</button>
        </div>
    </div>
    <div class="more" id="scroll-arrow">
        <span >See more!</span>
        <span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
    </div>
</div>
<div id="join" class="row">
    <div class="container centered">
        <h1>So let's compose!</h1>
        <h3>But... Why?</h3>
        <p>
            There is only one obvious answer. It's just because we can!
        </p>
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
                    <p>Contribute</p>
                </a>
            </div>
            <ul class="notes col s12 m6 offset-m3">
                <li>&#9833;</li>
                <li>&#9834;</li>
                <li>&#9835;</li>
                <li>&#9836;</li>
                @notmobile
                <li>&#9833;</li>
                <li>&#9834;</li>
                <li>&#9835;</li>
                <li>&#9836;</li>
                @endnotmobile
            </ul>
        </div>

    </div>
</div>
<div id="recent-projects">
    <div class="container">
        <div class="row">
            <h2 class="center-align">Most recent projects</h2>
        </div>
        <div class="row">
              @foreach($projects as $project)
                <div class="card col s12 m3 push-m1 l4 push-l1">
                    <div class="card-image">
                        @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                            <img src="{{ Storage::disk('s3')->url('avatars/'. $project->user_id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($project->user_id)->name }}-avatar">
                        @else
                            <img src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                        @endif
                    </div>
                    <div class="card-content">
                        <p>{{ $project->description }}</p>
                    </div>
                    <div class="card-action">
                        <a href="/projects/{{ $project->slug }}">See project</a>
                    </div>
                </div>
                @endforeach
        </div>
    </div>
</div>
@endsection
