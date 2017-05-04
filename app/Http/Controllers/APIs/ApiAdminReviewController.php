<?php

namespace App\Http\Controllers\APIs;

use App\AdminReview;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Helpers\FileTrait;
use App\Restaurant;

use App\Http\Requests;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Config;

use Illuminate\Support\Facades\Request;

/**
 * Admin Reviews.
 *
 * @Resource("Admin Reviews", uri="/api/v1/admin-review")
 */
class ApiAdminReviewController extends Controller
{

    use Helpers, FileTrait;

    /**
     * List Admin Reviews
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at,]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     * })s
     */
    public function index(Requests\AdminReviewRequest $request)
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

        $with = ['images'];

        if ($searchQuery) {
            $adminReviews = AdminReview::with($with)->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
        } else {
            $adminReviews = AdminReview::with($with)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $adminReviews);
    }

    /**
     * Create Admin Review
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("restaurant_name", type="string", required=true, description="The admin review name.", default=""),
     *      @Parameter("images", type="string", required=true, description="The admin review photo in base64 format.", default=""),
     *      @Parameter("description", type="string", required=true, description="Admin Review description.", default=""),
     * })
     */
    public function store(Requests\AdminReviewRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\AdminReviewRequest::getFields());

        $inputs = Requests\AdminReviewRequest::removeParent($inputs);

        $inputs['user_id'] = User::getCurrentUser()->id;

        \DB::beginTransaction();

        //Create Admin Review
        $adminReview = AdminReview::create($inputs);

        $adminReview->save();

        //Store Images
        $this->storeAdminReviewsImages($request, $adminReview);

        \DB::commit();

        $adminReview = AdminReview::with('images')->find($adminReview->id);

        return $this->response->created('', $adminReview);
    }

    /**
     * Read Admin Review
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="string", required=true, description="The admin review id."),
     * })
     */
    public function show(Requests\AdminReviewRequest $request, $id)
    {
        $adminReview = AdminReview::with(['images'])->whereId($id)->firstOrFail();

        return $this->response->created('', $adminReview);
    }

    /**
     * Update Admin Review
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="string", required=true, description="The admin review url friendly name."),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The restaurant slug."),
     *      @Parameter("name", type="string", required=true, description="The admin review name.", default=""),
     *      @Parameter("slug", type="string", required=true, description="The admin review url friendly name", default=""),
     *      @Parameter("image", type="string", required=true, description="The admin review photo in base64 format.", default=""),
     *      @Parameter("price", type="float", required=true, description="Menu item price", default=""),
     *      @Parameter("popular_dish", type="boolean", required=true, description="A flag if this admin review is a popular dish or not", default=""),
     *      @Parameter("dish_category", type="integer", required=true, description="The type of dish", default=""),
     *      @Parameter("description", type="string", required=true, description="Admin Review description.", default=""),
     * })
     */
    public function update(Requests\AdminReviewRequest $request, $id)
    {
        $adminReview = AdminReview::with(['images'])->whereId($id)->firstOrFail();

        $inputs = $request->only(Requests\AdminReviewRequest::getFields());

        //Get Inputs
        $inputs = Requests\AdminReviewRequest::removeParent($inputs);

        //Update Restaurant
        $adminReview->fill($inputs)->save();

        //Store Images
        $this->storeAdminReviewsImages($request, $adminReview);

        //Delete Images
        $this->deleteAdminReviewsImages($request, $adminReview);

        $adminReview = AdminReview::with(['images'])->find($adminReview->id);

        return $this->response->created('', $adminReview);
    }

    /**
     * Delete Admin Review
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="string", required=true, description="The admin review id."),
     * })
     */
    public function destroy(Requests\AdminReviewRequest $request, $id)
    {

        $adminReview = AdminReview::whereId($id)->delete();

        if (!$adminReview) {
            return $this->response->errorNotFound(trans('Admin Review not found!'));
        }

        return $this->response->created('', $adminReview);
    }

}
