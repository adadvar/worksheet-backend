<?php

namespace App\Listeners;

use App\Events\VisitWorksheet;
use App\Models\WorksheetRecent;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddVisitedWorksheetLogToWorksheetRecentsTable
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
        if (auth('api')->check()) {
            try {
                $worksheet = $event->getWorksheet();
                $userId = auth('api')->user()->id;
                $conditions = [
                    'user_id' => $userId,
                    'worksheet_id' => $worksheet->id,
                ];
                if (!WorksheetRecent::where($conditions)->count()) {
                    $worksheetRecent = WorksheetRecent::where(['user_id' => $userId]);
                    if ($worksheetRecent->count() > 30) {
                        $worksheetRecent->first()->delete();
                    }
                    WorksheetRecent::create([
                        'user_id' => $userId,
                        'worksheet_id' => $worksheet->id,
                    ]);
                }
            } catch (Exception $exception) {
                Log::error($exception);
            }
        }
    }
}
