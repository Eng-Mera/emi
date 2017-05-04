<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\JobVacancy;
use App\Http\Controllers\APIs\ApiJobVacancyController;

use App\MenuItem;
use App\Restaurant;
use App\Role;
use App\Lang;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminJobVacancyController extends ApiJobVacancyController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\JobVacancyRequest $request , $restaurantSlug = null)
    {
        $res = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $this->authorize('listJobVacancy', $res);

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'job_title_id', 'status', 'user_id', 'restaurant_id', 'created_at', 'updated_at']);

            $jobTitle = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/job-vacancy', $paging);

            return $this->datatables($jobTitle);

        }

        return view('admin.job-vacancy.index', ['slug' => $restaurantSlug]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($restaurantSlug, $jobId)
    {
        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'job_title_id', 'status', 'user_id', 'restaurant_id', 'created_at', 'updated_at']);

            $appliedUsers = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/applied-users/' . $jobId, $paging);

            return $this->datatables($appliedUsers);

        }

        return view('admin.job-vacancy.list', ['slug' => $restaurantSlug, 'jobId' => $jobId]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\JobVacancyRequest $request, $restaurantSlug)
    {
        $jobVacancy = new JobVacancy();

        $locales = Lang::all();


        $jobTitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/job-title?per_page=-1')->pluck('job_title', 'id')->toArray();

        return view('admin.job-vacancy.create')->with(['locales' => $locales, 'jobVacancy' => $jobVacancy, 'slug' => $restaurantSlug, 'job_titles' => $jobTitle]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\JobVacancyRequest $request, $restaurantSlug)
    {

        $inputs = $request->all();

        $jobVacancy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/job-vacancy', $inputs);

        return Redirect::route('admin.restaurant.job-vacancy.edit', [$restaurantSlug, $jobVacancy->id])->with('content-message', trans('Job Title has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {
        $jobVacancy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/job-vacancy/' . $id);

        return view('admin.job-vacancy.show')->with(['job_vacancy' => $jobVacancy, 'slug' => $restaurantSlug]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {
        $id = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/job-vacancy/' . $id);

        $jobTitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/job-title?per_page=-1')->pluck('job_title', 'id')->toArray();

        $locales = Lang::all();


        return view('admin.job-vacancy.edit')->with(['locales' => $locales, 'jobVacancy' => $id, 'slug' => $restaurantSlug, 'job_titles' => $jobTitle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {
        $inputs = $request->all();

        $jobVacancy = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/job-vacancy/' . $id, $inputs);

        return Redirect::route('admin.restaurant.job-vacancy.edit', [$restaurantSlug, $jobVacancy->id])->with('content-message', trans('Job Title has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {

        $jobTitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/restaurant/' . $restaurantSlug . '/job-vacancy/' . $id);

        if ($jobTitle) {
            $msg = 'Job Title has been deleted successfully!';
            return Redirect::route('admin.restaurant.job-vacancy.index', [$restaurantSlug])->with('content-message', trans($msg));
        } else {
            $msg = 'Job Title has already been deleted!';
            return Redirect::route('admin.restaurant.job-vacancy.index', [$restaurantSlug])->with('error-message', trans($msg));
        }

    }
}
