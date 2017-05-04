<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileMeta extends Model
{

    public $table = 'files_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description'
    ];

    function file(){
        return $this->belongsTo('\App\File');
    }

}
