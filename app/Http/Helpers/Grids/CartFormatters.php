<?php

namespace App\Http\Helpers\Grids;

use App\Coupon;

trait CartFormatters {

    /**
     * format coupon value so that % is prepended or plain value is returned
     * 
     * @param Coupon $coupon
     * @return type
     */
    public function formatCouponValue(Coupon $coupon) {
        if ($coupon->type == Coupon::COUPON_TYPE_PERCENTAGE) {
            return '<span class="label label-info">%' . $coupon->value . '</span>';
        } else {
            return '<span class="label label-info">%' . \Config::get('nilecode.cart.currency.code')
                    . ' '
                    . \Config::get('nilecode.cart.currency.symbol')
                    . $coupon->value
                    . '</span>';
        }
    }

    function formatCouponReusable($val) {
        if ($val) {
            return '<span class="label label-success">' . trans('Reusable') . '</span>';
        } else {
            return '<span class="label label-danger">' . trans('Only Once') . '</span>';
        }
    }

    function formatCouponUser(Coupon $coupon) {
        if (!$coupon->user) {
            return '<span class="label label-danger">' . trans('not set') . '</span>';
        } else {
            $url = url('admin/user', ['user_id' => $coupon->user->id]);
            return '<a href="{$url}">' . $coupon->user->name . '</a>';
        }
    }

    function formatCouponRestaurant(Coupon $coupon) {
        if (!$coupon->restaurant) {
            return '<span class="label label-danger">' . trans('not set') . '</span>';
        } else {
            $url = url('admin/restaurant', ['user_id' => $coupon->user->id]);
            return '<a href="{$url}">' . $coupon->restaurant->name . '</a>';
        }
    }

}
