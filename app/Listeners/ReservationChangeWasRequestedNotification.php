<?php

namespace App\Listeners;

use App\Events\ReservationChangeWasRequested;
use App\Http\Helpers\QueryableTrait;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation;
use App\User;
use App\Role;

class ReservationChangeWasRequestedNotification
{
    use QueryableTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ReservationChangeWasRequested $event
     * @return void
     */
    public function handle(ReservationChangeWasRequested $event)
    {
        // notify reservation managers
        $this->sendReservationChangeWasRequestedToReservationManagers($event->reservation);
    }

    /**
     * get restaurant id from reservation objec
     * loop all reservation managers for the found restaurant
     * send e-mail to reservation managers
     *
     * @param Reservation $reservation
     * @send by permission and not by role
     */
    public function sendReservationChangeWasRequestedToReservationManagers(Reservation $reservation)
    {
        // Customer Basic Info
        $customer = User::find($reservation->user_id)->first()->toArray();

        // Restaurant Manager Notification
        $role_id = Role::name(Role::RESERVATION_MANAGER)->first()->toArray();
        $reservation_managers = $this->getStaffByRestaurantAndRole($reservation->restaurant_id, $role_id['id']);

        // Loop and send to Reservation Managers
        foreach ($reservation_managers as $manager) {
            \Mail::send(
                'emails/cart/admin/change_requested',
                [
                    'user' => $manager,
                    'reservation' => $reservation,
                    'customer' => $customer
                ],
                function ($message) use ($manager) {
                    $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                    $message->to($manager->email, $manager->name)->subject(trans('Reservation change was requested'));
                }
            );
        }
    }
}
