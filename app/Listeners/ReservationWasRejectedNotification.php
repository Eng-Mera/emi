<?php

namespace App\Listeners;

use App\Events\ReservationWasRejected;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation;
use App\User;
use Mail;

class ReservationWasRejectedNotification
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
     * @todo SMS and Push Notification
     * @param  ReservationWasRejected $event
     * @return void
     */
    public function handle(ReservationWasRejected $event)
    {
        $this->sendEmailNotificationToCustomer($event->reservation);
    }

    /**
     * Retrieves customer model and then sends an email
     *
     * @param Reservation $reservation
     */
    public function sendEmailNotificationToCustomer(Reservation $reservation)
    {
        $user = User::whereId($reservation->user_id)->first();

        Mail::send('emails/cart/customer/rejected', [
            'user' => $user,
            'reservation' => $reservation
        ],
            function ($message) use ($user) {
                $message->from('sales@HTR.com', trans('HTR Sales!'));
                $message->to($user->email, $user->name)->subject('Reservation was Rejected!');
            }
        );
    }
}
