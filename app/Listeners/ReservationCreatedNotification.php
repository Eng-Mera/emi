<?php

namespace App\Listeners;

use App\Events\ReservationWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReservationCreatedNotification
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
     * @param  ReservationWasCreated  $event
     * @return void
     */
    public function handle(ReservationWasCreated $event)
    {
        //
    }
}
