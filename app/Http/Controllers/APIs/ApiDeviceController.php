<?php

namespace App\Http\Controllers\APIs;

use App\Exceptions\CustomValidationException;
use App\Exceptions\ORMException;
use App\Http\Repositories\DeviceRepository;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;

class ApiDeviceController extends Controller
{
    use Helpers;

    /**
     * @var DeviceRepository
     */
    private $device_repository;

    /**
     * ApiDeviceController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->device_repository = new DeviceRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'user_id',
            'device_id',
            'device_type'
        ]);

        try{
            $result = $this->device_repository->handleSaveDevice($data);
        } catch(CustomValidationException $ex){
            return $this->response->errorBadRequest($ex->getValidator()->errors());
        } catch(ORMException $ex){
            return $this->response->errorInternal($ex->getMessage());
        }

        return $this->response->created($request->getUri(), $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
