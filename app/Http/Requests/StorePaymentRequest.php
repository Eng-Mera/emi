<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class StorePaymentRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @todo different products as entities will be purchasable online
     *          add their foreign key as id
     *          use validation rule sometimes and a custome validator
     *          then make sure that the required entity exists
     *          depending on a key value supplied
     * @required in phase 2
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method_id' => 'sometimes|required|exists:payment_methods,id,enabled,1',
            'reservation_id' => 'required|exists:reservations,id|reservation_status_check|reservation_advance_payment_check'
        ];
    }
}
