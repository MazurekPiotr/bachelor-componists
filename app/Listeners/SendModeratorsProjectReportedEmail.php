<?php

namespace App\Listeners;

use Mail;
use App\User;
use App\Events\ProjectReported;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ProjectReported as ProjectReportedEmail;

class SendModeratorsProjectReportedEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProjectReported  $event
     * @return void
     */
    public function handle(ProjectReported $event)
    {
        $moderators = User::where('role', 'moderator')->get();
        $admin = User::where('role', 'admin')->first();
        $user = $event->user;
        if ($admin) {
            Mail::to($admin)->queue(new ProjectReportedEmail($event->project));
        }

        if (count($moderators)) {
            foreach ($moderators as $moderator) {
                if ($moderator->id !== $user->id) {
                    // only send email notification to moderator if they aren't the user who 'reported' the content
                    Mail::to($moderator)->queue(new ProjectReportedEmail($event->project));
                }
            }
        }
    }
}
