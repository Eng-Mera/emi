<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * Fillable fields for a Profile
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'mobile', 'address', 'qualification', 'current_employee', 'current_position', 'previous_employee', 'previous_position', 'experience_years', 'current_salary', 'expected_salary'
    ];

    protected $hidden = [
        'qualification', 'current_employee', 'current_position', 'previous_employee', 'previous_position', 'experience_years', 'current_salary', 'expected_salary'
    ];

    /**
     * A profile belongs to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo('User');
    }
}
