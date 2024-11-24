<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderCreateRequest;
use App\Http\Requests\Order\OrderDeleteRequest;
use App\Http\Requests\Order\OrderListRequest;
use App\Http\Requests\Order\OrderMyRequest;
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
        $orders = Order::get();
        return response($orders);
    }

    public function current(Request $r)
    {
        $user = $r->user();
        $order = $user->orders()->where('status', Order::TYPE_PENDING)->first();

        if (!$order) {
            return response(['message' => 'سفارش فعلی یافت نشد!'], 404);
        }

        $cart = $user->cart;
        if ($cart && $cart->cartItems->isNotEmpty()) {
            $cartItems = $cart->cartItems->keyBy('worksheet_id');

            foreach ($order->orderItems as $orderItem) {
                if (isset($cartItems[$orderItem->worksheet_id])) {
                    $orderItem->price = $cartItems[$orderItem->worksheet_id]->price;
                    $orderItem->save();
                }
            }
        }

        $totalPrice = $order->orderItems->sum('price');
        $order->total_price = $totalPrice;
        $order->save();

        return response($order);
    }

    public function my(Request $r)
    {
        $user = $r->user();
        $order = $user->orders()->whereNot('status', Order::TYPE_PENDING)->with('orderItems.worksheet.grade', 'orderItems.worksheet.subject', 'orderItems.worksheet.topic')->get();

        return response($order);
    }

    public function createOrUpdate(OrderCreateRequest $r)
    {
        try {
            DB::beginTransaction();

            $user = $r->user();
            $cart = $user->cart;

            if (!$cart || $cart->cartItems->isEmpty()) {
                return response(['message' => 'سبد خرید خالی است!'], 400);
            }

            $order = $user->orders()->where('status', Order::TYPE_PENDING)->first();
            $totalPrice = $cart->cartItems->sum('price');

            if (!$order) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_price' => $totalPrice,
                    'status' => Order::TYPE_PENDING,
                ]);
            } else {
                $order->update([
                    'total_price' => $totalPrice,
                ]);
            }

            $order->orderItems()->delete();

            foreach ($cart->cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'worksheet_id' => $item->worksheet_id,
                    'price' => $item->price,
                ]);
            }

            if ($totalPrice == 0) {
                $cart->cartItems()->delete();

                // ارسال ایمیل به کاربر
                $this->sendOrderConfirmationEmail($order);

                return response(['message' => 'سفارش با موفقیت ایجاد شد و به صورت خودکار پرداخت شد'], 201);
            }

            DB::commit();

            return response(['message' => 'سفارش با موفقیت ایجاد شد'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است!'], 500);
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
        $order = $r->order->whereNot('status', Order::TYPE_PENDING)->with('orderItems.worksheet.grade', 'orderItems.worksheet.subject', 'orderItems.worksheet.topic')->first();
        return response($order);
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

            // فقط اجازه حذف سفارش با وضعیت pending
            if ($order->status !== Order::TYPE_PENDING) {
                return response(['message' => 'امکان حذف این سفارش وجود ندارد!'], 403);
            }

            $order->delete();

            return response(['message' => 'سفارش با موفقیت حذف شد!'], 200);
        } catch (Exception $e) {
            Log::error($e);
            return response(['message' => 'خطایی رخ داده است!'], 500);
        }
    }

    public function payment(Order $order)
    {
        // فقط اجازه پرداخت برای سفارش با وضعیت pending
        if ($order->status !== Order::TYPE_PENDING) {
            return response(['message' => 'امکان پرداخت این سفارش وجود ندارد!'], 403);
        }

        // اگر قیمت کل صفر است، نیازی به پرداخت نیست
        if ($order->total_price == 0) {
            $order->update(['status' => Order::TYPE_PAID]);

            // حذف سبد خرید پس از پرداخت موفق
            $cart = Cart::where('user_id', $order->user_id)->first();
            if ($cart) {
                $cart->cartItems()->delete();
            }

            // ارسال ایمیل به کاربر
            $this->sendOrderConfirmationEmail($order);

            return response(['message' => 'سفارش به صورت خودکار پرداخت شد!'], 200);
        }

        // ایجاد یک Invoice جدید
        // $invoice = (new Invoice)->amount($order->total_price);

        // ارسال Invoice به درگاه پرداخت
        // return Payment::callbackUrl(route('payment.callback'))->purchase(
        //     $invoice,
        //     function ($driver, $transactionId) use ($order) {
        //         // ذخیره transactionId در سفارش
        //         $order->update(['transaction_id' => $transactionId]);
        //     }
        // )->pay()->render();
    }

    public function callback(Request $r)
    {
        //   دریافت transactionId از درخواست
        $transactionId = $r->Authority;

        //   یافتن سفارش بر اساس transactionId
        $order = Order::where('transaction_id', $transactionId)->first();

        if (!$order) {
            return response(['message' => 'سفارش یافت نشد!'], 404);
        }

        //    تایید پرداخت
        // $receipt = Payment::amount($order->total_price)->transactionId($transactionId)->verify();

        //   تغییر وضعیت سفارش به paid
        $order->update(['status' => Order::TYPE_PAID]);

        //   حذف سبد خرید پس از پرداخت موفق
        $cart = Cart::where('user_id', $order->user_id)->first();
        if ($cart) {
            $cart->cartItems()->delete();
        }

        //    ارسال ایمیل به کاربر
        $this->sendOrderConfirmationEmail($order);

        return response(['message' => 'پرداخت با موفقیت انجام شد!'], 200);
    }

    private function sendOrderConfirmationEmail(Order $order)
    {
        //   ارسال ایمیل به کاربر
        //این قسمت بسته به استفاده از کتابخانه‌های ایمیل مختلف ممکن است متفاوت باشد
        // Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
    }
}
