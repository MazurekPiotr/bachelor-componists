@extends('layouts.app')

@section('content')
    <div class="row about-project">
        <div class="col s12 centered">
            <h1 id="projectId" data-project-id="{{ $project->id }}" data-user-id="{{ Auth::check() ? Auth::user()->id : 1 }}" >{{ $project->title }}</h1>
            <p>Created by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{$project->user->name}}</a> {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>
            <iframe src="https://www.facebook.com/plugins/share_button.php?href={{ urlencode(url()->current())}}&layout=button_count&size=small&mobile_iframe=true&appId=169215616956952&width=69&height=20" width="69" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>            <p>{{ $project->description }}</p>
            @if (Auth::check())
                <subscribe-button class="subscribe-btn" project-slug="{{ $project->slug }}"></subscribe-button>
            @endif
            {{--
              @if(Auth::check())
                <report-project-button project-slug="{{ $project->slug }}" class="report-text report-topic"></report-project-button>
            @endif
              --}}
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
            @if(Auth::check())
              @if(Auth::user()->isElevated() || Auth::user()->id == $project->user_id)
              @if($project->settings)
              <div class="help-block danger red white-text">
                A new track has been added! Save changes to make it visible to the rest of the world!
              </div>
              @endif
            @endif
        </div>
    </div>

    @if(Auth::check())
      @if(Auth::user()->isElevated() || Auth::user()->id == $project->user_id)

      <div class="row" id="edit-panel">
                @if (count($fragments))
                <div class="col s10 offset-s1">
                    <h3 class="centered">Edit panel</h3>
                    <div class="row">
                      @foreach ($fragments as $fragment)
                      <div id="fragment-{{ $fragment->id }}" class="col s4">
                          <h4>{{ $fragment->name }}</h4>
                          <div><a href="/user/profile/{{'@' . App\User::findOrFail($fragment->user_id)->name }}"><span class="fa fa-edit"></span>Message {{ App\User::findOrFail($fragment->user_id)->name }}</a></div>
                          {{-- <report-fragment-button project-slug="{{ $project->slug }}" fragment-id="{{ $fragment->id }}" class=""></report-fragment-button>
                          @can ('edit', $fragment)
                              <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="fa fa-edit"></span> Edit this fragment</a>
                          @endcan --}}
                          <br>
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
              </div>
            @else
            <div class="row" id="edit-panel">
                @if (count($fragments))
                    @foreach ($fragments as $fragment)
                        @if(Auth::user()->id == $fragment->user_id)
                            <div id="edit" class="modal fade">
                                <div id="fragment-{{ $fragment->id }}">
                                    <h4>{{ $fragment->name }}</h4>
                                    <a href="{{ route('componists.projects.project.fragments.fragment.edit', [$project, $fragment]) }}"><span class="fa fa-edit"></span> Edit</a>
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
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
          </div>
      @endif
    @endif
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
                    <span title="zoom in" class="btn-zoom-in btn grey">
                    <i class="fa fa-search-plus"></i>
                    </span>
                    <span title="zoom out" class="btn-zoom-out btn grey">
                    <i class="fa fa-search-minus"></i>
                    </span>
                    <div>
                      <label for="auto-scroll">
                        Automatic Scroll
                      </label>
                      <input type="checkbox" id="auto-scroll" class="automatic-scroll">
                    </div>
                </div>
                @if(Auth::check())
                    @if(Auth::user()->isElevated() || Auth::user()->id == $project->user_id)
                    <div class="btn-group">
                      <span class="btn-cursor btn btn-default" title="select cursor"><i class="fa fa-italic"></i></span>
                      <span class="btn-shift btn btn-default" title="shift audio in time"><i class="fa fa-arrows"></i></span>
                      <span class="btn-fadein btn btn-default" title="set audio fade in">Fade-in</span>
                      <span class="btn-fadeout btn btn-default" title="set audio fade out">Fade-out</span>
                    </div>
                    <div class="btn-group fade-options" style="display:none">
                      <p style="display: block;">Fade-in/out options:</p>
                      <span class="btn btn-default btn-logarithmic">logarithmic</span>
                      <span class="btn btn-default btn-linear">linear</span>
                      <span class="btn btn-default btn-exponential">exponential</span>
                      <span class="btn btn-default btn-scurve">s-curve</span>
                    </div>
                    <div class="btn-group">
                        <span title="Prints playlist info to console" class="btn btn-info">Save changes</span>
                    </div>
                    <p style="display:none" class="changes-saved">Changed saved!</p>
                    @endif
                @endif
                @if(Auth::check())
                @else
                    <p>Please <a href="{{ url('/register') }}">register</a> and <a href="{{ url('/login') }}">login</a> to download the mix.</p>
                @endif
              </div>
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
        <h5>Or..</h5>
        <div class="row centered">
          <h4>Test your tracks before uploading!</h4>
          <div class="track-drop"></div>
        </div>
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
        <h5 class="white-text">Hover over the countries to see where the contributors are from!</h5>
        <div style="height: 46px;">
          <span title="zoom in" class="btn grey chart-zoom-in">
            <i class="fa fa-search-plus"></i>
          </span>
          <span title="zoom out" class="btn grey chart-zoom-out">
            <i class="fa fa-search-minus"></i>
          </span>
        </div>
            <div id="chartdiv" class="col s12"></div>
            <div class="contributors carousel">
                @if (count($users))
                    @foreach ($users as $user)
                        <div id="user-{{ $user->id }}" class="contributor col s4 m3 l2 carousel-item">
                            <div class="user-img">
                                <h4>{{ $user->name }}</h4>
                                @if( Storage::disk('s3')->exists('avatars/'. $user->id . '/avatar.jpg')  )
                                    <img class="circle" src="{{ Storage::disk('s3')->url('avatars/'. $user->id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($user->id)->name }}-avatar">
                                @else
                                    <img class="circle" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

    </div>
    <h4 class="center">Write a comment!</h4>
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
    <div class="posts">
    @foreach ($posts as $post)
      <div class="post row" id="post-{{ $post->id }}">
          <div class="col s2 m3 l2 offset-l2">
            @if( Storage::disk('s3')->exists('avatars/'. $post->user_id . '/avatar.jpg')  )
              <img class="circle" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $post->user_id . '/avatar.jpg'}}" alt="avatar">
            @else
              <img class="circle" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
            @endif
          </div>
          <div class="col s10 m7 l5">
              <p>
                  {!! GrahamCampbell\Markdown\Facades\Markdown::convertToHtml(
                      $post->body
                  ) !!}
                  <span>by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{ App\User::findOrFail($post->user_id)->name }}</a></span>
              </p>
              <hr>
          </div>

      </div>
    @endforeach
    </div>
@endsection
@section('facebook_meta')
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $project->title }}">
    <meta property="og:description" content="{{ $project->description }}">
@endsection
@section('js_libs')

<script src="/public/js/waveform.js"></script>
<script src="/public/js/multitrack.js"></script>
<script src="/public/js/emitter.js"></script>

    <script src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
@endsection
