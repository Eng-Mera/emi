<?php

namespace App\Http\Controllers\APIs;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\District;

use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Request;


class ApiDistrictController extends Controller
{
    use Helpers;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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

        if ($perPage == -1) {
            $districts = District::orderBy($orderBy, $orderDir)->get();
        } else {


            if ($searchQuery) {
                $districts = District::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $districts = District::orderBy($orderBy, $orderDir)->paginate($perPage);
            }
        }

        return $this->response->created('', $districts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\DistrictRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\DistrictRequest::getFields());

        $inputs = Requests\DistrictRequest::removeParent($inputs);

        //Create City
        $district = District::create($inputs);

        $district = District::find($district->id);

        return $this->response->created('', $district);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $district = District::findOrFail($id);
        return $this->response->created('', $district);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\DistrictRequest $request, $id)
    {
        $district = District::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\DistrictRequest::getFields());
        $inputs = Requests\DistrictRequest::removeParent($inputs);


        $inputs = array_filter($inputs);

        //Update Restaurant
        $district->fill($inputs)->save();

        $district = District::find($district->id);

        return $this->response->created('', $district);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $district = District::where(['id' => $id])->firstOrFail();

        $district->delete();

        return $this->response->created('', $district);
    }
}
