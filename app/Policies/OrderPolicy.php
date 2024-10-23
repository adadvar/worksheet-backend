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
        return $user->isAdmin() || ($order && $user->id == $order->user_id);

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
        return $user->isAdmin() || ($order && $user->id == $order->user_id);

    }

    // public function like(User $user = null, Order $order = null)
    // {
    //     if ($order && $order->isAccepted()) {
    //         $conditions = [
    //             'advert_id' => $order->id,
    //             'user_id' => $user ? $user->id : null
    //         ];

    //         if (empty($user)) {
    //             $conditions['user_ip'] = client_ip();
    //         }
    //         return AdvertFavourite::where($conditions)->count() == 0;
    //     }

    //     return false;
    // }

    // public function unlike(User $user = null, Order $order = null)
    // {
    //     $conditions = [
    //         'Advert_id' => $order->id,
    //         'user_id' => $user ? $user->id : null
    //     ];

    //     if (empty($user)) {
    //         $conditions['user_ip'] = client_ip();
    //     }

    //     return AdvertFavourite::where($conditions)->count();
    // }

    // public function deleteFavourite(User $user = null, Order $order = null)
    // {
    //     return $user->favouriteAdverts->find($order->id);
    // }

    // public function deleteRecent(User $user = null, Order $order = null)
    // {
    //     return $user->recentAdverts->find($order->id);
    // }
}
