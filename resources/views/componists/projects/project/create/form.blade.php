@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Create a Topic</div>

                <div class="panel-body">
                    <form action="{{ route('componists.projects.create.submit') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="control-label">Topic Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ (old('title') ? old('title') : '' ) }}" placeholder="Halt! Crocs?">
                            @if ($errors->has('title'))
                                <div class="help-block danger">
                                    {{ $errors->first('title') }}
                                </div>
                            @endif
                            <div class="help-block">
                                <p>All titles need to be unique.</p>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('fragmentText') ? ' has-error' : '' }}">
                            <label for="fragmentText" class="control-label">Your Post</label>
                            <textarea name="fragmentText" id="fragmentText" class="form-control" rows="8">{{ (old('fragmentText') ? old('fragmentText') : '' ) }}</textarea>
                            @if ($errors->has('fragmentText'))
                                <div class="help-block danger">
                                    {{ $errors->first('fragmentText') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('fragmentInstrument') ? ' has-error' : '' }}">
                            <label for="fragmentInstrument" class="control-label">The name of your instrument</label>
                            <input type="fragmentInstrument" name="fragmentInstrument" id="fragmentInstrument" class="form-control" value="{{ (old('fragmentInstrument') ? old('fragmentInstrument') : '' ) }}" placeholder="The name of your instrument">
                            @if ($errors->has('fragmentInstrument'))
                                <div class="help-block danger">
                                    {{ $errors->first('fragmentInstrument') }}
                                </div>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('fragmentSong') ? ' has-error' : '' }}">
                            <label for="fragmentSong" class="control-label">Your fragment (must be of filetype .mp3)</label>
                            <p>No spaces or special characters in the name of the file!</p>
                            <input type="file" id="fragmentSong" name="fragmentSong">
                            @if ($errors->has('fragmentSong'))
                                <div class="help-block danger">
                                    {{ $errors->first('fragmentSong') }}
                                </div>
                            @endif
                        </div>
                        <div class="help-block pull-left">
                            Feel free to use Markdown.
                        </div>
                        <div class="form-group checkbox pull-right">
                            <label for="subscribe">
                                <input type="checkbox" name="subscribe" id="subscribe" style="margin-top:4px; padding: 8px" checked> &nbsp; Subscribe to topic?
                            </label>
                        </div>
                        <br />
                        <br />
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default pull-right">Create Project</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
