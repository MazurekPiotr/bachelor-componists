<?php

namespace App\Http\Controllers;

use App\Fragment;
use App\Project;
use App\Report;
use Illuminate\Http\Request;
use App\Events\FragmentReported;

class FragmentsReportController extends Controller
{

    public function status (Project $project, Fragment $fragment)
    {
        $report = Report::where('type', Fragment::class)->where('content_id', $fragment->id)->first();

        return response()->json($report, 200);
    }

    public function report (Request $request, Project $project, Fragment $fragment)
    {
        // no need for authorization, protected by auth middleware
        $report = new Report();
        $report->user_id = $request->user()->id;
        $report->content_id = $fragment->id;
        $report->type = Fragment::class;
        $report->save();

        event(new FragmentReported($project, $fragment, $request->user()));

        return response()->json(null, 200);
    }
}
