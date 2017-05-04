<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;


use App\Claim;
use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

use App\Restaurant;
use App\User;
use App\Role;


/**
 * Claim presentation.
 *
 * @Resource("Claims", uri="/api/v1/claim")
 */
class ApiClaimController extends Controller
{
    use Helpers;

    public function index()
    {
        $perPage = Request::get('per_page', 10);

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';

        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $with = ['user' => function ($q) {
            $q->select('id', 'name', 'username');
        }];

        $searchParams = [
            Request::get('search', false) ? Request::get('search', false) : false,
        ];

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }
        if ($searchQuery) {
            $claims = Claim::with($with)->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }
        else
        {
            $claims = Claim::with($with)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $claims);
    }

    /**
     * Claim Owning Request
     *
     * @Post("/{restaurant_slug}/claim/{user_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="Restaurant Slug. the friendly url."),
     *      @Parameter("user_id", type="integer", required=true, description="Owner id."),
     * })
     */

    public function store(Requests\ClaimRequest $request)
    {
        $inputs = $request->only(['user_id']);
        $claim = Claim::create($inputs);
        $claim->save();

        return $this->response->created('', $claim);
    }

    public function show($id)
    {
        $claim = Claim::whereId($id)->firstOrFail();

        return $this->response->created('', $claim);

    }

    public function update(Requests\ClaimRequest $request, $id)
    {
        $claim = Claim::whereId($id)->firstOrFail();
        $inputs = $request->only(Requests\ClaimRequest::getFields());
        $slug = $inputs['slug'];
        $user_id = $claim->user_id;
        $restaurant = Restaurant::whereSlug($slug)->firstOrFail();

        $restaurant['owner_id'] = $user_id;

        $user = User::whereId($user_id)->firstOrFail();

        $newRole = Role::where('name','restaurant-manager')->first();
        $user->attachRoles([$newRole['id']]);

        $claim->status = 1;
        $claim->save();

        $user->save();

        $restaurant->save();

        return $this->response->created('', $claim);
    }

    public function destroy($id)
    {

    }

    public function approve(Requests\ClaimRequest $request,$id)
    {
        $claim = Claim::whereId($id)->firstOrFail();
        $inputs = $request->only(Requests\ClaimRequest::getFields());
        $slug = $inputs['slug'];
        $user_id = $inputs['user_id'];
        $restaurant = Restaurant::whereSlug($slug)->firstOrFail();

        $restaurant['owner_id'] = $user_id;

        $user = User::whereId($user_id)->firstOrFail();

        $newRole = Role::where('name','restaurant-manager')->first();
        $user->attachRoles([$newRole['id']]);

        $claim->status = 1;
        $claim->save();

        $user->save();

        $restaurant->save();

        return $this->response->created('', $claim);
    }

    public function cancel(Requests\ClaimRequest $request,$id)
    {
        $claim = Claim::whereId($id)->firstOrFail();

        $claim->status = 2;

        $claim->save();


        return $this->response->created('', $claim);
    }
}


