    @extends('layouts.app')

    @section('content')
    <div id="my-projects" class="container">
        <div class="row">
            <div class="col s12">
                @if (Session::get('register_using_code') !== null)
                <div class="alert alert-{{ (Session::get('register_using_code') ? 'success' : 'danger' ) }} alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {!! (Session::pull('register_using_code') ? '<strong>Awesome!</strong> You have successfully signed up with a valid registration code.' : '<strong>Bugger!</strong> Your registration code was not able to be used during signup, so you only have base user privileges.' ) !!}
                </div>
                @endif
                <h1>My Projects</h1>
                @if (!count($projects))
                    <p>You haven't created any projects yet.</p>
                @endif
                <div class="content">   
                    <div id="create-new" class="col l4 m6 s12">
                        <a href="{{ route('componists.projects.create.form') }}">
                            <div class="card-panel">
                                <div class="center-div center-align">
                                    <h4>Create a new project</h4>
                                    <i class="fa fa-plus fa-2x" aria-hidden="true"></i>
                                </div>
                            </div>
                        </a>
                  </div>
                  @if (count($projects))
                  @foreach ($projects as $project)
                  <div class="col l4 m6 s12">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title truncate">{{ $project->title }}</span>
                            <div class="divider"></div>
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
                            <a href="/projects/{{ $project->slug }}">See Project</a>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection
