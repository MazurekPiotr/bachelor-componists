<?php

namespace App\Listeners;

use Mail;
use App\Mail\UserSubscribedToProject as UserSubscribedToProjectEmail;
use App\Events\UserSubscribedToProject;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectOwnerSubscriptionCreatedEmail implements ShouldQueue
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
     * @param  UserSubscribedToProject  $event
     * @return void
     */
    public function handle(UserSubscribedToProject $event)
    {
        Mail::to($event->project->user()->first())->queue(new UserSubscribedToProjectEmail($event->project));
    }
}
