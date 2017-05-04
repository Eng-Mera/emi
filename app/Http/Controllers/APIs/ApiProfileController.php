<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Helpers\UserTrait;
use App\Profile;
use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

class ApiProfileController extends Controller
{
    use Helpers, UserTrait;

    /**
     * /username
     *
     * @param $username
     * @return Response
     */
    public function show($username)
    {
        $user = $this->getUserByUsername($username);
        return $this->response->created('', $user);
    }

    /**
     * /profile/username/edit
     *
     * @param $username
     * @return mixed
     */
    public function edit($username)
    {
        $user = $this->getUserByUsername($username);

        if ($user && $user->isOwner($user)) {
            return $this->response->created('', $user);
        } else {
            return $this->response->errorForbidden(trans('You don\'t have permission to access this page'));
        }
    }


    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update($username, Requests\ProfileRequest $request)
    {
        $inputs = $request->only('mobile', 'address', 'qualification', 'current_employee', 'current_position', 'previous_employee', 'previous_position', 'experience_years', 'current_salary', 'expected_salary');

        $user = $this->updateProfile($username, $inputs);

        return $this->response->created('', $user);
    }

}
