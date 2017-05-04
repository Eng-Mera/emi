<?php

namespace App\Jobs;

use App\Exceptions\PushNotificationException;
use App\Jobs\Job;
use App\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\UserDevice;
use Mail;
use App\Http\Helpers\Notifiable;

class sendReservationPaidNotifications extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    use Notifiable;

    /**
     * @var Reservation
     */
    private $reservation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Reservation $_reservation)
    {
        $this->reservation = $_reservation;
    }

    /**
     * Execute the job.
     *
     * @param  ReservationWasApproved $event
     * @return void
     */
    public function handle()
    {
        $this->sendReservationWasPaidToCustomer($this->reservation);
        $this->sendPushNotificationToCustomer($this->reservation);
    }

    /**
     * gets email and mobile from reservation object
     * then send an e-mail , SMS & PUSH Notification to the User
     *
     * @todo SMS and Push Notification
     * @param Reservation $reservation
     */
    public function sendReservationWasPaidToCustomer(Reservation $reservation)
    {
        // Customer Reservation Created Notification
        $user = User::whereId($reservation->user_id)->first();

        Mail::send(
            'emails/cart/customer/paid',
            [
                'user' => $user,
                'reservation' => $reservation
            ],
            function ($message) use ($user) {
                $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                $message->to($user->email, $user->name)->subject(trans('Reservation Payment was Received'));
            }
        );
    }

    /**
     * @param Reservation $reservation
     * @throws \App\Http\Helpers\PushNotificationException
     */
    public function sendPushNotificationToCustomer(Reservation $reservation)
    {
        $devices = $this->formatUserDevicesArray($reservation->user);

        $message = trans('cart.notifications.reservation.paid', ['id' => $reservation->id]);

        $params = [
            'reservation_id' => $reservation->id,
            "badge" => 1,
            "sound" => "bingbong.aiff",
            'custom' => [
                "title" => "Reservation Paid",
                "message" => $message,
                "action" => '',
                'reservation_id' => $reservation->id,
                "amount" => $reservation->total,
            ]
        ];

        $this->push($message, $devices, $params);
    }
}
