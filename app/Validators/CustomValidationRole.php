<?php
namespace App\Validators;

use App\Http\Helpers\UserTrait;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Validator;

trait CustomValidationRole
{

    use UserTrait;

    public function customValidator()
    {
        $this->dayNotExceed();
        $this->currentUserIs();
        $this->currentPasswordValid();
        $this->changeUserRole();
        $this->latitude();
        $this->longitude();
        $this->dayWeek();
        $this->timeRange();
        $this->base64();
        $this->arrayCount();
        $this->numericArray();
        $this->managersCheck();
        $this->realEmailDomain();
        $this->dateRangeExist();
        $this->validateRoleUser();
    }

    public function dayNotExceed()
    {
        //Date not exceed the given months
        Validator::extend('days_not_exceed', function ($attribute, $value, $parameters, $validator) {

            $givenDate = strtotime($value);
            $validatorDate = time() - ($parameters[0] * 24 * 60 * 60);

            return $givenDate - $validatorDate >= 0;
        });

        Validator::replacer('days_not_exceed', function ($message, $attribute, $rule, $parameters) {

            return trans('The date must not be less than ' . $parameters['0'] . ' day(s) from now', ['day' => $parameters['0']]);
        });
    }

    public function currentPasswordValid()
    {
        Validator::extend('current_password_valid',function($attribute,$value,$parameters,$validator){
            $user = User::getCurrentUser();
            if (\Hash::check($value, $user->password))
            {
                return true;
            }
            return false;
        });

        Validator::replacer('current_password_valid', function ($message, $attribute, $rule, $parameters) {

            return trans('The Current Password is invalid');
        });
    }

    public function currentUserIs()
    {
        //Date not exceed the given months
        Validator::extend('current_user_is', function ($attribute, $field, $parameters, $validator) {

            $user = User::getCurrentUser();

            if ($user->hasRole($parameters)) {
                return true;
            }

            return false;
        });

        Validator::replacer('current_user_is', function ($message, $attribute, $rule, $parameters) {

            return trans('You don\'t have the permission to change this field  "' . $attribute . '""');
        });
    }
    

    public function changeUserRole()
    {
        Validator::extend('current_user_change_role', function ($attribute, $roles, $parameters, $validator) {

            $userId = @$parameters[0];

            if (!$userId) {
                return false;
            }

            $currentUser = User::getCurrentUser();

            if ($currentUser->hasRole(Role::SUPER_ADMIN)) {
                return true;
            }

            if (!$currentUser->hasRole(Role::RESTAURANT_MANAGER)) {
                return false;
            }

            $user = User::find($userId);

            $restaurant = User::getManagersRestaurant();

            if (($currentUser->id != $user->created_by) && !$this->isRestaurantManager($currentUser, $restaurant)) {
                return false;
            }

            $newRoles = Role::find($roles);

            $allowedRoles = [Role::RESTAURANT_ADMIN, Role::RESERVATION_MANAGER];

            $newRolesArr = $newRoles->pluck('name')->toArray();

            if (!array_diff($newRolesArr, $allowedRoles) && $newRoles) {
                return true;
            }

            return false;
        });

        Validator::replacer('current_user_change_role', function ($message, $attribute, $rule, $parameters) {

            return trans('You don\'t have the permission to change this user role to "' . $attribute . '""');
        });
    }

    public function latitude()
    {
        //Latitude Validation
        Validator::extend('latitude', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/', $value);
        });

        Validator::replacer('latitude', function ($message, $attribute, $rule, $parameters) {
            return trans('The Latitude  format isn\'t right');
        });
    }

    public function longitude()
    {
        //Longitude Validation
        Validator::extend('longitude', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^[-]?(([0-1]?[0-7]?[0-9])\.(\d+))|(180(\.0+)?)$/', $value);
        });

        Validator::replacer('longitude', function ($message, $attribute, $rule, $parameters) {
            return trans('The longitude format isn\'t right');
        });
    }

    public function dayWeek()
    {
        //Day weeks Validation
        Validator::extend('day_week', function ($attribute, $value, $parameters, $validator) {

            $timestamp = strtotime('next Sunday');
            $days = array();

            for ($i = 0; $i < 7; $i++) {
                $days[] = strftime('%A', $timestamp);
                $timestamp = strtotime('+1 day', $timestamp);
            }

            return in_array($value, $days);
        });

        Validator::replacer('day_week', function ($message, $attribute, $rule, $parameters) {
            return trans('The day format isn\'t right. It must be looks like "Sunday"');
        });
    }

    public function timeRange()
    {
        Validator::extend('time_range', function ($attribute, $value, $parameters, $validator) {

            return strtotime(date('d-m-Y') . ' ' . $value) > strtotime(date('d-m-Y') . ' ' . \Illuminate\Support\Facades\Request::get($parameters[0]));
        });

        Validator::replacer('time_range', function ($message, $attribute, $rule, $parameters) {
            return trans('The to time must be greater than from time');
        });
    }

    public function base64()
    {
        //Base64 Validator
        Validator::extend('base64', function ($attribute, $value, $parameters, $validator) {

            $img = @imagecreatefromstring(base64_decode(substr($value, strpos($value, ",") + 1)));

            if (!$img) {
                return false;
            }

            imagepng($img, 'tmp.png');
            $info = getimagesize('tmp.png');

            unlink('tmp.png');

            if ($info[0] > 0 && $info[1] > 0 && $info['mime']) {
                return true;
            }

            return false;
        });

        Validator::replacer('base64', function ($message, $attribute, $rule, $parameters) {
            return 'The image format isn\'t right';
        });
    }

    public function arrayCount()
    {
        Validator::extend('array_count', function ($attribute, $value, $parameters) {

            if (!empty($parameters[0]) && count($value) !== intval($parameters[0])) {
                return false;
            }

            return true;
        });

        Validator::replacer('array_count', function ($message, $attribute, $rule, $parameters) {

            return 'One ore more of rate values are missing';
        });
    }

    public function numericArray()
    {
        Validator::extend('numeric_array', function ($attribute, $value, $parameters) {

            if (is_array($value)) {

                foreach ($value as $v) {

                    if (!is_int(intval($v))) {
                        return false;
                    }

                    if ($v < $parameters[0] || $v > $parameters[1]) {

                        return false;
                    }
                }
            }
            return true;
        });

        Validator::replacer('numeric_array', function ($message, $attribute, $rule, $parameters) {

            return $attribute . ' must be an array with numeric values and must be between ' . $parameters[0] . ', ' . $parameters[1];
        });
    }

    public function managersCheck()
    {
        Validator::extend('managers_check', function ($attribute, $value, $parameters) {

            if (!UserTrait::validateManagers($value)) {
                return false;
            }

            return true;
        });

        Validator::replacer('managers_check', function ($message, $attribute, $rule, $parameters) {

            return 'One or more admins aren\'t created by restaurant owner';
        });
    }

    public function realEmailDomain()
    {
        Validator::extend('real_email', function ($attribute, $value, $parameters) {

            $domain = @explode('@', $value)[1];

            if ($domain && checkdnsrr($domain)) {
                return true;
            }

            return false;
        });

        Validator::replacer('real_email', function ($message, $attribute, $rule, $parameters) {

            return 'The email address isn\'t a real one';
        });
    }


    public function dateRangeExist()
    {
        Validator::extend('date_range_exist', function ($attribute, $value, $parameters, $validator) {

            $request = app(Request::class);

            $endDateColumn = @$attribute;
            $startDateColumn = @$parameters[0];
            $table = @$parameters[1];
            $modelColumn = @$parameters[2];
            $modelId = @$parameters[3];
            $currentId = @$parameters[4];

            $className = 'App\\' . studly_case(str_singular($table));

            if (class_exists($className)) {
                $model = new $className;
            } else {
                return false;
            }

            $startDate = date('Y-m-d', strtotime($request->get($startDateColumn)));
            $endDate = date('Y-m-d', strtotime($request->get($endDateColumn)));


            $result = $model
                ->where(function ($query) use ($currentId, $modelColumn, $modelId, $startDateColumn, $endDateColumn, $startDate, $endDate) {

                    $query->where(function ($query) use ($currentId, $modelColumn, $modelId) {
                        $query->where('id', '<>', $currentId);
                        $query->where($modelColumn, $modelId);
                    });

                    $query->where(function ($query) use ($startDateColumn, $endDateColumn, $startDate, $endDate, $modelId) {
                        $query->whereBetween($startDateColumn, [$startDate, $endDate]);
                        $query->OrWhereBetween($endDateColumn, [$startDate, $endDate]);
                    });

                });

            if ($result->count()) {
                return false;
            }

            return true;

        });

        Validator::replacer('date_range_exist', function ($message, $attribute, $rule, $parameters) {
            return trans('The range of start date and end date conflicts with other existing ranges');
        });
    }

    public function validateRoleUser()
    {
        Validator::extend('validate_role_user', function ($attribute, $roleName, $parameters, $validator) {

            $currentUser = User::getCurrentUser();

            $currentUserRoles = !empty($currentUser->roles) ? $currentUser->roles->pluck('name')->toArray() : [];

            foreach ((array)$currentUserRoles as $currentUserRole) {

                $allowedRoles = Role::getAllowedRoles($currentUserRole);

                if (in_array($roleName, $allowedRoles)) {
                    return true;
                }
            }

            return false;
        });

        Validator::replacer('validate_role_user', function ($message, $attribute, $rule, $parameters) {

            return 'You aren\'t allowed to  add this role to this user';
        });
    }

}