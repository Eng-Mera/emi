<?php

namespace App\Listeners;

use App\Events\ReservationWasRescheduled;
use App\Reservation;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use Mail;

class ReservationWasRescheduledNotification
{
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
     * @param  ReservationWasRescheduled $event
     * @return void
     */
    public function handle(ReservationWasRescheduled $event)
    {
        $this->sendNotificationToCustomer($event->reservation);
    }

    /**
     * gets customer from reservation and then send e-mail notification
     *
     * @param Reservation $reservation
     */
    public function sendNotificationToCustomer(Reservation $reservation)
    {
        $user = User::whereId($reservation->user_id)->first();

        Mail::send('emails/cart/customer/rescheduled', [
            'user' => $user,
            'reservation' => $reservation
        ],
            function ($message) use ($user) {
                $message->from('sales@HTR.com', trans('HTR Sales!'));
                $message->to($user->email, $user->name)->subject('Reservation was Rescheduled!');
            }
        );
    }
}
