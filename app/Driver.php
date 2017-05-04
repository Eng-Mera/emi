<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = [
        'name',
        'username' ,
        'password' ,
        'phone' ,
        'email' ,
        'address' ,
        'about' ,
        'age',
        'gender' ,
        'accept_gender' ,
        'national_id',
        'national_id_image' ,
        'driver_license_id' ,
        'driver_license_image',
        'car_license_id' ,
        'car_license_image'
    ];
    
    protected $hidden = [
        'password',
    ];
}
