<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller {

    public function list(Request $request)
    {
        $cart = $request->user()->cart;
        return response()->json($cart->load('cartItems'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'worksheet_id' => 'required|exists:worksheets,id',
            'quantity' => 'nullable|integer',
            'price' => 'nullable|numeric',
        ]);

        try {
            DB::beginTransaction();

            $cart = $request->user()->cart ?? $request->user()->cart()->create();

            $cartItem = $cart->cartItems()->create([
                'worksheet_id' => $request->worksheet_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
            ]);

            DB::commit();

            return response()->json($cartItem, 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'nullable|integer',
            'price' => 'nullable|numeric',
        ]);

        try {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->update($request->only(['quantity', 'price']));

            return response()->json($cartItem);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function delete($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);
            $cartItem->delete();

            return response()->json(['message' => 'آیتم سبد خرید با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }
}
