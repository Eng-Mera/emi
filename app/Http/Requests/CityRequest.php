<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CityRequest extends Request
{

    protected static $_fields = [
        'I18N'
    ];


    public static function getFields()
    {
        return self::$_fields;
    }

    public static function removeParent($inputs)
    {
        foreach ($inputs['I18N'] as $key => $lang )
        {
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
    public function rules()
    {
        return [
//            'city_name' => 'required|string|unique:city_translation'
        ];
    }
}
