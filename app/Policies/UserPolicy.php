<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function list(User $user)
    {
        return $user->isAdmin();
    }

    public function get(User $user)
    {
        return $user->isAdmin();
    }

    public function delete(User $user)
    {
        return $user->isAdmin();
    }

    public function update(User $user, User $user2)
    {
        return ($user->isAdmin() || ($user->id == $user2->id));
    }

    public function resetPassword(User $user, User $user2)
    {
        return ($user->isAdmin() || ($user->id == $user2->id));
    }
}
