<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Requests;
use App\Http\Controllers\APIs\ApiRestaurantController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Dingo\Api\Http\Middleware\Auth;

use Input;
use App\Restaurant;
use DB;
use Excel;
use App\Http\Controllers\Controller;
use Exception;

class restaurantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Restaurant {import_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = ' Command Restaurant Uploading';

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
        $dispatcher = app('Dingo\Api\Dispatcher');
        if($this->argument('import_file'))        
        {                        
            $request = new Requests\RestaurantRequest();
            $this->info($this->argument('import_file'));

            $path = $this->argument('import_file');
            $data = Excel::load($path, function($reader) {})->get();
            
            if(!empty($data) && $data->count())
            {

                
                foreach ($data as $key => $value)
                {                    
                    try
                    {                        
//                        $b64image = base64_encode(file_get_contents($value->logo));
//                        $b64image = "data:image/png;base64," . $b64image;
                        
                        $slug = str_slug($value->name, "-");

                        // check retaurant if exist
                        $restaurant = Restaurant::whereSlug($slug)->first();
                        if ($restaurant) {
                            continue;
                        }
                        
                        echo $value->name . PHP_EOL;


                        $inputs = 
                        [
                            'slug' => $slug, 
                            'address' => $value->address, 
                            'phone' => $value->phone, 
                            'latitude' => $value->lat, 
                            'longitude' => $value->long,
                            'email' => $value->email,
//                            'logo' => $b64image
                        ];


                        $facilities = $dispatcher->get('api/v1/facility?per_page=-1');
                        $facilities = $facilities->pluck('name', 'id')->toArray();
                        $facilities = array_map('trim', $facilities);
                        $facilities = array_map('strtolower', $facilities);

                        $features = [$value->feature1, $value->feature2, $value->feature3, $value->feature4, $value->feature5, $value->feature6];
                        
                        $facilities_id = [];
                        foreach ($features as $feature) {
                            $check = array_search(strtolower(trim($feature)), $facilities);
                            if ($check) {
                                $facilities_id[] = $check;
                            }
                            else
                            {
                                if (!empty(trim($feature)))
                                {
                                    $facInputs['I18N']['en']['name'] = trim($feature);
                                    $facility = $dispatcher->post('api/v1/facility',$facInputs);
                                    $facilities_id[] = $facility->id ;
                                }

                            }

                        }
                        if (!empty($facilities_id)) {
                            $inputs['facilities'] = $facilities_id;
                        }
                        $categories = $dispatcher->get('api/v1/category?per_page=-1');

                        $tags = $value->tags;
                        $tags = explode(",", $tags);

                        $cats = $categories->pluck('category_name', 'id')->toArray();
                        $cats = array_map('trim', $cats);
                        $cats = array_map('strtolower', $cats);
                        $categories_id = [];

                        foreach ($tags as $tag) {
                            $category_id = array_search(strtolower(trim($tag)), $cats);
                            if ($category_id) {
                                $categories_id[] = $category_id;

                            } else {
                                if (!empty(trim($tag)))
                                {
                                    $catInputs['I18N']['en']['category_name'] = trim($tag);
                                    $category = $dispatcher->post('api/v1/category',$catInputs);
                                    $categories_id[] = $category->id;
                                }
                            }
                        }
                        
                        if (!empty($categories_id)) {
                            $inputs['categories'] = $categories_id;
                        }
                        $inputs['I18N']['en']['name'] = $value->name;


                        $restaurant = $dispatcher->attach($request->allFiles())->post('api/v1/restaurant',$inputs);

                        $weekDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                        $timing = ['am', 'pm'];

                        foreach ($weekDays as $day) {
                            if (strpos('24 HoursOPEN', $value->openingHours) !== -1) {
                                $input['status'] = 1;
                                $input['day_name'] = $day;
                                $input['from'] = '00:00';
                                $input['to'] = '20:00';
                                $openingday = $dispatcher->post('api/v1/restaurant/'.$restaurant->slug.'/opening-days',$input);                                
                            } else {                            
                                $openingTime = str_replace($timing, "", $value->openingHours);                            
                                $openingTime = str_replace("Opening hours: ", "", $openingTime);
                                $openingTime = str_replace(" to ", " ", $openingTime);
                                $openingHours = explode(" ", $openingTime);

                                $input['status'] = 1;
                                $input['day_name'] = $day;
                                echo $input['from'] = trim($openingHours[0]); echo PHP_EOL;
                                echo $input['to'] = trim($openingHours[1]); echo PHP_EOL;
                                $openingday = $dispatcher->post('api/v1/restaurant/'.$restaurant->slug.'/opening-days',$input);
                            }
                        }
                    }
                    catch (\Exception $e)
                    {
                        var_dump($e->getMessage());
                        var_dump($e->getTraceAsString());
                    }
                }
                
                $msg = 'Restaurants Data have been uploaded successfully!';
                return Redirect::route('admin.restaurant.index')->with('content-message', trans($msg));

            }
        }
        return back();
    }
}
