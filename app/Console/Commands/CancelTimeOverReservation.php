<?php

namespace App\Console\Commands;

use App\Reservation;
use Illuminate\Console\Command;

class CancelTimeOverReservation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservation:cancel-timeout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for accepted and pending orders which its date of reservation has been expired and change its status to canceled';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $status = [Reservation::STATUS_CHANGE_REQUESTED, Reservation::STATUS_PENDING, Reservation::STATUS_APPROVED];

        $currentDate = date('Y-m-d');
        $currentDateStamp = time();

        $reservations =
            Reservation::whereIn('status', $status)
                ->where('date', '<=', $currentDate)
                ->get();

        if (!$reservations->count()) {
            $this->info('No Reservation found!');
            return false;
        }

        foreach ($reservations as $reservation) {

            $reservationDate = strtotime($reservation->date . ' ' . $reservation->time);

            if ($reservationDate <= $currentDateStamp) {
                $reservation->status = Reservation::STATUS_CANCELLED;
                $reservation->save();
                $this->info($reservation->user_id. ' ' . $reservation->status);
            }
        }

        $this->info('Finished');
    }
}
