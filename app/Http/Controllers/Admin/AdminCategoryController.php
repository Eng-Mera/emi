<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\APIs\ApiCategoryController;

use App\MenuItem;
use App\Restaurant;
use App\Role;
use App\Lang;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminCategoryController extends ApiCategoryController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'category_name', 'created_at', 'updated_at']);

            $menuItem = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/category', $paging);
            return $this->datatables($menuItem);

        }

        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = new Category();
        $locales = Lang::all();

        return view('admin.category.create')->with(['locales'=>$locales,'category' => $category]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\CategoryRequest $request)
    {

        $inputs = $request->all();

        $category = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/category', $inputs);

        return Redirect::route('admin.category.edit', [$category->id])->with('content-message', trans('Category has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $category = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/category/' . $id);

        return view('admin.menuitem.show')->withCategory($category);
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
            ->get(env('API_VERSION', 'v1') . '/category/' . $id);

        $locales = Lang::all();


        return view('admin.category.edit')->with(['locales' => $locales,'category' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CategoryRequest $request, $id)
    {
        $inputs = $request->all();

        $category = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/category/' . $id, $inputs);

        return Redirect::route('admin.category.edit', [$category->id])->with('content-message', trans('Category has been updated Successfully'));
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
            ->delete(env('API_VERSION', 'v1') . '/category/' . $id);

        if ($menuItem) {
            $msg = 'Category has been deleted successfully!';
            return Redirect::route('admin.category.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Category has already been deleted!';
            return Redirect::route('admin.category.index')->with('error-message', trans($msg));
        }

    }
}
