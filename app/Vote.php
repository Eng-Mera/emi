<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'vote_up', 'vote_down', 'file_id', 'user_id'
    ];

    function file()
    {
        return $this->belongsTo('\App\File');
    }

}
