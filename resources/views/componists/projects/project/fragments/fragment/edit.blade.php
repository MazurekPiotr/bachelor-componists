@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p><a href="{{ route('componists.projects.project.show', $project) }}">&laquo; Back to the project</a></p>
            <div class="panel panel-default">
                <div class="panel-heading">Edit the fragment</div>

                <div class="panel-body">
                    <form action="{{ route('componists.projects.project.fragments.post.update', [$project, $fragment]) }}" method="post" enctype="multipart/form-data">
                        <div class="form-group{{ $errors->has('fragmentText') ? ' has-error' : '' }}">
                            <label for="fragmentText" class="control-label">Your Reply</label>
                            <textarea name="fragmentText" id="fragmentText" class="form-control" placeholder="Your reply to {{ $project->title }}" rows="8" required></textarea>
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
                            <label for="fragmentSong" class="control-label">Your fragment</label>
                            <p>No spaces or special characters in the name of the file!</p>
                            <input type="file" id="fragmentSong" name="fragmentSong">
                            @if ($errors->has('fragmentSong'))
                                <div class="help-block danger">
                                    {{ $errors->first('fragmentSong') }}
                                </div>
                            @endif
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default pull-right">Update your track</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
