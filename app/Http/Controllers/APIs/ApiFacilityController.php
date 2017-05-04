<?php

namespace App\Http\Controllers\APIs;

use App\Facility;
use Illuminate\Support\Facades\Request;
use Dingo\Api\Routing\Helpers;


use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Facilities
 *
 * @Resource("Facilities", uri="/api/v1/facility")
 */
class ApiFacilityController extends Controller
{
    use Helpers;

    /**
     * List Facilities
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
    public function index(Requests\FacilityRequest $request)
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
            $facilities = Facility::orderBy($orderBy, $orderDir)->get();
        } else {


            if ($searchQuery) {
                $facilities = Facility::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $facilities = Facility::orderBy($orderBy, $orderDir)->paginate($perPage);
            }
        }

        return $this->response->created('', $facilities);
    }

    /**
     * Create Facility
     *
     * @POST("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The facility id.", default=""),
     *      @Parameter("name", type="string", required=true, description="The facility name.", default=""),
     *      @Parameter("description", type="string", required=true, description="The facility description.", default=""),
     * })
     */
    public function store(Requests\FacilityRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\FacilityRequest::getFields());
        $inputs = Requests\FacilityRequest::removeParent($inputs);

        //Create Menu Item
        $facility = Facility::create($inputs);

        $facility = Facility::find($facility->id);

        return $this->response->created('', $facility);
    }

    /**
     * Read Facility
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The facility id.", default=""),
     * })
     */
    public function show($id)
    {
        $facility = Facility::findOrFail($id);
        return $this->response->created('', $facility);
    }

    /**
     * Update Facility
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The facility id.", default=""),
     *      @Parameter("name", type="string", required=true, description="The facility name.", default=""),
     *      @Parameter("description", type="string", required=true, description="The facility description.", default=""),
     * })
     */
    public function update(Requests\FacilityRequest $request, $id)
    {
        $facility = Facility::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\FacilityRequest::getFields());
        $inputs = Requests\FacilityRequest::removeParent($inputs);


        $inputs = array_filter($inputs);

        //Update Restaurant
        $facility->fill($inputs)->save();

        $facility = Facility::find($facility->id);

        return $this->response->created('', $facility);
    }

    /**
     * Delete Facility
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The facility id.", default=""),
     * })
     */
    public function destroy($id)
    {
        $facility = Facility::where(['id' => $id])->firstOrFail();

        $facility->delete();

        if (!$facility) {
            return $this->response->errorNotFound(trans('The requested Facility is no longer available!'));
        }

        return $this->response->created('', $facility);
    }
}
