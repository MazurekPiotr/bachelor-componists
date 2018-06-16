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
        <span >See more!</span>
        <span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
    </div>
</div>

<div id="about-link">
    <div class="container">
        <div class="center-div">
            <h2>Errr what?!</h2>
            <h3>For all musicians?</h3>
            <p>
                Of course! Everybody is welcome to join us! <br>
                This platform is made for everyone who likes to experiment together with totally random people! <br>
                Are you a novice or even a professional musician, we bet you will find something here worth your attention!
            </p>

            <h4>So how does this work, you ask?</h4>

            <div class="more" id="scroll-arrow">
                <span >See more!</span>
                <span><i class="fa fa-chevron-down" aria-hidden="true"></i></span>
            </div>
        </div>
    </div>
</div>
<div id="join" class="row">
    <div class="container   ">
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
                    <p>Contribute</p>
                </a>
            </div>
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
          <div class="col l4 m6 s12">
          <div class="card">
            <div class="card-image">

            </div>
            <div class="card-content">
                <p>{{ $project->description }}</p>
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
