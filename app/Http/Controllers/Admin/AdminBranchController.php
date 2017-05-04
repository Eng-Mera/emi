<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiBranchController;
use App\Http\Requests;
use App\Branch;
use App\Lang;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminBranchController extends ApiBranchController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($restaurantSlug)
    {
        if (Request::ajax()) {
            $paging = $this->getDatatablePaging(['id', 'slug', 'address', 'latitude', 'longitude','email','phone']);

            $branches = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/branch', $paging);
            return $this->datatables($branches);

        }
        return view('admin.branch.index')->with(['restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($restaurantSlug)
    {
        $branch = new Branch();
        $locales = Lang::all();
        return view('admin.branch.create')->with(['locales'=>$locales,'branch' => $branch, 'restaurant_slug' => $restaurantSlug]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\BranchRequest $request, $restaurantSlug)
    {
        $inputs = $request->all();

        $branch = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/branch', $inputs);



        return Redirect::route('admin.restaurant.branch.edit', [$restaurantSlug, $branch->slug])->with('content-message', trans('Branch has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($restaurantSlug, $id)
    {
        $branch = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/branch/' . $id);
        return view('admin.branch.show')->with(['branch' => $branch]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit($restaurantSlug, $slug)
    {
        $branch = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/branch/' . $slug);

        $locales = Lang::all();

        return view('admin.branch.edit')->with(['locales' => $locales, 'branch' => $branch, 'restaurant_slug' => $restaurantSlug]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\BranchRequest $request, $restaurantSlug, $slug)
    {

        $inputs = $request->all();

        $branch = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/branch/' . $slug, $inputs);

        return Redirect::route('admin.restaurant.branch.edit', [$restaurantSlug, $branch->slug])->with('content-message', trans('Branch has been updated Successfully'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($restaurantSlug, $slug)
    {
        $branch = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/branch/' . $slug);

        if ($branch) {
            $msg = 'Branch has been deleted successfully!';
            return Redirect::route('admin.restaurant.branch.index', $restaurantSlug)->with('content-message', trans($msg));
        } else {
            $msg = 'Branch has already been deleted!';
            return Redirect::route('admin.restaurant.branch.index', $restaurantSlug)->with('error-message', trans($msg));
        }
    }
}
