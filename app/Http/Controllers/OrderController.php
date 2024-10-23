<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderCreateRequest;
use App\Http\Requests\Order\OrderDeleteRequest;
use App\Http\Requests\Order\OrderListRequest;
use App\Http\Requests\Order\OrderShowRequest;
use App\Http\Requests\Order\OrderUpdateRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function list(OrderListRequest $r)
    {
        $orders = Order::with('orderItems')->get();
        return response()->json($orders);
    }

    public function create(OrderCreateRequest $request)
    {
        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $request->user_id,
                'total_price' => $request->total_price,
                'status' => $request->status,
            ]);

            $cart = Cart::where('user_id', $request->user_id)->first();
            if ($cart) {
                foreach ($cart->cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'worksheet_id' => $item->worksheet_id,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ]);
                }
                $cart->cartItems()->delete();
            }

            DB::commit();

            return response()->json($order, 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(OrderShowRequest $r)
    {
        $order = $r->order;
        return response()->json($order);
    }

    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(OrderUpdateRequest $r)
    {
        try {
            DB::beginTransaction();

            $order = $r->order;
            $order->update($r->only(['user_id', 'total_price', 'status']));

            if ($r->has('items')) {
                foreach ($r->items as $item) {
                    if (isset($item['id'])) {
                        $orderItem = OrderItem::findOrFail($item['id']);
                        $orderItem->update($item);
                    } else {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'worksheet_id' => $item['worksheet_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json($order);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    /**
     * Remove the specified order from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(OrderDeleteRequest $r)
    {
        try {
            $order = $r->order;
            $order->delete();

            return response()->json(['message' => 'سفارش با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'خطایی رخ داده است!'], 500);
        }
    }
}
