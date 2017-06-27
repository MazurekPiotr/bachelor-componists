<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserPostedOnProject' => [
            'App\Listeners\SendProjectSubscribersPostEmail',
        ],
        'App\Events\UserSubscribedToProject' => [
            'App\Listeners\SendProjectOwnerSubscriptionCreatedEmail',
        ],
        'App\Events\ProjectReported' => [
            'App\Listeners\SendModeratorsProjectReportedEmail',
        ],
        'App\Events\FragmentReported' => [
            'App\Listeners\SendModeratorsFragmentReportedEmail',
        ],
        'App\Events\FragmentDeleted' => [
            'App\Listeners\SendFragmentOwnerFragmentDeletedEmail',
        ],
        'App\Events\UserRoleModified' => [
            'App\Listeners\SendUserRoleModifiedEmail',
        ],
        'App\Events\UsersMentioned' => [
            'App\Listeners\SendUsersMentionedEmail',
        ],
        'App\Events\UserHasViewedMessagesFromSender' => [
            'App\Listeners\UpdateReadFlagOnMessagesForGivenSender',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
