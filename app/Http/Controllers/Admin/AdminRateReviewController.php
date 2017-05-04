<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiOpeningDayController;
use App\Http\Controllers\APIs\ApiRateReviewController;
use App\MenuItem;
use App\Http\Requests;
use App\OpeningDay;
use App\Rate;
use App\Reply;
use App\Restaurant;
use App\Review;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminRateReviewController extends ApiRateReviewController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\RateReviewsRequest $request, $restaurantSlug)
    {

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'title', 'description', 'created_at', 'updated_at']);

            $rate = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/rates', $paging);

            return $this->datatables($rate);
        }

        return view('admin.rate.index')->with(['restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\RateReviewsRequest $request, $restaurantSlug)
    {

        $review = new Review();
        $review->rate = new Rate();

//        return view('admin.rate.create')->with(['restaurant_slug' => $restaurantSlug, 'review' => $review]);

        return response('', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\RateReviewsRequest $request, $restSlug)
    {
        $inputs = $request->all();

        $rate = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restSlug . '/rates', $inputs);

        return Redirect::route('admin.restaurant.rates.edit', [$restSlug, $rate->id])->with('content-message', trans('Rate been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function show(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $rate = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/rates/' . $id);

        $reply = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/review/' . $id . '/reply');

        return view('admin.rate.show')->with(['restaurant_slug' => $restaurantSlug, 'review_id' => $id])->withRate($rate)->withReply($reply);
    }


    /**
     *
     */
    public function userStar($id)
    {

        $star = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/reviews/star/' . $id);

        return $star;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $rate = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/rates/' . $id);

        $rateValues = ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5'];

        $rate_types = Rate::getRateKeyValue();

        return view('admin.rate.edit')->with(['rate' => $rate, 'restaurant_slug' => $restaurantSlug, 'rate_values' => $rateValues, 'rate_types' => $rate_types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $inputs = $request->all();

        $inputs['last_visit_date'] = date('Y-m-d', strtotime($inputs['last_visit_date']));

        $rate = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/rates/' . $id, $inputs);

        return Redirect::route('admin.restaurant.rates.edit', [$restaurantSlug, $rate->id])->with('content-message', trans('Rate has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\RateReviewsRequest $request, $restaurantSlug, $id)
    {
        $rate = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/rates/' . $id);

        if ($rate) {
            $msg = 'Rate has been deleted successfully!';
            return Redirect::route('admin.restaurant.rates.index', $restaurantSlug)->with('content-message', trans($msg));
        } else {
            $msg = 'Rate has already been deleted!';
            return Redirect::route('admin.restaurant.rates.index', $restaurantSlug)->with('error-message', trans($msg));
        }

    }


    /*
     * 
     * Reply On Reviews 
     * 
    */

    public function createReply($restaurantSlug, $id)
    {
        $reply = new Reply();

        return view('admin.rate.reply')->with(['reply' => $reply, 'id' => $id, 'restaurantSlug' => $restaurantSlug]);
    }

    public function replyStore(Requests\ReplyRequest $request)
    {
//        $request = app(\Illuminate\Http\Request::class);
        $inputs = $request->all();
        $id = $inputs['review_id'];
        $slug = $inputs['slug'];

        $reply = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/review/' . $id . '/reply', $inputs);

        return Redirect::route('admin.restaurant.rates.edit', [$slug, $id])->with('content-message', trans('Reply been created Successfully'));
    }

    public function editReply($review_id, $id)
    {
        $reply = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/review/' . $review_id . '/reply/' . $id);

        return view('admin.rate.edit-reply')->with(['reply' => $reply, 'id' => $id, 'review_id' => $review_id]);
    }

    public function replyUpdate()
    {
        $request = app(\Illuminate\Http\Request::class);
        $inputs = $request->all();

        $id = $inputs['id'];
        $review_id = $inputs['review_id'];

        $reply = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->patch(env('API_VERSION', 'v1') . '/review/' . $review_id . '/reply/' . $id, $inputs);

        return Redirect::route('admin.restaurant.index')->with('content-message', trans('Reply has been updated Successfully'));
    }

    public function showReply($review_id, $id)
    {
        $reply = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/review/' . $review_id . '/reply/' . $id);


        return view('admin.rate.show-reply')->with(['reply' => $reply, 'review_id' => $review_id, 'id' => $id]);
    }

    public function deleteReply($review_id, $id)
    {
        $reply = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/review/' . $review_id . '/reply/' . $id);

        $review = Review::findOrFail($review_id);

        $restaurant = Restaurant::findOrFail($review->restaurant_id);

        if ($reply) {
            $msg = 'Reply has been deleted successfully!';
            return Redirect::route('admin.restaurant.rates.show', [$restaurant->slug, $review_id])->with('content-message', $msg);
        } else {
            $msg = 'Reply has already been deleted!';
            return Redirect::route('admin.restaurant.rates.show', [$restaurant->slug, $review_id])->with('error-message', $msg);
        }

    }


}
