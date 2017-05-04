<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Restaurant;
use App\User;

class JobVacancyRequest extends Request
{
    protected static $_fields = [
        'job_title_id',
        'description',
        'status',
        'I18N'

    ];


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
        return true;
    }

    public function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        $restaurant = @Restaurant::whereSlug($request->restaurant)->firstOrFail();

        if (!$restaurant || empty($restaurant->id)) {
            return false;
        }

        $currentManagerRestaurant = User::getManagersRestaurant();

        if ($restaurant->id != $currentManagerRestaurant->id) {
            return false;
        }

        if (!$restaurant->allow_job_vacancies) {
//            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['GET', 'DELETE', 'POST'])) {
            return [];
        }

        return [
            'job_title_id' => 'required|exists:jobs_titles,id',
//            'description' => 'required|string',
            'status' => 'required|boolean'
        ];
    }
}
