<?php
/**
 * Short description
 *
 * Long description for QueryableTrait.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Helpers;

use App\Reservation;
use DB;
use App\Coupon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait QueryableTrait
{
    /**
     * @param $restaurant_id
     * @return mixed
     */
    public function getStaffByRestaurantAndRole($restaurant_id, $role_id)
    {
        return DB::table('users')
            ->select('users.*')
            ->leftJoin('restaurant_users', 'users.id', '=', 'restaurant_users.user_id')
            ->leftJoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('restaurant_users.restaurant_id', $restaurant_id)
            ->where('role_user.role_id', $role_id)
            ->get();
    }

    /**
     * Get coupon id given its code
     *
     * @param $code
     * @return mixed
     */
    public function getCouponIdFromCode($code)
    {
        if (!trim($code)) {
            return null;
        }

        $id = Coupon::code($code)->first();

        if (!$id) {
            throw new ModelNotFoundException(' Coupon "'.$code.'" isn\'t valid');
        }

        return (!$code) ? null : $id->id;
    }

    /**
     * Get coupon code from its id
     *
     * @param $id
     * @return null
     */
    public function getCouponCodefromId($id)
    {
        return (!$id) ? null : Reservation::whereId($id)->first()->code;
    }

    /**
     * Get coupon code from its id
     *
     * @param $id
     * @return null
     */
    public function getDate($id)
    {
        return (!$id) ? null : Reservation::whereId($id)->first()->date;
    }

    /**
     * Load Reservation with related relations
     *
     * @param $reservation_id
     * @return mixed
     */
    public function eagerLoadReservation($reservation_id)
    {
        return Reservation::whereId($reservation_id)->with('changes', 'coupon', 'user', 'restaurant')->first();
    }

    /**
     * @todo get users who has a given permission and are assigned to the restaurant id
     * @todo the permission is searched through roles of the user
     *
     * @param $restaurant_id
     * @param $permission_name
     */
    public function getStaffByRestaurantIdWithPermission($restaurant_id, $permission_name)
    {

    }

    /**
     * get user id who is owner of this coupon from coupons assuming unique coupon code
     *
     * @param $_code
     * @return mixed
     */
    public function getCouponUserfromCode($_code)
    {
        return @Coupon::select('user_id')->whereCode($_code)->first()->user_id;
    }
}