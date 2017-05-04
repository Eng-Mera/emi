<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\OpeningDay;

use App\Http\Requests;
use App\Restaurant;
use App\User;
use Dingo\Api\Routing\Helpers;

/**
 * Favorite Restaurants.
 *
 * @Resource("Favorite Restaurants", uri="/api/v1/user/{username}/fav-restaurant")
 */
class ApiFavoriteRestaurantsController extends Controller
{

    use Helpers;

    /**
     * List Favorite Restaurants
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="The username of user.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index($username)
    {
        $user = User::with(['favoriteRestaurants', 'favoriteRestaurants.logo'])->whereUsername($username)->firstOrFail();

        $favoriteRestaurants = $user->favoriteRestaurants;

        return $this->response->created('', $favoriteRestaurants);
    }

    /**
     * Create Favorite Restaurant
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="The username of user.", default=10),
     *      @Parameter("restaurant_id[]", type="array", required=true, description="An array user favorite restaurant ids.", default=""),
     * })
     */
    public function store(Requests\FavoriteRestaurantsRequest $request, $username)
    {
        //Get Inputs
        $inputs = $request->only(['restaurant_id']);

        $restaurants = Restaurant::whereIn('id', $inputs['restaurant_id'])->pluck('id')->toArray();

        if (!$restaurants) {
            return $this->response->errorNotFound(trans('Restaurant(s) is no longer available'));
        }

        $user = User::whereUsername($username)->first();

        $user->favoriteRestaurants()->detach($restaurants);
        $user->favoriteRestaurants()->attach($restaurants);

        $user = User::with(['favoriteRestaurants'])->whereUsername($username)->firstOrFail();

        $favoriteRestaurants = $user->favoriteRestaurants;

        return $this->response->created('', $favoriteRestaurants);
    }

    public function update(Requests\FavoriteRestaurantsRequest $request, $username, $id)
    {
        return $this->store();
    }

    /**
     * Delete Favorite Restaurant
     *
     * @Delete("/{restaurant_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="The username of user.", default=10),
     *      @Parameter("restaurant_id", type="string", required=true, description="The restaurant id."),
     * })
     */
    public function destroy($username, $id)
    {
        $user = User::whereUsername($username)->firstOrFail();

        $restaurants = Restaurant::whereId($id)->pluck('id')->toArray();

        if (!$restaurants) {
            return $this->response->errorNotFound(trans('Restaurant(s) is no longer available'));
        }

        $result = $user->favoriteRestaurants()->detach($restaurants);

        if (!$result) {
            return $this->response->errorNotFound(trans('Restaurant has been already deleted from User Favorite list'));
        }

        return $this->response->created('', $result);
    }

}
