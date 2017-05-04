<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'rider_id',
        'driver_id',
        'start_place',
        'destination',
        'cost',
        'status',
        'start_date',
        'end_date',
    ];
}
