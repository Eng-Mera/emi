<?php
namespace App\Http\Controllers\Admin;

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
use RuntimeException;
class AdminUploadingExcelController extends ApiRestaurantController
{
    
    public function importExport()
    {
        return view('admin.restaurant.upload');
    }
    
    
    public function downloadExcel($type)
    {
        $data = Restaurant::get()->toArray();
        return Excel::create('restaurant', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public function importExcel()
    {
        if(Input::hasFile('import_file'))
        {

            $request = new Requests\RestaurantRequest();

            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            if(!empty($data) && $data->count())
            {

                    foreach ($data as $key => $value)
                    {
                        try
                        {
                            $b64image = base64_encode(file_get_contents($value->logo));
                            $b64image = "data:image/png;base64," . $b64image;
                            $slug = str_slug($value->name, "-");

                            $inputs = ['slug' => $slug, 'address' => $value->address, 'phone' => $value->phone, 'latitude' => $value->lat, 'longitude' => $value->long, 'email' => $value->email, 'logo' => $b64image];
                            $inputs['I18N']['en']['name'] = $value->name;


                            $facilities = $this->api->version(env('API_VERSION', 'v1'))
                                ->header('webAuthKey', Config::get('api.webAuthKey'))
                                ->get(env('API_VERSION', 'v1') . '/facility');

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
                                        $facility = $this->api->version(env('API_VERSION', 'v1'))
                                            ->header('webAuthKey', Config::get('api.webAuthKey'))
                                            ->post(env('API_VERSION', 'v1') . '/facility', $facInputs);
                                        $facilities_id[] = $facility->id ;
                                    }
                                    
                                }

                            }
                            if (!empty($facilities_id)) {
                                $inputs['facilities'] = $facilities_id;
                            }

                            $categories = $this->api->version(env('API_VERSION', 'v1'))
                                ->header('webAuthKey', Config::get('api.webAuthKey'))
                                ->get(env('API_VERSION', 'v1') . '/category');

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
                                        $category = $this->api->version(env('API_VERSION', 'v1'))
                                            ->header('webAuthKey', Config::get('api.webAuthKey'))
                                            ->attach($request->allFiles())
                                            ->post(env('API_VERSION', 'v1') . '/category', $catInputs);
                                        $categories_id[] = $category->id;
                                    }
                                }
                            }
                            if (!empty($categories_id)) {
                                $inputs['categories'] = $categories_id;
                            }

                            $restaurant = $this->api->version(env('API_VERSION', 'v1'))
                                ->header('webAuthKey', Config::get('api.webAuthKey'))
                                ->attach($request->allFiles())
                                ->post(env('API_VERSION', 'v1') . '/restaurant', $inputs);
                            $weekDays = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                            $timing = ['am', 'pm'];

                            foreach ($weekDays as $day) {
                                $openingTime = str_replace($timing, "", $value->openinghours);
                                $openingHours = explode(" to ", $openingTime);

                                $input['status'] = 1;
                                $input['day_name'] = $day;
                                $input['from'] = trim($openingHours[0]);
                                $input['to'] = trim($openingHours[1]);

                                $openingday = $this->api->version(env('API_VERSION', 'v1'))
                                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                                    ->attach($request->allFiles())
                                    ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurant->slug . '/opening-days', $input);

                            }

                        }
                        catch (\Exception $e)
                        {

                        }


                    }
                    $msg = 'Restaurants Data have been uploaded successfully!';
                    return Redirect::route('admin.restaurant.index')->with('content-message', trans($msg));

            }
        }
        return back();
    }

    public function importBranches()
    {
        if(Input::hasFile('import_branch'))
        {

            $request = new Requests\RestaurantRequest();

            $path = Input::file('import_branch')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            if(!empty($data) && $data->count())
            {
                $cities = $this->api->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                    ->get(env('API_VERSION', 'v1') . '/city');
                $cities = $cities->pluck('city_name','id')->toArray();
                $cities = array_map('trim', $cities);
                $cities = array_map('strtolower', $cities);

                $districts = $this->api->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                    ->get(env('API_VERSION', 'v1') . '/district');
                $districts = $districts->pluck('district_name','id')->toArray();
                $districts = array_map('trim', $districts);
                $districts = array_map('strtolower', $districts);

                foreach ($data as $key => $value)
                {
                    try
                    {
                        $restaurantName = trim($value->restaurant_en,'"');
                        $restaurant_slug = str_slug($restaurantName,'-');

                        $restaurant = $this->api->version(env('API_VERSION', 'v1'))
                            ->header('webAuthKey', Config::get('api.webAuthKey'))
                            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurant_slug);

                        if (!empty($restaurant->id))
                        {
                            $inputs['restaurant_id'] = $restaurant->id;
                            $restaurantInputs['I18N']['ar']['name'] = $value->restaurant_ar;
                            $restaurant = $this->api->version(env('API_VERSION', 'v1'))
                                ->header('webAuthKey', Config::get('api.webAuthKey'))
                                ->attach($request->allFiles())
                                ->patch(env('API_VERSION', 'v1') . '/restaurant/' . $restaurant_slug, $restaurantInputs);
                        }

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
                                $newCity = $this->api->version(env('API_VERSION', 'v1'))
                                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                                    ->attach($request->allFiles())
                                    ->post(env('API_VERSION', 'v1') . '/city', $cityInput);
                                $cities[] = [$newCity->id => strtolower(trim($newCity->city_name))];
                                $branchInputs['city_id'] = $newCity->id;
                            }
                        }

                        $branches = json_decode($value->branches);
                        foreach ($branches as $branch)
                        {
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
                                $districtInput['I18N']['en']['district_name'] = trim($district);
                                $newDistrict = $this->api->version(env('API_VERSION', 'v1'))
                                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                                    ->attach($request->allFiles())
                                    ->post(env('API_VERSION', 'v1') . '/district', $districtInput);
                                $districts[] = [$newDistrict->id => strtolower(trim($newDistrict->district_name))];
                                $branchInputs['district_id'] = $newDistrict->id;
                            }
                            $branchSlug = $restaurant->name .'-'. $district . '-' . rand(10,10000) ;
                            $branchInputs['slug'] = str_slug($branchSlug,'-');
                            try
                            {
                                $branch = $this->api->version(env('API_VERSION', 'v1'))
                                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                                    ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurant_slug . '/branch', $branchInputs);
                            }
                            catch (Exception $e)
                            {

                            }

                        }
                    }
                    catch (\Exception $e)
                    {

                    }


                }
                $msg = 'Branches Data have been uploaded successfully!';
                return Redirect::route('admin.restaurant.index')->with('content-message', trans($msg));

            }
        }
        return back();
    }
}
?>