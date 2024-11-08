<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WorksheetRecent extends Pivot
{
    protected $table = 'worksheet_recents';
    protected $fillable = ['user_id', 'worksheet_id'];
}
