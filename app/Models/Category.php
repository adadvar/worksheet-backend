<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    const TYPE_GRADE = 'grade';
    const TYPE_SUBJECT = 'subject';
    const TYPE_TOPIC = 'topic';
    const TYPES = [self::TYPE_GRADE, self::TYPE_SUBJECT, self::TYPE_TOPIC];

    protected $table = 'categories';
    protected $fillable = ['parent_id', 'name', 'type', 'slug', 'icon', 'banner'];

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function worksheets()
    {
        return $this->hasMany(Worksheet::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function child()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('child');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($category) {
            $category->child()->delete();
        });
    }

    static function extractChildrenIds($category)
    {
        $categoryIds = [$category->id];

        if ($category->child) {
            foreach ($category->child as $child) {
                $categoryIds = array_merge($categoryIds, self::extractChildrenIds($child));
            }
        }
        return $categoryIds;
    }
}
