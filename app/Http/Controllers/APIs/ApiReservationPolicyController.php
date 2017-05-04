<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Controllers\Controller;
use App\MenuItem;
use App\ReservationPolicy;
use App\Restaurant;

use App\Http\Requests;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;

/**
 * Restaurant Reservation Policies.
 *
 * @Resource("Reservation Policies", uri="/api/v1/restaurant/{restaurant_slug}/reservation-policy")
 */
class ApiReservationPolicyController extends Controller
{

    use Helpers;

    /**
     * List Reservation Policies
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="integer", required=false, description="The short name of restaurant.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at,]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index(Requests\ReservationPolicyRequest $request, $restaurantSlug)
    {
        $perPage = Request::get('per_page', 10);

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

        if ($searchQuery) {
            $reservationPolicies = ReservationPolicy::search($searchQuery)->where('restaurant_id', $restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $reservationPolicies = ReservationPolicy::where('restaurant_id', $restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $reservationPolicies);
    }

    /**
     * Create Reservation Policy
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
     *      @Parameter("description", type="array", required=true, description="Reservation Policy description.", default=""),
     * })
     */
    public function store(Requests\ReservationPolicyRequest $request, $restSlug)
    {
        //Get Inputs
        $inputs = $request->only(Requests\ReservationPolicyRequest::getFields());

        $restaurant = Restaurant::whereSlug($restSlug)->firstOrFail();

        $inputs['restaurant_id'] = $restaurant->id;
        $inputs['user_id'] = User::getCurrentUser()->id;

        //Create Reservation Policy
        $reservationPolicy = ReservationPolicy::create($inputs);

        $reservationPolicy = ReservationPolicy::find($reservationPolicy->id);

        return $this->response->created('', $reservationPolicy);
    }

    /**
     * Read Reservation Policy
     *
     * @Get("/{reservation_policy_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("reservation_policy_id", type="string", required=true, description="The menu item url friendly name."),
     * })
     */
    public function show(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {
        $reservationPolicy = ReservationPolicy::whereId($id)->firstOrFail();
        return $this->response->created('', $reservationPolicy);
    }

    /**
     * Update Reservation Policy
     *
     * @Put("/{reservation_policy_id}")
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
     * })
     */
    public function update(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $reservationPolicy = ReservationPolicy::where(['id' => $id, 'restaurant_id' => $restaurant->id])->firstOrFail();
        //Get Inputs
        $inputs = $request->only(Requests\ReservationPolicyRequest::getFields());

        $inputs['restaurant_id'] = $restaurant->id;
        $inputs['user_id'] = User::getCurrentUser()->id;

        //Update Restaurant
        $reservationPolicy->fill($inputs)->save();

        $reservationPolicy = ReservationPolicy::find($reservationPolicy->id);

        return $this->response->created('', $reservationPolicy);
    }

    /**
     * Delete Reservation Policy
     *
     * @Delete("/{reservation_policy_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("reservation_policy_id", type="string", required=true, description="The menu item url friendly name."),
     * })
     */
    public function destroy(Requests\ReservationPolicyRequest $request, $restaurantSlug, $id)
    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $reservationPolicy = ReservationPolicy::where(['id' => $id, 'restaurant_id' => $restaurant->id])->delete();

        if (!$reservationPolicy) {
            return $this->response->errorNotFound(trans('Reservation Policy not found!'));
        }

        return $this->response->created('', $reservationPolicy);
    }

}
