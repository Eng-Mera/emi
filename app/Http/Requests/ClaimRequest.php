<?php

namespace App\Http\Requests;

use App\Claim;
use App\Http\Requests\Request;

use App\User;
use App\Role;

use Illuminate\Support\Facades\Route;

class ClaimRequest extends Request
{

    protected static $_fields = [
        'user_id',
        'slug',
        'status',

    ];

    protected $_rules = [
        'user_id' => 'required|unique:claims',
    ];

    public static function getFields()
    {
        return self::$_fields;
    }


    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {
            $id = $request->route()->parameters()['claim'];
            $this->_object = Claim::whereId($id)->firstOrFail();
        }
        return true;
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(\Illuminate\Http\Request $request)
    {
        $this->setObject();

        $currentUser = User::getCurrentUser();

        if ($currentUser && $currentUser->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }


        return true;
    }

    public function rules(\Illuminate\Http\Request $request)
    {
        $rules = [];

        return $rules;

    }
}
