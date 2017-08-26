@extends('layouts.app')

@section('content')
    <div class="projects-container">
        <div class="container">
            <div class="row centered">
                @if($search)
                    <h1 class="center-align">Search results for {{ $search }}</h1>
                @else
                    <h1 class="center-align">All Projects</h1>
                @endif
                <a class="btn btn-create" href="{{ route('componists.projects.create.form') }}">Start a new project</a>
            </div>
            <div class="row">
                @foreach($projects as $project)
                    <div class="col s12 m6 l4">
                        <div class="card">
                            <div class="card-header">
                                <h3>{{ $project->title }}</h3>
                            </div>
                            <div class="card-image">
                                @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                                    <img src="{{ Storage::disk('s3')->url('avatars/'. $project->user_id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($project->user_id)->name }}-avatar">
                                @else
                                    <img src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                                @endif
                            </div>
                            <div class="card-content">
                                <h4 class="black-text">About {{ $project->title }}</h4>
                                <p class="project-info">{{ $project->description }}</p>

                                <div class="chip">{{ $project->fragmentCount() }} tracks</div>
                                <p>Created {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>

                                <p>Last add fragment {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }} </p>
                                @can ('delete', $project)
                                    <form action="{{ route('componists.projects.project.delete', $project) }}" method="post">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                    </form>
                                @endcan
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
