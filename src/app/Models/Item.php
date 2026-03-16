<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'brand',
        'description',
        'price',
        'condition',
        'image_path'
    ];

    /**
     * 出品ユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * カテゴリ
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * コメント
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * いいね
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * 購入情報
     */
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
