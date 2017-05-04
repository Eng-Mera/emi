<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\City;
use App\Lang;
use App\Http\Controllers\APIs\ApiCityController;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminCityController extends ApiCityController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'city_name', 'created_at', 'updated_at']);

            $menuItem = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/city', $paging);
            return $this->datatables($menuItem);

        }

        return view('admin.city.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $city = new City();
        $locales = Lang::all();

        return view('admin.city.create')->with(['locales'=>$locales,'city' => $city]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CityRequest $request)
    {


        $inputs = $request->all();

        $city = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/city', $inputs);

        return Redirect::route('admin.city.edit', [$city->id])->with('content-message', trans('City has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $city = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/city/' . $id);

        return view('admin.city.show')->withCity($city);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/city/' . $id);

        $locales = Lang::all();


        return view('admin.city.edit')->with(['locales' => $locales,'city' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CityRequest $request, $id)
    {
        $inputs = $request->all();

        $city = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/city/' . $id, $inputs);

        return Redirect::route('admin.city.edit', [$city->id])->with('content-message', trans('City has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $menuItem = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/city/' . $id);

        if ($menuItem) {
            $msg = 'City has been deleted successfully!';
            return Redirect::route('admin.city.index')->with('content-message', trans($msg));
        } else {
            $msg = 'City has already been deleted!';
            return Redirect::route('admin.city.index')->with('error-message', trans($msg));
        }

    }
}
