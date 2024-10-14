<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';
    protected $fillable = ['user_id', 'worksheet_id', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worksheet()
    {
        return $this->belongsTo(Worksheet::class);
    }
}
