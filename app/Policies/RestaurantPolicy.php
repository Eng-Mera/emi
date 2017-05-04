<?php

namespace App\Policies;

use App\Restaurant;
use App\Role;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RestaurantPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        $user = User::getCurrentUser();
        
        if ($user->hasRole(Role::SUPER_ADMIN)) {
            return true;
        }
    }

    private function restaurantChilds($user, $restaurant)
    {
        $res = User::getManagersRestaurant();

        return @$res->id === $restaurant->id;

    }

    /**
     * Determine if the given Restaurant can be updated by the user.
     *
     * @param  \App\User $user
     * @param  Restaurant $restaurant
     * @return bool
     */
    public function listMenuItems(User $user, Restaurant $restaurant)
    {
        return $this->restaurantChilds($user, $restaurant);
    }

    /**
     * Determine if the given Restaurant can be updated by the user.
     *
     * @param  \App\User $user
     * @param  Restaurant $restaurant
     * @return bool
     */
    public function listReservationPolicy(User $user, Restaurant $restaurant)
    {
        return $this->restaurantChilds($user, $restaurant);
    }

    /**
     * Determine if current user is can request reviews for specified restaurant.
     *
     * @param  \App\User $user
     * @param  Restaurant $restaurant
     * @return bool
     */
    public function requestReview(User $user, Restaurant $restaurant)
    {
        return $this->restaurantChilds($user, $restaurant);
    }

    /**
     * Determine if the given Restaurant can be updated by the user.
     *
     * @param  \App\User $user
     * @param  Restaurant $restaurant
     * @return bool
     */
    public function listOpeningDay(User $user, Restaurant $restaurant)
    {
        return $this->restaurantChilds($user, $restaurant);
    }

    /**
     * Determine if the given Restaurant can be updated by the user.
     *
     * @param  \App\User $user
     * @param  Restaurant $restaurant
     * @return bool
     */
    public function listJobVacancy(User $user, Restaurant $restaurant)
    {
        if (!$restaurant->allow_job_vacancies) {
            return false;
        }
        return $this->restaurantChilds($user, $restaurant);
    }
}
