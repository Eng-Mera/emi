<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\OpeningDay;
use App\Restaurant;
use App\Role;
use Illuminate\Support\Facades\Route;

class OpeningDayRequest extends Request
{
    protected $_rules = [
        'day_name' => 'required|day_week|unique:opening_days,day_name,[:id],id,restaurant_id,[:restaurant_id]',
        'from' => 'required|date_format:H:i',
        'to' => 'required|date_format:H:i',
//        'to' => 'required|date_format:H:i|time_range:from',
        'status' => 'boolean'
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        $id = @$request->opening_day;

        if ($id) {
            $this->_object = OpeningDay::find($id);
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
                $id = Route::current()->parameters()['opening_day'];
                $openingDay = OpeningDay::find($id);

                foreach ($this->_rules as $ruleName => $ruleValidation) {
                    if (isset($inputs[$ruleName]) && $ruleName == 'day_name') {
                        $rules[$ruleName] = str_replace('[:id]', $id, $ruleValidation);
                        $rules[$ruleName] = str_replace('[:restaurant_id]', $openingDay->restaurant_id, $rules[$ruleName]);
                    } elseif (isset($inputs[$ruleName])) {
                        $rules[$ruleName] = $ruleValidation;
                    }
                }

                break;

            case 'GET':
            case 'DELETE':
                $rules = [];
                break;

            case 'POST':
            default:
                $id = $request->restaurant ? $request->restaurant :  Route::current()->parameters()['restaurant'];
                $restaurant = Restaurant::whereSlug($id)->first();

                $rules = $this->_rules;
                $rules['day_name'] = 'required|day_week|unique:opening_days,day_name,0,,restaurant_id,' . $restaurant->id;
                break;
        }

        return $rules;
    }
}
