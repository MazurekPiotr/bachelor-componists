@extends('layouts.app')

@section('content')
    <div class="row about-project">
        <div class="col s12 centered">
            <h1 id="projectId" data-project-id="{{ $project->id }}">{{ $project->title }}</h1>
            <p>Created by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{$project->user->name}}</a> {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>
            <iframe src="https://www.facebook.com/plugins/share_button.php?href={{ urlencode(url()->current())}}&layout=button_count&size=small&mobile_iframe=true&appId=169215616956952&width=69&height=20" width="69" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>            <p>{{ $project->description }}</p>
            @if (Auth::check())
                <subscribe-button class="subscribe-btn" project-slug="{{ $project->slug }}"></subscribe-button>
            @endif
            @if(Auth::check())
                <report-project-button project-slug="{{ $project->slug }}" class="report-text report-topic"></report-project-button>
            @endif
            @if(session()->has('status'))
                <div class="alert centered green white-text">
                {!! session('status') !!}
                </div>
            @endif
            @if ($errors->has('fragmentInstrument'))
                <div class="help-block danger red white-text">
                    {{ $errors->first('fragmentInstrument') }}
                </div>
            @endif
            @if ($errors->has('fragmentSong'))
                <div class="help-block danger red white-text">
                    {{ $errors->first('fragmentSong') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row" id="edit-panel">
        @if(Auth::check())
            @if(Auth::user()->isElevated() || Auth::user()->id == $project->user_id)
                @if (count($fragments))
                <div class="col s10 offset-s1">
                    <h3 class="centered">Edit panel</h3>
                    <h4 class="centered">You are the master of your own project!</h4>
                    <p class="centered">Change volume, edit or delete tracks!</p>
                    <div class="row">
                      @foreach ($fragments as $fragment)
                      <div id="fragment-{{ $fragment->id }}" class="col m4">
                          <h4>{{ $fragment->name }}</h4>
                          <report-fragment-button project-slug="{{ $project->slug }}" fragment-id="{{ $fragment->id }}" class=""></report-fragment-button>
                          <div id="volume-message-{{ $fragment->id }}"></div>
                          <p>Set volume of {{ $fragment->name }}</p>
                          <set-volume-fragment volume="{{ $fragment->volume }}" fragment-id="{{ $fragment->id }}"></set-volume-fragment>
                          @can ('edit', $fragment)
                              <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="fa fa-edit"></span> Edit this fragment</a>
                          @endcan
                          @can ('delete', $fragment)
                          <button data-target="deleteTrack" class="btn modal-trigger">Delete track</button>
                          <div id="deleteTrack" class="modal fade">
                              <div class="centered col s12">
                                  <h1>Delete {{ $fragment->name }}</h1>
                                  <h2>Are you sure?</h2>
                                  </br>
                                  <form class="" action="{{ route('componists.projects.project.fragments.post.delete', [$project, $fragment]) }}" method="post">
                                      {{ method_field('DELETE') }}
                                      {{ csrf_field() }}
                                      <button type="submit" class="btn btn-link"><span class="fa fa-remove"></span> Delete this fragment</button>
                                  </form>
                              </div>
                          </div>
                          @endcan
                      </div>
                      @endforeach
                    </div>
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
                                        <button type="submit" class="btn btn-link"><span class="fa fa-remove"></span> Delete</button>
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
                <h4>Download the mix to play along!</h4>
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
        <div class="col s12">
          <div class="loader"></div>
        </div>
        <div id="playlist" class="col s12 m10 offset-m1 l10 offset-l1"></div>
    </div>
    <div id="addtrackwrapper" class="row">
        <h3>Want to contribute to this project?</h3>
        <button data-target="addTrack" class="btn modal-trigger">Add track</button>
        <div id="addTrack" class="modal fade">
            @if (Auth::check())
            <div class="centered col s12">
                <h1>Add a track!</h1>
                <form action="{{ route('componists.projects.fragments.create.submit', $project) }}" method="post" enctype="multipart/form-data">
                    <div class="col s12 input-field">
                        <label for="fragmentInstrument" >The name of your instrument</label>
                        <input type="text" name="fragmentInstrument" id="fragmentInstrument" value="{{ (old('fragmentInstrument') ? old('fragmentInstrument') : '' ) }}" placeholder="The name of your instrument">

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

                    </div>
                    {{ csrf_field() }}
                    <button type="submit" class="btn">Add your track</button>
                </form>
            </div>
            @else
                <div class="register">
                    <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to add a track to this project.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="project-chart centered">
        <div id="contributors-wrapper">
        <h3 class="white-text">Contributors</h3>
        <h4 class="white-text">Hover over the countries to see where the contributors are from!</h4>
            <div id="chartdiv" class="col s12"></div>
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

    </div>
    <h4 class="center">Or write a comment!</h4>
    <div class="posts">
    @foreach ($posts as $post)
      <div class="post row" id="post-{{ $post->id }}">
          <div class="col s12 m6 offset-m3 l2 offset-l2">
            @if( Storage::disk('s3')->exists('avatars/'. $post->user_id . '/avatar.jpg')  )
              <img class="circle" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $post->user_id . '/avatar.jpg'}}" alt="avatar">
            @else
              <img class="circle" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
            @endif
              <p>by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{ App\User::findOrFail($post->user_id)->name }}</a></p>
          </div>
          <div class="col s12 m6 offset-m3 l5">
              <p>
                  {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(
                      $post->body
                  ) !!}
              </p>
              <hr>
          </div>

      </div>
    @endforeach
    </div>
    @if (Auth::check())
        <div class="row">
        <div class=" reply col s12 m6 offset-m3">
            <form id="addPost">
              {{ csrf_field() }}
              <input type="hidden" name="projectId" value="{{ $project->id }}">
              <input type="hidden" name="userId" value="{{ Auth::user()->id }}">
                <div class="form-group{{ $errors->has('body') ? ' has-error' : '' }}">
                    <label for="body" class="control-label">Your Reply</label>
                    <textarea name="body" id="body" class="form-control" placeholder="Your comment on {{ $project->title }}" rows="8" required></textarea>
                    @if ($errors->has('body'))
                        <div class="help-block danger">
                            {{ $errors->first('body') }}
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-default">Write a comment</button>
            </form>
        </div>
        </div>
    @else
        <p style="text-align: center">Please <a href="{{ url('/register') }}">register</a> or <a href="{{ url('/login') }}">login</a> to post a comment</p>
    @endif
@endsection
@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $project->title }}">
    <meta property="og:description" content="{{ $project->description }}">
@endsection
@section('js_libs')
    <script src="/public/js/chart.js"></script>
    <script src="/public/js/waveform.js"></script>
    <script src="/public/js/multitrack.js"></script>
    <script src="/public/js/emitter.js"></script>
    <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
@endsection
