<?php

namespace App\Http\Controllers;

use App\Project;
use App\Report;
use Illuminate\Http\Request;
use App\Events\ProjectReported;

class ProjectsReportController extends Controller
{
    public function status(Request $request, Project $project)
    {
        $report = Report::where('type', Project::class)->where('content_id', $project->id)->first();

        return response()->json($report, 200);
    }

    public function report(Request $request, Project $project)
    {
        // no need for authorization, protected by auth middleware
        $report = new Report();
        $report->user_id = $request->user()->id;
        $report->content_id = $project->id;
        $report->type = Project::class;
        $report->save();

        event(new ProjectReported($project, $request->user()));

        return response()->json(null, 200);
    }
}
