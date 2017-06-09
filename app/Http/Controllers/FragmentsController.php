<?php

namespace App\Http\Controllers;

use App\Fragment;
use App\Project;
use Auth;
use App\User;
use App\GetMentionedUsers;
use Illuminate\Support\Facades\Storage;
use App\Events\FragmentDeleted;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe\DataMapping\Format;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Audio\Vorbis;
use FFMpeg\Media\Audio;
use Illuminate\Http\Request;
use App\Events\UsersMentioned;
use App\Events\UserPostedOnProject;
use Illuminate\Support\Collection;
use App\Http\Requests\CreateFragmentFormRequest;
use App\Http\Requests\UpdateFragmentFormRequest;

class FragmentsController extends Controller
{

    public function create (CreateFragmentFormRequest $request, Project $project)
    {
        $fragment = new Fragment();

        $music_file = $request->file('fragmentSong');

        $timeName = $music_file->getClientOriginalName();
        $time = time();
        $extension = $music_file->getClientOriginalExtension();
        $fileName = $timeName . $extension;
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
        $waveform_link = $timeName .'.png';
        $waveform->save( $location . '/' . $timeName . '.png' );

        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.mp3', fopen($location . '/' . $timeName . '.mp3', 'r+'));
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.ogg', fopen($location . '/' . $timeName . '.ogg', 'r+'));

        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/'. $timeName  .'.png', fopen($location . '/' . $timeName . '.png', 'r+'));

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

        event(new UserPostedOnProject($project, $fragment, $request->user()));

        return redirect()->route('componists.projects.project.show', [
            'project' => $project,
        ]);
    }

    public function edit (Request $request, Project $project, Fragment $fragment)
    {
        $this->authorize('edit', $fragment);

        return view('componists.projects.project.fragments.post.edit', [
            'project' => $project,
            'fragment' => $fragment,
        ]);
    }


    public function update (UpdateFragmentFormRequest $request, Project $project, Fragment $fragment)
    {
        $this->authorize('update', $fragment);

        $fragment->body = $request->fragmentText;
        $fragment->save();

        $mentioned_users = GetMentionedUsers::handle($request->fragmentText);

        if (count($mentioned_users)) {
            event(new UsersMentioned($mentioned_users, $project, $fragment));
        }

        return redirect()->route('componists.projects.project.show', [
            'project' => $project,
        ]);
    }

    public function destroy (Request $request, Project $project, Fragment $fragment)
    {
        $this->authorize('delete', $fragment);

        $fragment->delete();

        if ($fragment->user->id !== $request->user()->id) {
            event(new FragmentDeleted($fragment));
        }

        return redirect()->route('componists.projects.project.show', [
            'project' => $project,
        ]);
    }

}
