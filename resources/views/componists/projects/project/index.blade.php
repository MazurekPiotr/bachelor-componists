@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <p><a href="{{ route('home.index') }}">&laquo; Back to your topics</a></p>
            <report-project-button project-slug="{{ $project->slug }}" class="pull-right report-text report-topic"></report-project-button>
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: center">
                    <h4>{{ $project->title }}</h4>
                    {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }} by <a href="/user/profile/{{ '@' . App\User::findOrFail($project->user_id)->name }}">{{ '@' . App\User::findOrFail($project->user_id)->name }}</a>
                    <br />
                    @can ('delete', $project)
                        <form action="{{ route('componists.projects.project.delete', $project) }}" method="post">
                            {{ method_field('DELETE') }}
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                        </form>
                    @endcan
                    @if (Auth::check())
                        <subscribe-button topic-slug="{{ $project->slug }}"></subscribe-button>
                    @endif
                    <br />
                    <span class="text-muted pull-right badge">{{ count($fragments) }}</span>
                    <br />
                </div>

                <div class="panel-body">
                    @if (count($fragments))
                        @foreach ($fragments as $fragment)
                            <report-fragment-button project-slug="{{ $fragment->slug }}" fragment-id="{{ $fragment->id }}" class="pull-right report-text"></report-fragment-button>
                            <div class="post" id="post-{{ $fragment->id }}">
                                @if( Storage::disk('s3')->exists('avatars/'. $fragment->user_id . '/avatar.jpg')  )
                                    <img src="{{ Storage::disk('s3')->url('avatars/'. $fragment->user_id . '/') . 'avatar.jpg' }}" class="img-thumbnail" width="100" height="100" alt="{{ App\User::findOrFail($fragment->user_id)->name }}-avatar">
                                @else
                                    <img src="{{ Storage::disk('s3')->url('avatars/'. 'no-avatar.png') }}" class="img-thumbnail" width="100" height="100" alt="blank-avatar">
                                @endif
                                <br /><br /><br />
                                <p>
                                    {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(
                                        $fragment->body
                                    ) !!}
                                </p>
                                    {{ $fragment->link }}
                                @if (Storage::disk('s3')->exists('/fragments/'. $project->slug . '/' . $fragment->link . '.mp3'))
                                    <audio controls>
                                        <source src="{{ Storage::disk('s3')->url('fragments/'. $project->slug . '/' . $fragment->link . '.mp3') }}" type="audio/mpeg">
                                        <source src="{{ Storage::disk('s3')->url('fragments/'. $project->slug . '/' . $fragment->link . '.ogg') }}" type="audio/ogg">
                                        Your browser does not support the audio tag.
                                    </audio>
                                @endif
                                @if(Storage::disk('s3')->exists('fragments/'. $project->slug . '/waveform.png'))
                                    <img src="{{ Storage::disk('s3')->url('fragments/'. $project->slug . '/waveform.png') }}">
                                @endif
                                @can ('edit', $fragment)
                                    <a href="{{ route('componists.projects.project.fragments.post.edit', [$project, $fragment]) }}"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
                                @endcan
                                @can ('delete', $fragment)
                                    <form class="inline" action="{{ route('componists.projects.project.fragments.post.delete', [$project, $fragment]) }}" method="post">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                    </form>
                                @endcan
                            </div>
                        @endforeach
                    @else
                        <p>The are currently no posts for this topic.</p>
                    @endif

                    <br />
                    @if (Auth::check())
                        <form action="{{ route('componists.projects.fragments.create.submit', $project) }}" method="post">
                            <div class="form-group{{ $errors->has('fragment') ? ' has-error' : '' }}">
                                <label for="post" class="control-label">Your Reply</label>
                                <textarea name="fragment" id="fragment" class="form-control" placeholder="Your reply to {{ $project->title }}" rows="8" required></textarea>
                                @if ($errors->has('fragment'))
                                    <div class="help-block danger">
                                        {{ $errors->first('fragment') }}
                                    </div>
                                @endif
                            </div>
                            <div class="help-block pull-left">
                                Feel free to use Markdown. Use @username to mention another user.
                            </div>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default pull-right">Add Reply</button>
                        </form>
                    @else
                        <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to join the conversation.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
