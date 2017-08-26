@extends('layouts.app')

@section('content')
    <div id="project-create">
        <div class="row">
            <div class="login-form col l6 m6 s12 offset-l3 offset-m3">
                <form method="POST" action="{{ route('componists.projects.create.submit') }}" enctype="multipart/form-data" class="col s12">
                    <h2 class="black-text">Create a new project</h2>
                    {{ csrf_field() }}
                    <div class="row">
                        <p class="col s12">What is the title of your project?</p>
                        <div class="input-field col s12">
                            <input name="title" type="text" id="title" value="{{ (old('title') ? old('title') : '' ) }}" autofocus>
                            <label for="title">Title</label>
                        </div>
                        @if ($errors->has('title'))
                            <div class="help-block danger">
                                {{ $errors->first('title') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <p class="col s12">Put here something about the project or tell other musicians what you are searching for!</p>
                        <div class="input-field col s12">
                            <input name="description" type="text" id="description" value="{{ (old('description') ? old('description') : '' ) }}">
                            <label for="description">Description of your project</label>
                        </div>
                        @if ($errors->has('description'))
                            <div class="help-block danger">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <p class="col s12">What is the name of your track?</p>
                        <div class="input-field col s12">
                            <input name="fragmentInstrument" type="text" id="fragmentInstrument" value="{{ (old('fragmentInstrument') ? old('fragmentInstrument') : '' ) }}">
                            <label for="fragmentInstrument">Name of the first track</label>
                        </div>
                        @if ($errors->has('fragmentInstrument'))
                            <div class="help-block danger">
                                {{ $errors->first('fragmentInstrument') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <p class="col s12">Important! Be sure that your file has the .mp3 extension and contains no special characters or spaces! Example: componist.mp3</p>
                        <div class="file-field input-field col s12">
                            <div class="btn">
                                <span>Track</span>
                                <input type="file" id="fragmentSong" name="fragmentSong">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" placeholder="componist.mp3" type="text">
                            </div>
                        </div>

                        @if ($errors->has('fragmentSong'))
                            <div class="help-block danger">
                                {{ $errors->first('fragmentSong') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <button type="submit" class="waves-effect waves-light btn">
                                Create!
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
