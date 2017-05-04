<?php

/**
 * A Reservation Trait
 *
 * A leverage of common functions that don't belong to either Model or Controller, but they are related to Reservation.
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */
namespace App\Http\Helpers;

use App\Exceptions\CartException;
use App\Reservation;
use App\ReservationChanges;
use Illuminate\Support\Facades\DB;

Trait ReservableTrait
{
    use DateTimeUtils;

    /**
     * filters changed attributes only
     *
     * @param $fields
     * @param Reservation $reservation
     * @return array
     */
    public function distillChanged($fields, Reservation &$reservation)
    {
        $ret = [];

        $fillable_attributes = $reservation->getFillable();
        foreach ($fields as $key => $value) {
            if (empty($value))
                continue;

            if (!in_array($key, $fillable_attributes))
                continue;

            if ($reservation->$key == $value)
                continue;

            if (($key == 'date' || $key == 'time') && $this->isEqualDatesOrTimes($value, $reservation->$key))
                continue;

            $ret[$key] = $value;
        }

        return $ret;
    }

    /**
     * it is called back by the transaction
     * it loops al changed fields and inserts them
     * then updates the reservation status
     * rollsback all in case of failure handled automatically by Laraval Transaction
     *
     * @param $fields
     * @param Reservation $reservation
     * @return array
     */
    public function reservationChangeTransactionCallback($fields, Reservation &$reservation)
    {
        // no changes
        if (empty($fields))
            throw new CartException(trans('No changes were requested'));

        $ret = [];

        foreach ($fields as $attribute => $value) {

            // delete older changes
            ReservationChanges::where('reservation_id', $reservation->id)
                ->where('attribute', $attribute)
                ->delete();

            // insert EAV
            $single_change = ReservationChanges::create([
                'reservation_id' => $reservation->id,
                'attribute' => $attribute,
                'value' => $value
            ]);

            if (!$single_change)
                throw new CartException(trans('Changes could not be saved'));

            $ret[] = $single_change;
        }

        // update reservation status to change requested
        $result = $reservation->update(['status' => Reservation::STATUS_CHANGE_REQUESTED]);

        if (!$result)
            throw new CartException(trans('Status could not be updated'));

        return $ret;
    }

    /**
     * A wrapper for transaction function call
     *
     * @param $fields
     * @param Reservation $reservation
     */
    public function reservationChangeTransaction($fields, Reservation &$reservation)
    {
        return DB::transaction(function () use ($fields, $reservation) {
            return $this->reservationChangeTransactionCallback($fields, $reservation);
        });
    }

    /**
     * Loop given changes ids
     * Fetch Change Attribute-Value
     * Fill Array of changes for update
     * Apply the Change to the reservation
     *
     * @param Reservation $reservation
     * @return bool|int
     */
    public function applyReservationChangesAux(Reservation $reservation)
    {
        // fill changes
        $attributes_values = [];
        foreach ($reservation->changes as $change) {
            $attributes_values[$change->attribute] = $change->value;
        }

        $attributes_values['status'] = Reservation::STATUS_RESCHEDULED;

        // update
        $result = $reservation->update($attributes_values);

        if (!$result)
            throw new CartException(trans('Changes could not be applied'));

        // delete changes as not necessary any more
        $ret = $reservation->changes()->delete();

        if (!$ret)
            throw new CartException(trans('Changes could not be cleaned'));

        return true;
    }

    /**
     * * Loop given changes ids
     * Fetch Change Attribute-Value
     * Fill Array of changes for update
     * Apply the Change to the reservation
     *
     * @param Reservation $reservation
     * @return bool|int
     */
    public function applyReservationChanges(Reservation $reservation)
    {
        return DB::transaction(function () use ($reservation) {
            return $this->applyReservationChangesAux($reservation);
        });
    }
}