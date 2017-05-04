<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Role;
use App\User;

class AuthRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(\Illuminate\Http\Request $request)
    {

        return [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'username' => 'required|max:50|unique:users',
            'password' => 'confirmed|min:6|required_without_all:google_id,fb_id,intgm_id',
            'mobile' => 'required|numeric|digits:11|unique:profiles',
            'dob' => 'date|before:2000-12-29',
            'role' => 'validate_role_user',
            'user_type' => 'in:' . Role::JOB_SEEKER . ',' . Role::RESTAURANT_MANAGER,
            'gender' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'password.required_without_all' => 'The password field is required',
            'password.confirmed' => 'The password and its confirmation do not match',
        ];
    }

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {

            $username = !empty($request->restaurant) ? $request->restaurant : $request->route()->parameters()['username'];

            $this->_object = Restaurant::whereUsername($username)->firstOrFail();
        }

        return true;
    }

    /**
     * Extend these method in your Form Request class if you need to apply more validation in CRUD actions.
     *
     * @param \Illuminate\Http\Request $request
     * @param $currentUser
     * @return bool
     */
    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        $roles = [Role::SUPER_ADMIN, Role::RESTAURANT_MANAGER];
        if (User::getCurrentUser()->hasRole($roles)) {
            return true;
        }
        return false;
    }
}
