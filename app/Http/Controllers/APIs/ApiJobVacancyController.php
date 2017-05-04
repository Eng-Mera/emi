<?php

namespace App\Http\Controllers\APIs;

use App\Category;
use App\File;
use App\Http\Controllers\Controller;
use App\JobTitle;
use App\JobVacancy;
use App\MenuItem;
use App\Restaurant;
use App\Role;
use App\User;

use App\Http\Requests;
use Dingo\Api\Routing\Helpers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

/**
 * Jobs Vacancies.
 *
 * @Resource("Job Vacancies", uri="/api/v1/restaurant/{restaurant_slug}/job-vacancy")
 */
class ApiJobVacancyController extends Controller
{

    use Helpers;

    /**
     * List Jobs Vacancies
     *
     * @Get("/{?per_page,search,order,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by.[id, created_at, rate_value, user_rating]", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (DESC - ASC).", default="DESC"),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function index(Requests\JobVacancyRequest $request, $restaurantSlug = null)
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

        $with = ['restaurant' => function ($query) {
            return $query->select('id', 'slug');
        }, 'jobTitle'];

        if ($perPage == -1) {
            $jobsTitles = JobVacancy::with($with)->orderBy($orderBy, $orderDir)->allowed()->get();
        } else {

            if ($searchQuery) {
                $jobsTitles = JobVacancy::with($with)->search($searchQuery)->filterByJobTitle()->allowed()->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $jobsTitles = JobVacancy::with($with)->orderBy($orderBy, $orderDir)->filterByJobTitle()->allowed()->paginate($perPage);
            }
        }

        return $this->response->created('', $jobsTitles);
    }

    /**
     * Apply to Job
     *
     * @Post("/apply-tot-job")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("job_id", type="integer", required=true, description="The title of job .", default=""),
     *      @Parameter("user_id", type="integer", required=true, description="The id of user applied to the job.", default=""),
     *      @Parameter("status", type="boolean", required=true, description="The status of job.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function apply($restaurantSlug, $jobVacancyId)
    {
        $user = User::getCurrentUser();

        if (!$user || !$user->hasRole(Role::JOB_SEEKER)) {
            return $this->response->errorForbidden('Your must be a job seeker to apply to this job');
        }

        $rest = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $manager = User::find($rest->owner_id);

        $jobVacancy = JobVacancy::with('appliedUsers')->findOrFail($jobVacancyId);

        if ($jobVacancy->appliedUsers->contains($user->id)) {
            return $this->response->errorBadRequest('You have already applied to this job');
        }

        $jobVacancy->appliedUsers()->attach($user->id);

        //@TODO Handle Mail Request.
        Mail::send('emails.cart.admin.job_applied_user', ['user' => $user, 'manager' => $manager], function ($m) use ($user, $manager, $rest) {

            $m->from('jobs@howtheyrate.net', 'A new job seeker applied for your job');

            if ($rest->email) {
                $m->to($rest->email, $user->name)->subject('A new job seeker applied for your job!');
            } else {
                $m->to('jobs@howtheyrate.net', $user->name)->subject('A new job seeker applied for your job!');
            }

        });

        return $this->response->created('', [trans('Your application has been saved!')]);
    }

    /**
     * List Users applied to jobs
     *
     * @Post("/apply-tot-job")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("job_id", type="integer", required=true, description="The title of job .", default=""),
     *      @Parameter("user_id", type="integer", required=true, description="The id of user applied to the job.", default=""),
     *      @Parameter("status", type="boolean", required=true, description="The status of job.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function listJobs($restaurantSlug, $jobId)
    {
        $user = User::getCurrentUser();

        $restaurant = Restaurant::whereSlug($restaurantSlug)->firstOrFail();

        $userRestaurant = User::getManagersRestaurant();

        if (!$user || @$userRestaurant->id !== $restaurant->id) {
            return $this->response->errorForbidden('Your must be a restaurant manager to view this page');
        }

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

        $with = ['jobTitle', 'appliedUsers'];

        $where = ['id' => $jobId, 'restaurant_id' => $restaurant->id];

        if ($perPage == -1) {
            $jobVacancy = JobVacancy::with($with)->where($where)->first()->appliedUsers()->orderBy($orderBy, $orderDir)->allowed()->get();
        } else {

            $jobVacancy = JobVacancy::with($with)->where($where)->first();
            if ($jobVacancy) {
                if ($searchQuery) {
                    $jobVacancy = $jobVacancy->appliedUsers()->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
                } else {
                    $jobVacancy = $jobVacancy->appliedUsers()->orderBy($orderBy, $orderDir)->paginate($perPage);
                }
            }
        }

//        foreach ((array)$jobVacancy as $a => $j) {
//            $jobVacancy[$a]->created_at = $jobVacancy[$a]->pivot->created_at;
//            $jobVacancy[$a]->updated_at = $jobVacancy[$a]->pivot->updated_at;
//        }

        return $this->response->created('', $jobVacancy);
    }

    /**
     * Create Job Vacancy
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("job_title_id", type="integer", required=true, description="The title of job .", default=""),
     *      @Parameter("description", type="string", required=true, description="The job title description.", default=""),
     *      @Parameter("status", type="boolean", required=true, description="The status of job.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function store(Requests\JobVacancyRequest $request, $restaurantSlug)
    {
        //Get Inputs
        $inputs = $request->only(Requests\JobVacancyRequest::getFields());
        $inputs = Requests\JobVacancyRequest::removeParent($inputs);

        $inputs['user_id'] = User::getCurrentUser()->id;

        $restaurant = @Restaurant::whereSlug($restaurantSlug)->first();

        $inputs['restaurant_id'] = $restaurant->id;

        //Create Job Vacancy
        $jobVacancy = JobVacancy::create($inputs);

        $jobVacancy = JobVacancy::with(['restaurant' => function ($query) {
            return $query->select('id', 'slug');
        }])->find($jobVacancy->id);

        return $this->response->created('', $jobVacancy);
    }

    /**
     * Read Job Vacancy
     *
     * @Get("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The job title id.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function show(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {
        $jobVacancy = JobVacancy::with(['restaurant' => function ($query) {
            return $query->select('id', 'slug');
        }])->findOrFail($id);
        return $this->response->created('', $jobVacancy);
    }

    /**
     * Update Job Vacancy
     *
     * @Put("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("job_title_id", type="integer", required=true, description="The title of job .", default=""),
     *      @Parameter("description", type="string", required=true, description="The job title description.", default=""),
     *      @Parameter("status", type="boolean", required=true, description="The status of job.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function update(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {
        $jobVacancy = JobVacancy::whereId($id)->firstOrFail();

        //Get Inputs
        $inputs = $request->only(Requests\JobVacancyRequest::getFields());
        $inputs = Requests\JobVacancyRequest::removeParent($inputs);

        //Update Job title
        $jobVacancy->fill($inputs)->save();

        $jobVacancy = JobVacancy::with(['restaurant' => function ($query) {
            return $query->select('id', 'slug');
        }])->find($jobVacancy->id);

        return $this->response->created('', $jobVacancy);
    }

    /**
     * Delete Job Vacancy
     *
     * @Delete("/{id}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("id", type="integer", required=true, description="The job title id.", default=""),
     *      @Parameter("restaurant_slug", type="string", required=true, description="The slug name of restaurant.", default="DESC"),
     * })
     */
    public function destroy(Requests\JobVacancyRequest $request, $restaurantSlug, $id)
    {
        $user = User::getCurrentUser();

        $restaurant = Restaurant::whereSlug($restaurantSlug)->first();

        $jobVacancy = JobVacancy::where(['id' => $id, 'user_id' => $user->id, 'restaurant_id' => $restaurant->id])->firstOrFail();

        $jobVacancy->delete();

        return $this->response->created('', $jobVacancy);
    }

}
