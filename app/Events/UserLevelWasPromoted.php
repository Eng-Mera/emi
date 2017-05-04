<?php

namespace App\Events;

use App\Coupon;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\User;

class UserLevelWasPromoted extends Event
{
    use SerializesModels;

    /**
     * @var User
     */
    public $user;

    /**
     * @var Coupon
     */
    public $coupon;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $_user, Coupon $_coupon = null)
    {
        $this->user = $_user;
        $this->coupon = $_coupon;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
