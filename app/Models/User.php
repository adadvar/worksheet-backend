<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // const TYPE_ADMIN = 'admin';
    // const TYPE_TEACHER = 'teacher';
    // const TYPE_STUDENT = 'student';
    // const TYPE_PARENT = 'parent';
    // const TYPES = [self::TYPE_ADMIN, self::TYPE_TEACHER, self::TYPE_STUDENT, self::TYPE_PARENT];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mobile',
        'email',
        'name',
        'password',
        'google_id',
        // 'type',
        'avatar',
        'website',
        'city_id',
        'is_active',
        'verify_code',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'verify_code'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_at' => 'datetime',
    ];

    protected $with = ['roles'];

    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = to_valid_mobile_number($value);
    }

    // public function isAdmin()
    // {
    //     return $this->type === User::TYPE_ADMIN;
    // }

    // public function isUser()
    // {
    //     return $this->type === User::TYPE_TEACHER || $this->type === User::TYPE_STUDENT || $this->type === User::TYPE_PARENT;
    // }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isStudent()
    {
        return $this->hasRole('student');
    }

    public function isTeacher()
    {
        return $this->hasRole('teacher');
    }

    public function isParent()
    {
        return $this->hasRole('parent');
    }

    public function isUser()
    {
        return $this->hasRole('teacher') || $this->hasRole('student') || $this->hasRole('parent');
    }

    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function favouriteProducts()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductFavourite::class,
            'user_id', //product_favorites.user_id
            'id', //product.id
            'id', //user.id
            'product_id', //product_favorites.product_id
        );
    }

    public function recentProducts()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductRecent::class,
            'user_id', //product_favorites.user_id
            'id', //product.id
            'id', //user.id
            'product_id', //product_favorites.product_id
        );
    }

    public function views()
    {
        return $this
            ->belongsToMany(Product::class, 'product_views')
            ->withTimestamps();
    }
}
