<?php
/**
 * Authorizable Trait
 *
 * A collection of functions related to Authorization
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Helpers;

use App\Permission;
use App\PermissionRole;
use App\RestaurantUsers;

trait Authorizable
{
    /**
     * Check if the given user is connected to the restaurant
     * that is whether a restaurant has a user with this user_id
     * directly by checking the database instead of looping in application layer
     *
     * @param $restaurant_id
     * @param $user_id
     * @return bool
     */
    public function isRestaurantUser($restaurant_id, $user_id)
    {
        $ret = RestaurantUsers::whereUserId($user_id)->whereRestaurantId($restaurant_id)->first();

        if ($ret)
            return true;
        else
            return false;
    }

    /**
     * Given a permission name, then find the permission_id,
     * then it checks whether any of the given roles, at least one,
     * was assigned this permission
     *
     * @param $array_role_ids
     * @param $permission_name
     * @return bool
     */
    public function patchCan($array_role_ids, $permission_name)
    {
        $permission = Permission::whereName($permission_name)->first();

        if (!$permission)
            return false;

        $cnt = PermissionRole::wherePermissionId($permission->id)->whereIn('role_id', $array_role_ids)->count();
        if ($cnt)
            return true;

        return false;
    }

    /**
     * Checks if current user belongs to a Role that has the permission
     * Then checks if the current user is linked to the resouce / restaurant
     *
     * @param $_user    User
     * @param $permission_name Permission
     * @param $restaurant_id Resource
     * @return bool
     */
    public function canUserPermissionOnRestaurant($_user, $permission_name, $restaurant_id)
    {
        // Check if user is related to restaurant AND has permission to view
        $predicate_a = $this->isRestaurantUser($restaurant_id, $_user->id);
        $predicate_b = $this->canUserPermission($_user, $permission_name);

        if ($predicate_a || $predicate_b) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check if the user has permission by looping its roles
     *
     * @param $_user
     * @param $_permission_name
     * @return bool
     */
    public function canUserPermission($_user, $_permission_name)
    {
        // Get current user with roles
        $roles = $_user->roles->map(function ($item) {
            return $item['id'];
        })->toArray();

        return $this->patchCan($roles, $_permission_name);
    }
}