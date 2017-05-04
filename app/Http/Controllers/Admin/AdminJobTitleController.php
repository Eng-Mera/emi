<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\JobTitle;
use App\Http\Controllers\APIs\ApiJobTitleController;

use App\MenuItem;
use App\Lang;
use App\Restaurant;
use App\Role;
use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminJobTitleController extends ApiJobTitleController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id', 'job_title', 'created_at', 'updated_at']);

            $jobTitle = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/job-title', $paging);
            return $this->datatables($jobTitle);

        }

        return view('admin.job-title.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $jobtitle = new JobTitle();
        $locales = Lang::all();

        return view('admin.job-title.create')->with(['locales'=>$locales,'jobtitle' => $jobtitle]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\JobTitleRequest $request)
    {

        $inputs = $request->all();

        $jobtitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/job-title', $inputs);

        return Redirect::route('admin.job-title.edit', [$jobtitle->id])->with('content-message', trans('Job Title has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $jobtitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/job-title/' . $id);

        return view('admin.menuitem.show')->withJobTitle($jobtitle);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $jobtitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/job-title/' . $id);


        $locales = Lang::all();

        return view('admin.job-title.edit')->with(['locales' => $locales,'jobtitle' => $jobtitle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\JobTitleRequest $request, $id)
    {
        $inputs = $request->all();

        $jobtitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/job-title/' . $id, $inputs);

        return Redirect::route('admin.job-title.edit', [$jobtitle->id])->with('content-message', trans('Job Title has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $jobTitle = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/job-title/' . $id);

        if ($jobTitle) {
            $msg = 'Job Title has been deleted successfully!';
            return Redirect::route('admin.job-title.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Job Title has already been deleted!';
            return Redirect::route('admin.job-title.index')->with('error-message', trans($msg));
        }

    }
}
