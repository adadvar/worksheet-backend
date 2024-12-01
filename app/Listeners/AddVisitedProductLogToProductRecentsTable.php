<?php

namespace App\Listeners;

use App\Events\VisitProduct;
use App\Models\ProductRecent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddVisitedProductLogToProductRecentsTable
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VisitProduct $event): void
    {
        if (auth('api')->check()) {
            try {
                $product = $event->getProduct();
                $userId = auth('api')->user()->id;
                $conditions = [
                    'user_id' => $userId,
                    'product_id' => $product->id,
                ];
                if (!ProductRecent::where($conditions)->count()) {
                    $productRecent = ProductRecent::where(['user_id' => $userId]);
                    if ($productRecent->count() > 30) {
                        $productRecent->first()->delete();
                    }
                    ProductRecent::create([
                        'user_id' => $userId,
                        'product_id' => $product->id,
                    ]);
                }
            } catch (Exception $exception) {
                Log::error($exception);
            }
        }
    }
}
