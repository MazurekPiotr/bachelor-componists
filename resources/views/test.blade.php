@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="intro-header">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <h1>Componists</h1>
                        <h4>For musicians all over the world!</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <div class="row info-index">
            <div class="col-sm-12 col-lg-8 info-text">
                <h2>Find or create a project!</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In in nulla faucibus, egestas velit vitae, lobortis urna. Aenean dictum in libero in tempus. Nunc sapien lectus, luctus a nisl nec, congue volutpat risus. Phasellus tincidunt dui non lorem facilisis volutpat. Pellentesque bibendum libero et tellus dictum aliquam. Mauris et sem vulputate, feugiat neque non, lobortis enim. Aenean enim metus, venenatis at hendrerit ut, auctor ac mauris. Integer ultrices ex sapien, vitae sollicitudin felis finibus quis.</p>
            </div>
            @desktop
            <div class="info-img">
                <img src="/assets/img/header.jpg">
            </div>
            @enddesktop
        </div>


        <div class="row info-index">
            @desktop
            <div class="col-sm-12 col-lg-4 info-img">
                <img src="/assets/img/header.jpg">
            </div>
            @enddesktop
            <div class="col-sm-12 col-lg-8 info-text">
                <h2>Find or create a project!</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In in nulla faucibus, egestas velit vitae, lobortis urna. Aenean dictum in libero in tempus. Nunc sapien lectus, luctus a nisl nec, congue volutpat risus. Phasellus tincidunt dui non lorem facilisis volutpat. Pellentesque bibendum libero et tellus dictum aliquam. Mauris et sem vulputate, feugiat neque non, lobortis enim. Aenean enim metus, venenatis at hendrerit ut, auctor ac mauris. Integer ultrices ex sapien, vitae sollicitudin felis finibus quis.</p>
            </div>
        </div>
        <div class="row info-index">

            <div class="col-sm-12 col-lg-8 info-text">
                <h2>Find or create a project!</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In in nulla faucibus, egestas velit vitae, lobortis urna. Aenean dictum in libero in tempus. Nunc sapien lectus, luctus a nisl nec, congue volutpat risus. Phasellus tincidunt dui non lorem facilisis volutpat. Pellentesque bibendum libero et tellus dictum aliquam. Mauris et sem vulputate, feugiat neque non, lobortis enim. Aenean enim metus, venenatis at hendrerit ut, auctor ac mauris. Integer ultrices ex sapien, vitae sollicitudin felis finibus quis.</p>
            </div>
            @desktop
            <div class="col-sm-12 col-lg-4 info-img">
                <img src="/assets/img/header.jpg">
            </div>
            @enddesktop
        </div>
    </div>
@endsection
