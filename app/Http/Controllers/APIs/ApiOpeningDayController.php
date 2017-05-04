<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Helpers\OpeningDayTrait;
use App\OpeningDay;
use App\Restaurant;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Request;

/**
 * Opening Days.
 *
 * @Resource("Opening Days", uri="/api/v1/restaurant/{restaurant_slug}/opening-days")
 */
class ApiOpeningDayController extends Controller
{

    use Helpers;

    /**
     * List Opening Days
     *
     * @Get("   {?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="integer", required=false, description="The short name of restaurant.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("as_string", type="string", required=false, description="Return days as string .", default="0"),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index(Requests\OpeningDayRequest $request, $restaurantSlug)
    {
        $perPage = Request::get('per_page', 10);
        $asString = Request::get('as_string', 0);

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
            $openDays = OpeningDay::search($searchQuery)->where('restaurant_id', $restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $openDays = OpeningDay::where('restaurant_id', $restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        if ($asString) {
            $openDays = ['message' => OpeningDayTrait::convertToString($openDays)];
        }

        return $this->response->created('', $openDays);
    }


    /**
     * Create Opening day
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("day_name", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("from", type="string", required=true, description="The review title.", default=""),
     *      @Parameter("to", type="string", required=true, description="The review body", default=""),
     *      @Parameter("status", type="string", required=true, description="The last date for restaurant.", default=""),
     * })
     */
    public function store(Requests\OpeningDayRequest $request, $restSlug)
    {
        //Get Inputs
        $inputs = $request->only(['day_name', 'from', 'to', 'status']);

        $restaurant = Restaurant::whereSlug($restSlug)->first();

        $inputs['restaurant_id'] = $restaurant->id;

        if (is_null($inputs['status'])) {
            $inputs['status'] = 0;
        }

        //Create Menu Item
        $openDays = $restaurant->openingDays()->create($inputs);

        $openDays = OpeningDay::find($openDays->id);

        return $this->response->created('', $openDays);
    }

    /**
     * Show Opening Day
     *
     * @Get("/{opening_day_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("opening_day_id", type="string", required=true, description="The id of opening Day."),
     * })
     */
    public function show(Requests\OpeningDayRequest $request, $restaurantSlug, $id)
    {
        $openDays = OpeningDay::whereId($id)->firstOrFail();

        return $this->response->created('', $openDays);
    }

    /**
     * Update Opening Day
     *
     * @Put("/{opening_day_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("day_name", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("from", type="string", required=true, description="The review title.", default=""),
     *      @Parameter("to", type="string", required=true, description="The review body", default=""),
     *      @Parameter("status", type="string", required=true, description="The last date for restaurant.", default=""),
     *      @Parameter("opening_day_id", type="string", required=true, description="The id of opening Day.")
     * })
     */
    public function update(Requests\OpeningDayRequest $request, $restaurantSlug, $id)
    {
        $openDays = OpeningDay::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(['day_name', 'from', 'to', 'status']);

        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        if (is_null($inputs['status'])) {
            $inputs['status'] = 0;
        }

        //Update Restaurantv
        $openDays->fill($inputs)->save();

        $openDays = OpeningDay::find($openDays->id);

        return $this->response->created('', $openDays);
    }

    /**
     * Delete Opening Day
     *
     * @Delete("/{opening_day_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("review_id", type="string", required=true, description="The id of review."),
     *
     * })
     */
    public function destroy(Requests\OpeningDayRequest $request, $restaurantSlug, $id)
    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $openDays = OpeningDay::where(['id' => $id, 'restaurant_id' => $restaurant->id])->delete();

        if (!$openDays) {
            return $this->response->errorNotFound(trans('Opening day not found!'));
        }

        return $this->response->created('', $openDays);
    }

}
