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

        return response($cart->load('cartItems'));
    }

    public function create(CartCreateRequest $r)
    {
        try {
            DB::beginTransaction();

            $cart = $r->user()->cart;

            $existingCartItem = $cart->cartItems()->where('worksheet_id', $r->worksheet_id)->first();

            if ($existingCartItem) {
                $existingCartItem->update([
                    'price' => $r->price,
                ]);
            } else {
                $cart->cartItems()->create([
                    'worksheet_id' => $r->worksheet_id,
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
            $worksheet = $r->worksheet;
            $cartItem = CartItem::where([
                'cart_id' => $cart->id,
                'worksheet_id' => $worksheet->id,
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
