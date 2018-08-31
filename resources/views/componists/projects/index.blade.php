@extends('layouts.app')

@section('content')
    <div class="projects-container">
        <div class=" ">
            <div class="row">
                <h1 class="center-align">All Projects</h1>
                @if($search != '')
                  <div class="centered">Your results for '{{ $search }}'</div>
                @endif
            </div>
            <div class="row" id="columns">
                @foreach($projects as $project)
                    <div class="col s12">
                        <div class="card">
                            <div class="card-content">
                                <h3>{{ $project->title }}</h3>
                                <p style="max-width: 200px;">{{ str_limit($project->description, 50, '...') }}</p>
                                <div class="project-info">
                                    <p>Created {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>
                                    <p>by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{$project->user->name}}</a></p>

                                    <p>Last added track {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }} </p>
                                    @can ('delete', $project)
                                        <form action="{{ route('componists.projects.project.delete', $project) }}" method="post">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                        </form>
                                    @endcan
                                </div>
                                <h5>By: </h5>
                                @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                                  <img class="circle left-top-img" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $project->user_id . '/avatar.jpg'}}" alt="{{ $project->user->name }}">
                                @else
                                  <img class="circle left-top-img" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="{{ $project->user->name }}">
                                @endif
                                @foreach($project->fragments->unique('user_id') as $fragment)
                                  @if($fragment->user_id != $project->user_id)
                                    @if( Storage::disk('s3')->exists('avatars/'. $fragment->user_id . '/avatar.jpg')  )
                                      <img class="circle contributor-img" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $fragment->user_id . '/avatar.jpg'}}" alt="{{ $fragment->user->name }}">
                                    @else
                                      <img class="circle contributor-img" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="{{ $fragment->user->name }}">
                                    @endif
                                  @endif
                                @endforeach
                            </div>
                            <div class="card-action">
                                <a href="/projects/{{ $project->slug }}">See project</a>
                                <div class="chip">{{ $project->fragmentCount() }} track(s)</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
