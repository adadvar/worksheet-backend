<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Worksheet extends Model
{
    use HasFactory;

    protected $table = 'worksheets';
    protected $fillable = [
        'grade_id',
        'subject_id',
        'topic_id', 
        'name',
        'slug',
        'description',
        'price',
        'banner',
        'file',
        'publish_at'];

    protected $appends = ['age'];

    // protected $casts = [
    // 'banners' => 'array',
    // ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function grade()
    {
        return $this->belongsTo(Category::class, 'grade_id');
    }

    public function subject()
    {
        return $this->belongsTo(Category::class, 'subject_id');
    }

    public function topic()
    {
        return $this->belongsTo(Category::class, 'topic_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function getAgeAttribute()
    {
        $diff = $this->created_at->diffForHumans(null, true, true, 2);
        return $diff;
    }

    public function getBannerLinkAttribute()
    {
        return Storage::disk('worksheets')->url($this->banner);
    }

    public function getFileLinkAttribute()
    {
        return Storage::disk('worksheets')->url($this->file);
    }
    

    public function toArray()
    {
        $data = parent::toArray();
        $data['banner_link'] = $this->banner_link;
        $data['file_link'] = $this->file_link;
        
        return $data;
    }
}
