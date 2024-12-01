<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartCreateRequest;
use App\Http\Requests\Cart\CartDeleteRequest;
use App\Http\Requests\Cart\CartShowRequest;
use App\Http\Requests\Cart\CartUpdateRequest;
use App\Models\CartItem;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{

    public function current(CartShowRequest $r)
    {
        $user = $r->user();
        $cart = $user->cart ?? $user->cart()->create();
        $cart->load('cartItems.product.grade', 'cartItems.product.subject', 'cartItems.product.topic');
        $cart->cartItems->map(function ($cartItem) {
            $product = $cartItem->product;
            $cartItem->price = $product->price;
            $cartItem->save();
        });

        $cart->load('cartItems.product.grade', 'cartItems.product.subject', 'cartItems.product.topic');
        return response($cart);
    }

    public function create(CartCreateRequest $r)
    {
        try {
            DB::beginTransaction();

            $cart = $r->user()->cart;

            $existingCartItem = $cart->cartItems()->where('product_id', $r->product_id)->first();

            if ($existingCartItem) {
                $existingCartItem->update([
                    'price' => $r->price,
                ]);
            } else {
                $cart->cartItems()->create([
                    'product_id' => $r->product_id,
                    'prev_price' => $r->price,
                    'price' => $r->price,
                ]);
            }

            DB::commit();
            if ($existingCartItem)
                return response(['message' => 'کاربرگ در سبد خرید وجود دارد'], 500);
            if (!$existingCartItem)
                return response(['message' => 'با موفقیت به سبد خرید اضافه شد'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function update(CartUpdateRequest $r)
    {
        try {
            $cartItem = $r->cartItem;
            $cartItem->update($r->only(['price']));

            return response($cartItem);
        } catch (Exception $e) {
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function delete(CartDeleteRequest $r)
    {
        try {
            $cart = $r->user()->cart;
            $product = $r->product;
            $cartItem = CartItem::where([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
            ])->first();
            if ($cartItem) {
                $cartItem->delete();
                return response(['message' => 'آیتم سبد خرید با موفقیت حذف شد!'], 200);
            } else {
                return response(['message' => 'آیتم سبد خرید پیدا نشد!'], 404);
            }
        } catch (Exception $e) {
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }
}
