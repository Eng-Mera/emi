<?php

namespace App\Http\Controllers\APIs;

use App\City;
use App\Http\Controllers\Controller;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Request;

/**
 * Cities.
 *
 * @Resource("Cities", uri="/api/v1/city")
 */
class ApiCityController extends Controller
{

    use Helpers;

    /**
     * List Cities
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, city_name]", default="id"),
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
            $categories = City::orderBy($orderBy, $orderDir)->get();
        } else {


            if ($searchQuery) {
                $categories = City::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $categories = City::orderBy($orderBy, $orderDir)->paginate($perPage);
            }
        }

        return $this->response->created('', $categories);
    }

    /**
     * Create City
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("city_name", type="string", required=true, description="The city name.", default=""),
     * })
     */
    public function store(Requests\CityRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\CityRequest::getFields());

        $inputs = Requests\CityRequest::removeParent($inputs);

        //Create City
        $city = City::create($inputs);

        $city = City::find($city->id);

        return $this->response->created('', $city);
    }

    /**
     * Read City
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The city id.", default=""),
     *      @Parameter("city_name", type="string", required=true, description="The city id."),
     * })
     */
    public function show($id)
    {
        $city = City::findOrFail($id);
        return $this->response->created('', $city);
    }

    /**
     * Update City
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The city id.", default=""),
     *      @Parameter("city_name", type="string", required=true, description="The city name.", default=""),
     * })
     */
    public function update(Requests\CityRequest $request, $id)
    {
        $city = City::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\CityRequest::getFields());
        $inputs = Requests\CityRequest::removeParent($inputs);


        $inputs = array_filter($inputs);

        //Update Restaurant
        $city->fill($inputs)->save();

        $city = City::find($city->id);

        return $this->response->created('', $city);
    }

    /**
     * Delete City
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The city id.", default=""),
     * })
     */
    public function destroy($id)
    {
        $city = City::where(['id' => $id])->firstOrFail();

        $city->delete();

        return $this->response->created('', $city);
    }

}
