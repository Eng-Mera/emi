<?php

namespace App\Listeners;

use App\Events\RequestReview;
use App\Http\Helpers\Notifiable;
use App\Http\Helpers\QueryableTrait;
use App\Restaurant;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class RequestReviewNotification
{
    use InteractsWithQueue, SerializesModels;

    use Notifiable, QueryableTrait;

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
     * @param  RequestReview $event
     * @return void
     */
    public function handle(RequestReview $event)
    {
        $this->sendEmailNotificationToCustomer($event->user, $event->restaurant);
        $this->sendPushNotificationToCustomer($event->user, $event->restaurant);
    }

    /**
     * Retrieves customer model and then sends an email
     *
     * @param User $user
     * @param Restaurant $restaurant
     */
    public function sendEmailNotificationToCustomer(User $user, Restaurant $restaurant)
    {
        Mail::send('emails/cart/customer/request-review', [
            'user' => $user,
            'restaurant' => $restaurant
        ],
            function ($message) use ($user) {
                $message->from(\Config::get('nilecode.emails.sales.email'), trans(\Config::get('nilecode.emails.sales.name')));
                $message->to($user->email, $user->name)->subject('Request a Review');
            }
        );
    }

    /**
     * @param User $user
     * @param Restaurant $restaurant
     * @throws \App\Http\Helpers\PushNotificationException
     */
    public function sendPushNotificationToCustomer(User $user, Restaurant $restaurant)
    {
        $devices = $this->formatUserDevicesArray($user);

        $message = trans('cart.notifications.request_review.review', ['restaurant_name' => $restaurant->name]);

        $params = [
            'restaurant_name' => $restaurant->name,
            "badge" => 1,
            "sound" => "bingbong.aiff",
            'content-available' => 1,
            'custom' => [
                "title" => "Request a review",
                "message" => $message,
                "action" => '',
                'restaurant_id' => $restaurant->id,
                'restaurant_slug' => $restaurant->slug,
                'restaurant_request_review' => 1,
                'content-available' => 1
            ]
        ];

        $this->push($message, $devices, $params);
    }

    /**
     * formats array as required
     *
     * @param User $user
     * @return array
     */
    public function formatUserDevicesArray(User $user)
    {
        $devices = [];

        // loop user devices
        foreach ($user->devices()->get() as $device) {
            $devices[$device->device_type][] = $device->device_id;
        }

        return $devices;
    }
}
