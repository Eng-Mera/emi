<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiReportsController;
use App\Http\Requests;
use App\Report;
//use App\User;
use App\Role;
//use Dingo\Api\Http\Middleware\Auth;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;




class AdminReportController extends ApiReportsController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\ReportRequest $request)
    {

        if (Request::ajax()) {
            
            $paging = $this->getDatatablePaging(['id','report_type', 'report_subject', 'reported_id','user', 'details','created_at', 'updated_at']);

            if (Request::has('type_filter')) {
                $paging['type_filter'] = Request::get('type_filter');
            }

            $report = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/report/', $paging);
            
            return $this->datatables($report);

        }


        return view('admin.report.index');
    }

    public function types($type)
    {

        $report = $this->api->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                    ->get(env('API_VERSION', 'v1') . '/report/types/'.$type);
        return $report;
    }

    public function reportedIds($type)
    {
        $reported = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/report/reported/'.$type);
        return $reported;
    }


   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/users-by-role/'.Role::DINNER);
        $report = new Report();
        $users = ['' => 'Select User ..'] + array_pluck($users->toArray(), ['name'], 'id');
        return view('admin.report.create')->with([
            'report' => $report,
            'users' => $users,
            'types' => ['' => 'Select Type ..'] + Report::getReportTypes(),
            'subjects' => ['' => 'Select Subject ..'] + Report::getReportSubjects(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Requests\ReportRequest $request)
    {
        $inputs = $request->only(Requests\ReportRequest::getFields());

        $report = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/report', $inputs);

        return Redirect::route('admin.report.edit', $report->id)->with('content-message', trans('Report has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,Requests\ReportRequest $request)
    {
        $report = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/report/' . $id);

        return view('admin.report.show')->withReport($report);
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\ReportRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\ReportRequest $request, $id)
    {
        $report = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/report/' . $id, $request->all());

        return Redirect::route('admin.report.edit', $report->id)->with('content-message', trans('Report has been updated Successfully'));
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id , Requests\ReportRequest $request)
    {
        $report = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/report/' . $id);

        if ($report) {
            $msg = 'Report has been deleted successfully!';
            return Redirect::route('admin.report.index')->with('content-message', trans($msg));
        } else {
            $msg = 'Report has already been deleted!';
            return Redirect::route('admin.report.index')->with('error-message', trans($msg));
        }

    }
}
