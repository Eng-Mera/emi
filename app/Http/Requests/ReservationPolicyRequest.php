<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Restaurant;

class ReservationPolicyRequest extends Request
{
    protected static $_fields = ['user_id', 'restaurant_id', 'name', 'start_date', 'end_date', 'status', 'amount'];

    protected $_rules = [
        'name' => 'required',
        'start_date' => 'required:date|date_format:Y-m-d',
        'end_date' => 'required|date|date_format:Y-m-d|after:start_date|date_range_exist:start_date,reservation_policies,restaurant_id',
        'status' => 'required|boolean',
        'amount' => 'required:float'
    ];

    public static function getFields()
    {
        return self::$_fields;
    }

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        $slug = $request->menu_item;

        if ($slug) {
            $this->_object = MenuItem::whereSlug($slug)->firstOrFail();
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
        return true;
    }


    protected function extendListValidation(\Illuminate\Http\Request $request, $currentUser)
    {
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

        $inputs = $request->all();

        $restaurant = Restaurant::whereSlug($request->restaurant)->firstOrFail();
        switch ($request->method()) {
            case 'PUT':
            case 'PATCH':
                $id = $request->reservation_policy;

                foreach ($this->_rules as $ruleName => $ruleValidation) {

                    if (isset($inputs[$ruleName])) {
                        switch ($ruleName) {
                            case 'end_date':
                                $ruleValidation = $ruleValidation . ',' . $restaurant->id.','.$id;

                                $rules[$ruleName] = $ruleValidation;
                                break;

                            default:
                                $rules[$ruleName] = $ruleValidation;
                                break;
                        }
                    }
                }
                break;

            case 'DELETE':
            case 'GET':
                $rules = [];
                break;
            case 'POST':
            default     :
                $rules = $this->_rules;
                $rules['end_date'] = $rules['end_date'] . ',' . $restaurant->id;
                break;
        }

        return $rules;
    }

}
