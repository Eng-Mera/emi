<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiClaimController;
use App\Http\Requests;
use App\Claim;
use App\Http\Controllers\Controller;

use App\Restaurant;
use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class AdminClaimController extends ApiClaimController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Request::ajax()) {

            $paging = $this->getDatatablePaging(['id','user', 'status','created_at','updated_at']);

            $claims = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/claim/', $paging);
            return $this->datatables($claims);

        }
        return view('admin.claim.index');
    }

    public function create()
    {
        
    }

    public function store(Requests\ClaimRequest $request)
    {

    }

    public function show($id)
    {
        $claim = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/claim/' . $id);
        return view('admin.claim.show')->with(['claim' => $claim]);
    }

    public function edit($id)
    {
        $claim = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/claim/' . $id);

        $restaurants = Restaurant::all();
        $restaurants = ['' => 'Select Restaurant ..'] + array_pluck($restaurants->toArray(), ['name'], 'slug');
        $users = Claim::with('user')->get();
        $users_name = [];
        foreach ($users as $user)
        {
            $users_name[] = array('id' => $user->user->id , 'name' => $user->user->name);
        }
        $claims = ['' => 'Select User ..'] + array_pluck($users_name, ['name'], 'id');


        return view('admin.claim.edit')->with(['claim' => $claim,'restaurants' => $restaurants, 'claims' => $claims]);
    }

    public function update(Requests\ClaimRequest $request, $id)
    {
        $claim = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/claim/' . $id, $request->all());

        return Redirect::route('admin.claim.show',$claim->id)->with('content-message', trans('Claim has been Approved Successfully'));
    }

    public function destroy($id)
    {
        
    }

    public function cancel(Requests\ClaimRequest $request,$id)
    {
        $claim = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/claim/' . $id . '/cancel');

        return Redirect::route('admin.claim.index')->with('content-message', trans('Claim has been Cancelled Successfully'));

    }


}
