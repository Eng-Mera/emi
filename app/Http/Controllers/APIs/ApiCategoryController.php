<?php

namespace App\Http\Controllers\APIs;

use App\Category;
use App\File;
use App\Http\Controllers\Controller;
use App\MenuItem;
use App\Restaurant;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Request;

/**
 * Categories.
 *
 * @Resource("Categories", uri="/api/v1/category")
 */
class ApiCategoryController extends Controller
{

    use Helpers;

    /**
     * List Categories
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
            $categories = Category::orderBy($orderBy, $orderDir)->get();
        } else {


            if ($searchQuery) {
                $categories = Category::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $categories = Category::orderBy($orderBy, $orderDir)->paginate($perPage);
            }
        }

        return $this->response->created('', $categories);
    }

    /**
     * Create Category
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("category_name", type="string", required=true, description="The category name.", default=""),
     * })
     */
    public function store(Requests\CategoryRequest $request)
    {
        //Get Inputs
        $inputs = $request->only(Requests\CategoryRequest::getFields());
        $inputs = Requests\CategoryRequest::removeParent($inputs);

        //Create Menu Item
        $category = Category::create($inputs);

        $category = Category::find($category->id);

        return $this->response->created('', $category);
    }

    /**
     * Read Category
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The category id.", default=""),
     *      @Parameter("category_name", type="string", required=true, description="The category id."),
     * })
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return $this->response->created('', $category);
    }

    /**
     * Update Category
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The category id.", default=""),
     *      @Parameter("category_name", type="string", required=true, description="The category name.", default=""),
     * })
     */
    public function update(Requests\CategoryRequest $request, $id)
    {
        $category = Category::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\CategoryRequest::getFields());
        $inputs = Requests\CategoryRequest::removeParent($inputs);


        $inputs = array_filter($inputs);

        //Update Restaurant
        $category->fill($inputs)->save();

        $category = Category::find($category->id);

        return $this->response->created('', $category);
    }

    /**
     * Delete Category
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The category id.", default=""),
     * })
     */
    public function destroy($id)
    {
        $category = Category::where(['id' => $id])->firstOrFail();

        $category->delete();

        return $this->response->created('', $category);
    }

}
