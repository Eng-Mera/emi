<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Role;

class AdminReviewRequest extends Request
{
    protected static $_fields = [
        'images',
        'I18N',
        'removed_images_ids'
    ];

    protected $_rules = [
        'I18N.*.restaurant_name' => 'required|string|min:1|max:90',
        'I18N.*.description' => 'required|string|min:5|max:3000',
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

        return false;
    }


    protected function extendListValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        return true;
    }

    public static function removeParent($inputs)
    {

        foreach ((array)$inputs['I18N'] as $key => $lang) {
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
        switch ($this->method()) {

            case 'GET':
            case 'DELETE':
                $rules = [];
                break;
            case 'POST':
            case 'PATCH':
            case 'PUT':
            default:
                $rules = $this->_rules;
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'I18N.*.restaurant_name.required' => trans('The restaurant name field is required.'),
            'I18N.*.description.required' => trans('The description field is required.'),

            'I18N.*.restaurant_name.min' => trans('The restaurant name may not be less than 1 characters.'),
            'I18N.*.description.min' => trans('The description may not be less than 1 characters.'),

            'I18N.*.restaurant_name.max' => trans('The restaurant name may not be greater than than 90 characters.'),
            'I18N.*.description.max' => trans('The description may not be greater than 3000 characters.'),

        ];
    }
}
