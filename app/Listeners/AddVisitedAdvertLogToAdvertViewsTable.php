<?php

namespace App\Listeners;

use App\Events\VisitAdvert;
use App\Models\AdvertView;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddVisitedAdvertLogToAdvertViewsTable
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
    public function handle(VisitAdvert $event): void
    {
        try {
            $advert = $event->getAdvert();
            $conditions = [
                'user_id' => auth('api')->id(),
                'advert_id' => $advert->id,
                ['created_at', '>', now()->subDays(1)]
            ];
            $clientIp = client_ip();

            if (!auth('api')->check()) {
                $conditions['user_ip'] = $clientIp;
            }

            if (!AdvertView::where($conditions)->count()) {
                AdvertView::create([
                    'user_id' => auth('api')->id(),
                    'advert_id' => $advert->id,
                    'user_ip' => $clientIp
                ]);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }
}
