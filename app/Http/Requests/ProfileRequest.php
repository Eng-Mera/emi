<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\Profile;
use App\Role;
use App\User;
use Illuminate\Contracts\Validation\Validator;

class ProfileRequest extends Request
{
    protected $_rules = [
        'mobile' => 'required|numeric|digits:11|unique:profiles',
        'address' => 'string',
        'qualification' => 'string|min:15',
        'current_employee' => 'string|min:4',
        'current_position' => 'string|min:4',
        'previous_employee' => 'string|min:4',
        'previous_position' => 'string|min:4',
        'experience_years' => 'numeric',
        'current_salary' => 'numeric|min:1',
        'expected_salary' => 'numeric|min:1'
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {
            $username = $request->route()->parameters()['profile'];
            $this->_object = User::whereUsername($username)->first();
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
        if ($currentUser->hasRole(Role::RESTAURANT_MANAGER) && $currentUser->id == $this->_object->created_by) {
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
                foreach ($this->_rules as $ruleName => $ruleValidation) {
                    if (!isset($inputs[$ruleName])) {
                        continue;
                    }

                    $user = User::whereUsername($request->profile)->firstOrFail();
                    $profile = Profile::whereUserId($user->id)->first();

                    if ($ruleName == 'mobile') {
                        $ruleValidation = $ruleValidation . ',' . $ruleName . ',' . $profile->id;
                        $rules[$ruleName] = $ruleValidation;
                    } else {
                        $rules[$ruleName] = $ruleValidation;
                    }
                }

                break;
            case 'POST':
            default:
                $rules = $this->_rules;
        }

        return $rules;
    }

}
