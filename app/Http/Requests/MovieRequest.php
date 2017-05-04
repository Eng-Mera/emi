<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Role;

class MovieRequest extends Request
{
    protected static $_fields = [
        'name',
        'description',
        'poster',
        'booking_url',
        'enable_booking',
        'add_to_featured'
    ];

    protected $_rules = [
        'name' => 'required|string',
        'description' => 'string|min:10',
        'enable_booking' => 'boolean',
        'booking_url' => 'url|required_with:enable_booking',
        'poster' => 'required|base64',
        'add_to_featured' => 'boolean',
        'movie_featured_image' => 'base64'
    ];

    public static function getFields()
    {
        return self::$_fields;
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

        $rules = [];

        switch ($this->method()) {
            case 'PATCH':
            case 'PUT':
                $rules = $this->_rules;
                $rules['poster'] = 'base64';
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

    public function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {

        if ($currentUser->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        return false;
    }
}
