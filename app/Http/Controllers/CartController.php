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

        return response()->json($cart->load('cartItems'));
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

                $cartItem = $existingCartItem;
            } else {
                $cartItem = $cart->cartItems()->create([
                    'worksheet_id' => $r->worksheet_id,
                    'price' => $r->price,
                ]);
            }

            DB::commit();

            return response()->json($cartItem, 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function update(CartUpdateRequest $r)
    {
        try {
            $cartItem = $r->cartItem;
            $cartItem->update($r->only(['price']));

            return response()->json($cartItem);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function delete(CartDeleteRequest $r)
    {
        try {
            $cartItem = $r->cartItem;
            $cartItem->delete();

            return response()->json(['message' => 'آیتم سبد خرید با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }
}
