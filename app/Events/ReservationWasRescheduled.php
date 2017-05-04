<?php

namespace App\Events;

use App\Events\Event;
use App\Reservation;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReservationWasRescheduled extends Event
{
    use SerializesModels;

    /**
     * @var Reservation
     */
    public $reservation;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Reservation $_reservation)
    {
        $this->reservation = $_reservation;
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
