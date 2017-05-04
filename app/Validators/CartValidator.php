<?php
/**
 * Short description
 *
 * Long description for CartValidator.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Validators;

use App\Http\Helpers\Authorizable;
use App\Http\Helpers\QueryableTrait;
use App\Permission;
use App\Reservation;
use App\ReservationChanges;
use App\Restaurant;
use App\User;
use Auth;
use Illuminate\Support\MessageBag;
use App\Coupon;

class CartValidator
{
    /**
     * Authorization Related Methodsd
     */
    use Authorizable;

    /**
     * Queryable Interface
     */
    use QueryableTrait;

    /**
     * validate coupons by following criteria
     * 1 - coupon code exists
     * 2 - coupon not expired
     * 3 - coupon is reusable
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateCoupon($attribute, $value, $parameters, $validator)
    {
        $data = $validator->getData();
        $user_id = $data['user_id'];

        $coupon = Coupon::whereCode($value)
            ->whereRestaurantId(@$data['restaurant_id'])
            ->where(function ($q) use ($user_id) {
                $q->whereUserId($user_id)
                    ->orWhere('user_id', '=', 0)
                    ->orWhereNull('user_id');
            })
            ->get()->toArray();

        if (!$coupon)
            return false;

        $timestamp = strtotime($coupon[0]['expired_at']);
        if ($timestamp < time() && false == $coupon[0]['reusable'])
            return false;

        return true;
    }

    /**
     * Checks that the sent date and time fields when combined together
     * should form a datetime that is before now
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateDateTimeAfterNow($attribute, $value, $parameters, $validator)
    {
        $data = $validator->getData();
        $date = $data['date'];
        $time = $data['time'];
        $string_date_time = $date . ' ' . $time;
        $timestamp = strtotime($string_date_time);
        #dd($string_date_time, strftime('%c', $timestamp), $timestamp, strftime('%c', time()), $timestamp < time());
        if ($timestamp < time())
            return false;
        return true;
    }

    /**
     * In order to make a payment for a reservation
     * the reservation status must be pending or approved
     * pending means it was created and then a credit card capture will be made
     * approved means it was created and approved by restaurant manager and a credit card authorize will be made
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     */
    public function validateReservationStatus($attribute, $value, $parameters, $validator)
    {
        $reservation = Reservation::where('id', $value)->first()->toArray();
        if ($reservation['status'] == Reservation::STATUS_PENDING || $reservation['status'] == Reservation::STATUS_APPROVED)
            return true;
        return false;
    }

    /**
     * If the reservation was created without setting the advance payment flag
     * then return false
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationAdvancePayment($attribute, $value, $parameters, $validator)
    {
        $reservation = Reservation::where('id', $value)->first()->toArray();
        if ($reservation['advance_payment'] == false)
            return false;
        return true;
    }

    /**
     * Check if and only if a reservation is pending
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationIsPending($attribute, Reservation $reservation, $parameters, $validator)
    {
        if ($reservation->status != Reservation::STATUS_PENDING) {
            return false;
        }

        return true;
    }

    /**
     * Check if and only if a reservation is paid
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationStatusForPaid($attribute, Reservation $reservation, $parameters, $validator)
    {
        if (!in_array($reservation->status, [Reservation::STATUS_PAID, Reservation::STATUS_APPROVED])) {
            return false;
        }

        return true;
    }

    /**
     * Checks if the user making the request has the right to view the reservation
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     */
    public function validateReservationCanView($attribute, Reservation $reservation, $parameters, $validator)
    {

        $current_user = User::getCurrentUser();

        // can view this restaurant
        if ($this->canUserPermissionOnRestaurant($current_user, Permission::PERM_VIEW_RESERVATION, $reservation->restaurant_id)) {
            return true;
        }

        // Current user is owner of this reservation
        if ($reservation->user_id == $current_user->id) {
            return true;
        }

        return false;
    }

    /**
     * Validation Criteria:
     * --------------------
     * 1- user_id is owner of this reservation
     * 2- current user is admin or reservation_manager
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     */
    public function validateReservationCanChange($attribute, Reservation $reservation, $parameters, $validator)
    {
        $current_user = User::getCurrentUser();

        // can view this restaurant
        if ($this->canUserPermissionOnRestaurant($current_user, Permission::PERM_CHANGE_RESERVATION, $reservation->restaurant_id)) {
            return true;
        }

        // Current user is owner of this reservation
        if ($reservation->user_id == $current_user->id) {
            return true;
        }

        return false;
    }

    /**
     * Validate that the current user can reschedule
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationCanReschedule($attribute, Reservation $reservation, $parameters, $validator)
    {
        $current_user = User::getCurrentUser();

        // can view this restaurant
        if ($this->canUserPermissionOnRestaurant($current_user, Permission::PERM_RESCHEDULE_RESERVATION, $reservation->restaurant_id)) {
            return true;
        }

        // Current user is owner of this reservation
        if ($reservation->user_id == $current_user->id) {
            return true;
        }

        return false;
    }

    /**
     * validates if the reservation can be cancelled:
     *  1 - current user is either owner of the reservation
     *  2 - current user is a member of role that has permission can_cancel_reservation
     *  3 - the current user is either admin / reservation manager of the restaurant
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     */
    public function validateReservationCanCancel($attribute, Reservation $reservation, $parameters, $validator)
    {
        $current_user = User::getCurrentUser();

        // can view this restaurant
        if ($this->canUserPermissionOnRestaurant($current_user, Permission::PERM_CANCEL_RESERVATION, $reservation->restaurant_id)) {
            return true;
        }

        // Current user is owner of this reservation
        if ($reservation->user_id == $current_user->id) {
            return true;
        }

        return false;
    }

    /**
     * Validates that the status are either pending / approved / rescheduled
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationStatusForChange($attribute, Reservation $reservation, $parameters, $validator)
    {
        if ($reservation->status != Reservation::STATUS_PENDING
            && $reservation->status != Reservation::STATUS_APPROVED
            && $reservation->status != Reservation::STATUS_RESCHEDULED
            && $reservation->status != Reservation::STATUS_PAID
        ) {
            return false;
        }
        return true;
    }

    /**
     * Validates that the reservation is not already rescheduled
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationStatusForReschedule($attribute, Reservation $reservation, $parameters, $validator)
    {

        if ($reservation->status != Reservation::STATUS_CHANGE_REQUESTED)
            return false;

        return true;
    }

    /**
     * Validates that the reservation is not already cancelled
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationStatusForCancel($attribute, Reservation $reservation, $parameters, $validator)
    {

        if ($reservation->status == Reservation::STATUS_CANCELLED)
            return false;

        return true;
    }

    /**
     * validate that reservation date:time has not passed in the past
     * in other words validate that the user is not trying to change
     * a reservation that was already served.
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationDateHasNotPassed($attribute, Reservation $reservation, $parameters, $validator)
    {
        return time() <= strtotime($reservation->date . ' ' . $reservation->time);
    }

    /**
     * Checks if the current user can PERMISSION_RESERVATION_LIST_ALL
     * or if can PERMISSION_RESERVATION_LIST_RESTAURANT passed in restaurant id
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationList($attribute, $restaurant_id, $parameters, $validator)
    {
        $current_user = User::getCurrentUser();

        // has super admin role with permission to list all
        $a = $this->canUserPermission($current_user, Permission::PERM_LIST_RESERVATIONS);

        // has permission list on given restaurant
        $b = $this->canUserPermissionOnRestaurant($current_user, Permission::PERM_LIST_RESTAURANT_RESERVATIONS, $restaurant_id);

        if ($a || $b)
            return true;

        return false;
    }

    /**
     * A simple validation whether user is entitled to use the coupon
     * or the coupon has no owner, then many users can use it
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateReservationIsCouponOwner($attribute, $value, $parameters, $validator)
    {
        $current_user = User::getCurrentUser();

        $coupon_user_id = $this->getCouponUserfromCode($value);

        if (empty($coupon_user_id) || $current_user->id == $coupon_user_id)
            return true;
        else
            return false;
    }

    /**
     * Check if the current user can list related models of the user
     *
     * @param $attribute
     * @param User $user
     * @param $parameters
     * @param $validators
     * @return bool
     */
    public function validateCanViewUserRelated($attribute, User $user, $parameters, $validators)
    {
        $current_user = User::getCurrentUser();

        // has permission view user related
        $a = $this->canUserPermission($current_user, Permission::PERM_VIEW_USER_RELATED);

        // current user is same user owner of related models
        $b = ($current_user->id == $user->id);

        if ($a || $b)
            return true;

        return false;
    }

    /**
     * validate reservation create if current user can make reservation for other users
     * and user_id is related to the restaurant
     * validate if current user is the same as user_id for reservation
     *
     * @param $attribute
     * @param User $user
     * @param $parameters
     * @param $validators
     * @todo
     */
    public function validateUserCanMakeReservation($attribute, $user_id, $parameters, $validators)
    {
        $current_user = User::getCurrentUser();

        if ($current_user->id == $user_id) {
            return true;
        }

        $request_data = $validators->getData();

        if (isset($request_data['restaurant_id']) && $this->canUserPermissionOnRestaurant($current_user, Permission::PERM_MAKE_RESERVATION_FOR_OTHER_USER, $request_data['restaurant_id']))
            return true;

        return false;
    }

    /**
     * validates that the coupon is unique for both user_id and code
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateCouponUniqueCodeUser($attribute, $value, $parameters, $validator)
    {

        if (empty($value)) {
            return true;
        }

        $request_data = $validator->getData();

        $query = Coupon::select('*')
            ->whereCode($value);

        if (isset($request_data['user_id']) && !empty($request_data['user_id'])) {
            $query->whereUserId($request_data['user_id']);
        }

        $count = $query->count();

        if (
            $parameters[0] == 'PATCH' && $count == 1
        ) {
            // if update an existing entry and only one exists with same value
            $record = $query->first();
            // if different record
            if ($parameters[1] != $record->id) {
                return false;
            }
        } else if ($parameters[0] == 'PATCH' && $count > 1) {
            // if update an existing entry and others exist with same values
            return false;
        } else if ($count > 0) {
            // if create new entry
            return false;
        }

        return true;
    }

    /**
     * validates if current user is same as reservation maker
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateIsReservationOwner($attribute, Reservation $reservation, $parameters, $validator)
    {
        $current_user = User::getCurrentUser();
        if ($reservation->user->id != $current_user->id)
            return false;
        return true;
    }

    /**
     * validates if reservation can be paid
     *
     * @param $attribute
     * @param Reservation $reservation
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateIsReservationApproved($attribute, Reservation $reservation, $parameters, $validator)
    {
        if ($reservation->status != Reservation::STATUS_APPROVED)
            return false;

        return true;
    }

    /**
     * spatial is indoors or outdoors
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validateIsValidSpatialOPtion($attribute, $value, $parameters, $validator)
    {
        $posted_data = $validator->getData();

        if (!isset($posted_data['restaurant_id']))
            return false;

        $model_restaurant = Restaurant::whereId($posted_data['restaurant_id'])->first();

        if (!$model_restaurant)
            return false;

//        if($model_restaurant->in_out_door == Restaurant::INDOORS && $value == Reservation::OUTDOORS)
//            return false;
//
//        if($model_restaurant->in_out_door == Restaurant::OUTDOORS && $value == Reservation::INDOORS)
//            return false;

        return true;
    }
}