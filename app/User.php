<?php

namespace App;

use Config;
use App\Message;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'avatar', 'role', 'password', 'last_activity',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function fragments()
    {
        return $this->hasManyThrough(Fragment::class, Project::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function isModerator()
    {
        return $this->role === 'moderator';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isElevated()
    {
        return $this->role === 'moderator' || $this->role === 'admin';
    }

    public function role()
    {
        return $this->role;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    protected function getUserSubscriptions()
    {
        return Subscription::where('user_id', $this->id)->get();
    }

    public function isSubscribedTo(Project $project)
    {
        // loop through all subscriptions for current user
        foreach ($this->getUserSubscriptions() as $subscription) {
            if ($subscription->project_id === $project->id) {
                // has a certain subscription, let's return it
                return $subscription;
            }
        }

        // no subscriptions at all..
        return null;
    }

    public function isRecipient (Message $message)
    {
        return $this->id === $message->recipient_id;
    }

    public function hasUnreadMessages ()
    {
        return count(Message::where('recipient_id', $this->id)->where('read', 0)->get()) > 0;
    }

    public function hasUnreadMessagesFromSender (User $user)
    {
        return count(Message::where('recipient_id', $this->id)->where('sender_id', $user->id)->where('read', 0)->get()) > 0;
    }

    public function receivedMessagesFromSender (User $user)
    {
        return Message::where('recipient_id', $this->id)->where('sender_id', $user->id)->where('read', 0)->get();
    }

    public function unreadMessageCount ()
    {
        return count(Message::where('recipient_id', $this->id)->where('read', 0)->get());
    }

    public function unreadMessageCountForSender (User $user)
    {
        return count(Message::where('recipient_id', $this->id)->where('sender_id', $user->id)->where('read', 0)->get());
    }

}
