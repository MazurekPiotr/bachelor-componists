@extends('layouts.app')

@section('content')
    <div class="projects-container">
        <div class="container ">
            <div class="row">
                <h1 class="center-align">All Projects</h1>
            </div>
            <div class="row grid">
                @foreach($projects as $project)
                    <div class="col s12">
                        <h3 class="truncate">{{ $project->title }}</h3>
                        <div class="card">
                            <div class="card-image">
                                @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                                    <img src="{{ Storage::disk('s3')->url('avatars/'. $project->user_id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($project->user_id)->name }}-avatar">
                                @else
                                    <img src="{{ Storage::disk('s3')->url('avatars/no-avatar.png') }}" alt="blank-avatar">
                                @endif
                            </div>
                            <div class="card-content">

                                <p class="project-description">{{ $project->description }}</p>

                                <div class="project-info">
                                    <p>Created {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}</p>

                                    <p>Last added fragment {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }} </p>
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
