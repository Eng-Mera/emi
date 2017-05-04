<?php
/**
 * Admin Coupon Form View Composer
 *
 * generates necessary values for view
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\ViewComposers;

use App\Coupon;
use Illuminate\Contracts\View\View;

class AdminCouponFormComposer
{
    /**
     * @var array
     */
    protected $coupon_types;

    public function __construct()
    {
        $this->coupon_types = [
            Coupon::COUPON_TYPE_PERCENTAGE => Coupon::COUPON_TYPE_PERCENTAGE,
            Coupon::COUPON_TYPE_FIXED => Coupon::COUPON_TYPE_FIXED
        ];
    }

    public function compose(View $view)
    {
        $view->with('coupon_types', $this->coupon_types);
    }
}