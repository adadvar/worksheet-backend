<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = [
        'type',
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

    const TYPE_WORKSHEET = 'worksheet';
    const TYPE_TOOLS = 'tools';
    const TYPE_HANDICRAFT = 'handicraft';
    const TYPES = [self::TYPE_WORKSHEET, self::TYPE_TOOLS, self::TYPE_HANDICRAFT];

    protected $appends = [
        'age',
        'views_count',
        'liked_count',
        'is_in_cart',
        'banner_link',
        'file_pdf_link',
        'file_word_link'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'file_pdf_link',
        'file_word_link'
    ];


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
        if ($this->isPaid() && $this->file_pdf && Storage::disk('products')->exists($this->file_pdf))
            return Storage::disk('products')->url($this->file_pdf);
        return null;
    }

    public function getFileWordLinkAttribute()
    {
        if ($this->isPaid() && $this->file_word && Storage::disk('products')->exists($this->file_word))
            return Storage::disk('products')->url($this->file_word);
        return null;
    }

    public function isPaid()
    {
        $user = auth('api')->user();

        if (empty($user)) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }
        if ($user) {
            $orderItem = OrderItem::where('product_id', $this->id)
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
            ->belongsToMany(User::class, 'product_views')
            ->withTimestamps();
    }

    public static function views($userId)
    {
        return static::where('products.user_id', $userId)
            ->join('product_views', 'products.id', '=', 'product_views.product_id');
    }

    public function getViewsCountAttribute()
    {
        return ProductView::where('product_id', $this->id)->count();
    }

    public function getLikedCountAttribute()
    {
        return ProductFavourite::where('product_id', $this->id)->count();
    }

    public function getIsInCartAttribute()
    {
        if (auth('api')->check() && auth('api')->user()->cart) {
            return $this->cartItems()
                ->where('cart_id', auth('api')->user()->cart->id)
                ->exists();
        }
        return false;
    }

    public function toArray()
    {
        $data = parent::toArray();
        // $data['banner_link'] = $this->banner_link;
        // $data['file_pdf_link'] = $this->file_pdf_link;
        // $data['file_word_link'] = $this->file_word_link;
        // $data['views_count'] = $this->views_count;
        // $data['is_in_cart'] = $this->is_in_cart;

        return $data;
    }
}
