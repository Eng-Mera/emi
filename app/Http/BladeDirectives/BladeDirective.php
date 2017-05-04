<?php
namespace App\Http\BladeDirectives;

use App\Restaurant;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Blade;

trait BladeDirective
{

    public function initBladeDirective()
    {
        $this->IsManager();
    }

    public function IsManager()
    {
        $expression = '["' . Role::RESTAURANT_MANAGER . '", "' . Role::RESTAURANT_ADMIN . '","' . Role::RESERVATION_MANAGER . '"]';

        Blade::directive('is_manager_no_restaurant', function () {

            $expression = '["' . Role::RESTAURANT_MANAGER . '", "' . Role::RESTAURANT_ADMIN . '","' . Role::RESERVATION_MANAGER . '"]';
            return "<?php if (\\Entrust::hasRole({$expression}) && 
            !\\App\\Http\\BladeDirectives\\BladeDirective::checkIsManager()) : ?>";
        });

        Blade::directive('end_is_manager', function () {
            return "<?php endif; ?>";
        });
    }

    public static function checkIsManager()
    {
        $restaurant = User::getManagersRestaurant();

        if ($restaurant) {
            return $restaurant->slug;
        }

        return false;
    }

}