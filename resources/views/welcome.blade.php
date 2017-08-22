@extends('layouts.app')

@section('content')
<!-- Header -->
<a name="about"></a>
<div class="intro-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-message">
                    <h1>Componists</h1>
                    <h3>For musicians all over the world!</h3>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container -->

</div>
<!-- /.intro-header -->

<!-- Page Content -->

<a  name="services"></a>
<div class="content-section-a">

    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-sm-6">
                <hr class="section-heading-spacer">
                <div class="clearfix"></div>
                <h1 class="section-heading">Start your own project!</h1>
                <p class="lead">At Componists you will find music projects that you can contribute to!</p>
                <p class="lead">Or start you own project!</p>
            </div>
            @desktop
            <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                <img class="img-responsive" src="assets/img/pexels-photo.jpg" alt="">
            </div>
            @enddesktop
        </div>

    </div>
    <!-- /.container -->

</div>
<!-- /.content-section-a -->

<div class="content-section-b">

    <div class="container">

        <div class="row">
            <div class="col-lg-5 col-lg-offset-1 col-sm-push-6  col-sm-6">
                <hr class="section-heading-spacer">
                <div class="clearfix"></div>
                <h2 class="section-heading">Contribute to a project!</h2>
                <p class="lead">Find a project you like and add a </p>
            </div>
            @desktop
            <div class="col-lg-5 col-sm-pull-6  col-sm-6">
                <img class="img-responsive" src="assets/img/header.jpg" alt="">
            </div>
            @enddesktop
        </div>

    </div>
    <!-- /.container -->

</div>
<!-- /.content-section-b -->

<div class="content-section-a">

    <div class="container">

        <div class="row">
            <div class="col-lg-5 col-sm-6">
                <hr class="section-heading-spacer">
                <div class="clearfix"></div>
                <h2 class="section-heading">Upload your newly recorded track!</h2>
                <p class="lead">This template features the 'Lato' font, part of the <a target="_blank" href="http://www.google.com/fonts">Google Web Font library</a>, as well as <a target="_blank" href="http://fontawesome.io">icons from Font Awesome</a>.</p>
            </div>
            @desktop
            <div class="col-lg-5 col-lg-offset-2 col-sm-6">
                <img class="img-responsive" src="assets/img/header.jpg" alt="">
            </div>
            @enddesktop
        </div>

    </div>
    <!-- /.container -->

</div>


@endsection
