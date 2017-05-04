<?php

namespace App\Http\Repositories;

use App\Exceptions\CustomValidationException;
use App\Coupon;
use App\Exceptions\ORMException;
use App\Events\UserLevelWasPromoted;

class CouponRepository
{

    /**
     * assert that:
     *  coupon code+userid is unique and required
     *  type is either percentage or fixed and required
     *  reusable is either 0 or 1
     *  user_id exists or null
     *  expiry date is after now or null
     *  value is required and float
     *
     * @param type $data
     * @throws \App\Exceptions\CartException
     */
    public function validateCoupon($data, $method, $record_id = null)
    {

        $rules = [
            'code' => 'required|check_coupon_unique_code_user:' . $method . ',' . $record_id . '|max:255',
            'value' => 'required|numeric',
            'type' => 'required',
            'reusable' => 'required|boolean',
            'expired_at' => 'sometimes|required',
            'user_id' => 'sometimes|required|exists:users,id'
        ];

        $validator = \Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new CustomValidationException($validator);
        }
    }

    public function saveCoupon($data)
    {
        $result = Coupon::create($data);

        if (!$result) {

            throw new ORMException;
        }

        \Event::fire(new UserLevelWasPromoted($result->user, $result));
    }

    public function updateCoupon($coupon, $data)
    {
        $result = $coupon->update($data);
        if (!$result) {
            throw new ORMException;
        }

        \Event::fire(new UserLevelWasPromoted($coupon->user, $coupon));
    }

    public function handleStoreCoupon($data)
    {
        $data = $this->normalizeData($data);
        $this->validateCoupon($data, 'POST');
        $this->saveCoupon($data);
    }

    public function handleUpdateCoupon($coupon, $data)
    {
        $data = $this->normalizeData($data);
        $this->validateCoupon($data, 'PATCH', $coupon->id);
        $this->updateCoupon($coupon, $data);
    }

    public function normalizeData($data)
    {
        // remove hidden field user id since it's only sometimes required
        if (isset($data['user_id']) && empty($data['user_id'])) {
            unset($data['user_id']);
        }

        // filter boolean

        $data['reusable'] = (isset($data['reusable'])) ? true : false;

        return $data;
    }
}
