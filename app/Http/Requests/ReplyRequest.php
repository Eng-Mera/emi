<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Reply;
use App\Restaurant;
use App\Review;
use App\Role;

class ReplyRequest extends Request
{
    protected $_rules = [
        'title' => 'required|min:15|max:60',
        'comment' => 'required|min:25',
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {

            $this->_object = Reply::findOrFail($request->route()->parameters()['reply']);
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(\Illuminate\Http\Request $request)
    {
        return $this->_rules;
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
        $method = $request->method();


        if($currentUser && ( $currentUser->hasRole(Role::SUPER_ADMIN) || $currentUser->hasRole(Role::RESTAURANT_MANAGER) ))
        {
            $review = Review::whereId($request->review)->firstOrFail();

            $restaurant = Restaurant::whereId($review->restaurant_id)->firstOrFail();

            $managers = $restaurant->managers->pluck('id')->toArray();

            $managers[] = $restaurant->owner_id;

            if (!in_array($currentUser->id, $managers)) {
                return false;
            }
        }

        return true;
    }
}
