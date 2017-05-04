<?php
/**
 * Short description
 *
 * Long description for CartTrait.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Helpers;

use App\Coupon;
use App\Exceptions\CartException;
use App\Reservation;
use App\ReservationChanges;
use App\ReservationPolicy;
use App\Restaurant;
use DB;
use Mockery\CountValidator\Exception;

trait CartTrait
{
    /**
     * amount = number of guests x price per guest
     * @param $restaurant_id
     * @param $number_of_guests
     */
    public function calculateReservationAmount($restaurant_id, $number_of_guests, $date)
    {

        $amount = $this->calculateReservationPolicyAmount($restaurant_id, $number_of_guests, $date);

        if ($amount) {
            return $amount;
        }

        $amount = Restaurant::GetAmount($restaurant_id);

        if (is_array($amount) && isset($amount[0])) {
            $amount = $amount[0];
        }

        return floatval($amount) * intval($number_of_guests);
    }

    /**
     * Calculate Amount the user must pay if the reservation date exists in any of restaurant's reservation policy
     *
     * @param $restaurant_id
     * @param $number_of_guests
     * @param $date
     * @return bool|float
     */
    public function calculateReservationPolicyAmount($restaurant_id, $number_of_guests, $date)
    {
        if (!$date) {
            return false;
        }

        $policy = ReservationPolicy::whereRestaurantId($restaurant_id)
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where('status', '=', 1)
            ->first();

        if (!$policy) {
            return false;
        }

        $amount = $policy->amount;

        return floatval($amount) * intval($number_of_guests);

    }

    /**
     * Calculate total amount after discount by coupons
     * Total = Amount - Discount
     * Coupons either percentage or fixed amount
     */
    public function calculateReservationTotal($restaurant_id, $number_of_guests, $date, $coupon_code = null)
    {
        $amount = $this->calculateReservationAmount($restaurant_id, $number_of_guests, $date);

        if (is_null($coupon_code) || empty($coupon_code))
            return $amount;

        // reduce coupon
        $coupon_reduction = $this->calculateCouponValue($coupon_code, $amount);
        $amount = $amount - $coupon_reduction;

        // taxes or other calculations

        // return total amount
        return $amount;
    }

    /**
     * if coupon is fixed amount then return fixed amount
     * if coupon is percentage then return amount * coupon / 100
     * @param $coupon_id
     * @param $total
     */
    public function calculateCouponValue($coupon_code, $total)
    {
        if (empty($coupon_code))
            return 0;

        $coupon = Coupon::code($coupon_code)->first();

        if (!$coupon)
            return 0;

        $coupon = $coupon->toArray();

        if ($coupon['type'] == Coupon::COUPON_TYPE_FIXED) {
            return $coupon['value'];
        } else if ($coupon['type'] == Coupon::COUPON_TYPE_PERCENTAGE) {
            return floatval($coupon['value']) * floatval($total) / 100;
        } else {
            return 0;
        }
    }

    /**
     * Switch and route to the correct payment handler depending on three criteria
     * 1 - entity_type
     * 2 - reservation_status =>
     *  -- pending --> capture
     *  -- approved --> authorize
     * 3 - payment_mthod_id =>
     *  -- 2D
     *  -- 3D
     *
     * @note don't ever make calculations in here / even if payment method fees are to be calculated
     *       they should be calculated in the storeReservation Request
     *
     * @param $entity_id
     * @param $entity_type Reservation | Subscription | Product ... etc
     * @param $payment_method_id
     */
    public function doPay($entity_id, $entity_type, $payment_method_id)
    {
        dd($entity_id, $entity_type, $payment_method_id);
    }

    /**
     * Controller method that calls all the required calculations in order
     *
     * @param $restaurant_id
     * @param $number_of_people
     * @param $coupon_code
     * @return array
     */
    public function populateFields($restaurant_id, $number_of_people, $coupon_code, $date)
    {
        $defaults = [];

        $defaults['amount'] = $this->calculateReservationAmount($restaurant_id, $number_of_people, $date);
        $defaults['total'] = $this->calculateReservationTotal($restaurant_id, $number_of_people, $date, $coupon_code);
        $defaults['discount'] = $this->calculateCouponValue($coupon_code, $defaults['amount']);
        $defaults['coupon_id'] = $this->getCouponIdFromCode($coupon_code);

        return $defaults;
    }

    /**
     * call populateFields and feeds it the changed fields or the old ones
     *
     * @param $fields
     * @param Reservation $reservation
     * @return Array
     */
    public function populateFieldsForChange($fields, Reservation &$reservation)
    {
        $restaurant_id = !empty($fields['restaurant_id']) ? $fields['restaurant_id'] : $reservation->restaurant_id;
        $number_of_people = !empty($fields['number_of_people']) ? $fields['number_of_people'] : $reservation->number_of_people;
        $coupon_code = !empty($fields['coupon_code']) ? $fields['coupon_code'] : '';
        $date = !empty($fields['date']) ? $fields['date'] : false;

        $defaults = $this->populateFields($restaurant_id, $number_of_people, $coupon_code, $date);

        return array_merge($fields, $defaults);
    }

}