<?php

namespace App\Jobs;

use App\Coupon;
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
use App\Http\Helpers\QueryableTrait;
use App\Role;

class sendReservationPendingNotifications extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    use Notifiable, QueryableTrait;

    /**
     * @var array
     */
    private $reservation_managers;

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
        $this->setReservationManagers($this->reservation);
    }

    /**
     * sets reservation managers
     */
    public function setReservationManagers(Reservation $reservation)
    {
        // Restaurant Manager Notification
        $role_id = Role::name(Role::RESERVATION_MANAGER)->first()->toArray();
        $this->reservation_managers = $this->getStaffByRestaurantAndRole($reservation->restaurant_id, $role_id['id']);
    }

    /**
     * Execute the job.
     *
     * @param  ReservationWasApproved $event
     * @return void
     */
    public function handle()
    {
        // notify customer
        $this->sendReservationWasCreatedToCustomer($this->reservation);

        // notify admin
        $this->sendReservationWasCreatedToReservationManagers($this->reservation);

        // expire coupon if any
        $this->addCouponExpiryDate($this->reservation);
    }

    /**
     * @param Reservation $reservation
     */
    public function sendReservationWasCreatedToCustomer(Reservation $reservation)
    {
        $this->sendReservationWasCreatedToCustomerByEmail($reservation);
        //$this->sendPushNotificationToCustomer($reservation);
    }

    /**
     * @param Reservation $reservation
     */
    public function sendReservationWasCreatedToReservationManagers(Reservation $reservation)
    {
        $this->sendReservationWasCreatedToReservationManagersByEmail($reservation);
    }

    /**
     * gets email and mobile from reservation object
     * then send an e-mail , SMS & PUSH Notification to the User
     *
     * @param Reservation $reservation
     */
    public function sendReservationWasCreatedToCustomerByEmail(Reservation $reservation)
    {
        // Customer Reservation Created Notification
        Mail::send(
            'emails/cart/customer/pending',
            [
                'user' => $reservation->user,
                'reservation' => $reservation
            ],
            function ($message) use ($reservation) {
                $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                $message->to($reservation->user->email, $reservation->user->name)->subject(trans('Reservation is Pending'));
            }
        );
    }

    /**
     * get restaurant id from reservation objec
     * loop all reservation managers for the found restaurant
     * send e-mail to reservation managers
     *
     * @param Reservation $reservation
     * @todo send by permission and not by role
     */
    public function sendReservationWasCreatedToReservationManagersByEmail(Reservation $reservation)
    {
        // Customer Basic Info
        $customer = $reservation->user->toArray();

        // Loop and send to Reservation Managers
        foreach ($this->reservation_managers as $manager) {
            Mail::send(
                'emails/cart/admin/pending',
                [
                    'user' => $manager,
                    'reservation' => $reservation,
                    'customer' => $customer
                ],
                function ($message) use ($manager) {
                    $message->from('sales@howtheyrate.net', trans('HTR Sales!'));
                    $message->to($manager->email, $manager->name)->subject(trans('Reservation is Pending'));
                }
            );
        }
    }

    /**
     * add current timestamp as coupon expiry date
     *
     * @param Reservation $reservation
     * @throws CartException
     */
    public function addCouponExpiryDate(Reservation $reservation)
    {
        // no coupon was applied
        if (empty($reservation->coupon_id))
            return;

        // get model
        $coupon = Coupon::whereId($reservation->coupon_id)->first();

        if (!$coupon)
            throw new CartException(trans('No coupon was applied to this reservation'));

        $coupon->expired_at = time();

        $result = $coupon->update();

        if (!$result)
            throw new CartException(trans('Coupon could not be invalidated'));
    }

    /**
     * @param Reservation $reservation
     * @throws \App\Http\Helpers\PushNotificationException
     */
    public function sendPushNotificationToCustomer(Reservation $reservation)
    {
        $devices = $this->formatUserDevicesArray($reservation->user);

        $message = trans('cart.notifications.reservation.pending', ['id' => $reservation->id]);

        $params = [
            'reservation_id' => $reservation->id,
            "badge" => 1,
            "sound" => "bingbong.aiff",
            'custom' => [
                "title" => "Reservation Pending",
                "message" => $message,
                "action" => '',
                'reservation_id' => $reservation->id,
                "amount" => $reservation->total,
            ]
        ];

        $this->push($message, $devices, $params);
    }
}
