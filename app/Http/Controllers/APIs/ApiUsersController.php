<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\APIs;

use App\File;
use App\Gallery;
use App\Http\Controllers\Controller;
use App\Http\Helpers\FileTrait;
use App\Http\Helpers\UserTrait;
use App\Restaurant;
use App\Review;
use App\Role;
use App\User;
use App\UserLang;

use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use App\Http\Requests;

/**
 * Users.
 *
 * @Resource("Users", uri="/api/v1/user")
 */
class ApiUsersController extends Controller
{
    use Helpers, UserTrait, FileTrait;

    /**
     * List all users
     *
     * @Get("/{?per_page,search,order,id,order_type}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("per_page", type="integer", required=false, description="Number of items per pages.", default=10),
     *      @Parameter("search", type="string", required=false, description="A search query.", default=""),
     *      @Parameter("order", type="string", required=false, description="Column name to order results by (name - id - email).", default="id"),
     *      @Parameter("order_type", type="string", required=false, description="Type of sorting (Descending - Ascending).", default="desc")
     *      @Parameter("code", type="string", required=false, description="Type of sorting (Descending - Ascending).", default="desc")
     * })
     */
    public function index(Requests\UserRequest $request)
    {

        $perPage = Request::get('per_page', 10);

        $searchParams = [
            Request::get('search', false),
            Request::get('role_filter', false),
        ];

        $code = Request::get('code',false);

        if (array_filter($searchParams)) {
            $searchQuery = implode(' ', $searchParams);
        } else {
            $searchQuery = false;
        }

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        if ($searchQuery) {
            $users = User::with(['roles' => function ($query) {
                $query->select('display_name', 'color');
            }, 'profile'])->checkRole()->search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            if ($code == 1)
            {
                if ($users->count() == 1)
                {
                    foreach ($users as $user)
                    {
                        $user->special_code = rand();
                        $user->save();
                    }
                }
            }
            elseif ($code == 2)
            {
                foreach ($users as $user)
                {
                    $user->special_code = '';
                    $user->save();
                }
            }
        } else {
            $users = User::with(['roles' => function ($query) {
                $query->select('display_name', 'color');
            }, 'profile'])->checkRole()->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $users);
    }

    /**
     * View User information and details
     *
     * @Get("/{username}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="Username of specified user."),
     * })
     */
    public function show($username, Requests\UserRequest $request)
    {
        $user = User::with(['profile', 'profilePicture'])->whereUsername($username)->firstOrFail();

        $user->mobile = @$user->profile->mobile;

        return $this->response->accepted('', $user);
    }

    /**
     * Update the specified User
     *
     * @Put("/{username}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="Username of specified user."),
     *      @Parameter("name", type="string", required=true, description="User first and last name.", default=""),
     *      @Parameter("email", type="string", required=true, description="Email address", default=""),
     *      @Parameter("current_password", type="string", required=false, description="Required when trying to change password", default=""),
     *      @Parameter("password", type="float", required=false, description="New Password.", default=""),
     *      @Parameter("password_confirmation", type="float", required=false, description="Confirm of new password.", default=""),
     *      @Parameter("dob", type="string", required=false, description="User Date of birth.", default=""),
     *      @Parameter("mobile", type="string", required=false, description="User mobile phone.", default=""),
     *      @Parameter("address", type="integer", required=false, description="User Address.", default=""),
     *      @Parameter("qualification", type="string", required=false, description="User Qualification.", default=""),
     *      @Parameter("current_employee", type="string", required=false, description="User current employee.", default=""),
     *      @Parameter("current_position", type="string", required=false, description="User current position.", default=""),
     *      @Parameter("previous_employee", type="string", required=false, description="User previous employee.", default=""),
     *      @Parameter("previous_position", type="string", required=false, description="User previous position.", default=""),
     *      @Parameter("experience_years", type="string", required=false, description="Years of experience.", default=""),
     *      @Parameter("current_salary", type="float", required=false, description="Current salary.", default=""),
     *      @Parameter("expected_salary", type="float", required=false, description="Excepected salary.", default=""),
     *      @Parameter("fb_id", type="integer", required=false, description="The facebook id.", default=""),
     *      @Parameter("google_id", type="integer", required=false, description="The google account id.", default=""),
     *      @Parameter("intgm_id", type="integer", required=false, description="The instagram id.", default=""),
     * })
     */
    public function update($username, Requests\UserRequest $request)
    {
        $user = User::with(['profile', 'profilePicture'])->whereUsername($username)->firstOrFail();

        $userName = $request->get('username', false);

        if (!$username) $username = $userName;

        //Update user.
        $this->updateUser($request, $user);

        //Update user role.
        $this->updateUserRoles($user, $request->get('roles'));

        //Update user profile
        $this->updateUserProfile($request->all(), $user);

        //Update user profile image
        $this->updateProfileImage($request, $user);

        return $this->response->created('', $this->getUserByUsername($username));
    }

    /**
     * Delete specified user
     *
     * @Delete("/{username}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="Username of specified user."),
     * })
     */
    public function destroy($username, Requests\UserRequest $request)
    {
        $user = User::whereUsername($username)->delete();

        if (!$user) {
            return $this->response->errorNotFound(trans('User not found!'));
        }

        return $this->response->created('', $user);
    }

    /**
     * List users Favorite restaurants
     *
     * @Get("/{username}/favorite-restaurants")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="The username for the specified user.", default=""),
     * })
     */
    public function favoriteRestaurant($username)
    {

        $user = User::with(['favoriteRestaurants', 'favoriteRestaurants.logo'])->whereUsername($username)->firstOrFail();

        return $this->response->created('', $user->favoriteRestaurants->toArray());
    }

    /**
     * List users uploaded restaurants images
     *
     * @Get("/{username}/restaurants-images")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("username", type="string", required=true, description="The username for the specified user.", default=""),
     * })
     */
    public function restaurantsImages($username)
    {
        $perPage = Request::get('per_page', 10);

        $orderBy = Request::get('order', 'id') ? Request::get('order', 'id') : 'id';
        $orderDir = Request::get('order_type', 'desc') ? Request::get('order_type', 'desc') : 'desc';

        $user = User::whereUsername($username)->firstOrFail();

        $with = ['meta', 'imageable'];

        if ($perPage < 0) {
            $images = File::with($with)->objectType(Gallery::class)->whereUserId($user->id)->orderBy($orderBy, $orderDir)->get();
        } else {
            $images = File::with($with)->objectType(Gallery::class)->whereUserId($user->id)->orderBy($orderBy, $orderDir)->paginate($perPage);
        }

        return $this->response->created('', $images);
    }

    /**
     * List users by their role
     *
     * @Get("/{role_name}")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("role_name", type="string", required=true, description="The name of role to list users by.
    ::: note
    The role name value must be one of the below values
     * super-admin
     * restaurant-manager
     *  restaurant-admin
     *  reservation-manager
     *  auditor
     *  auditor-of-auditors
     *  blogger-food-critics
     *  diner
    :::
    ", default=""),
     * })
     */
    public function usersByRole($roleName)
    {
        $roles = ['super-admin', 'restaurant-manager', 'restaurant-admin', 'reservation-manager', 'auditor', 'auditor-of-auditors', 'blogger-food-critics', 'diner'];

        if (!in_array($roleName, $roles)) {
            $this->response->errorNotFound(trans('Role not found!'));
        }

        $roles = Role::with(['users' => function ($query) {
            $query->checkRole();
        }, 'users.profilePicture', 'users.profile'])->where('name', $roleName)->first();

        $users = $roles->users;

        return $this->response->created('', $users);
    }


    public function setLang($id, $lang)
    {
        $userLang = UserLang::Where('user_id', $id)->first();
        if (!empty($userLang->id)) {
            $userLanguage = UserLang::find($userLang->id);
            $userLanguage->lang = $lang;
            $userLanguage->save();
            return $userLanguage;
        } else {
            $attributes = array('user_id' => $id, 'lang' => $lang);

            $newLang = UserLang::create($attributes);
            $userLang = UserLang::find($newLang->id);
        }

        return $this->response->created('', $userLang);
    }

    public function getLang($id)
    {
        $lang = UserLang::Where('user_id', $id)->firstOrFail();

        return $this->response->created('', $lang->lang);
    }


    /**
     * Users Coupons
     *
     * @Get("/{user}/coupons")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("user", type="string", required=true, description="The name of role to list users by.", default=""),
     * })
     *
     * @see User one to many relation
     * @author Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
     *
     * @Versions({"1"})
     * @Get("/{user_id}/coupons")
     */
    public function coupons(User $user)
    {
        $data = [
            'user' => $user
        ];
        $rules = [
            'user' => 'check_can_view_user_related'
        ];
        $validator = \Validator::make($data, $rules);
        if ($validator->fails()) {
            return $this->response->errorBadRequest($validator->errors());
        }
        return $this->response->array($user->coupons);
    }

    /**
     * searches the database for all users who have names like the given one
     * and then returns json id => username array
     *
     * @param Request $request
     * @return mixed
     */
    public function autocomplete(Request $request)
    {
        $q = Request::get('term');

        $results = User::select(['id', 'username'])
            ->where('name', 'like', '%' . $q . '%')
            ->orWhere('username', 'like', '%' . $q . '%')
            ->orWhere('email', 'like', '%' . $q . '%')
            ->get();

        return $this->response->array($results);
    }

    /**
     * Manager Restaurant
     *
     * @Get("/manager-restaurant")
     * @Versions({"v1"})
     *
     * @Versions({"1"})
     */
    public function managerRestaurant()
    {

        $user = User::getCurrentUser();

        if (!$user->hasRole(Role::RESTAURANT_MANAGER)) {
            return $this->response->errorForbidden('You must be a restaurant manager');
        }

        $with = ['owner' => function ($q) {
            $q->select('id', 'name', 'username');
        }, 'logo', 'featured', 'categories', 'managers', 'city', 'facilities'];

        $restaurant = Restaurant::with($with)->whereOwnerId($user->id)->firstOrFail();

        return $this->response->created('', $restaurant);
    }

    /**
     * Send Ad
     *
     * @Post("/send-ads-email")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("email", type="email", required=false, description="if user not logged in it's not required.", default=""),
     *      @Parameter("message", type="text", required=true, description="The body of mail.", default=""),
     * })
     *
     * @Versions({"1"})
     */
    public function sendAdvertisementEmail(\Illuminate\Http\Request $request)
    {
        $user = User::getCurrentUser();

        $rules = [
            'message' => 'required',
        ];

        if (!$user) {
            $rules['email'] = 'required|email|real_email';
        }

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            throw new ValidationHttpException($errors);
        }

        $message = $request->get('message');

        if (!$user) {
            $email = $request->get('email');
        } else {
            $email = $user->email;
        }

        \Mail::send('emails.ads.ads', ['mail' => $message], function ($m) use ($user, $email) {

            $m->from($email, 'Advertisement Request');

            $m->to(config('nilecode.emails.ads.email'))->subject('Advertisement Request');
        });

        return $this->response->created('', ['message' => 'Email has been sent']);
    }
}
