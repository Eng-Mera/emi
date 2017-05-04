<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Rate;
use App\Restaurant;
use App\Review;
use App\Role;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Request;

/**
 * Review presentation.
 *
 * @Resource("Restaurants Reviews", uri="/api/v1/restaurant/{restaurant_slug}/rates")
 */
class ApiRateReviewController extends Controller
{
    use Helpers;

    /**
     * List Reviews
     *
     * @Get("{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="integer", required=false, description="The short name of restaurant.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index(Requests\RateReviewsRequest $request, $restaurantSlug)
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

        $with = ['rate', 'restaurant', 'user', 'user.profilePicture'];

        $reviews = Review::with($with)->where('restaurant_id', $restaurant->id);

        if ($searchQuery) {
            $reviews->search($searchQuery);
        }

        if ($orderBy == 'user_rating') {
            $reviews->userLevel($orderDir);
        } else {
            $reviews->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        $reviews = $reviews->paginate($perPage);

        return $this->response->created('', $reviews);
    }

    public function listReviews(Requests\RateReviewsRequest $request)
    {

        $with = ['rate', 'restaurant', 'user', 'user.profilePicture'];
        $reviews = Review::with($with)->orderBy('created_at', 'desc')->take(6)->get();
        return $this->response->created('', $reviews);
    }

    /**
     * Create Review
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("title", type="string", required=true, description="The review title.", default=""),
     *      @Parameter("description", type="string", required=true, description="The review body", default=""),
     *      @Parameter("last_visit_date", type="string", required=true, description="The last date for restaurant.", default=""),
     *      @Parameter("seen", type="boolean", required=true, description="The seen flag is mainly by restaurant manageer to check that the review has been seen.", default=""),
     *      @Parameter("rate_value[]", type="array", required=true, description="An array contains the values of rates value must be 1 and 5", default=""),
     *      @Parameter("type[]", type="array", required=true, description="An array contains the type of rates.
    \
    MUSIC = 1,
    LOOKS_OF_RESTAURANT = 2
    ACCESSIBILITY = 3
    TEMPERATURE = 4
    TASTE = 5
    CLEAN_FLOORING = 6
    CLEAN_TABLES = 7
    CLEAN_ENVIRONMENT = 8
    DOOR_GREETING = 9
    WAITER_FRIENDLINESS = 10
    SPEED_OF_SERVICE = 11
    WAITERS_KNOWLEDGE_OF_MENU = 12
    PRESENTATION = 13
    \
    ", default=""),
     * })
     */
    public function store(Requests\RateReviewsRequest $request, $restSlug)
    {
        //Get Inputs
        $rateParams = $request->only(['rate_value', 'type']);

        $review = $request->only(['title', 'last_visit_date', 'description', 'seen']);

        $restaurant = Restaurant::whereSlug($restSlug)->first();

        $currentUser = User::getCurrentUser();

        $reviewData = [
            'title' => $review['title'],
            'description' => $review['description'],
            'restaurant_id' => $restaurant->id,
            'last_visit_date' => date('Y-m-d', strtotime($review['last_visit_date'])),
            'user_id' => $currentUser->id,
            'seen' => $review['seen'],
        ];

        \DB::beginTransaction();

        //Create Review
        $review = Review::create($reviewData);

        for ($i = 0; $i < count($rateParams['rate_value']); $i++) {

            if (!isset($rateParams['type'][$i])) {
                continue;
            }

            $rate = [];
            $rate['restaurant_id'] = $restaurant->id;
            $rate['user_id'] = $currentUser->id;
            $rate['review_id'] = $review->id;
            $rate['rate_value'] = $rateParams['rate_value'][$i];
            $rate['type'] = $rateParams['type'][$i];

            //Create Rate
            $review->rate()->create($rate);
        }

        \DB::commit();

        $with = ['rate'];

        $review->afterSave();

        $rate = Review::with($with)->find($review->id);

        return $this->response->created('', $rate);
    }

    /**
     * Read Review
     *
     * @Get("/{review_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("review_id", type="string", required=true, description="The id of review."),
     * })
     */
    public function show(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $with = ['rate'];

        $rate = Review::with($with)->findOrFail($id);

        return $this->response->created('', $rate);
    }

    /**
     * Update Review
     *
     * @Put("/{review_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("review_id", type="integer", required=true, description="The id of review.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("title", type="string", required=true, description="The review title.", default=""),
     *      @Parameter("description", type="string", required=true, description="The review body", default=""),
     *      @Parameter("last_visit_date", type="string", required=true, description="The last date for restaurant.", default=""),
     *      @Parameter("rate_value[]", type="array", required=true, description="An array contains the values o f rates", default=""),
     *      @Parameter("type[]", type="array", required=true, description="An array contains the type of rates.", default=""),
     * })
     */
    public function update(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $review = Review::with(['rate'])->whereId($id)->firstOrFail();

        //Get Inputs
        $rateParams = $request->only(['rate_value', 'type']);
        $reviewParams = $request->only(['title', 'last_visit_date', 'description', 'seen']);

        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();
        $rateParams['restaurant_id'] = $restaurant->id;

        $currentUser = User::getCurrentUser();

        $reviewData = [
            'title' => $reviewParams['title'],
            'description' => $reviewParams['description'],
            'restaurant_id' => $restaurant->id,
            'last_visit_date' => date('Y-m-d', strtotime($reviewParams['last_visit_date'])),
            'user_id' => $currentUser->id,
            'seen' => $review['seen'],
        ];

        \DB::beginTransaction();

        //Update Review
        $review->fill($reviewData)->save();

        for ($i = 0; $i < count($rateParams['rate_value']); $i++) {

            if (!isset($rateParams['type'][$i])) {
                continue;
            }

            $rateArr = [];
            $rateArr['restaurant_id'] = $restaurant->id;
            $rateArr['user_id'] = $currentUser->id;
            $rateArr['review_id'] = $review->id;
            $rateArr['type'] = $rateParams['type'][$i];

            $rate = Rate::where($rateArr)->first();

            //Update Rate
            $rate->fill(['rate_value' => $rateParams['rate_value'][$i]])->save();
        }

        \DB::commit();

        $with = ['rate'];

        $rate = Review::with($with)->find($review->id);

        return $this->response->created('', $rate);
    }

    /**
     * Delete Review
     *
     * @Delete("/{review_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("review_id", type="string", required=true, description="The id of review."),
     * })
     */
    public function destroy(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $reviewBackup = $review = Review::where(['id' => $id, 'restaurant_id' => $restaurant->id])->firstOrFail();

        $review->delete();

        $reviewBackup->afterDelete();

        if (!$review) {
            return $this->response->errorNotFound(trans('The requested review is no longer available!'));
        }

        return $this->response->created('', $review);
    }

    public function userStar($id)
    {
        $review = Review::find($id);
        $user = User::find($review->user_id);

        $role = $user->roles->toArray()[0]['name'];
        switch ($role) {
            case Role::AUDITOR;
                $star = 'golden';
                break;
            case Role::AUDITOR_OF_AUDITORS;
                $star = 'black';
                break;
            default;
                $star = 'none';
                break;
        }
        return $this->response->created('', $star);
    }

}
