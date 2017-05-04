<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PhotoComment;
use App\Role;

class PhotoCommentRequest extends Request
{
    protected $_rules = [
        'comment' => 'required|min:2|max:1000',
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {

            $id = $request->route()->parameters()['id'];
            $this->_object = PhotoComment::find($id);
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
        if ($currentUser && $currentUser->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }

        $action = (array)@explode('@', $this->route()->getAction()['uses']);

        if (!empty($action[1]) && $action[1] == 'update') {
            return true;
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
            case 'PUT':
            case 'PATCH':

                $inputs = $request->all();

                foreach ($this->_rules as $ruleName => $ruleValidation) {
                    if (isset($inputs[$ruleName])) {
                        $rules[$ruleName] = $ruleValidation;
                    }
                }

                break;
            default:
                $rules = $this->_rules;
        }

        return $rules;
    }
}
