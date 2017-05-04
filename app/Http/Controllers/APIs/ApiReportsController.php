<?php

/**
 * Report resource representation.
 *
 * @Resource("Reports", uri="/report")
 */

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Report;
use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Reports API Representation
 *
 * @Resource("Reports",uri="/report")
 */

class ApiReportsController extends Controller
{
    use Helpers;

    /**
     * Display a listing of the Reports.
     *
     * @Get("/{per_page,search,order,id,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page",type="integer",required=false,description="Number of items per pages . ",default=10),
     *     @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by (name - id - email).", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (Descending - Ascending).", default="desc")
     * })
     */

    public function index(Requests\ReportRequest $request )
    {

        $perPage = Request::get('per_page', -1);

        $searchParams = [
            Request::get('search', false),
            Request::get('type_filter', false)
        ];

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }




        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';
        if ($perPage == -1) {
            $perPage = Report::all()->count();
        }

        $with = ['user' => function ($q) {
            $q->select('id', 'name', 'username');
        }];
        if ($searchQuery) {
            $reports = Report::with($with)->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);

        } else {
            $reports = Report::with($with)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        
        return $this->response->created('', $reports);
    }

    /**
     * Display a listing of the Reports.
     *
     * @Get("/{report_type}/{reported_id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("report_type",type="integer",required=true,description="type of Three types 1 => Restaurant , 2 => Review, 3 => Photo", default=3),
     *     @Parameter("reported_id", type="integer", required=true, description="id of item to be reported", default=""),
     * })
     */
    
    public function listReports(Requests\ReportRequest $request , $report_type , $reported_id)
    {
        $matchThis = ['report_type'=>$report_type , 'reported_id' => $reported_id];
        $reports = Report::Where($matchThis)->get();
        return $this->response->created('',$reports);
    }

    /*
        Get Reports for report/types/{type}
        - Restaurant Reports : type = Restaurant
        - Review Reports : type = Review
        - Photo Reports : type = Photo
    */
    public function types($type)
    {
        $perPage = Request::get('per_page', -1);

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        if ($perPage == -1) {
            $perPage = Report::all()->count();
        }
        
        $with = ['user' => function ($q) {
            $q->select('id', 'name', 'username');
        }];

        $reports = Report::with($with)->Where('report_type',$type)->orderBy($orderBy, $orderDir)->paginate($perPage);
        return $this->response->created('', $reports);
    }

    public function reportedIds($type)
    {
        if ($type == 'Restaurant')
        {
            $reported = \App\Restaurant::all('id','name');
        }
        elseif ($type == 'Review')
        {
            $reported = \App\Review::all('id','title');
        }
        elseif ($type == 'Photo')
        {
            $reported = \App\PhotoComment::all('id');
        }
        return $this->response->created('', $reported);
    }

    /**
     * Create New Report
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *     @Parameter("report_type", type="string", required=true, description="Every created element must has a type of Three types Restaurant => Restaurant , Review => Review, Photo => Photo", default=""),
     *     @Parameter("report_subject", type="string", required=true, description="Every created element must has a type of Three types Spam => Spam , Offensive Language => Offensive Language, Wrong Restaurant => Wrong Restaurant , Irrelevant => Irrelevant", default=""),
     *      @Parameter("reported_id", type="integer", required=true, description="ID of the Reported item", default=""),
     *      @Parameter("user_id", type="integer", required=true, description="ID of the User make the report", default=""),
     *      @Parameter("details", type="string", required=false, description="Report details", default=""),
     * })
     */
    public function store(Requests\ReportRequest $request)
    {
        //Get Inputs
         $inputs = $request->only(Requests\ReportRequest::getFields());

        $newReport = Report::create($inputs);

         $report = Report::find($newReport->id);

        return $this->response->created('', $report);
    }

    /**
     * View User information and details
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="ID of specified report."),
     * })
     */
    public function show($id, Requests\ReportRequest $request)
    {
        $report = Report::with(['user' => function ($q) {
            $q->select('id', 'name', 'username');
        }])->whereId($id)->firstOrFail();

        return $this->response->created('', $report);
    }


    /**
     * Update the specified Report
     *
     * @PUT("/id")
     * @Versions({"v1"})
     * @Parameters({
     *     @Parameter("report_type", type="string", required=true, description="Every created element must has a type of Three types Restaurant => Restaurant , Review => Review, Photo => Photo", default=""),
     *     @Parameter("report_subject", type="string", required=true, description="Every created element must has a type of Three types Spam => Spam , Offensive Language => Offensive Language, Wrong Restaurant => Wrong Restaurant , Irrelevant => Irrelevant", default=""),
     *      @Parameter("reported_id", type="integer", required=true, description="ID of the Reported item", default=""),
     *      @Parameter("user_id", type="integer", required=true, description="ID of the User make the report", default=""),
     *      @Parameter("details", type="string", required=false, description="Report details", default=""),
     * })
     */
    public function update(Requests\ReportRequest $request, $id)
    {
        $report = Report::Where('id',$id)->firstOrFail();
        //Get Inputs
         $inputs = $request->only(Requests\ReportRequest::getFields());

        //Update Report
         $report->fill(array_filter($inputs))->save();

        //Get report
        $report = Report::with(['user' => function ($q) {
            $q->select('id', 'name', 'username');
        }])->find($report->id);
        
        return $this->response->created('', $report);
    }

    /**
     * Delete specified report
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="Report Id "),
     * })
     */
    public function destroy($id, Requests\ReportRequest $request)
    {

        $report = Report::whereId($id)->delete();

        if (!$report) {
            return $this->response->errorNotFound(trans('Report not found!'));
        }

        return $this->response->created('', $report);
    }

}

