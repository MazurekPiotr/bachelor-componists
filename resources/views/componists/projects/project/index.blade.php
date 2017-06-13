@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="">
            <p><a href="{{ route('home.index') }}">&laquo; Back to your topics</a></p>
            <report-project-button project-slug="{{ $project->slug }}" class="pull-right report-text report-topic"></report-project-button>
            <div class="panel panel-default">
                <div class="panel-heading" style="text-align: center">
                    <h4 id="projectId" data-project-id="{{ $project->id }}">{{ $project->title }}</h4>
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
                        <div id="top-bar" class="playlist-top-bar">
                            <div class="playlist-toolbar">
                                <div class="btn-group">
                                  <span class="btn-pause btn btn-warning">
                                    <i class="fa fa-pause"></i>
                                  </span>
                                                                <span class="btn-play btn btn-success">
                                    <i class="fa fa-play"></i>
                                  </span>
                                                                <span class="btn-stop btn btn-danger">
                                    <i class="fa fa-stop"></i>
                                  </span>
                                                                <span class="btn-rewind btn btn-success">
                                    <i class="fa fa-fast-backward"></i>
                                  </span>
                                                                <span class="btn-fast-forward btn btn-success">
                                    <i class="fa fa-fast-forward"></i>
                                  </span>
                                </div>
                                <div class="btn-group">
                                    <span title="zoom in" class="btn-zoom-in btn btn-default">
                                        <i class="fa fa-search-plus"></i>
                                    </span>
                                    <span title="zoom out" class="btn-zoom-out btn btn-default">
                                        <i class="fa fa-search-minus"></i>
                                    </span>
                                </div>
                                <div class="btn-group btn-playlist-state-group">
                                  <span class="btn-cursor btn btn-default active" title="select cursor">
                                    <i class="fa fa-headphones"></i>
                                  </span>
                                                                <span class="btn-select btn btn-default" title="select audio region">
                                    <i class="fa fa-italic"></i>
                                  </span>
                                                                <span class="btn-shift btn btn-default" title="shift audio in time">
                                    <i class="fa fa-arrows-h"></i>
                                  </span>
                                                                <span class="btn-fadein btn btn-default" title="set audio fade in">
                                    <i class="fa fa-long-arrow-left"></i>
                                  </span>
                                                                <span class="btn-fadeout btn btn-default" title="set audio fade out">
                                    <i class="fa fa-long-arrow-right"></i>
                                  </span>
                                </div>
                                <div class="btn-group">
                                    <span title="Prints playlist info to console" class="btn btn-info">Print</span>
                                    <span title="Clear the playlist's tracks" class="btn btn-clear btn-danger">Clear</span>
                                </div>
                                <div class="btn-group">
                                  <span title="Download the current work as Wav file" class="btn btn-download btn-primary">
                                    <i class="fa fa-download"></i>
                                  </span>
                                </div>
                            </div>
                        </div>
                    <div id="playlist" ></div>

                    <br />
                    @if (Auth::check())
                        <form action="{{ route('componists.projects.fragments.create.submit', $project) }}" method="post" enctype="multipart/form-data">
                            <div class="form-group{{ $errors->has('fragmentText') ? ' has-error' : '' }}">
                                <label for="post" class="control-label">Your Reply</label>
                                <textarea name="fragmentText" id="fragmentText" class="form-control" placeholder="Your reply to {{ $project->title }}" rows="8" required></textarea>
                                @if ($errors->has('fragmentText'))
                                    <div class="help-block danger">
                                        {{ $errors->first('fragmentText') }}
                                    </div>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('fragmentSong') ? ' has-error' : '' }}">
                                <label for="fragmentSong" class="control-label">Your fragment</label>
                                <input type="file" id="fragmentSong" name="fragmentSong">
                                @if ($errors->has('fragmentSong'))
                                    <div class="help-block danger">
                                        {{ $errors->first('fragmentSong') }}
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
