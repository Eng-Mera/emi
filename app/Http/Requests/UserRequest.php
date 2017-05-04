<?php

namespace App\Http\Requests;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Yaml\Exception\DumpException;

class UserRequest extends Request
{

    protected $_rules = [
        'name' => 'required',
        'email' => 'required|email|real_email|unique:users,email',
        'dob' => 'required|date|before:2000-12-29',
        'current_password' => 'min:6',
        'password' => 'min:6|confirmed|different:current_password',
        'password_confirmation' => 'required_with:password|min:6',
        'roles' => 'required|array|current_user_is:' . Role::SUPER_ADMIN . ',' . Role::RESTAURANT_MANAGER,
        'about_me' => 'string|min:30',
        'fb_id' => 'integer|unique:users,fb_id',
        'google_id' => 'integer|unique:users,google_id',
        'intgm_id' => 'integer|unique:users,intgm_id',
        'uploaded_file' => ''
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        $username = @$request->route()->parameters()['user'];

        if ($username && in_array($request->method(), ['PUT', 'PATCH', 'DELETE', 'GET'])) {

            if ($username) {
                $this->_object = User::whereUsername($username)->first();
            } else {
                $this->_object = null;
            }
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        $method = $request->method();

        if ($method == 'GET') {
            return true;
        }
        if ($currentUser->hasRole(Role::RESTAURANT_MANAGER) && $currentUser->id == @$this->_object->created_by) {
            return true;
        }

        return false;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(\Illuminate\Http\Request $request)
    {
        $inputs = $request->all();
        $rules = [];

        switch ($this->method()) {
            case 'PATCH':
            case 'PUT':

                $username = !empty($request->user) ? $request->user : Route::current()->parameters()['user'];
                $user = User::whereUsername($username)->firstOrFail();

                foreach ($this->_rules as $ruleName => $ruleValidation) {

                    if (isset($inputs[$ruleName])) {

                        switch ($ruleName) {
                            case 'email':
                            case 'fb_id':
                            case 'google_id':
                            case 'intgm_id':
                                $ruleValidation .= ',' . $user->id;
                                $rules[$ruleName] = $ruleValidation;
                                break;

                            case 'current_password':
                                $ruleValidation .= '|current_password_valid';
                                $rules[$ruleName] = $ruleValidation;
                                break;
                            case 'password_confirmation':
                            case 'password':

                                if (empty($inputs['current_password']) && empty($inputs['password']) && empty($inputs['password_confirmation'])) {
                                } else {
                                    $rules[$ruleName] = $ruleValidation;
                                }
                                break;

                            case 'roles':

                                $ruleValidation .= '|current_user_change_role:' . $user->id;
                                $rules[$ruleName] = $ruleValidation;
                                break;

                            case 'uploaded_file':

                                $txt = [];

                                if ($request->get('uploaded_file', false)) {
                                    $txt[] = 'base64';
                                }

                                if ($request->file('uploaded_file', false)) {
                                    $txt [] = 'image|mimes:jpeg,bmp,png';
                                }

                                if ($txt) {
                                    $rules[$ruleName] = implode('|', $txt);
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

            default:
                $rules = $this->_rules;
        }

        return $rules;
    }

}
