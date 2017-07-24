@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="">
            <div id="chartdiv" class="large"></div>
            <a href="{{ route('home.index') }}">&laquo; Back to your projects</a>
            <div class="">
                <div class="panel-heading" style="text-align: center">
                    <h1 id="projectId" data-project-id="{{ $project->id }}">{{ $project->title }}</h1>
                    <report-project-button project-slug="{{ $project->slug }}" class="pull-right report-text report-topic"></report-project-button>
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
                        <subscribe-button project-slug="{{ $project->slug }}"></subscribe-button>
                    @endif
                </div>
                Contributors
                @if (count($users))
                    @foreach ($users as $user)
                        <div id="user-{{ $user->id }}">
                            <p>{{ $user->name }}</p>
                            <a href="">
                            @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                                <img src="{{ Storage::disk('s3')->url('avatars/'. $user->id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($user->id)->name }}-avatar">
                            @else
                                <img src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                            @endif
                            </a>
                        </div>
                    @endforeach
                @endif
                @if(Auth::check())
                    @if(Auth::user()->isElevated() || Auth::user()->id == $project->user_id)
                    <div class="panel-body wrapper" >
                        Edit panel
                        <div id="columns">
                        @if (count($fragments))
                            @foreach ($fragments as $fragment)
                                <div id="post-{{ $fragment->id }}">
                                    <report-fragment-button project-slug="{{ $project->slug }}" fragment-id="{{ $fragment->id }}" class="pull-right report-text"></report-fragment-button>
                                    <p>
                                        {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(
                                            $fragment->body
                                        ) !!}
                                    </p>
                                    <div id="volume-message-{{ $fragment->id }}"></div>
                                    <set-volume-fragment volume="{{ $fragment->volume }}" fragment-id="{{ $fragment->id }}"></set-volume-fragment>
                                    @can ('edit', $fragment)
                                        <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
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
                        @endif
                    </div>
                    @else
                        Edit panel
                        @if (count($fragments))
                            @foreach ($fragments as $fragment)
                                @if(Auth::user()->id == $fragment->user_id)
                                    <div id="post-{{ $fragment->id }}">
                                        <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="glyphicon glyphicon-pencil"></span> Edit</a>

                                        <form class="inline" action="{{ route('componists.projects.project.fragments.post.delete', [$project, $fragment]) }}" method="post">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                        </form>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endif
                @endif
                    <div class="playlist-toolbar">
                        <div class="btn-group">
                            <span class="btn-pause btn btn-warning">
                                <i class="glyphicon glyphicon-pause"></i>
                            </span>
                            <span class="btn-play btn btn-success">
                                <i class="glyphicon glyphicon-play"></i>
                            </span>
                            <span class="btn-stop btn btn-danger">
                                <i class="glyphicon glyphicon-stop"></i>
                            </span>
                        </div>
                        @if(Auth::check())
                        <div class="btn-group">
                            <span title="Download the current work as Wav file" class="btn btn-download btn-primary">
                                <i class="glyphicon glyphicon-download-alt"></i>
                            </span>
                        </div>
                        @else
                            <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to download the mix.</p>
                        @endif
                    </div>
                <div id="playlist" ></div>

                    <h1 style="text-align: center">Upload your own track for this project!</h1>

                @if (Auth::check())
                    <div class="col-sm-12 col-md-6 col-md-offset-3 pin">
                        <form action="{{ route('componists.projects.fragments.create.submit', $project) }}" method="post" enctype="multipart/form-data">
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
                            <button type="submit" class="btn btn-default pull-right">Add your track</button>
                        </form>
                    </div>
                    @else
                        <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to add a track to this project.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
