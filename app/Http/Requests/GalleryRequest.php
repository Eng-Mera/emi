<?php

namespace App\Http\Requests;

use App\Gallery;
use App\Http\Requests\Request;
use App\Restaurant;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Route;

class GalleryRequest extends Request
{


    protected static $_fields = [
        'slug',
        'image',
        'user_id',
        'restaurant_id',
        'I18N'
    ];

    protected $_rules = [
        'slug' => 'string|unique:galleries',
    ];

    public static function getFields()
    {
        return self::$_fields;
    }

    public static function removeParent($inputs)
    {
        foreach ( (array) $inputs['I18N'] as $key => $lang) {
            $inputs[$key] = $lang;
        }
        unset($inputs['I18N']);
        return $inputs;
    }

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        $slug = @$request->gallery;

        $method = $request->method();

        if ($slug && $method == 'UPDATE') {
            $this->_object = Gallery::whereSlug($slug)->firstOrFail();
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

        $action = (array)@explode('@', $this->route()->getAction()['uses']);

        if (!empty($action[1]) && $action[1] == 'update') {
            return true;
        }

        $restaurant = Restaurant::with('managers')->whereSlug($request->restaurant)->firstOrFail();

        $managers = $restaurant->managers->pluck('id')->toArray();

        $managers[] = $restaurant->owner_id;


        //@TODO check likes and dislikes
        if (!in_array($currentUser->id, $managers)) {
//            return false;
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

        if ($currentUser && !in_array($currentUser->id, $managers)) {
//            return false;
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
        $rules = [];

        switch ($this->method()) {
            case 'PATCH':

                $slug = !empty($request->gallery) ? $request->gallery : Route::current()->parameters()['gallery'];
                $menuItem = Gallery::whereSlug($slug)->firstOrFail();
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
