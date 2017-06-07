<?php

namespace App\Listeners;

use App\Events\ProjectDeleted;
use Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectOwnerProjectDeletedEmail implements ShouldQueue
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

    public function handle(ProjectDeleted $event)
    {
        Mail::to($event->project->user->email)->queue(new ProjectDeleted($event->project));
    }
}
