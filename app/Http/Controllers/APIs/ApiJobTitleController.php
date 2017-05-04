<?php

namespace App\Http\Controllers\APIs;

use App\Category;
use App\File;
use App\Http\Controllers\Controller;
use App\JobTitle;
use App\MenuItem;
use App\Restaurant;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Request;

/**
 * Jobs Titles.
 *
 * @Resource("Job Titles", uri="/api/v1/job-title")
 */
class ApiJobTitleController extends Controller
{

    use Helpers;

    /**
     * List Jobs Titles
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
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
            $jobsTitles = JobTitle::orderBy($orderBy, $orderDir)->get();
        } else {

            if ($searchQuery) {
                $jobsTitles = JobTitle::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $jobsTitles = JobTitle::orderBy($orderBy, $orderDir)->paginate($perPage);
            }
        }

        return $this->response->created('', $jobsTitles);
    }

    /**
     * Create Job Title
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("job_title", type="string", required=true, description="The title of job .", default=""),
     *      @Parameter("description", type="string", required=true, description="The job title description.", default=""),
     * })
     */
    public function store(Requests\JobTitleRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\JobTitleRequest::getFields());

        $inputs = Requests\JobTitleRequest::removeParent($inputs);

        //Create Job Title
        $jobTitle = JobTitle::create($inputs);

        $jobTitle = JobTitle::find($jobTitle->id);

        return $this->response->created('', $jobTitle);
    }

    /**
     * Read Job Title
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The job title id.", default=""),
     * })
     */
    public function show($id)
    {
        $jobTitle = JobTitle::findOrFail($id);
        return $this->response->created('', $jobTitle);
    }

    /**
     * Update Job Title
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The job title id.", default=""),
     *      @Parameter("job_title", type="string", required=true, description="The title of job.", default=""),
     * })
     */
    public function update(Requests\JobTitleRequest $request, $id)
    {
        $jobTitle = JobTitle::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\JobTitleRequest::getFields());
        $inputs = Requests\JobTitleRequest::removeParent($inputs);


        $inputs = array_filter($inputs);

        //Update Job title
        $jobTitle->fill($inputs)->save();

        $jobTitle = JobTitle::find($jobTitle->id);

        return $this->response->created('', $jobTitle);
    }

    /**
     * Delete Job Title
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The job title id.", default=""),
     * })
     */
    public function destroy($id)
    {
        $jobTitle = JobTitle::where(['id' => $id])->firstOrFail();

        $jobTitle->delete();

        return $this->response->created('', $jobTitle);
    }

}
