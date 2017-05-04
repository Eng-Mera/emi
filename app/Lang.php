<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lang'
    ];
    
    public $timestamps = false;
}
