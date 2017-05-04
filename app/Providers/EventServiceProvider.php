<?php

namespace App\Providers;

use App\Events\ReservationChangeWasRequested;
use App\Events\ReservationWasCancelled;
use App\Events\RequestReview;
use App\Events\ReservationWasCreated;
use App\Events\ReservationWasApproved;
use App\Events\ReservationWasRejected;
use App\Events\ReservationWasRescheduled;
use App\Events\UserLevelWasPromoted;
use App\Listeners\ReservationApprovedNotification;
use App\Listeners\ReservationChangeWasRequestedNotification;
use App\Listeners\ReservationCreatedNotification;
use App\Listeners\ReservationWasCancelledNotification;
use App\Listeners\RequestReviewNotification;
use App\Listeners\ReservationWasRejectedNotification;
use App\Listeners\ReservationWasRescheduledNotification;
use App\Reservation;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener'
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
        ],
        // Cart Module
        ReservationWasCreated::class => [
            ReservationCreatedNotification::class
        ],
        ReservationWasApproved::class => [
            ReservationApprovedNotification::class
        ],
        ReservationWasRejected::class => [
            ReservationWasRejectedNotification::class
        ],
        ReservationChangeWasRequested::class => [
            ReservationChangeWasRequestedNotification::class
        ],
        ReservationWasRescheduled::class => [
            ReservationWasRescheduledNotification::class
        ],
        ReservationWasCancelled::class => [
            ReservationWasCancelledNotification::class
        ],
        UserLevelWasPromoted::class => [
            \App\Listeners\UserLevelWasPromoted::class
        ],
        RequestReview::class => [
            RequestReviewNotification::class
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {

        parent::boot($events);
    }
}
