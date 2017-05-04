<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;

use App\Branch;
use App\Restaurant;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Request;

/**
 * Branch presentation.
 *
 * @Resource("Restaurants Branches", uri="/api/v1/restaurant/{restaurant_slug}/branch")
 */
class ApiBranchController extends Controller
{

    use Helpers;
    /**
     * List Branches
     *
     * @Get("{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The short name of restaurant.", default=10),
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })
     */
    public function index($restaurantSlug)
    {
        $perPage = Request::get('per_page', 10);

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';

        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $searchParams = [
            Request::get('search', false) ? Request::get('search', false) : false,
        ];

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }

        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();
        if ($searchQuery) {
            $branches = Branch::where('branches.restaurant_id',$restaurant->id)->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);

        }
        else
        {
            $branches = Branch::where('branches.restaurant_id',$restaurant->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $branches);
    }

    /**
     * Create Branch
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("slug", type="string", required=true, description="The branch slug."),
     *      @Parameter("latitude", type="string", required=true, description="The Branch latitude.", default=""),
     *      @Parameter("longitude", type="string", required=true, description="The Branch longitude.", default=""),
     *      @Parameter("email", type="string", required=true, description="The Branch email.", default=""),
     *      @Parameter("phone", type="string", required=true, description="The Branch phone.", default=""),
     *      @Parameter("I18N[locale][address]", type="array", required=true, description="An array contains the values of translation of address", default=""),
     * })
     */
    public function store(Requests\BranchRequest $request, $restaurantSlug)
    {
        $inputs = $request->only(Requests\BranchRequest::getFields());
        $inputs = Requests\BranchRequest::removeParent($inputs);
        $restaurant = Restaurant::whereSlug($restaurantSlug)->first();
        $inputs['restaurant_id'] = $restaurant->id;

        $branch = Branch::create($inputs);

        return $this->response->created('', $branch);
    }

    /**
     * Read Branch
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("slug", type="string", required=true, description="The branch slug."),
     *      @Parameter("id", type="integer", required=true, description="The ID of review."),
     * })
     */
    public function show($restaurantSlug, $slug)
    {
        $branch = Branch::whereSlug($slug)->firstOrFail();

        return $this->response->created('', $branch);

    }

    /**
     * Update Review
     *
     * @Put("/{slug}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("latitude", type="string", required=true, description="The Branch latitude.", default=""),
     *      @Parameter("longitude", type="string", required=true, description="The Branch longitude.", default=""),
     *      @Parameter("email", type="string", required=true, description="The Branch email.", default=""),
     *      @Parameter("phone", type="string", required=true, description="The Branch phone.", default=""),
     *      @Parameter("I18N[locale][address]", type="array", required=true, description="An array contains the values of translation of address", default=""),
     * })
     */
    public function update(Requests\BranchRequest $request, $restaurantSlug, $slug)
    {
        $branch = Branch::whereSlug($slug)->first();
        
        $inputs = $request->only(Requests\BranchRequest::getFields());
        $inputs = Requests\BranchRequest::removeParent($inputs);
        $restaurant = Restaurant::whereSlug($restaurantSlug)->first();
        $inputs['restaurant_id'] = $restaurant->id;
        $branch->fill($inputs)->save();

        return $this->response->created('', $branch);

    }

    /**
     * Delete specified branch
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *     @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("id", type="integer", required=true, description="Branch Id "),
     * })
     */
    public function destroy($restaurantSlug, $slug)
    {
        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $branch = Branch::where(['slug' => $slug, 'restaurant_id' => $restaurant->id])->firstOrFail();

        $branch->delete();

        if (!$branch) {
            return $this->response->errorNotFound(trans('The requested branch is no longer available!'));
        }

        return $this->response->created('', $branch);
    }
}
