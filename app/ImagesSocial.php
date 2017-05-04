<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagesSocial extends Model
{
    public $table = 'images_social';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'file_id', 'likes', 'dislikes', 'shares'
    ];

    public function file()
    {
        return $this->belongsTo('\App\File');
    }

    public function user()
    {
        return $this->belongsTo('\App\User');
    }

}