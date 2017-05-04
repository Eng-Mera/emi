<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FavoriteRestaurantsRequest extends Request
{
    protected $_rules = [
        'restaurant_id' => 'required|array',
    ];


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(\Illuminate\Http\Request $request)
    {
        return $this->_rules;
    }

    public function setObject()
    {
        return true;
    }

    /**
     * Extend these method in your Form Request class if you need to apply more validation in CRUD actions.
     *
     * @param \Illuminate\Http\Request $request
     * @param $currentUser
     * @return bool
     */
    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {

        return true;
    }

}
