<?php
/**
 * Reservation Transformer
 *
 * Convert an array / collection to a readable presentable format
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

namespace App\Transformers;

use App\Reservation;
use League\Fractal\TransformerAbstract;

class ReservationTransformer extends TransformerAbstract
{
    /**
     * convert item to presentable format
     *
     * @param Reservation $reservation
     * @return array
     */
    public function transform(Reservation $reservation)
    {
        return [
            'id' => (int) $reservation->id,
            'restaurant_id' => $reservation->restaurant_id,
            'user_id' => $reservation->user_id,
            'status' => $reservation->status,
            'note' => $reservation->note,
            'number_of_people' => $reservation->number_of_people,
            'time' => $reservation->time,
            'date' => $reservation->date,
            'total' => $reservation->total,
            'coupon_id' => $reservation->coupon_id,
            'discount' => $reservation->discount,
            'amount' => $reservation->amount,
            'advance_payment' => $reservation->advance_payment,
            'created_at' => $reservation->created_at,
            'updated_at' => $reservation->updated_at,
            'user' => $reservation->user
        ];
    }
}