<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\MenuItem;
use App\Restaurant;
use App\Role;
use Illuminate\Support\Facades\Route;

class MenuItemRequest extends Request
{

    protected static $_fields = [
        'slug',
        'image',
        'price',
        'popular_dish',
        'dish_category_id',
        'restaurant_id',
        'I18N'
    ];

    protected $_rules = [
        'slug' => 'required|string|unique:menu_items',
        'price' => 'required',
        'dish_category_id' => 'required|exists:dish_categories,id',
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


    protected function extendListValidation(\Illuminate\Http\Request $request, $currentUser)
    {

        if (@$currentUser && @$currentUser->hasRole(Role::SUPER_ADMIN)) {
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

    public static function removeParent($inputs)
    {
        foreach ($inputs['I18N'] as $key => $lang) {
            $inputs[$key] = $lang;
        }
        unset($inputs['I18N']);
        return $inputs;
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

                $slug = !empty($request->menu_item) ? $request->menu_item : Route::current()->parameters()['menu_item'];
                $menuItem = MenuItem::whereSlug($slug)->firstOrFail();
                $inputs = $request->all();

                foreach ($this->_rules as $ruleName => $ruleValidation) {

                    if (isset($inputs[$ruleName])) {
                        switch ($ruleName) {
                            case 'name':
                            case 'slug':
                                $ruleValidation = $ruleValidation . ',' . $ruleName . ',' . $menuItem->id;

                                $rules[$ruleName] = $ruleValidation;
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
