<?php

namespace App\Http\Controllers;

use App\Project;
use App\User;
use App\Subscription;
use App\Events\UserSubscribedToProject;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{

    public function getSubscriptionStatus (Request $request, Project $project)
    {
        $user = $request->user();
        $subscription = $user->isSubscribedTo($project);

        if ($subscription !== null) {
            // was already a subscription, send back the subscription status
            return $subscription->subscribed;
        }

        // was no subscription..
        return null;
    }

    public function handleSubscription (Request $request, Project $project)
    {
        $user = $request->user();
        $subscription = $user->isSubscribedTo($project);

        if ($subscription !== null) {
            // subscription exists, but can be either subscribed or unsubscribed
            if ($subscription->subscribed === 0) {
                $subscription->subscribed = 1;
            } else if ($subscription->subscribed === 1) {
                $subscription->subscribed = 0;
            }
        } else {
            // wasn't subscribed
            $subscription = new Subscription();
            $subscription->project_id = $project->id;
            $subscription->user_id = $user->id;
            $subscription->subscribed = 1;
        }
        $subscription->save();

        if ($subscription->subscribed === 1) {
            event(new UserSubscribedToProject($project));
        }

        return response()->json(null, 200);
    }
}
