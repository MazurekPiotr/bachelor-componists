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
        $extension = $music_file->getClientOriginalExtension();
        if(strpos($timeName, '#') || strpos($timeName, ' ') || strpos($timeName, '#') || strpos($timeName, '#')){
            return view('componists.projects.project.fragments.fragment.edit', [
                'project' => $project,
                'fragment' => $fragment
            ]);
        }
        $time = time();
        $fileName = $timeName . '.' .  $extension;
        $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
        $music_file->move($location,$timeName);

        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $request->fragmentInstrument . '.mp3', fopen($location . '/' . $timeName, 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->time = $time;
        $url = env('APP_URL');
        $fragment->name = $request->fragmentInstrument;

        $fragment->save();

        event(new UserPostedOnProject($project, $fragment, $request->user()));

        return redirect()->route('componists.projects.project.show', [
            'project' => $project,
        ]);
    }

    public function edit (Request $request, Project $project, Fragment $fragment)
    {
        $this->authorize('edit', $fragment);

        return view('componists.projects.project.fragments.fragment.edit', [
            'project' => $project,
            'fragment' => $fragment,
        ]);
    }


    public function update (UpdateFragmentFormRequest $request, Project $project, Fragment $fragment)
    {
        $this->authorize('update', $fragment);

        $music_file = $request->file('fragmentSong');

        $timeName = $music_file->getClientOriginalName();
        $extension = $music_file->getClientOriginalExtension();
        if(strpos($timeName, '#') || strpos($timeName, ' ') || strpos($timeName, '#') || strpos($timeName, '#')){
            return view('componists.projects.project.fragments.fragment.edit', [
                'project' => $project,
                'fragment' => $fragment
            ]);
        }
        $time = time();
        $fileName = $timeName . '.' .  $extension;
        $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
        $music_file->move($location,$timeName);

        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $timeName . '.mp3', fopen($location . '/' . $timeName, 'r+'));

        $fragment->time = $time;
        $url = env('APP_URL');
        $fragment->name = $request->fragmentInstrument;

        $fragment->save();

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

    public function getVolume ($id)
    {
        $volume = Fragment::where('id', $id)->select('volume')->first();

        return response()->json($volume, 200);
    }

    public function setVolume ($id, $volume)
    {
        $fragment = Fragment::where('id', $id)->first();
        $fragment->volume = $volume;
        $fragment->save();

        return response()->json(null, 200);
    }

}
