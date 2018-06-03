@extends('layouts.app')

@section('content')
    <div class="row" id="login">
                <div class="centered col s12">
                    <form action="{{ route('componists.projects.project.fragments.post.update', [$project, $fragment]) }}" method="post" enctype="multipart/form-data">
                        <h1>Edit your track! ({{ $fragment->name }})</h1>
                        <div class="col s12 input-field">
                            <label for="fragmentInstrument" >The name of your instrument</label>
                            <input type="text" name="fragmentInstrument" id="fragmentInstrument" value="{{ (old('fragmentInstrument') ? old('fragmentInstrument') : '' ) }}" placeholder="The name of your instrument">
                            @if ($errors->has('fragmentInstrument'))
                                <div class="help-block danger">
                                    {{ $errors->first('fragmentInstrument') }}
                                </div>
                            @endif
                        </div>
                        <p>No spaces or special characters in the name of the file!</p>

                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Track</span>
                                <input type="file" name="fragmentSong">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                            @if ($errors->has('fragmentSong'))
                                <div class="help-block danger">
                                    {{ $errors->first('fragmentSong') }}
                                </div>
                            @endif
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="btn">Edit your track</button>
                    </form>
                </div>

    </div>
@endsection
