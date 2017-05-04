<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['path', 'method'];

    public function routePermissions()
    {
        return $this->belongsToMany('\App\Permission');
    }

    public function routeRoles()
    {
        return $this->belongsToMany('\App\Role');
    }
}
