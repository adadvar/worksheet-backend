<?php

namespace App\Listeners;

use App\Events\VisitWorksheet;
use App\Models\WorksheetView;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddVisitedWorksheetLogToWorksheetViewsTable
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
    public function handle(VisitWorksheet $event): void
    {
        try {
            $worksheet = $event->getWorksheet();
            $conditions = [
                'user_id' => auth('api')->id(),
                'worksheet_id' => $worksheet->id,
                ['created_at', '>', now()->subDays(1)]
            ];
            $clientIp = client_ip();

            if (!auth('api')->check()) {
                $conditions['user_ip'] = $clientIp;
            }

            if (!WorksheetView::where($conditions)->count()) {
                WorksheetView::create([
                    'user_id' => auth('api')->id(),
                    'worksheet_id' => $worksheet->id,
                    'user_ip' => $clientIp
                ]);
            }
        } catch (Exception $exception) {
            Log::error($exception);
        }
    }
}
