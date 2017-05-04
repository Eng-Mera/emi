<?php
namespace App\Http\Repositories;

use App\Exceptions\CustomValidationException;
use App\Exceptions\ORMException;
use App\Http\Helpers\ReservationSearchQuery;
use App\Reservation;
use App\ReservationPaymentLog;

class ReservationRepository
{
    /**
     * traits
     */
    use ReservationSearchQuery;

    /**
     * @param $data
     * @throws CustomValidationException
     */
    public function validateDoPayData($data)
    {
        $rules = [
            'reservation' => 'check_reservation_is_approved|check_reservation_is_owner',
            'external_reference' => 'required'
        ];

        $validator = \Validator::make($data, $rules);

        if ($validator->fails())
            throw new CustomValidationException($validator);
    }

    /**
     * @param $data
     */
    public function storeDoPayData($data)
    {
        $reservation = $data['reservation'];

        $result = $reservation->update([
            'status' => Reservation::STATUS_PAID,
            'external_reference' => $data['external_reference']
        ]);

        if (!$result)
            throw new ORMException;

        $log_data = [];

        if (isset($data['request']) && !empty($data['request'])) {
            $log_data['request'] = $data['request'];
        }

        if (isset($data['response']) && !empty($data['response'])) {
            $log_data['response'] = $data['response'];
        }

        if(empty($log_data))
            return;

        $log = new ReservationPaymentLog($log_data);

        $result = $reservation->logs()->save($log);

        if(!$result)
            throw new ORMException;
    }

    /**
     * @param $data
     * @throws CustomValidationException
     */
    public function doPay($data)
    {
        $this->validateDoPayData($data);
        $this->storeDoPayData($data);
    }

    /**
     * list reservation
     *
     * @param $data
     * @return mixed
     */
    public function listReservations($data)
    {
        $this->validateListReservations($data);

        return $this->search($data);
    }
}