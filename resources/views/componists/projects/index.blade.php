@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Forum</div>

                <div class="panel-body" style="text-align: center">
                    <a href="{{ route('componists.projects.create.form') }}" class="btn btn-primary btn-block">Create a new project</a>
                    <br />
                    <ul class="list-group">
                        @if (count($projects))
                            @foreach ($projects as $project)
                                <li class="list-group-item">
                                    <a href="/projects/{{ $project->slug }}">{{ $project->title }} <span class="badge">{{ $project->postCount() }}</span></a>
                                    <br />
                                    <strong>Created</strong> {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->created_at))->diffForHumans() }}
                                    <br />
                                    <strong>Last post</strong> {{ Carbon\Carbon::createFromTimeStamp(strtotime($project->updated_at))->diffForHumans() }}
                                    @can ('delete', $project)
                                        <form action="{{ route('componists.projects.project.delete', $project) }}" method="post">
                                            {{ method_field('DELETE') }}
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-link danger-link"><span class="glyphicon glyphicon-remove"></span> Delete</button>
                                        </form>
                                    @endcan
                                </li>
                            @endforeach
                        @else
                            <p>There are currently no topics listed in the forum.</p>
                        @endif
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
