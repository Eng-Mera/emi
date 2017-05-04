<?php

namespace App\Jobs;

use App\Http\Helpers\Notifiable;
use App\Jobs\Job;
use App\Reservation;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class sendReservationRejectedNotifications extends Job implements ShouldQueue
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
     * @param  ReservationWasRejected $event
     * @return void
     */
    public function handle()
    {
        $this->sendReservationWasRejectedToCustomer($this->reservation);
        $this->sendPushNotificationToCustomer($this->reservation);
    }

    /**
     * gets email and mobile from reservation object
     * then send an e-mail , SMS & PUSH Notification to the User
     *
     * @todo SMS and Push Notification
     * @param Reservation $reservation
     */
    public function sendReservationWasRejectedToCustomer(Reservation $reservation)
    {
        // Customer Reservation Created Notification
        $user = User::whereId($reservation->user_id)->first();

        Mail::send(
            'emails/cart/customer/rejected',
            [
                'user' => $user,
                'reservation' => $reservation
            ],
            function ($message) use ($user) {
                $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                $message->to($user->email, $user->name)->subject(trans('Reservation was Rejected'));
            }
        );
    }

    public function sendPushNotificationToCustomer(Reservation $reservation)
    {
        $devices = [];

        // loop user devices
        foreach ($reservation->user->devices()->get() as $device) {
            $devices[$device->device_type][] = $device->device_id;
        }

        $message = trans('cart.notifications.reservation.rejected', ['id' => $reservation->id]);

        $params = [
            'reservation_id' => $reservation->id,
            "badge" => 1,
            "sound" => "bingbong.aiff",
            'custom' => [
                "title" => "Reservation Rejected",
                "message" => $message,
                "action" => 1,
                'reservation_id' => $reservation->id,
                "amount" => $reservation->total,
            ]
        ];

        $this->push($message, $devices, $params);
    }
}
