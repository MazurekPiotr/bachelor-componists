@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div style="text-align: center">
                <h1 id="projectId" data-project-id="all">Componists</h1>
                <br>

                    <div id="chartdiv"></div>
                <br>
                <ul class="list-group container">
                    @if (count($projects))
                        @foreach ($projects as $project)
                                <div class="col-sm-1 col-md-4 col-lg-3 pin">
                                    <a href="/projects/{{ $project->slug }}" style="text-decoration: none; text-decoration-color: #0d0d0d">
                                        <li class="list-group-item">
                                            <span class="badge">{{ $project->postCount() }} tracks</span>
                                            <br>
                                            <h1>{{ $project->title }}</h1>
                                            <br>
                                            @if( Storage::disk('s3')->exists('avatars/'. $project->user_id . '/avatar.jpg')  )
                                                <img src="{{ Storage::disk('s3')->url('avatars/'. $project->user_id . '/') . 'avatar.jpg' }}" alt="{{ App\User::findOrFail($project->user_id)->name }}-avatar">
                                            @else
                                                <img src="{{ Storage::disk('s3')->url('avatars/'. 'no-avatar.png') }}" alt="blank-avatar">
                                            @endif
                                            <br>
                                            <strong>Created</strong> {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}
                                            <br>
                                            <strong>Last post</strong> {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }}
                                            @can ('delete', $project)
                                                <br>
                                                <form action="{{ route('componists.projects.project.delete', $project) }}" method="post">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                                </form>
                                            @endcan
                                        </li>
                                    </a>
                                </div>
                        @endforeach
                    @endif
                </ul>
                <a href="{{ route('componists.projects.create.form') }}" class="btn btn-primary btn-block">Create a new project</a>
            </div>
        </div>
    </div>
</div>
@endsection
