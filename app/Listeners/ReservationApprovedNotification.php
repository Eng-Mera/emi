<?php

namespace App\Listeners;

use App\Events\ReservationWasApproved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationApprovedNotification
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
     * @param  ReservationWasApproved  $event
     * @return void
     */
    public function handle(ReservationWasApproved $event)
    {
        //
    }
}
