<?php

namespace App\Listeners;

use App\Events\VisitProduct;
use App\Models\ProductView;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddVisitedProductLogToProductViewsTable
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
        try {
            $product = $event->getProduct();
            $conditions = [
                'user_id' => auth('api')->id(),
                'product_id' => $product->id,
                ['created_at', '>', now()->subDays(1)]
            ];
            $clientIp = client_ip();

            if (!auth('api')->check()) {
                $conditions['user_ip'] = $clientIp;
            }

            if (!ProductView::where($conditions)->count()) {
                ProductView::create([
                    'user_id' => auth('api')->id(),
                    'product_id' => $product->id,
                    'user_ip' => $clientIp
                ]);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }
}
