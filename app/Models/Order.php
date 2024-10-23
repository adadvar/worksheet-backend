<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = ['user_id', 'total_price', 'status'];

    const TYPE_PENDING = 'pending';
    //TODO: add event paid and send email
    const TYPE_PAID = 'paid';
    const TYPE_REJECTED = 'rejected';
    const TYPE_CANCELLED = 'cancelled';
    const TYPES = [self::TYPE_PENDING, self::TYPE_PAID, self::TYPE_REJECTED, self::TYPE_CANCELLED];

    protected $with = ['orderItems'];

    public function worksheets()
    {
        return $this->hasMany(Worksheet::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
