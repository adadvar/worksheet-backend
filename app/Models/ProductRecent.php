<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductRecent extends Pivot
{
    protected $table = 'product_recents';
    protected $fillable = ['user_id', 'product_id'];
}
