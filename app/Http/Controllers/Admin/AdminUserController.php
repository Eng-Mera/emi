<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\APIs\ApiUsersController;
use App\User;
use App\Http\Requests;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Support\Facades\App;
use App\UserLang;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use RelationalExample\Model\Users;

class AdminUserController extends ApiUsersController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\UserRequest $request)
    {
        if (Request::ajax()) {
//while (1 != 2){ sleep(1); }
            $paging = $this->getDatatablePaging(['id', 'name', 'username', 'email', 'dob', 'created_at', 'updated_at']);

            if (Request::has('role_filter')) {
                $paging['role_filter'] = Request::get('role_filter');
            }

            $user = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->get(env('API_VERSION', 'v1') . '/user', $paging);

            return $this->datatables($user);

        }

        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Requests\UserRequest $request)
    {
        return view('admin.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\AuthRequest $request)
    {

        $inputs = $request->only('pending', 'name', 'email', 'username', 'password', 'password_confirmation', 'dob', 'profile_picture', 'mobile', 'gender', 'role');

        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->attach($request->allFiles())
            ->post(env('API_VERSION', 'v1') . '/register', $inputs);

        return Redirect::route('admin.user.edit', $user->username)->with('content-message', trans('User has been created Successfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($username, Requests\UserRequest $request)
    {
        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/profile/' . $username);

        return view('admin.user.show')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($username, Requests\UserRequest $request)
    {
        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/user/' . $username);

        $profile = @\Session::get('inputs')['profile'];

        if ($profile) {
            $user->profile->fill($profile);
        }

        return view('admin.user.edit')->withUser($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($username, Requests\UserRequest $request)
    {
        try {

            $inputs = $request->all();
            unset($inputs['uploaded_file']);

            $user = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->attach($request->allFiles())
                ->post(env('API_VERSION', 'v1') . '/user/' . $username, $inputs);

        } catch (ValidationHttpException $validator) {

            return Redirect::route('admin.user.edit', $username)->withInputs($request->all())->withErrors($validator->getErrors()->messages());
        } catch (\Exception $e) {

            return Redirect::route('admin.user.edit', $username)->withInputs($request->all())->withErrors($e->getMessage());
        }

        return Redirect::route('admin.user.edit', $user->username)->with('content-message', trans('Profile has been updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($username, Requests\UserRequest $request)
    {
        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->delete(env('API_VERSION', 'v1') . '/user/' . $username);

        if ($user) {
            $msg = 'User has been deleted successfully!';
            return Redirect::route('admin.user.index')->with('content-message', trans($msg));
        } else {
            $msg = 'User already deleted!';
            return Redirect::route('admin.user.index')->with('error-message', trans($msg));
        }

    }

    public function setLang($id, $lang)
    {

        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/setlang/' . $id . '/' . $lang);

        return Redirect::route('admin.user.index', $user)->with('content-message', trans('Profile has been updated Successfully'));

    }

    public function getLang($id)
    {

        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/lang/' . $id);
        return $user;

    }

    public function autocomplete(Request $request)
    {
        return parent::autocomplete($request); // TODO: Change the autogenerated stub
    }
}
