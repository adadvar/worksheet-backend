<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductView extends Pivot
{
    protected $table = 'product_views';
    protected $fillable = ['user_id', 'user_ip', 'product_id'];
}
