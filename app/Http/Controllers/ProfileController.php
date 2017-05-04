<?php

namespace App\Http\Controllers;

use App\Http\Controllers\APIs\ApiProfileController;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Mockery\CountValidator\Exception;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileController extends ApiProfileController
{
    /**
     * /username
     *
     * @param $username
     * @return Response
     */
    public function show($username)
    {

        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/profile/' . $username);

        return view('profile.show')->withUser($user);
    }

    /**
     * /profile/username/edit
     *
     * @param $username
     * @return mixed
     */
    public function edit($username)
    {
        
        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->get(env('API_VERSION', 'v1') . '/profile/' . $username . '/edit');

        return view('profile.edit')->withUser($user);

    }

    /**
     * Update a user's profile
     *
     * @param $username
     * @return mixed
     * @throws Laracasts\Validation\FormValidationException
     */
    public function update($username, Requests\ProfileRequest $request)
    {

        $user = parent::updateProfile($username, $request);

        $user = $this->api->version(env('API_VERSION', 'v1'))
            ->header('webAuthKey', Config::get('api.webAuthKey'))
            ->post(env('API_VERSION', 'v1') . '/profile/' . $username)->attach([
                'uploaded_file' => $request->file('uploaded_file')
            ]);

        return Redirect::route('profile.edit', $user->username)->with('content-message', trans('Profile has been updated Successfully'));
    }

}