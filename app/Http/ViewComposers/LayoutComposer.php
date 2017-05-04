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
use App\Restaurant;
use App\Role;
use App\User;
use Illuminate\Contracts\View\View;

class LayoutComposer
{
    private function restaurantSlug()
    {
        return @User::getManagersRestaurant()->slug;
    }

    public function compose(View $view)
    {
        $view->with('restaurant_slug', $this->restaurantSlug());
    }
}
