<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\APIs\ApiFacilityController;
use App\Http\Requests;
use App\Facility;
use App\Lang;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

use App\Http\Controllers\Controller;

class AdminFacilityController extends ApiFacilityController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\FacilityRequest $request)
    {

        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id','name', 'description','created_at', 'updated_at']);
            
            $facilities = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/facility/', $paging);

            return $this->datatables($facilities);

        }


        return view('admin.facility.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\FacilityRequest $request)
    {
        $facility = new Facility();
        $locales = Lang::all();
        return view('admin.facility.create')->with(['locales'=>$locales,'facility' => $facility]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\FacilityRequest $request)
    {
        $inputs = $request->all();

//        echo '<pre>';
//        var_dump($inputs);
//        die();

        $facility = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/facility/', $inputs);



        return Redirect::route('admin.facility.edit', [$facility->id])->with('content-message', trans('Facility has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $facility = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/facility/' . $id);
        return view('admin.facility.show')->with(['facility' => $facility]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function edit(Requests\FacilityRequest $request, $id)
    {
        $facility = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/facility/' . $id );

        $locales = Lang::all();

        return view('admin.facility.edit')->with(['locales' => $locales, 'facility' => $facility]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\RestaurantRequest $request
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\FacilityRequest $request, $id)
    {
        $inputs = $request->all();

        $facility = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/facility/'. $id , $inputs);

        return Redirect::route('admin.facility.edit', [$facility->id])->with('content-message', trans('Facility has been updated Successfully'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $facility = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/facility/' . $id);

        if ($facility) {
            $msg = 'Facility has been deleted successfully!';
            return Redirect::route('admin.facility.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Facility has already been deleted!';
            return Redirect::route('admin.facility.index')->with('error-message', trans($msg));
        }
    }
}






