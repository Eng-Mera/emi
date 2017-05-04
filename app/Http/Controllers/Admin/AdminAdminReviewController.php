<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\AdminReview;
use App\Http\Controllers\APIs\ApiAdminReviewController;
use App\Lang;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminAdminReviewController extends ApiAdminReviewController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\AdminReviewRequest $request)
    {
        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'restaurant_name', 'description', 'created_at']);

            $adminReview = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/admin-review/', $paging);

            return $this->datatables($adminReview);
        }

        return view('admin.admin-review.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\AdminReviewRequest $request)
    {
        $adminReview = new AdminReview();

        $locales = Lang::all();

        return view('admin.admin-review.create')->with(['locales' => $locales, 'adminReview' => $adminReview]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AdminReviewRequest $request)
    {
        $inputs = $request->all();

        $adminReview = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/admin-review', $inputs);

        return Redirect::route('admin.admin-review.edit', $adminReview->id)->with('content-message', trans('Admin Review has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Requests\AdminReviewRequest $request, $id)
    {
        $adminReview = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/admin-review/' . $id);

        return view('admin.admin-review.show')->with(['adminReview' => $adminReview]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\AdminReviewRequest $request, $id)
    {
        $locales = Lang::all();

        $adminReview = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/admin-review/' . $id);

        return view('admin.admin-review.edit')->with(['locales' => $locales, 'adminReview' => $adminReview]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\AdminReviewRequest $request, $id)
    {
        $inputs = $request->all();
        $adminReview = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/admin-review/' . $id, $inputs);

        return Redirect::route('admin.admin-review.edit', [$adminReview->id])->with('content-message', trans('Admin Review has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\AdminReviewRequest $request, $id)
    {

        $adminReview = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/admin-review/' . $id);

        if ($adminReview) {
            $msg = 'Admin Review has been deleted successfully!';
            return Redirect::route('admin.admin-review.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Admin Review has already been deleted!';
            return Redirect::route('admin.admin-review.index')->with('error-message', trans($msg));
        }

    }
}
