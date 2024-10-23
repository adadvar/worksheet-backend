<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
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

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isUser();
    }

    public function update(User $user, Order $order = null)
    {
        return $user->isAdmin();
    }

    public function list(User $user, Order $order = null)
    {
        return $user->isAdmin() || ($order && $user->id == $order->user_id);
    }

    public function show(User $user, Order $order = null)
    {
        return $user->isAdmin() || ($order && $user->id == $order->user_id);
    }


    public function changeState(User $user, Order $order)
    {
        return $user->isAdmin();
    }


    public function delete(User $user, Order $order = null)
    {
        return $user->isAdmin();
    }
}
