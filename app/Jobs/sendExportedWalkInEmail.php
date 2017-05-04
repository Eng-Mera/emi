<?php

namespace App\Jobs;

use App\Jobs\Job;
use Mail;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendExportedWalkInEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var Restaurant
     */
    private $restaurant;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Restaurant $_restaurant)
    {
        $this->restaurant = $_restaurant;
    }

    /**
     * Execute the job.
     *
     * @param  $event
     * @return void
     */
    public function handle()
    {
        $this->sendExportedFile($this->restaurant);
    }

    /**
     * Send Exported walkin data to restaurant manager.
     *
     * @param Restaurant $restaurant
     */
    public function sendExportedFile(Restaurant $restaurant)
    {
        $request = app(Request::class);

        $exported_file = $request->file('exported_file');

        Mail::send(
            'emails/restaurant/walkin',
            [
                'restaurant' => $restaurant
            ],
            function ($message) use ($restaurant, $exported_file) {

                $title = trans('Export Report for walk-in for date ' . date('D m Y', time()));

                $path = $exported_file->getRealPath();

                $message->attach($path, [
                    'as' => 'report-walkin' . date('Y-m-d', time()) . '.csv',
                ]);

                $message->from('no-reply@howtheyrate.net', trans('HTR Team'));

                if ($restaurant->email) {
                    $message->to($restaurant->email)->subject($title);
                }
            }
        );
    }
}
