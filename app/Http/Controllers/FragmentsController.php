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
        $time = time();
        $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
        $music_file->move($location,$timeName);

        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $request->fragmentInstrument . '.mp3' , fopen($location . '/' . $timeName, 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->time = $time;
        $url = env('APP_URL');
        $fragment->settings= null;
        $fragment->name = $request->fragmentInstrument;
        $fragment->save();
        $project->settings = false;
        $project->save();
        event(new UserPostedOnProject($project, $fragment, $request->user()));
        $request->session()->flash('status', 'You added a new track! Awesome! Wait for the head componist of this project to accept your track!');

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


    public function update (CreateFragmentFormRequest $request, Project $project, Fragment $fragment)
    {
        $this->authorize('update', $fragment);

          $music_file = $request->file('fragmentSong');

          $timeName = $music_file->getClientOriginalName();

          $extension = $music_file->getClientOriginalExtension();
          $time = time();
          $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
          $music_file->move($location,$timeName);

          $disk = Storage::disk('s3');
          $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $fragment->name . '.mp3' , fopen($location . '/' . $timeName, 'r+'));

          $fragment->project_id = $project->id;
          $fragment->user_id = $request->user()->id;
          $fragment->time = $time;
          $url = env('APP_URL');
          $fragment->save();

          $request->session()->flash('status', 'Your track has been updated!');

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

    public function setVolume (Request $request, $id, $volume)
    {
      var_dump($request);
        $fragment = Fragment::where('id', $id)->first();
        $fragment->volume = $request;
        $fragment->save();

        return response()->json(null, 200);
    }

}
