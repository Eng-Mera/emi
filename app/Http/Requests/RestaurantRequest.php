<?php

namespace App\Http\Requests;

use App\Permission;
use App\Restaurant;
use App\Http\Requests\Request;

use App\Role;
use App\User;
use Illuminate\Support\Facades\Route;

class RestaurantRequest extends Request
{

    protected static $_fields = [
        'slug',
        'address',
        'city_id',
        'latitude',
        'longitude',
        'phone',
        'email',
        'dress_code',
        'facebook',
        'twitter',
        'instagram',
        'snap_chat',
        'owner_id',
        'logo',
        'featured_image',
        'price_from',
        'price_to',
        'categories',
        'facilities',
        'type',
        'htr_stars',
        'managers',
        'I18N',
        'in_out_door',
        'allow_job_vacancies',
        'reservable_online',
        'is_trendy',
        'amount'
    ];

    protected $_rules = [
        'I18N.*.name' => 'required|min:1',
        'I18N.*.description' => 'string|min:20|max:2000',
        'I18N.*.address' => 'required',
        'owner_id' => 'unique:restaurants',
        'slug' => 'required|string|unique:restaurants|regex:/^[a-z][-a-z0-9]*$/',
        'address' => 'max:200',
        'city_id' => 'exists:cities,id',
        'latitude' => 'latitude',
        'longitude' => 'longitude',
        'phone' => 'required|unique:restaurants',
//        'phone' => 'required|min:11|max:11|unique:restaurants',
        'email' => 'required_if:allow_job_vacancies,1|required_if:reservable_online,1|email|unique:restaurants',
        'weather' => 'string|min:3',
        'facebook' => 'max:100',
        'twitter' => 'max:100',
        'instagram' => 'max:100',
        'snap_chat' => 'max:100',
        'logo' => 'base64',
        'featured_image' => 'base64',
        'price_from' => 'integer',
        'price_to' => 'integer',
        'type' => 'integer|between:1,4',
        'categories' => 'array|exists:categories,id',
        'facilities' => 'array',
        'htr_stars' => 'integer|between:1,3',
        'in_out_door' => 'integer|between:1,3',
        'managers' => 'array',
        'allow_job_vacancies' => 'boolean',
        'reservable_online' => 'boolean',
        'amount' => 'required_if:reservable_online,1|numeric|min:0',
        'is_trendy' => 'boolean'
    ];

    public function messages()
    {
        return [
            'I18N.*.name.required' => trans('Restaurant Name is Required.'),
            'I18N.*.address.required' => trans('Restaurant Address is Required.'),
            'I18N.*.description.required' => trans('The description field is required.'),

            'I18N.*.name.min' => trans('The restaurant name Must not be less than 1 characters.'),
            'I18N.*.description.min' => trans('The description may not be less than 20 characters.'),

            'I18N.*.restaurant_name.max' => trans('The restaurant name may not be greater than than 90 characters.'),
            'I18N.*.description.max' => trans('The description may not be greater than 2000 characters.'),

            'slug.regex' => trans('Slug Must be in English characters and Friendly to URL '),

        ];
    }

    public static function getFields()
    {
        return self::$_fields;
    }

    public static function removeParent($inputs)
    {
        foreach ((array)$inputs['I18N'] as $key => $lang) {
            $inputs[$key] = $lang;
        }
        unset($inputs['I18N']);
        return $inputs;
    }

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        $slug = @$request->restaurant;

        if ($slug) {
            $this->_object = Restaurant::with(['managers'])->whereSlug($slug)->firstOrFail();
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


        $managers = $this->_object->managers->pluck('id')->toArray();

        $managers[] = $this->_object->owner_id;

        if (!in_array($currentUser->id, $managers)) {
            return false;
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
            case 'PUT':
                $slug = !empty($request->restaurant) ? $request->restaurant : Route::current()->parameters()['restaurant'];
                $restaurant = Restaurant::whereSlug($slug)->firstOrFail();
                $inputs = $request->all();

                foreach ($this->_rules as $ruleName => $ruleValidation) {

                    if (isset($inputs[$ruleName])) {
                        switch ($ruleName) {
                            case 'owner_id':
                                $rules[$ruleName] = $ruleValidation . ',owner_id,' . $restaurant->id;
                                break;
                            case 'slug':
                                break;
                            case 'phone':
                            case 'email':
                                $ruleValidation = $ruleValidation . ',' . $ruleName . ',' . $restaurant->id;
                                $rules[$ruleName] = $ruleValidation;
                                break;
                            case 'managers':
                                $rules[$ruleName] = $ruleValidation . '|managers_check';
                                break;
                            case 'reservable_online':
                            case 'amount':
                                $rules['reservable_online'] = 'boolean';
                                if (@$inputs['reservable_online']) {
                                    $rules['amount'] = 'required_if:reservable_online,1|numeric|min:0';
                                }
                                break;
                            case 'logo':
                            case 'featured_image':
                                if (($request->has($ruleName) && !empty($request->get($ruleName)))) {
                                    if (!$request->hasFile($ruleName)) {
                                        $rules[$ruleName] = $ruleValidation;
                                    }
                                }
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
