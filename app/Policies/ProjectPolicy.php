<?php

namespace App\Policies;

use App\User;
use App\Fragment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function edit (User $user, Fragment $fragment)
    {
        return $user->id === $fragment->user_id || $user->isElevated();
    }

    public function update (User $user, Fragment $fragment)
    {
        return $user->id === $fragment->user_id || $user->isElevated();
    }

    public function delete (User $user, Fragment $fragment)
    {
        return $user->id === $fragment->user_id || $user->isElevated();
    }
}
