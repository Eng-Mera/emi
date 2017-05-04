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

class sendReservationChangedNotifications extends Job implements ShouldQueue
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
        $this->sendReservationWasChangedToCustomer($this->reservation);
        $this->sendPushNotificationToCustomer($this->reservation);
    }

    /**
     * gets email and mobile from reservation object
     * then send an e-mail , SMS & PUSH Notification to the User
     *
     * @todo SMS and Push Notification
     * @param Reservation $reservation
     */
    public function sendReservationWasChangedToCustomer(Reservation $reservation)
    {
        // Customer Reservation Created Notification
        $user = User::whereId($reservation->user_id)->first();

        Mail::send(
            'emails/cart/customer/rescheduled',
            [
                'user' => $user,
                'reservation' => $reservation
            ],
            function ($message) use ($user) {
                $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                $message->to($user->email, $user->name)->subject(trans('Reservation was Rescheduled'));
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
        $time = date( 'g:ia', strtotime($reservation->time));
        $message = trans('cart.notifications.reservation.approved', ['id' => $reservation->id , 'date' => $reservation->date , 'time' => $time]);

        $params = [
            'reservation_id' => $reservation->id,
            "badge" => 1,
            "sound" => "bingbong.aiff",
            'custom' => [
                "title" => "Reservation Rescheduled",
                "message" => $message,
                "action" => 1,
                'reservation_id' => $reservation->id,
                "amount" => $reservation->total,
            ]
        ];

        $this->push($message, $devices, $params);
    }
}
