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
class branchesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:branches {import_branch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Branches Uploading';

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
        if($this->argument('import_branch'))
        {

            $request = new Requests\RestaurantRequest();
            $path = $this->argument('import_branch');
            $data = Excel::load($path, function($reader) {})->get();

            if(!empty($data) && $data->count())
            {

                $cities = $dispatcher->get('api/v1/city?per_page=-1');
                $cities = $cities->pluck('city_name','id')->toArray();
                $cities = array_map('trim', $cities);
                $cities = array_map('strtolower', $cities);

                $districts = $dispatcher->get('api/v1/district?per_page=-1');
                $districts = $districts->pluck('district_name','id')->toArray();
                $districts = array_map('trim', $districts);
                $districts = array_map('strtolower', $districts);

                $facilities = $dispatcher->get('api/v1/facility?per_page=-1');
                $facilities = $facilities->pluck('name', 'id')->toArray();
                $facilities = array_map('trim', $facilities);
                $facilities = array_map('strtolower', $facilities);

                $categories = $dispatcher->get('api/v1/category?per_page=-1');
                $cats = $categories->pluck('category_name', 'id')->toArray();
                $cats = array_map('trim', $cats);
                $cats = array_map('strtolower', $cats);


                foreach ($data as $key => $value)
                {
                    try
                    {
                        $restaurantName = preg_replace('/^\"?(.*?(?=\"?$))\"?$/','$1' ,$value->restaurant_en);
                        $restaurant_slug = str_slug($restaurantName,'-');
                        $branchInputs = [];

                        $restaurant = $dispatcher->get('api/v1/restaurant/' . $restaurant_slug);
                        if (!empty($restaurant->id))
                        {
                            $inputs['restaurant_id'] = $restaurant->id;
                            $restaurantInputs['I18N']['en']['name'] = $value->restaurant_en;
                            $restaurantInputs['I18N']['ar']['name'] = $value->restaurant_ar;
                            $restaurantInputs['I18N']['en']['address'] = $value->address_en;
                            $restaurantInputs['I18N']['ar']['address'] = $value->address_ar;
                            $restaurant = $dispatcher->patch('api/v1/restaurant/' . $restaurant_slug,$restaurantInputs);
                            $branchInputs['restaurant_id'] = $restaurant->id;
                        }
                        else
                        {
                            $restSlug = str_slug($value->restaurant_en);
                            $restInputs = [
                                    'slug' => $restSlug,
                                    'phone' => $value->phone,
                                    'latitude' => $value->lat,
                                    'longitude' => $value->long,
                                    'email' => $value->email,
                            ];
                            $restInputs['I18N']['en']['name'] = $value->restaurant_en;
                            $restInputs['I18N']['ar']['name'] = $value->restaurant_ar;
                            $restInputs['I18N']['en']['address'] = $value->address_en;
                            $restInputs['I18N']['ar']['address'] = $value->address_ar;

                            $city = $value->city;

                            $check = array_search(strtolower(trim($city)), $cities);
                            if ($check)
                            {
                                $restInputs['city_id'] = $check;
                            }
                            else
                            {
                                $cityInput['I18N']['en']['city_name'] = trim($city);
                                if (!empty($cityInput['I18N']['en']['city_name']))
                                {
                                    $newCity = $dispatcher->attach($request->allFiles())->post('api/v1/city',$cityInput);
                                    $cities[$newCity->id] = strtolower(trim($newCity->city_name));
                                    $restInputs['city_id'] = $newCity->id;
                                }
                            }


                            $features = $value->features;
                            $features = explode(",", $features);

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
                                $restInputs['facilities'] = $facilities_id;
                            }

                            $tags = $value->tags;
                            $tags = explode(",", $tags);


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
                                $restInputs['categories'] = $categories_id;
                            }

                            $restaurant = $dispatcher->attach($request->allFiles())->post('api/v1/restaurant',$restInputs);
                            $weekDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                            $timing = ['am', 'pm'];

                            foreach ($weekDays as $day) {
                                if (strpos('24 HoursOPEN', $value->openingHours) !== -1) {
                                    $input['status'] = 1;
                                    $input['day_name'] = $day;
                                    $input['from'] = '00:00';
                                    $input['to'] = '20:00';
                                    $openingday = $dispatcher->post('api/v1/restaurant/'.$restaurant->slug.'/opening-days',$input);
                                }
                                else
                                {
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
                            $branchInputs['restaurant_id'] = $restaurant->id;
                        }



                        $branches = json_decode($value->branches);
                        foreach ($branches as $branch)
                        {
                            $city = $value->city;
                            $check = array_search(strtolower(trim($city)), $cities);
                            if ($check)
                            {
                                $branchInputs['city_id'] = $check;
                            }
                            else
                            {
                                $cityInput['I18N']['en']['city_name'] = trim($city);
                                if (!empty($cityInput['I18N']['en']['city_name']))
                                {

                                    $newCity = $dispatcher->attach($request->allFiles())->post('api/v1/city',$cityInput);
                                    $cities[$newCity->id] = strtolower(trim($newCity->city_name));
                                    $branchInputs['city_id'] = $newCity->id;
                                }
                            }

                            $phone = preg_replace('/\s+/', '', implode(",",$branch->phone));

                            $branchInputs['phone'] = $phone;
                            $branchInputs['I18N']['en']['address'] = $branch->address;
                            $branchInputs['I18N']['ar']['address'] = $branch->ar_address;
                            $branchInputs['latitude'] = $branch->lat;
                            $branchInputs['longitude'] = $branch->lng;
                            $branchInputs['email'] = '';

                            $district = $branch->area;

                            $districtCheck = array_search(strtolower(trim($district)), $districts);
                            if ($districtCheck)
                            {
                                $branchInputs['district_id'] = $districtCheck;
                            }
                            else
                            {
                                $districtInput['city_id'] = $branchInputs['city_id'];
                                $districtInput['I18N']['en']['district_name'] = trim($district);
                                $newDistrict = $dispatcher->attach($request->allFiles())->post('api/v1/district',$districtInput);
                                $districts[$newDistrict->id] =  strtolower(trim($newDistrict->district_name));
                                $branchInputs['district_id'] = $newDistrict->id;
                            }
                            $branchSlug = $restaurant->name .'-'. $district . '-' . $restaurant->id ;
                            $branchInputs['slug'] = str_slug($branchSlug,'-');
                            try
                            {
                                $branch = $dispatcher->attach($request->allFiles())->post('api/v1/restaurant/'. $restaurant_slug . '/branch', $branchInputs);
                            }
                            catch (Exception $e)
                            {
                                var_dump($e->getMessage());

                            }

                        }
                    }
                    catch (Exception $e)
                    {
                        var_dump($e->getMessage());

                    }


                }
                $msg = 'Branches Data have been uploaded successfully!';
                return $msg;

            }
        }
        return "Failed";
    }
}
