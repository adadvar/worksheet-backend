<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductFavourite;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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
        return $user->isAdmin();
    }

    public function update(User $user, Product $product = null)
    {
        // return ($user->isAdmin() || ($user->id == $product->user_id));
        return $user->isAdmin();
    }

    public function changeState(User $user, Product $product)
    {
        return $user->isAdmin();
    }


    public function delete(User $user, Product $product = null)
    {
        // return ($user->isAdmin() || ($user->id == $product->user_id));
        return $user->isAdmin();
    }

    public function like(User $user = null, Product $product = null)
    {
        // if ($product && $product->isAccepted()) {
        if ($product) {
            $conditions = [
                'product_id' => $product->id,
                'user_id' => $user ? $user->id : null
            ];

            if (empty($user)) {
                $conditions['user_ip'] = client_ip();
            }
            return ProductFavourite::where($conditions)->count() == 0;
        }

        // return $this->deny('شما مجاز به این کار نیستید');
        return false;
    }

    public function unlike(User $user = null, Product $product = null)
    {
        $conditions = [
            'Product_id' => $product->id,
            'user_id' => $user ? $user->id : null
        ];

        if (empty($user)) {
            $conditions['user_ip'] = client_ip();
        }

        return ProductFavourite::where($conditions)->count();
    }

    public function download(User $user, Product $product)
    {
        // if (!$user) {
        //     return false;
        // }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user) {
            $orderItem = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('status', Order::TYPE_PAID);
                })
                ->first();

            return $orderItem !== null;
        }
        return false;
    }

    // public function deleteFavourite(User $user = null, Product $product = null)
    // {
    //     return $user->favouriteProducts->find($product->id);
    // }

    // public function deleteRecent(User $user = null, Product $product = null)
    // {
    //     return $user->recentProducts->find($product->id);
    // }
}
