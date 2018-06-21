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
                                @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                                  <img class="circle" src="{{'https://s3.eu-west-2.amazonaws.com/tracks-bachelor/' . 'avatars/'. $project->user_id . '/avatar.jpg'}}" alt="avatar">
                                @else
                                  <img class="circle" src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                                @endif
                                <p style="max-width: 200px;">{{ str_limit($project->description, 50, '...') }}</p>
                                <div class="project-info">
                                    <p>Created {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>
                                    <p>by <a href="/user/profile/{{'@' . App\User::findOrFail($project->user_id)->name }}">{{$project->user->name}}</a></p>
                                    <p>Last added track {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }} </p>
                                    <div class="chip">{{ $project->fragmentCount() }} track(s)</div>
                                    @can ('delete', $project)
                                        <form action="{{ route('componists.projects.project.delete', $project) }}" method="post">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                            <div class="card-action">
                                <a href="/projects/{{ $project->slug }}">See project</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
