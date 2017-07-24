<?php

namespace App\Http\Controllers;

use App\Events\ProjectDeleted;
use App\Http\Requests\CreateProjectFormRequest;
use App\Project;
use Auth;
use Illuminate\Http\File;
use ClassPreloader\Config;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Vorbis;
use Illuminate\Support\Facades\Storage;
use App\User;
use FFMpeg\FFMpeg;
use App\Fragment;
use App\Subscription;
use App\GetMentionedUsers;
use Illuminate\Http\Request;
use App\Events\UsersMentioned;
use Illuminate\Support\Collection;
use PhpParser\Node\Expr\Cast\Int_;

class ProjectsController extends Controller
{
    public function home() {
        $projects = Project::orderBy('created_at', 'desc')->get();

        return view('welcome', [
            'projects' => $projects,
        ]);
    }

    public function index ()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();

        return view('componists.projects.index', [
            'projects' => $projects,
        ]);
    }

    public function show (Request $request, Project $project)
    {
        $fragments = Fragment::where('project_id', $project->id)->get();

        $users = [];
        foreach ($fragments as $key => $fragment) {
            $user = User::where('id', $fragment->user_id)->first();
            $users[$key] = $user;
        }

        return view('componists.projects.project.index', [
            'project' => $project,
            'fragments' => $fragments,
            'users' => array_unique($users)
        ]);
    }

    public function showCreateForm ()
    {
        return view('componists.projects.project.create.form');
    }

    public function create (CreateProjectFormRequest $request)
    {
        $project = new Project();
        $project->user_id = $request->user()->id;


        $project->slug = str_slug(mb_strimwidth($request->title, 0, 255), '-');
        $project->title = $request->title;
        $project->save();

        $fragment = new Fragment();

        $music_file = $request->file('fragmentSong');

        $timeName = $music_file->getClientOriginalName();
        if(strpos($timeName, '#') || strpos($timeName, ' ')){
            return view('componists.projects.project.fragments.fragment.edit', [
                'project' => $project,
                'fragment' => $fragment
            ]);
        }
        $extension = $music_file->getClientOriginalExtension();
        $time = time();
        $fileName = $timeName . '.' .  $extension;
        $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
        $music_file->move($location,$timeName);

        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.mp3', fopen($location . '/' . $timeName, 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->body = $request->fragmentText;
        $fragment->link = 'https://tracks-bachelor.s3.eu-west-2.amazonaws.com/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.mp3';
        $url = env('APP_URL');
        $fragment->body = preg_replace('/\@\w+/', "[\\0]($url/user/profile/\\0)", $request->fragmentText);
        $fragment->name = $request->fragmentInstrument;
        $fragment->save();

        // do @mention functionality
        $mentioned_users = GetMentionedUsers::handle($request->fragmentText);

        if (count($mentioned_users)) {
            event(new UsersMentioned($mentioned_users, $project, $fragment));
        }

        // create the subscription
        $subscription = new Subscription();
        $subscription->project_id = $project->id;
        $subscription->user_id = $request->user()->id;
        $subscription->subscribed = ($request->subscribe === null ? 0 : 1);
        $subscription->save();

        return redirect()->route('componists.projects.project.show', [
            'project' => $project,
        ]);
    }

    public function destroy (Request $request, Project $project)
    {
        $project->delete();

        if ($project->user->id !== $request->user()->id) {
            // don't want to send email to the owner of the project, if they deleted it
            event(new ProjectDeleted($project));
        }

        return redirect()->route('componists.projects.index');
    }

    public function getFragmentSlugsFromProject($projectId) {
        $fragments = Fragment::where('project_id', $projectId)->select('link', 'name', 'volume')->get();

        return $fragments->toJson();
    }

    public function getMapData($projectId) {
        $fragments = Fragment::where('project_id', $projectId)->select('user_id')->get();

        $users = [];
        foreach ($fragments as $key => $fragment) {
            $user = User::where('id', $fragment->user_id)->select('id', 'country')->first();
            $users[$key] = $user;
        }

        return $users;
    }

    public function getUserData() {
        $projects = Project::all();

        $users = [];
        foreach ($projects as $key => $project) {
            $user = User::where('id', $project->user_id)->select('id', 'country')->first();
            $users[$key] = $user;
        }

        return $users;
    }

}
