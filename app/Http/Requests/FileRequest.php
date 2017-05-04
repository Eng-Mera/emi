<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use Illuminate\Contracts\Validation\Validator;

class FileRequest extends Request
{

    protected $_rules = [
        'uploaded_file' => 'required'
    ];

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
            case 'POST':
            case 'PATCH':

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

    public function setObject()
    {
        return true;
    }

    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {

        return true;
    }
}
