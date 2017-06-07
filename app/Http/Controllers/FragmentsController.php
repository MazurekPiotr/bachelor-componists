<?php

namespace App\Http\Controllers;

use App\Fragment;
use App\Project;
use Auth;
use App\User;
use App\GetMentionedUsers;
use App\Events\FragmentDeleted;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe\DataMapping\Format;
use FFMpeg\Format\Audio\Mp3;
use FFMpeg\Format\Video\Ogg;
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

        $fileName = time() . '.' . $music_file->getClientOriginalExtension();

        $location = storage_path() . '/fragments/' . $project->slug . '/' . $request->user()->id;
        $music_file->move($location,$fileName);

        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => storage_path() . '/ff/ffmpeg',
            'ffprobe.binaries' => storage_path() . '/ff/ffprobe'
        ]);

        // Set an audio format$
        $audio = $ffmpeg->open( $location . '/' . $fileName );

        $audio_format = new FFMpeg/Format/Audio/Mp3();
        $audio->save($fileName . '.mp3');

        $audio_format = new FFMpeg/Format/Audio/Ogg();
        $audio->save($fileName . '.mp3');

        // Create the waveform
        $waveform = $audio->waveform();
        $waveform->save( $location . '/'.'waveform.png' );



        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $request->user()->id . '/' . $fileName, fopen($location .'/'. $fileName, 'r+'));
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $request->user()->id . '/waveform.png', fopen($location .'/waveform.png', 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->body = $request->fragmentText;
        $fragment->link = 'fragments/'. $project->id . '/' . $fileName;
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
