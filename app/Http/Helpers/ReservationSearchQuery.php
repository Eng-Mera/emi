<?php
/**
 * Reservation Search Query
 *
 * search query to filter reservations listing
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Http\Helpers;

use App\Reservation;
use App\RestaurantUsers;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\CustomValidationException;
use Illuminate\Support\Facades\Request;


trait ReservationSearchQuery
{

    /**
     * validates that the reservations can be listed by the logged-in user
     *
     * @param $fields
     * @throws CustomValidationException
     */
    public function validateListReservations($fields)
    {
        if (!isset($fields['restaurant_id']) || empty($fields['restaurant_id'])) {
            $fields['restaurant_id'] = -1;
        }

        $rules = [
            'restaurant_id' => 'reservation_list'
        ];

        $validator = Validator::make($fields, $rules);

        if ($validator->fails()) {
            throw new CustomValidationException($validator);
        }
    }

    /**
     * filter parameters and query listing
     *
     * @param $fields
     * @return mixed
     */
    public function search($fields)
    {

        // unset empty fields
        foreach ($fields as $key => $field) {
            if (empty($field)) {
                unset($fields[$key]);
            }
        }

        $with = ['user', 'user.profile', 'user.profilePicture', 'restaurant'];

        $query = Reservation::select('*');

        $searchParams = [
            Request::get('search', false),
        ];

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';

        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $query = $query->orderBy($orderBy, $orderDir);

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }
        if ($searchQuery) {
            $query = $query->search($searchQuery);
        }

        if (isset($fields['restaurant_id']) && !empty($fields['restaurant_id'])) {
            if (User::getCurrentUser()->hasRole(\App\Role::SUPER_ADMIN)) {
                $query = $query->whereRestaurantId($fields['restaurant_id']);
            } else {

                $restaurant = RestaurantUsers::whereUserId(User::getCurrentUser()->id)->first();

                if (!empty($restaurant)) {
                    $restaurant_id = $restaurant->restaurant_id;
                    $query = $query->whereRestaurantId($restaurant_id);

                }
            }
        }

        if (isset($fields['user_id']) && !empty($fields['user_id'])) {
            $query = $query->whereUserId($fields['user_id']);
        }

        if (isset($fields['status']) && !empty($fields['status'])) {
            if (is_array($fields['status'])) {
                $query = $query->whereIn('status', $fields['status']);
            } else {
                $query = $query->whereStatus($fields['status']);
            }
        }

        if (isset($fields['note']) && !empty($fields['note'])) {
            $query = $query->where('note', 'like', '%' . $fields['note'] . '%');
        }

        if (isset($fields['number_of_people']) && !empty($fields['number_of_people'])) {
            $query = $query->whereNumberOfPeople($fields['number_of_people']);
        }

        if (isset($fields['time']) && !empty($fields['time'])) {
            $query = $query->whereTime(strftime(Reservation::FORMAT_TIME, strtotime($fields['time'])));
        }

        if (isset($fields['date']) && !empty($fields['date'])) {
            $query = $query->where('date', strftime(Reservation::FORMAT_DATE, strtotime($fields['date'])));
        }

        if (isset($fields['total']) && !empty($fields['total'])) {
            $query = $query->whereTotal($fields['total']);
        }

        if (isset($fields['coupon_id']) && $fields['coupon_id']) {
            $query = $query->whereCouponId($fields['coupon_id']);
        }

        if (isset($fields['discount']) && !empty($fields['discount'])) {
            $query = $query->whereDiscount($fields['discount']);
        }
        if (isset($fields['per_page']) && !empty($fields['per_page'])) {
            return $query->with($with)->paginate($fields['per_page']);
        } else {
            return $query->with($with)->paginate();
        }
    }
}