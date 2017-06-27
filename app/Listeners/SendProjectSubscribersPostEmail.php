<?php

namespace App\Listeners;

use Mail;
use App\Mail\UserPostedOnProject as UserPostedOnProjectEmail;
use App\Events\UserPostedOnProject;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendProjectSubscribersPostEmail implements ShouldQueue
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
     * @param  UserPostedOnProject  $event
     * @return void
     */
    public function handle(UserPostedOnProject $event)
    {
        // get subscriptions where a subscription both exists and has a subscribed field of 1
        $subscriptions = $event->project->subscriptions()->where('subscribed', 1)->get();
        $current_user = $event->user;
        if (count($subscriptions)) {
            foreach ($subscriptions as $subscription) {
                $user = $subscription->user()->first();
                if ($user->id !== $current_user->id) {
                    // only send an email to a user if they are subscribed AND are not the current user
                    Mail::to($user)->queue(new UserPostedOnProjectEmail($event->project, $event->fragment));
                }
            }
        }
    }
}
