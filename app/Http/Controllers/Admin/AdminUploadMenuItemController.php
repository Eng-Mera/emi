<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\APIs\ApiRestaurantController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Dingo\Api\Http\Middleware\Auth;

use Input;
use App\MenuItem;
use DB;
use Excel;
use App\Http\Controllers\Controller;
use Exception;
class AdminUploadMenuItemController extends ApiRestaurantController
{
    public function importExport()
    {
        return view('admin.restaurant.uploadMenu');
    }
    public function downloadExcel($type)
    {
        $data = MenuItem::get()->toArray();
        return Excel::create('menuitem', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public function importExcel()
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', -1);
        if(Input::hasFile('import_file'))
        {

            $request = new Requests\MenuItemRequest();

            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();


            if(!empty($data) && $data->count())
            {

                foreach ($data as $key => $value)
                {
                    try
                    {
                        $slug = str_slug($value->restaurant, "-");
                        try
                        {
                            $restaurant = $this->api->version(env('API_VERSION', 'v1'))
                                ->header('webAuthKey', Config::get('api.webAuthKey'))
                                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $slug);
                            if (!empty($restaurant->slug))
                            {
                                $inputs['restaurant_id'] = $restaurant->id;
                                $dishCategories = $this->api->version(env('API_VERSION', 'v1'))
                                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                                    ->get(env('API_VERSION', 'v1') . '/dish-category?per_page=-1');
                                $dishCategories = $dishCategories->pluck('category_name', 'id')->toArray();
                                $dishCategories = array_map('trim', $dishCategories);
                                $dishCategories = array_map('strtolower', $dishCategories);

                                $dish_id = array_search(strtolower(trim($value->category)), $dishCategories);
                                if ($dish_id)
                                {
                                    $inputs['dish_category_id'] = $dish_id;
                                }
                                else
                                {
                                    $dishInputs['I18N']['en']['category_name'] = $value->category;
                                    $category = $this->api->version(env('API_VERSION', 'v1'))
                                        ->header('webAuthKey', Config::get('api.webAuthKey'))
                                        ->attach($request->allFiles())
                                        ->post(env('API_VERSION', 'v1') . '/dish-category', $dishInputs);
                                    $inputs['dish_category_id'] = $category->id;
                                }
                                $inputs['I18N']['en']['name'] = $value->name;
                                $inputs['I18N']['en']['description'] = $value->description;
                                $inputs['price'] = $value->price;
                                $inputs['popular_dish'] = 0;
                                $inputs['slug'] = str_slug($value->name);
                                $menuItem = $this->api->version(env('API_VERSION', 'v1'))
                                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                                    ->attach($request->allFiles())
                                    ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurant->slug . '/menu-item', $inputs);
                            }
                        }
                        catch (\Exception $e)
                        {

                        }
                    }
                    catch (\Exception $e)
                    {

                    }


                }
                $msg = 'Menu Items Data have been uploaded successfully!';
                return Redirect::route('admin.restaurant.index')->with('content-message', trans($msg));

            }
        }
        return back();
    }
}
?>