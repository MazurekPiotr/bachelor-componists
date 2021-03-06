<?php
namespace App\Http\Controllers;

use App\Events\ProjectDeleted;
use App\Http\Requests\CreateProjectFormRequest;
use App\Http\Requests\CreatePostRequest;
use App\Project;
use App\Post;
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
        $projects = Project::orderBy('created_at', 'desc')->take(3)->get();

        return view('welcome', [
            'projects' => $projects,
        ]);
    }

    public function index ()
    {
        $projects = Project::orderBy('created_at', 'desc')->get();

        return view('componists.projects.index', [
            'projects' => $projects,
            'search' => ''
        ]);
    }

    public function search (Request $request)
    {
        $projects = Project::where("title", "LIKE","%$request->keyword%")
            ->orWhere("description", "LIKE", "%$request->keyword%")->get();

        return view('componists.projects.index', [
            'projects' => $projects,
            'search' => $request->keyword
        ]);
    }

    public function show (Request $request, Project $project)
    {
        $fragments = Fragment::where('project_id', $project->id)->get();
        $posts = Post::where('project_id', $project->id)->get();

        $users = [];
        foreach ($fragments as $key => $fragment) {
            $user = User::where('id', $fragment->user_id)->first();
            $users[$key] = $user;
        }

        return view('componists.projects.project.index', [
            'project' => $project,
            'fragments' => $fragments,
            'posts' => $posts,
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
        $fragment = new Fragment();

        $music_file = $request->file('fragmentSong');

        $timeName = $music_file->getClientOriginalName();

        $project->user_id = $request->user()->id;

        $project->description = $request->description;
        $project->slug = str_slug(mb_strimwidth($request->title, 0, 255), '-');
        $project->title = $request->title;
        $project->settings = false;
        $project->save();

        $extension = $music_file->getClientOriginalExtension();
        $time = time();
        $location = storage_path() . '/fragments/' . $project->slug . '/' . $time;
        $music_file->move($location,$timeName);

        $disk = Storage::disk('s3');
        $disk->getDriver()->put('/fragments/'. $project->slug . '/' . $time . '/' . $request->fragmentInstrument . '.mp3' , fopen($location . '/' . $timeName, 'r+'));

        $fragment->project_id = $project->id;
        $fragment->user_id = $request->user()->id;
        $fragment->time = $time;
        $fragment->volume = 1;
        $fragment->settings = null;
        $url = env('APP_URL');
        $fragment->name = $request->fragmentInstrument;
        $fragment->save();

        $subscription = new Subscription();
        $subscription->project_id = $project->id;
        $subscription->user_id = $request->user()->id;
        $subscription->subscribed = ($request->subscribe === null ? 0 : 1);
        $subscription->save();

        unlink($location . '/' . $timeName);

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

    public function setSettings(Request $request, $projectId) {
      $project = Project::where('id', $projectId)->first();

      $fragments = Fragment::where('project_id', $projectId)->get();

      $settings = json_decode($request->request->get('settings'));

      foreach ($fragments as $key => $fragment) {
          $fragment->settings = json_encode($settings[$key]);
          $fragment->save();
      }

      $project->settings = true;
    }

    public function getSavedFragmentSlugsFromProject($projectId, $userId) {
        $project = Project::where('id', $projectId)->first();

        $fragments = Fragment::where('project_id', $projectId)->get();

        $s3 = \Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+5 seconds";
        $response = [];
        foreach ($fragments as $key => $fragment) {
            $command = $client->getCommand('GetObject', [
                'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
                'Key'    => 'fragments/' . $project->slug . '/' . $fragment->time . '/' . $fragment->name . '.mp3'
            ]);

            $request = $client->createPresignedRequest($command, $expiry);
            $src = (string) $request->getUri();
            if($fragment->settings == null) {
              if($project->user_id == $userId){
              $valuesToPush = [ 'gain' => $fragment->volume, 'src' => $src, 'name' => $fragment->name];
              array_push($response, $valuesToPush);
              }
            }
            else {
              $settings = json_decode($fragment->settings);
              $settings->src = $src;
              array_push($response, $settings);
            }
        }
        dd($response);
        return $response;
    }

    public function getFragmentSlugsFromProject($projectId, $userId) {
        $project = Project::where('id', $projectId)->first();

        $fragments = Fragment::where('project_id', $projectId)->get();

        $s3 = \Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+10 seconds";
        $response = [];
        foreach ($fragments as $key => $fragment) {
            $command = $client->getCommand('GetObject', [
                'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
                'Key'    => 'fragments/' . $project->slug . '/' . $fragment->time . '/' . $fragment->name . '.mp3'
            ]);

            $request = $client->createPresignedRequest($command, $expiry);
            $src = (string) $request->getUri();
            if($fragment->settings == null) {
              if($project->user_id == $userId) {
                $valuesToPush = [ 'gain' => $fragment->volume, 'src' => $src, 'name' => $fragment->name];
                array_push($response, $valuesToPush);
              }
            }
            else {
              $settings = json_decode($fragment->settings);
              $settings->src = $src;
              array_push($response, $settings);
            }
        }

        return $response;
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

    public function addPost(CreatePostRequest $request) {

        $post = new Post();
        $post->body = $request->body;
        $post->project_id = $request->projectId;
        $post->user_id = $request->userId;
        $post->save();

        if(Auth::user()->imageURL != null) {
          $post->imgUrl = Auth::user()->imageURL;
        }
        else {
          $post->imgUrl = 'https://tracks-bachelor.s3.eu-west-2.amazonaws.com/avatars/no-avatar.png';
        }
        return $post;
    }

}
