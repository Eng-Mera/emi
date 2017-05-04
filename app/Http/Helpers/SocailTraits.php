<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 4/25/16
 * Time: 11:09 AM
 */

namespace App\Http\Helpers;


use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use App\SocialProvider;

trait SocailTraits
{

    public function handleSocialMediaResponse($user, $socialUser, $driver)
    {

        $data = $this->getSocailData($socialUser, $driver);

        if (!$user || !$user->count()) {

            $password = Hash::make('123456');

            $user = $this->api->version(env('API_VERSION', 'v1'))->post(env('API_VERSION', 'v1') . '/register', [
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => $password,
                'password_confirmation' => $password
            ]);

            $imgType = get_headers($data['uploaded_file'], 1)["Content-Type"];

            if (is_array($imgType)) {
                $imgType = $imgType[0];
            }

            $user = $this->api->version(env('API_VERSION', 'v1'))->header('webAuthKey', Config::get('api.webAuthKey'))->post(env('API_VERSION', 'v1') . '/file', [
                'uploaded_file' => 'data:' . $imgType . ';base64,' . base64_encode(@file_get_contents($data['uploaded_file'])),
                'internal_user_id' => $user->id
            ]);


        }

        Auth::login($user);

        $this->saveSocailProvider($socialUser, $user, $driver);

        return $user;
    }

    protected function getSocailData($socialUser, $driver)
    {

        $data = [];

        switch ($driver) {
            case 'facebook':

                $data = [
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'username' => str_slug($socialUser->name . '_' . $socialUser->id),
                    'uploaded_file' => !empty($socialUser->avatar_original) ? $socialUser->avatar_original : ''
                ];

                break;

            case 'twitter':

                $data = [
                    'name' => $socialUser->name,
                    'username' => str_slug($socialUser->nickname . '_' . $socialUser->id),
                    'pid' => $socialUser->id,
                    'token' => $socialUser->token,
                    'email' => $socialUser->email,
                    'driver' => $driver,
                    'uploaded_file' => !empty($socialUser->avatar_original) ? $socialUser->avatar_original : ''
                ];

                break;
            case 'instagram':

                $data = [
                    'name' => $socialUser->name,
                    'username' => str_slug($socialUser->nickname . '_' . $socialUser->id),
                    'pid' => $socialUser->id,
                    'token' => $socialUser->token,
                    'email' => $socialUser->email,
                    'driver' => $driver,
                    'uploaded_file' => !empty($socialUser->avatar) ? $socialUser->avatar : ''
                ];

                break;
            default:
                break;
        }

        return $data;

    }

    protected function saveSocailProvider($socialUser, $user, $driver)
    {
        $socialProvider = new SocialProvider();
        $socialProvider->pid = $socialUser->id;
        $socialProvider->token = $socialUser->token;
        $socialProvider->refresh_token = $socialUser->token;
        $socialProvider->token = $socialUser->token;
        $socialProvider->provider_type = $driver;
        $socialProvider->owner_id = $user->id;

        $socialProvider->save();
    }
}