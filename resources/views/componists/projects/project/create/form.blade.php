@extends('layouts.app')

@section('content')
<div class="container" id="create-project">
    <div class="row">
        <div class="col s12">
            <div class="panel panel-default">
                <h2> Create a new project </h2>
                <div class="panel-heading">Create a Project</div>

                <div class="panel-body">
                    <form action="{{ route('componists.projects.create.submit') }}" method="post" enctype="multipart/form-data">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="control-label">Project Title</label>
                            <div class="input-info">
                                <input type="text" name="title" id="title" class="form-control" value="{{ (old('title') ? old('title') : '' ) }}">
                                @if ($errors->has('title'))
                                <span class="danger-link"> {{ $errors->first('title') }} </span>
                                @else 
                                <span>All titles need to be unique.</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="control-label">Description of your newly made project</label>
                            <textarea name="description" id="description" class="form-control" rows="8">{{ (old('description') ? old('description') : '' ) }}</textarea>
                            @if ($errors->has('description'))
                            <span class="danger-link">{{ $errors->first('description') }} </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('fragmentInstrument') ? ' has-error' : '' }}">
                            <label for="fragmentInstrument" class="control-label">The name of your instrument</label> <br>
                            <div class="input-info">
                                <input type="fragmentInstrument" name="fragmentInstrument" id="fragmentInstrument" class="form-control" value="{{ (old('fragmentInstrument') ? old('fragmentInstrument') : '' ) }}" placeholder="e.g. guitar">
                                @if ($errors->has('fragmentInstrument'))
                                <span class="danger-link">{{ $errors->first('fragmentInstrument') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('fragmentSong') ? ' has-error' : '' }}">
                            <label for="fragmentSong" class="control-label">Your fragment (must be of filetype .mp3)</label>
                            <div class="file-field input-field">
                              <div class="btn">
                                <span>File</span>
                                <input name="fragmentSong" type="file">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                            @if ($errors->has('fragmentSong'))
                                <div class="input-info"><span class="danger-link">{{ $errors->first('fragmentSong') }}</span></div>
                            @else 
                                <div class="input-info">
                                    <span>No spaces or special characters in the name of the file!</span> <br>
                                    <span>Feel free to use Markdown.</span>
                                </div>
                            @endif
                        </div>
                        
                    </div>
                    <br />
                    <br />
                    
                    <button type="submit" class="btn btn-default">Create Project</button>
                    <div class="form-group checkbox">
                        <label for="subscribe">
                            <input type="checkbox" name="subscribe" id="subscribe" style="margin-top:4px; padding: 8px" checked> &nbsp; Subscribe to project?
                        </label>
                    </div>
                    {{ csrf_field() }}
                </form>
            </div>

        </div>
    </div>
</div>
</div>
@endsection
