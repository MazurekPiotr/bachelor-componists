<?php

namespace App\Listeners;

use Mail;
use App\Events\FragmentDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\FragmentDeleted as PostDeletedEmail;

class SendFragmentOwnerFragmentDeletedEmail implements ShouldQueue
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
     * @param  FragmentDeleted  $event
     * @return void
     */
    public function handle(FragmentDeleted $event)
    {
        Mail::to($fragment->user->email)->queue(new PostDeletedEmail($fragment));
    }
}
