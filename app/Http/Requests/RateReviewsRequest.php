<?php

namespace App\Http\Requests;


use App\Restaurant;
use App\Review;
use App\Role;

class RateReviewsRequest extends Request
{
    protected $_rules = [
        'title' => 'required|min:15|max:60',
        'description' => 'required|min:100',
        'last_visit_date' => 'required|date|days_not_exceed:90',
        'seen' => 'boolean',
        'rate_value' => 'required|array|numeric_array:1,5|array_count:13',
        'type' => 'required|array|numeric_array:1,13|array_count:13',
        'seen' => 'required|boolean'
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        $id = @$request->rate;

        if ($id) {
            $this->_object = Review::find($id);
        }

        return true;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        if ($currentUser && $currentUser->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        if ($request->method() == 'GET' || $request->method() == 'POST') {
            return true;
        }

        $restaurant = Restaurant::whereSlug($request->restaurant)->firstOrFail();

        $managers = $restaurant->managers->pluck('id')->toArray();

        $managers[] = $restaurant->owner_id;

        if (!in_array($currentUser->id, $managers)) {
            return false;
        }

        return true;
    }


    protected function extendListValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        if ($currentUser && $currentUser->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        $restaurant = Restaurant::whereSlug($request->restaurant)->firstOrFail();

        $managers = $restaurant->managers->pluck('id')->toArray();

        $managers[] = $restaurant->owner_id;

//        if (!in_array($currentUser->id, $managers)) {
//            return false;
//        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(\Illuminate\Http\Request $request)
    {
        $rules = [];

        switch ($this->method()) {
            case 'PATCH':
            case 'PUT':

                $inputs = $request->all();

                foreach ($this->_rules as $ruleName => $ruleValidation) {

                    if (isset($inputs[$ruleName])) {
                        switch ($ruleName) {
                            case 'rate_value':
                                $rules[$ruleName] = 'required|array|numeric_array:1,5';
                                break;
                            case 'type':
                                $rules[$ruleName] = 'required|array|numeric_array:1,13';
                                break;
                            default:
                                $rules[$ruleName] = $ruleValidation;
                                break;
                        }
                    }
                }
                break;

            case 'GET':
            case 'DELETE':
                $rules = [];
                break;
            case 'POST':
            default:
                $rules = $this->_rules;
                break;
        }
        return $rules;
    }
}
