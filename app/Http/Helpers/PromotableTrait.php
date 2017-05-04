<?php
/**
 * PromotableTrait
 *
 * Methods related to coupon assignment to users
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

trait PromotableTrait
{

    /**
     * Users from level 5 to level 9 will receive regular 10% discount to all places. Users who reach level 10 will receive 20% discount.
     *
     * @param $user_id
     * @param $user_level
     */
    public function generateUserCoupons($user_id, $user_level)
    {
        $user_level = 20;

        $flag_should_generate = false;
        if ($user_level >= 5 && $user_level <= 9) {
            $attrs = [
                'code' => $this->generateRandomPromoCode(),
                'value' => 10,
                'reusable' => false,
                'type' => Coupon::COUPON_TYPE_PERCENTAGE,
                'user_id' => $user_id,
                'expired_at' => null
            ];
            $flag_should_generate = true;
        } else if ($user_level >= 10) {
            $attrs = [
                'code' => $this->generateRandomPromoCode(),
                'value' => 20,
                'reusable' => false,
                'type' => Coupon::COUPON_TYPE_PERCENTAGE,
                'user_id' => $user_id,
                'expired_at' => null
            ];
            $flag_should_generate = true;
        }

        if (!$flag_should_generate) {
            return null;
        }

        try {
            $result = Coupon::create($attrs);
        } catch (\Exception $ex) {
            throw new CartException(trans('Promo Code / Coupon could not be generated'));
        }
        return $result;
    }

    /**
     * generate random string
     *
     * @return mixed
     */
    public function generateRandomPromoCode()
    {
        $integer_high = (int)(rand(time(), time() + 60 * 60 * 24));
        $integer_low = (int)(rand(time(), time() + 60 * 60 * 24) % 1000);

        $arr_rnd_str_high = str_split(substr($integer_high,0,3));
        $arr_rnd_str_low = str_split(strrev($integer_low));

        $arr_rnd_str = [];

        for ($i = 0; $i < count($arr_rnd_str_high); $i++) {
            if ($i % 2 == 1) {
                $arr_rnd_str[] = $arr_rnd_str_high[$i];
                continue;
            }

            $arr_rnd_str[] = chr($arr_rnd_str_high[$i] + 65);
        }

        for ($i = 0; $i < count($arr_rnd_str_low); $i++) {
            if ($i % 2 == 0) {
                $arr_rnd_str[] = $arr_rnd_str_low[$i];
                continue;
            }

            $arr_rnd_str[] = chr($arr_rnd_str_low[$i] + 65);
        }

        return implode('', $arr_rnd_str);
    }

    /**
     * checks whether values were supplied and sets defaults for them
     *
     * @param $fields
     * @return mixed
     */
    public function setCouponDefaults($fields)
    {
        return array_merge($fields, [
            'expired_at' => isset($fields['expired_at']) ? $fields['expired_at'] : null,
            'code' => $this->generateRandomPromoCode(),
            'reusable' => isset($fields['reusable']) ? $fields['reusable'] : false,
            'type' => isset($fields['type']) ? $fields['type'] : Coupon::COUPON_TYPE_PERCENTAGE
        ]);
    }
}