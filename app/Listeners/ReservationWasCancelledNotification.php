<?php

namespace App\Listeners;

use App\Events\ReservationWasCancelled;
use App\Http\Helpers\Authorizable;
use App\Http\Helpers\QueryableTrait;
use App\Reservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\role;
use Mail;

class ReservationWasCancelledNotification
{
    /**
     * database queries
     */
    use QueryableTrait;

    /**
     * authorization functions
     */
    use Authorizable;

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
     * @param  ReservationWasCancelled $event
     * @return void
     */
    public function handle(ReservationWasCancelled $event)
    {
        // notify customer
        $this->sendNotificationToCustomer($event->reservation);

        // notify reservation managers
        $this->sendNotificationToReservationManager($event->reservation);
    }

    /**
     * Send e-mail to customer
     *
     * @param Reservation $reservation
     */
    public function sendNotificationToCustomer(Reservation $reservation)
    {
        // Customer Reservation Created Notification
        $user = User::whereId($reservation->user_id)->first();

        Mail::send(
            'emails/cart/customer/cancelled',
            [
                'user' => $user,
                'reservation' => $reservation
            ],
            function ($message) use ($user) {
                $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                $message->to($user->email, $user->name)->subject(trans('Reservation was Cancelled'));
            }
        );
    }

    /**
     * Loop reservation managers and send e-mail
     *
     * @param Reservation $reservation
     * @todo send by permission and not by role
     */
    public function sendNotificationToReservationManager(Reservation $reservation)
    {
        // Customer Basic Info
        $customer = User::find($reservation->user_id)->first()->toArray();

        // Restaurant Manager Notification @todo replace with permission instead of linking to role
        $role_id = Role::name(Role::RESERVATION_MANAGER)->first()->toArray();
        $reservation_managers = $this->getStaffByRestaurantAndRole($reservation->restaurant_id, $role_id['id']);

        // Loop and send to Reservation Managers
        foreach ($reservation_managers as $manager) {
            Mail::send(
                'emails/cart/admin/cancelled',
                [
                    'user' => $manager,
                    'reservation' => $reservation,
                    'customer' => $customer
                ],
                function ($message) use ($manager) {
                    $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                    $message->to($manager->email, $manager->name)->subject(trans('Reservation was Cancelled'));
                }
            );
        }
    }
}
