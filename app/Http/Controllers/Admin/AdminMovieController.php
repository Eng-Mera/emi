<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Movie;
use App\Http\Controllers\APIs\ApiMovieController;

use App\MenuItem;
use App\Restaurant;
use App\Role;
use App\Lang;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminMovieController extends ApiMovieController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'name','description', 'created_at', 'updated_at']);

            $menuItem = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/movie', $paging);
            return $this->datatables($menuItem);

        }

        return view('admin.movie.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $movie = new Movie();

        return view('admin.movie.create')->with(['movie' => $movie]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\MovieRequest $request)
    {

        $inputs = $request->all();

        $movie = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/movie', $inputs);

        return Redirect::route('admin.movie.edit', [$movie->id])->with('content-message', trans('Movie has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $movie = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/movie/' . $id);

        return view('admin.movie.show')->withMovie($movie);
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
            ->get(env('API_VERSION', 'v1') . '/movie/' . $id);

        $locales = Lang::all();


        return view('admin.movie.edit')->with(['locales' => $locales,'movie' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\MovieRequest $request, $id)
    {
        $inputs = $request->all();

        $movie = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/movie/' . $id, $inputs);

        return Redirect::route('admin.movie.edit', [$movie->id])->with('content-message', trans('Movie has been updated Successfully'));
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
            ->delete(env('API_VERSION', 'v1') . '/movie/' . $id);

        if ($menuItem) {
            $msg = 'Movie has been deleted successfully!';
            return Redirect::route('admin.movie.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Movie has already been deleted!';
            return Redirect::route('admin.movie.index')->with('error-message', trans($msg));
        }

    }
}
