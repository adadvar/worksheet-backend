<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WorksheetView extends Pivot
{
    protected $table = 'worksheet_views';
    protected $fillable = ['user_id', 'user_ip', 'worksheet_id'];
}
