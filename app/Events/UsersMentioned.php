<?php

namespace App\Events;

use App\Fragment;
use App\Project;
use Illuminate\Support\Collection;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UsersMentioned
{
    use InteractsWithSockets, SerializesModels;

    public $users;
    public $project;
    public $fragment;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Collection $users, Project $project, Fragment $fragment)
    {
        $this->users = $users;
        $this->project = $project;
        $this->fragment = $fragment;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
