@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p><a href="{{ route('projects', $topic) }}">&laquo; Back to the topic</a></p>
            <div class="panel panel-default">
                <div class="panel-heading">Edit the post</div>

                <div class="panel-body">
                    <form action="{{ route('project', [$topic, $post]) }}" method="post">
                        <div class="form-group{{ $errors->has('fragment') ? ' has-error' : '' }}">
                            <label for="post" class="control-label">Post</label>
                            <textarea name="post" id="post" class="form-control" rows="8" required>{{ $post->body }}</textarea>
                            @if ($errors->has('post'))
                                <div class="help-block danger">
                                    {{ $errors->first('post') }}
                                </div>
                            @endif
                            <div class="help-block pull-left">
                                Feel free to use Markdown.
                                <br />
                                Use [@username]({{ env('APP_URL') }}/user/profile/@username) to mention another user here.
                            </div>
                        </div>
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-default pull-right">Update</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
