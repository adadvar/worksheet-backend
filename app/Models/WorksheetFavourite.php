<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class WorksheetFavourite extends Pivot
{
    protected $table = 'worksheet_favourites';
    protected $fillable = ['user_id', 'worksheet_id', 'user_ip'];
}
