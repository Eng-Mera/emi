<?php

namespace App\Http\Controllers\APIs;

use App\Driver;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Request;


class ApiDriverController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\DriverRequest $request)
    {
        $perPage = Request::get('per_page', 10);

        $searchParams = [
            Request::get('search', false) ? Request::get('search', false) : false,
        ];

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        if ($searchQuery) {
            $adminReviews = Driver::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $adminReviews = Driver::orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $adminReviews);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\DriverRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\DriverRequest::getFields());

        //Create Driver
        $driver = Driver::create($inputs);

        $driver->save();
        
        $driver = Driver::find($driver->id);

        return $this->response->created('', $driver);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $driver = Driver::whereId($id)->firstOrFail();

        return $this->response->created('', $driver);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
