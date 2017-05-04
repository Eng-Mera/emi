<?php

namespace App\Http\Controllers\APIs;

use App\DishCategory;
use App\File;
use App\Http\Controllers\Controller;
use App\MenuItem;
use App\Restaurant;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Tests\File\FakeFile;

/**
 * Restaurant Menu Items.
 *
 * @Resource("Menu Items", uri="/api/v1/restaurant/{restaurant_slug}/menu-item")
 */
class ApiMenuItemController extends Controller
{

    use Helpers;

    /**
     * List Menu Items
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="integer", required=false, description="The short name of restaurant.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at,]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     *      @Parameter("dish_category", type="boolean", required=false, description="Return Menu Items by dish Categories", default="DESC"),
     *      @Parameter("dish_category_id", type="boolean", required=false, description="Return Menu Items by dish Categories id", default="DESC"),
     * })
     */
    public function index(Requests\MenuItemRequest $request, $restaurantSlug)
    {
        $perPage = Request::get('per_page', 10);

        $dishCategories = Request::get('dish_category', false);
        $dishCategoriesId = Request::get('dish_category_id', false);

        $searchParams = [
            Request::get('search', false) ? Request::get('search', false) : false,
        ];

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        if ($dishCategories) {
            $menuItems = $this->listByDishCatgories($restaurant->id);
            return $this->response->created('', $menuItems);
        }

        if ($searchQuery) {
            $menuItems = MenuItem::with(['image'])->dishCategoryFilter($dishCategoriesId)->search($searchQuery)->where('restaurant_id', $restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $menuItems = MenuItem::with(['image'])->dishCategoryFilter($dishCategoriesId)->where('restaurant_id', $restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $menuItems);
    }

    private function listByDishCatgories($restaurantId)
    {
        return DishCategory::with(['menuItems' => function ($query) use ($restaurantId) {
            $query->whereRestaurantId($restaurantId);
        }, 'menuItems.image'])->whereExists(function ($query) use ($restaurantId) {
            $query->select(\DB::raw(1))
                ->from('menu_items')
                ->whereRaw('dish_categories.id = menu_items.dish_category_id');
        })->get();


    }

    /**
     * Create Menu Item
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("name", type="string", required=true, description="The menu item name.", default=""),
     *      @Parameter("slug", type="string", required=true, description="The menu item url friendly name", default=""),
     *      @Parameter("image", type="string", required=true, description="The menu item photo in base64 format.", default=""),
     *      @Parameter("price", type="float", required=true, description="Menu item price", default=""),
     *      @Parameter("popular_dish", type="boolean", required=true, description="A flag if this menu item is a popular dish or not", default=""),
     *      @Parameter("dish_category", type="integer", required=true, description="The type of dish", default=""),
     *      @Parameter("description", type="array", required=true, description="Menu Item description.", default=""),
     * })
     */
    public function store(Requests\MenuItemRequest $request, $restSlug)
    {
        //Get Inputs
        $inputs = $request->only(Requests\MenuItemRequest::getFields());

        $inputs = Requests\MenuItemRequest::removeParent($inputs);

        $restaurant = Restaurant::whereSlug($restSlug)->first();

        $inputs['restaurant_id'] = $restaurant->id;

        //Create Menu Item
        $menuItem = MenuItem::create($inputs);

        $menuItem->save();

        //Store Images
        $this->storeImages($request, $menuItem);

        $menuItem = MenuItem::with('image')->find($menuItem->id);

        return $this->response->created('', $menuItem);
    }

    protected function storeImages($request, $menuItem)
    {
        if (!empty($request->get('image')) || !empty($request->file('image'))) {

            //Update Logo
            $file = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->attach(['uploaded_file' => $request->file('image')])
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $request->get('image'),
                    'internal_user_id' => User::getCurrentUser()->id
                ]);

            if ($file) {
                $menuItem->image()->delete();
                $file->category = File::getCategorySlug('restaurant_menu_item');
                $menuItem->image()->save($file);
            }

        }
    }

    /**
     * Read Menu Item
     *
     * @Get("/{menu_item_slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("menu_item_slug", type="string", required=true, description="The menu item url friendly name."),
     * })
     */
    public function show(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {
        if (is_numeric($slug)) {
            $menuItem = MenuItem::with(['image'])->whereId($slug)->firstOrFail();
        } else {

            $menuItem = MenuItem::with(['image'])->whereSlug($slug)->firstOrFail();
        }
        return $this->response->created('', $menuItem);
    }

    /**
     * Update Menu Item
     *
     * @Put("/{menu_item_slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("menu_item_slug", type="string", required=true, description="The menu item url friendly name."),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("name", type="string", required=true, description="The menu item name.", default=""),
     *      @Parameter("slug", type="string", required=true, description="The menu item url friendly name", default=""),
     *      @Parameter("image", type="string", required=true, description="The menu item photo in base64 format.", default=""),
     *      @Parameter("price", type="float", required=true, description="Menu item price", default=""),
     *      @Parameter("popular_dish", type="boolean", required=true, description="A flag if this menu item is a popular dish or not", default=""),
     *      @Parameter("dish_category", type="integer", required=true, description="The type of dish", default=""),
     *      @Parameter("description", type="string", required=true, description="Menu Item description.", default=""),
     * })
     */
    public function update(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {
        $menuItem = MenuItem::with(['image'])->whereSlug($slug)->firstOrFail();
        //Get Inputs
        $inputs = $request->only(['name', 'slug', 'price', 'popular_dish', 'description', 'dish_category']);

        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();
        $inputs['restaurant_id'] = $restaurant->id;

        $inputs = array_filter($inputs);

        if (!isset($inputs['popular_dish'])) {
            $inputs['popular_dish'] = 0;
        }

        //Update Restaurant
        $menuItem->fill($inputs)->save();

        //Store Images
        $this->storeImages($request, $menuItem);

        $menuItem = MenuItem::with(['image'])->find($menuItem->id);

        return $this->response->created('', $menuItem);
    }

    /**
     * Delete Menu Item
     *
     * @Delete("/{menu_item_slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("menu_item_slug", type="string", required=true, description="The menu item url friendly name."),
     * })
     */
    public function destroy(Requests\MenuItemRequest $request, $restaurantSlug, $slug)
    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $menuItem = MenuItem::where(['slug' => $slug, 'restaurant_id' => $restaurant->id])->delete();

        if (!$menuItem) {
            return $this->response->errorNotFound(trans('Menu Item not found!'));
        }

        return $this->response->created('', $menuItem);
    }

}
