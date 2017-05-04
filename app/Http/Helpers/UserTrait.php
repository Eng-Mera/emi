<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 4/25/16
 * Time: 11:09 AM
 */

namespace App\Http\Helpers;


use App\Profile;
use App\Restaurant;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


trait UserTrait
{
    /**
     * Validate Managers
     *
     * @param array $managersId
     * @return bool
     */
    public static function validateManagers($managersId)
    {
        $managers = User::whereIn('id', $managersId)->get();

        $currentUser = User::getCurrentUser();

        foreach ($managers as $manager) {

            if (!$manager->hasRole([Role::RESTAURANT_ADMIN, Role::RESERVATION_MANAGER])) {

                return false;
            }

            if (!$currentUser->hasRole(Role::SUPER_ADMIN) && $manager->created_by != $currentUser->id) {

                return false;
            }
        }

        return true;
    }

    /**
     * Append Role to user according to the way the account created
     *
     * @param User $user
     * @return bool
     */
    public function assignRole(User $user, $roleName)
    {
        // Assign Diner Role.
        if (!$roleName) {
            $this->assignDinerRole($user);
            return true;
        }

        $role = Role::whereName($roleName)->first();

        if ($role) {
            $user->attachRole($role);
        }

        $this->assignDinerRole($user);

        return true;
    }

    /**
     * Assign Role to user.
     *
     * @param User $user
     * @param $roleName
     * @return bool
     */
    public function assignUserTypeRole(User $user, $roleName)
    {
        if (!$roleName) {
            return false;
        }

        $this->assignRole($user, $roleName);

        return true;
    }

    /**
     * Check if user has the role of dine and if not assign to him.
     *
     * @param User $user
     * @return bool
     */
    private function assignDinerRole(User $user)
    {
        if ($user->hasRole(Role::DINNER)) return false;

        $dineObj = Role::whereName(Role::DINNER)->first();

        $user->attachRole($dineObj);

        return true;
    }

    /**
     * Check if user has the role of dine and if not assign to him.
     *
     * @param User $user
     * @return bool
     */
    private function assignDevAdminRole(User $user)
    {
        if ($user->hasRole(Role::DEV_ADMIN)) return false;

        $dineObj = Role::whereName(Role::DEV_ADMIN)->first();

        $user->attachRole($dineObj);

        return true;
    }

    /**
     * Update user roles
     *
     * @param User $user
     * @param $newRoles
     * @return bool
     */
    public function updateUserRoles(User $user, $newRoles)
    {
        if (!$newRoles) {

            if ($user->roles->count() == 0) {
                $this->assignDinerRole($user);
            }

            return false;
        }

        $newRoles = $this->handleNewRoles($newRoles, $user);

        if (!$newRoles) return false;

        if ($newRoles) {

            $isDevAdmin = false;

            if ($user->roles) {
                $isDevAdmin = $user->hasRole(Role::DEV_ADMIN);
                $user->detachRoles($user->roles);
            }

            $user->attachRoles($newRoles);

            if ($isDevAdmin) {
                $this->assignDevAdminRole($user);
            }

            $this->updateManagerRestaurantId($user);
        }

        $this->assignDinerRole($user);

        return true;
    }

    /**
     * Add manager role to current user during update his profile.
     *
     * @param $newRoles
     * @param User $user
     * @return mixed
     */
    private function handleNewRoles($newRoles, User $user)
    {
        $currentUser = User::getCurrentUser();

        if ($user->id == $currentUser->id && $currentUser->hasRole(Role::RESTAURANT_MANAGER)) {

            $newRoles[] = @Role::whereName(Role::RESTAURANT_MANAGER)->first()->id;

            array_unique($newRoles);

        }

        return Role::find($newRoles);
    }

    /**
     * Update manager / admins restaurant.
     *
     * @param User $user
     * @return bool
     */
    public function updateManagerRestaurantId(User $user)
    {
        $restaurant = User::getManagersRestaurant();

        if (!$restaurant || !$user->hasRole([Role::RESTAURANT_MANAGER, Role::RESTAURANT_ADMIN, Role::RESERVATION_MANAGER])) {

            return false;

        } else if (!$user->hasRole([Role::RESTAURANT_MANAGER, Role::RESTAURANT_ADMIN, Role::RESERVATION_MANAGER])) {

            $user->manage_restaurant_id = null;

        } else {

            $user->manage_restaurant_id = $restaurant->id;
        }

        $user->save();

        return true;
    }

    /**
     * Update user Profile.
     *
     * @param $fields
     * @param $username
     * @return mixed
     */
    protected function updateUserProfile($fields, User $user)
    {
        $fields = $this->filterProfileFields($fields);

        if (!$user->profile) {
            $this->createProfile($user);
        }

        if (!isset($fields['profile'])) {
            return false;
        }

        $profile = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->patch(env('API_VERSION', 'v1') . '/profile/' . $user->username, $fields['profile']);

        return $profile;
    }

    /**
     * Replace the data of profile in profile array.
     *
     * @param $fields
     * @return mixed
     */
    private function filterProfileFields($fields)
    {
        $profileFields = ['mobile', 'address', 'qualification', 'current_employee', 'current_position', 'previous_employee', 'previous_position', 'experience_years', 'current_salary', 'expected_salary'];

        foreach ($profileFields as $profileField) {

            if (isset($fields[$profileField])) {
                $fields['profile'][$profileField] = $fields[$profileField];
                unset($fields[$profileField]);
            }

        }

        return $fields;
    }

    /**
     * Update User profile
     *
     * @param $username
     * @param $inputs
     * @return mixed
     */
    protected function updateProfile($username, $inputs)
    {
        $user = $this->getUserByUsername($username);

        $input = array_filter($inputs);

        if (!empty(array_filter($input)) && $user->profile) {

            $user->profile->fill($input)->save();

        } else {
            $input['user_id'] = $user->id;

            Profile::create($input);

            $user = $this->getUserByUsername($username);
        }

        return $user;
    }

    /**
     * Fetch user
     * (You can extract this to repository method)
     *
     * @param $username
     * @return mixed
     */
    public function getUserByUsername($username)
    {
        $user = User::with(['profile', 'profilePicture'])->whereUsername($username)->firstOrFail();
        $user->mobile = @$user->profile->mobile;

        return $user;
    }

    /**
     * Update Usr.
     *
     * @param Request $request
     * @param User $user
     * @return bool
     */
    public function updateUser(Request $request, User $user)
    {
        $inputs = $request->only('name', 'username', 'email', 'current_password', 'password', 'password_confirmation', 'dob', 'gender', 'fb_id', 'google_id', 'intgm_id', 'about_me');

        $password = @$inputs['password'];

        if ($password) {
            $inputs['password'] = \Hash::make($password);
        } else {
            unset($inputs['password']);
        }

        if (array_filter($inputs)) {
            $user->fill(array_filter($inputs))->save();
        }

        return true;
    }

    /**
     * Create profile for user.
     *
     * @param User $user
     * @param bool $mobile
     * @return static
     */
    public function createProfile(User $user, $mobile = false)
    {
        $profile = Profile::create([
            'user_id' => $user->id,
            'mobile' => $mobile
        ]);

        return $profile;
    }

    /**
     * Validate user is manager of the specified restaurant
     *
     * @param User $user
     * @param Restaurant $restaurant
     * @return bool
     */
    public function isRestaurantManager(User $user, Restaurant $restaurant)
    {
        return $user->id == $restaurant->owner_id;
    }
}