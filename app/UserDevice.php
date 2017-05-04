<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    /**
     * @const string
     */
    const DEVICE_IOS = 'IOS';

    /**
     * @const string
     */
    const DEVICE_ANDROID = 'ANDROID';

    /**
     * @var array
     */
    public $fillable = [
        'user_id',
        'device_id',
        'device_type'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(){
        return $this->belongsTo(User::class, 'foreign_key');
    }
}
