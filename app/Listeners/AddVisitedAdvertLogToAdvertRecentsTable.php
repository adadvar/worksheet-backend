<?php

namespace App\Listeners;

use App\Events\VisitAdvert;
use App\Models\AdvertRecent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddVisitedAdvertLogToAdvertRecentsTable
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
        if (auth('api')->check()) {
            try {
                $advert = $event->getAdvert();
                $userId = auth('api')->user()->id;
                $conditions = [
                    'user_id' => $userId,
                    'advert_id' => $advert->id,
                ];
                if (!AdvertRecent::where($conditions)->count()) {
                    $advertRecent = AdvertRecent::where(['user_id' => $userId]);
                    if ($advertRecent->count() > 30) {
                        $advertRecent->first()->delete();
                    }
                    AdvertRecent::create([
                        'user_id' => $userId,
                        'advert_id' => $advert->id,
                    ]);
                }
            } catch (Exception $exception) {
                Log::error($exception);
            }
        }
    }
}
