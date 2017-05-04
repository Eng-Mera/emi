<?php
/**
 * Short description
 *
 * Long description for ApiReservationController.php (if any)...
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Http\Requests;

use App\Http\Requests\Request;


class ReservationRequest extends Request
{

    public function setObject()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
//        return [
//            'date' => 'required|date_format:Y-n-j',
//            'time' => 'required|date_format:H:i|custom_after_now',
//            'number_of_people' => 'required|numeric',
//            'restaurant_id' => 'required|exists:restaurants,id,reservable_online,1',
//            'user_id' => 'required|exists:users,id|check_user_can_make_reservation',
//            'advance_payment' => 'sometimes|required|boolean',
//            'coupon_id' => 'sometimes|required|coupon_check|reservation_is_coupon_owner',
//            'option' => 'sometimes|required|in:INDOORS,OUTDOORS,INOUT|check_reservation_is_valid_spatial_option'
//        ];
    }

    /**
     * Extend these method in your Form Request class if you need to apply more validation in CRUD actions.
     *
     * @param \Illuminate\Http\Request $request
     * @param $currentUser
     * @return bool
     */
    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        return true;
    }
}
