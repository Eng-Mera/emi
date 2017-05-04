<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use App\Http\Controllers\APIs\ApiRestaurantController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Dingo\Api\Http\Middleware\Auth;

use Input;
use Excel;
use Exception;

class uploadCommand extends Command
{
    use Helpers;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:Upload {import_file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Uploading CSV';

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

            $request = new Requests\MenuItemRequest();
            $this->info($this->argument('import_file'));

            $path = $this->argument('import_file');
            $data = Excel::load($path, function($reader) {})->get();


            if(!empty($data) && $data->count())
            {
                $dishCategories = $dispatcher->get('api/v1/dish-category?per_page=-1');

                $dishCategories = $dishCategories->pluck('category_name', 'id')->toArray();
                $dishCategories = array_map('trim', $dishCategories);
                $dishCategories = array_map('strtolower', $dishCategories);

                foreach ($data as $key => $value)
                {
                    var_dump($value->name);
                    try
                    {
                        $slug = str_slug($value->restaurant, "-");
                        var_dump($slug);
                        try
                        {
                            $restaurant = $dispatcher->get('api/v1/restaurant/'.$slug.'');
                            if (!empty($restaurant->slug))
                            {

                                $inputs['restaurant_id'] = $restaurant->id;
                                $dish_id = array_search(strtolower(trim($value->category)), $dishCategories);
                                var_dump($dish_id);
                                if ($dish_id)
                                {
                                    $inputs['dish_category_id'] = $dish_id;
                                }
                                else
                                {
                                    $dishInputs['I18N']['en']['category_name'] = $value->category;
                                    $category = $dispatcher->post('api/v1/dish-category',$dishInputs);
                                    $dishCategories[$category->id] = strtolower(trim($category->category_name));
                                    $inputs['dish_category_id'] = $category->id;
                                }
                                $inputs['I18N']['en']['name'] = $value->name;
                                $inputs['I18N']['en']['description'] = $value->description;
                                $inputs['price'] = $value->price;
                                $inputs['popular_dish'] = 0;
                                $inputs['slug'] = str_slug($value->restaurant . $value->name);
                                $menuItem = $dispatcher->post('api/v1/restaurant/'. $restaurant->slug .'/menu-item',$inputs);

                            }
                        }
                        catch (\Exception $e)
                        {
                            var_dump($e->getTraceAsString());
                        }
                    }
                    catch (\Exception $e)
                    {
                        var_dump($e->getTraceAsString());
                    }


                }
                $msg = 'Menu Items Data have been uploaded successfully!';
                $this->info($msg);
            }
        }
        return back();
    }
}
