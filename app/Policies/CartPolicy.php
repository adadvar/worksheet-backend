<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
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

    public function addToCart(User $user)
    {
        return $user->isUser();
    }

    public function removeFromCart(User $user)
    {
        return $user->isUser();
    }

    public function updateCartItem(User $user)
    {
        return $user->isUser();
    }

    public function checkout(User $user)
    {
        return $user->isUser();
    }
}
