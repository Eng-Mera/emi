<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FacilityRequest extends Request
{

    protected $_rules = [
        'I18N.*.name' => 'required|string|min:1|max:25',
        'I18N.*.description' => 'string|min:5|max:1000',
        'icon' => 'string',
    ];

    protected static $_fields = [
        'I18N',
        'icon'
    ];

    public static function getFields()
    {
        return self::$_fields;
    }

    public static function removeParent($inputs)
    {
        foreach ($inputs['I18N'] as $key => $lang) {
            $inputs[$key] = $lang;
        }
        unset($inputs['I18N']);
        return $inputs;
    }

    public function setObject()
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
            'I18N.*.name.required' => trans('The name field is required.'),
            'I18N.*.description.required' => trans('The description field is required.'),

            'I18N.*.name.min' => trans('The name field is required.'),
            'I18N.*.description.min' => trans('The description field is required.'),

            'I18N.*.name.max' => trans('The name may not be less than 1 characters.'),
            'I18N.*.description.max' => trans('The description may not be less than 5 characters.'),

        ];
    }
}
