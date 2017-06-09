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

class ProjectsController extends Controller
{

    public function index ()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();

        return view('componists.projects.index', [
            'projects' => $projects,
        ]);
    }

    public function show (Request $request, Project $project)
    {
        $fragments = $project->fragments()->get();

        return view('componists.projects.project.index', [
            'project' => $project,
            'fragments' => $fragments,
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

        $extension = $music_file->getClientOriginalExtension();
        $time = time();
        $fileName = $timeName . '.' .  $extension;
        $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
        $music_file->move($location,$fileName);


        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => storage_path() . '/ff/ffmpeg',
            'ffprobe.binaries' => storage_path() . '/ff/ffprobe',
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ]);

        $audio = $ffmpeg->open( $location . '/' . $fileName );

        $audio_format = new Mp3();
        $audio->save($audio_format, $location . '/' . $timeName . '.mp3');

        $audio_format = new Vorbis();
        $audio->save($audio_format, $location . '/' . $timeName . '.ogg');

        // Create the waveform
        $waveform = $audio->waveform();
        $waveform->save( $location . '/' . $timeName . '.png' );


        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.mp3', fopen($location . '/' . $timeName . '.mp3', 'r+'));
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.ogg', fopen($location . '/' . $timeName . '.ogg', 'r+'));

        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/'. $timeName .'png', fopen($location . '/' . $timeName . '.png', 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->body = $request->fragmentText;
        $fragment->link = $time . '/' . $timeName;
        $url = env('APP_URL');
        $fragment->body = preg_replace('/\@\w+/', "[\\0]($url/user/profile/\\0)", $request->fragmentText);

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
}
