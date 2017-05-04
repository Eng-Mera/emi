<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use App\User;
use App\Branch;
use App\Role;

use Illuminate\Support\Facades\Route;

class BranchRequest extends Request
{

    protected static $_fields = [
        'slug',
        'latitude',
        'longitude',
        'phone',
        'email',
        'restaurant_id',
        'I18N'
    ];

    protected $_rules = [
        'slug' => 'required|string|unique:branches',
        'latitude' => 'required|latitude',
        'longitude' => 'required|longitude',
        'phone' => 'required',
        'email' => 'email|unique:branches',
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
        $request = app('\Illuminate\Http\Request');

        if (in_array($request->method(), ['PUT', 'PATCH', 'DELETE'])) {
            $slug = $request->route()->parameters()['branch'];
            $this->_object = Branch::whereSlug($slug)->firstOrFail();
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

        switch ($this->method()) {
            case 'PATCH':
            case 'PUT':
                $slug = !empty($request->branch) ? $request->branch : Route::current()->parameters()['branch'];

                $branch = Branch::whereSlug($slug)->firstOrFail();
                $inputs = $request->all();


                foreach ($this->_rules as $ruleName => $ruleValidation) {

                    if (isset($inputs[$ruleName])) {
                        switch ($ruleName) {
                            case 'slug':
                            case 'phone':
                                $ruleValidation = $ruleValidation . ',' . $ruleName . ',' . $branch->id;

                                $rules[$ruleName] = $ruleValidation;
                                break;

                            default:
                                $rules[$ruleName] = $ruleValidation;
                                break;
                        }
                    }
                }
                break;

            case 'POST':
            default:

            $rules = $this->_rules;
                break;
        }

        return $rules;

    }
}
