<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductFavourite extends Pivot
{
    protected $table = 'product_favourites';
    protected $fillable = ['user_id', 'product_id', 'user_ip'];
}
