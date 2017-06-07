<?php

namespace App\Http\Controllers;

use App\Events\ProjectDeleted;
use App\Http\Requests\CreateProjectFormRequest;
use App\Project;
use Auth;
use ClassPreloader\Config;
use Folour\Flavy\Flavy;
use Illuminate\Support\Facades\Storage;
use App\User;
use FFMpeg\FFMpeg as FFMpeg;
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
        $fileName = $timeName . $extension;
        $location = storage_path() . '/fragments/' . $project->slug;
        $music_file->move($location,$fileName);
        /*
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => storage_path() . '/ff/ffmpeg',
            'ffprobe.binaries' => storage_path() . '/ff/ffprobe',
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
        ]);

        $audio = $ffmpeg->open( $location . '/' . $fileName );

        $audio_format = new Mp3();
        $audio->save($audio, $fileName . '.mp3');

        $audio_format = new FFMpeg/Format/Audio/Ogg();
        $audio->save($fileName . '.mp3');
        // Create the waveform
        $waveform = $audio->waveform();
        $waveform_link = time() .'.png';
        $waveform->save( $location . '/' . $waveform_link );
        */
        $flavy = new Flavy([
            'ffmpeg_path' => storage_path() . '/ff/ffmpeg',
            'ffprobe_path' => storage_path() . '/ff/ffprobe',
        ]);

        $flavy->from($location . '/' . $fileName)
            ->to($location . '/' . $timeName . '.mp3')
            ->aBitrate(128)
            ->aCodec('libmp3lame')
            ->overwrite()
            ->run();

        $flavy->from($location . '/' . $fileName)
            ->to($location . '/' . $timeName . '.ogg')
            ->aBitrate(128)
            ->aCodec('libvorbis')
            ->overwrite()
            ->run();


        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $timeName . '.mp3', fopen($location . '/' . $timeName . '.mp3', 'r+'));
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $timeName . '.ogg', fopen($location . '/' . $timeName . '.ogg', 'r+'));

        //$disk->getDriver()->put('/fragments/'. $project->slug . '/'. $waveform_link .'png', fopen($location . '/' . $waveform_link, 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->body = $request->fragmentText;
        $fragment->link = $timeName;
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
