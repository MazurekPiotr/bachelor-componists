@extends('layouts.app')

@section('content')
    <div class="project-chart centered row">
        <h1 id="projectId" data-project-id="{{ $project->id }}">{{ $project->title }}</h1>
        <h4>Musicians from over the world!</h4>
        <div id="chartdiv" class="col s12"></div>
        <h4>Contributors</h4>
        <div class="contributors carousel">
            @if (count($users))
                @foreach ($users as $user)
                    <div id="user-{{ $user->id }}" class="contributor col s4 m3 l2 carousel-item">
                        <div class="user-img">
                            <h4>{{ $user->name }}</h4>
                            @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                                <img src="{{ Storage::disk('s3')->url('avatars/'. $user->id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($user->id)->name }}-avatar">
                            @else
                                <img src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
    <div class="row about-project">
        <div class="col s8 offset-s2 centered">
            @if(Auth::check())
                <report-project-button project-slug="{{ $project->slug }}" class="report-text report-topic pull-right"></report-project-button>
            @endif
            <h3>About {{ $project->title }}</h3>

            <p>Created by <a href="/user/profile/{{ '@' . App\User::findOrFail($project->user_id)->name }}">{{ '@' . App\User::findOrFail($project->user_id)->name }}</a></p>

        {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}

        @if (Auth::check())
            <subscribe-button class="subscribe-btn" project-slug="{{ $project->slug }}"></subscribe-button>
        @endif
        <p>{{ $project->description }}</p>
        </div>
    </div>
    <div class="row">
        @if(Auth::check())
            @if(Auth::user()->isElevated() || Auth::user()->id == $project->user_id)
                @if (count($fragments))
                <div class="edit-panel col s8 offset-s2">
                    <h3>Edit panel</h3>
                    @foreach ($fragments as $fragment)
                    <div id="fragment-{{ $fragment->id }}">
                        <h4>{{ $fragment->name }}</h4>
                        <report-fragment-button project-slug="{{ $project->slug }}" fragment-id="{{ $fragment->id }}" class=""></report-fragment-button>
                        <div id="volume-message-{{ $fragment->id }}"></div>
                        <set-volume-fragment volume="{{ $fragment->volume }}" fragment-id="{{ $fragment->id }}"></set-volume-fragment>
                        @can ('edit', $fragment)
                            <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="fa fa-edit"></span> Edit</a>
                        @endcan
                        @can ('delete', $fragment)
                            <form class="" action="{{ route('componists.projects.project.fragments.post.delete', [$project, $fragment]) }}" method="post">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-link danger-link"><span class="fa fa-remove"></span> Delete</button>
                            </form>
                        @endcan
                    </div>
                    @endforeach
                </div>
                @endif
            @else
                @if (count($fragments))
                    @foreach ($fragments as $fragment)
                        @if(Auth::user()->id == $fragment->user_id)
                            <div id="edit" class="modal fade">
                                <div id="fragment-{{ $fragment->id }}">
                                    <h4>{{ $fragment->name }}</h4>
                                    <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="fa fa-edit"></span> Edit</a>
                                    <form class="" action="{{ route('componists.projects.project.fragments.post.delete', [$project, $fragment]) }}" method="post">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-link danger-link"><span class="fa fa-remove"></span> Delete</button>
                                    </form>
                                    <set-volume-fragment volume="{{ $fragment->volume }}" fragment-id="{{ $fragment->id }}"></set-volume-fragment>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endif
        @endif
    </div>
    <div class="row player">
        <div class="col s10 offset-s1 centered">
            <div class="playlist-toolbar">
                <h2>Player</h2>
                <div class="btn-group">
                    <span class="btn-pause btn grey">
                        <i class="fa fa-pause"></i>
                    </span>
                    <span class="btn-play btn teal">
                        <i class="fa fa-play"></i>
                    </span>
                    <span class="btn-stop btn">
                        <i class="fa fa-stop"></i>
                    </span>
                </div>
                <div class="btn-group">
                    <span title="zoom in" class="btn-zoom-in btn blue">
                    <i class="fa fa-search-plus"></i>
                    </span>
                    <span title="zoom out" class="btn-zoom-out btn blue">
                    <i class="fa fa-search-minus"></i>
                    </span>
                </div>

                @if(Auth::check())
                <div class="btn-group">
                    <span title="Download the current work as Wav file" class="btn btn-download orange darken-3">
                        <i class="fa fa-download"></i>
                    </span>
                </div>
                @else
                    <p>Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to download the mix.</p>
                @endif
            </div>
        </div>
        <div id="playlist" class="col s12 m10 offset-m1 l10 offset-l1"></div>
    </div>
    <div class="row">
        <h3>Want to contribute to this project?</h3>
        <button data-target="addTrack" class="btn modal-trigger">Add track</button>
        <div id="addTrack" class="modal fade">
            @if (Auth::check())
            <div class="centered">
                <h1>Add a track!</h1>
                <form action="{{ route('componists.projects.fragments.create.submit', $project) }}" method="post" enctype="multipart/form-data">
                    <div >
                        <label for="fragmentInstrument" class="control-label">The name of your instrument</label>
                        <input type="fragmentInstrument" name="fragmentInstrument" id="fragmentInstrument" class="form-control" value="{{ (old('fragmentInstrument') ? old('fragmentInstrument') : '' ) }}" placeholder="The name of your instrument">
                        @if ($errors->has('fragmentInstrument'))
                            <div class="help-block danger">
                                {{ $errors->first('fragmentInstrument') }}
                            </div>
                        @endif
                    </div>
                    <div >
                        <label for="fragmentSong" >Your fragment</label>
                        <p>No spaces or special characters in the name of the file!</p>
                        <input type="file" id="fragmentSong" name="fragmentSong">
                        @if ($errors->has('fragmentSong'))
                            <div class="help-block danger">
                                {{ $errors->first('fragmentSong') }}
                            </div>
                        @endif
                    </div>
                    {{ csrf_field() }}
                    <button type="submit" class="">Add your track</button>
                </form>
            </div>
            @else
                <div class="register">
                    <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to add a track to this project.</p>
                </div>
            @endif
        </div>
    </div>
                        @foreach ($posts as $post)
                        <div class="post" id="post-{{ $post->id }}">
                            @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                                <img src="{{ Storage::disk('s3')->url('avatars/'. $user->id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($user->id)->name }}-avatar">
                            @else
                                <img src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                            @endif
                            <p>
                                {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(
                                    $post->body
                                ) !!}
                            </p>
                        </div>
                        @endforeach
                @if (Auth::check())
                    <div class="col-sm-12 col-md-6 col-md-offset-3">
                        <form action="{{ route('componists.projects.posts.create.submit', $project) }}" method="post" enctype="multipart/form-data">
                            <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                                <label for="body" class="control-label">Your Reply</label>
                                <textarea name="body" id="body" class="form-control" placeholder="Your comment on {{ $project->title }}" rows="8" required></textarea>
                                @if ($errors->has('body'))
                                    <div class="help-block danger">
                                        {{ $errors->first('body') }}
                                    </div>
                                @endif
                            </div>
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-default pull-right">Add your track</button>
                        </form>
                    </div>
                @else
                    <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> or <a href="{{ url('/login') }}">login</a> to post a comment</p>
                @endif
                </div>
            </div>
@endsection
