<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use App\Vote;

class VoteRequest extends Request
{

    protected $_rules = [
        'vote_up' => 'required|boolean',
        'vote_down' => 'required_unless:vote_up,0|boolean',
    ];

    public function setObject()
    {
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {

            $id = $request->route()->parameters()['id'];
            $this->_object = Vote::find($id);
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

        $request->merge(['user_id' => User::getCurrentUser()->id]);

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
