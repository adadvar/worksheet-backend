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
        'file_word',
        'file_pdf',
        'publish_at'
    ];

    protected $appends = ['age', 'views_count'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    // protected $hidden = [
    //     'file_word',
    //     'file_pdf'
    // ];


    // protected $casts = [
    // 'banners' => 'array',
    // ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

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
        return Storage::disk('banners')->url($this->banner);
    }

    public function getFilePdfLinkAttribute()
    {
        if ($this->isPaid())
            return Storage::disk('worksheets')->url($this->file_pdf);
        return null;
    }

    public function getFileWordLinkAttribute()
    {
        if ($this->isPaid())
            return Storage::disk('worksheets')->url($this->file_word);
        return null;
    }

    public function isPaid()
    {
        $user = auth('api')->user();
        if ($user) {
            $orderItem = OrderItem::where('worksheet_id', $this->id)
                ->whereHas('order', function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                        ->where('status', Order::TYPE_PAID);
                })
                ->first();

            return $orderItem !== null;
        }
        return false;
    }

    public function viewers()
    {
        return $this
            ->belongsToMany(User::class, 'worksheet_views')
            ->withTimestamps();
    }

    public static function views($userId)
    {
        return static::where('worksheets.user_id', $userId)
            ->join('worksheet_views', 'worksheets.id', '=', 'worksheet_views.worksheet_id');
    }

    public function getViewsCountAttribute()
    {
        return WorksheetView::where('worksheet_id', $this->id)->count();
    }

    // public function getIsInCartAttribute()
    // {
    //     return $this->cartItems()
    //         ->where('cart_id', auth()->user()->cart->id)
    //         ->exists();
    // }

    public function toArray()
    {
        $data = parent::toArray();
        $data['banner_link'] = $this->banner_link;
        $data['file_pdf_link'] = $this->file_pdf_link;
        $data['file_word_link'] = $this->file_word_link;
        // $data['views_count'] = $this->views_count;
        // $data['is_in_cart'] = $this->is_in_cart;

        return $data;
    }
}
