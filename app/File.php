<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;

class File extends Model
{

    protected $appends = ['image_url', 'number_of_comments'];

    public static $category = [
        'user_profile_picture' => ['alias' => 'user_profile_picture'],
        'restaurant_logo' => ['alias' => 'restaurant_logo'],
        'restaurant_featured' => ['alias' => 'restaurant_featured'],
        'restaurant_gallery' => ['alias' => 'restaurant_gallery'],
        'restaurant_menu_item' => ['alias' => 'restaurant_menu_item'],
        'movie_poster' => ['alias' => 'movie_poster'],
        'movie_featured_image' => ['alias' => 'movie_featured_image'],
        'admin_reviews' => ['alias' => 'admin_reviews'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename', 'mime', 'original_filename', 'imageable_id', 'imageable_type', 'user_id', 'category'
    ];

    public function getImageUrlAttribute()
    {
        return url(env('APP_URL') . '/file/' . $this->attributes['filename']);
    }

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * Number of comments per file
     *
     * @return mixed
     */
    public function getNumberOfCommentsAttribute()
    {
        return PhotoComment::whereFileId($this->id)->count();
    }

    public static function getCategorySlug($slug)
    {
        if (!empty(static::$category[$slug])) {
            return static::$category[$slug]['alias'];
        }

        return false;
    }

    public function meta()
    {
        return $this->hasOne('\App\FileMeta');
    }

    public function likesDislikes()
    {
        return $this->hasMany('\App\ImagesSocial');
    }

    public function votes()
    {
        return $this->hasMany('\App\Vote');
    }

    public function comments()
    {
        return $this->hasMany('\App\PhotoComment');
    }

    public function scopeObjectType($query, $type)
    {
        return $query->where([
            'imageable_type' => $type
        ]);
    }
}
