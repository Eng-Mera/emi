<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\APIs\ApiAuthController;
use App\Http\Helpers\SocailTraits;
use App\Http\Requests\AuthRequest;

use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends ApiAuthController
{

    use SocailTraits;

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    public function showLoginForm()
    {
        if (!Auth::guest()) {
            return redirect('/home');
        }
        return parent::showLoginForm();
    }

    public function showRegistrationForm()
    {
        return redirect('login');
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    public function register(AuthRequest $request)
    {

    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function social($driver)
    {
        if (Auth::check()) {
            return Redirect::to('/profile/' . Auth::user()->username . '/edit')->withErrors('You already logged In.');
        }

        return Socialite::driver($driver)->redirect();
    }

    public function redirectPath()
    {
        $request = app('Illuminate\Http\Request');
        $redirectUrl = $request->session()->get('redirect_url');

        if (!empty($redirectUrl)) {
            $request->session()->forget('redirect_url');
            return $redirectUrl;
        }

        return parent::redirectPath();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function socialCallback($driver)
    {

        if (Auth::check()) {
            return Redirect::to('/profile/' . Auth::user()->username . '/edit')->withErrors('You already logged In.');
        }

        try {

            $socialUser = Socialite::driver($driver)->user();
            $user = User::whereEmail($socialUser->email)->first();

        } catch (\Exception $e) {
            return Redirect::to('/auth/' . $driver);
        }

        try {

            if (in_array($driver, ['twitter', 'instagram'])) {

                return Redirect::to('register?social=' . $driver)->withInput([
                    'name' => $socialUser->name,
                    'username' => str_slug($socialUser->name . '_' . $socialUser->id),
                    'pid' => $socialUser->id,
                    'token' => $socialUser->token,
                    'driver' => $driver,
                    'uploaded_file' => $socialUser->avatar
                ]);

            }

            $user = $this->handleSocialMediaResponse($user, $socialUser, $driver);

            return Redirect::to('/profile/' . $user->username . '/edit');

        } catch (\Exception $e) {

            return Redirect::to('/login')->withErrors(trans('Something went wrong. Please try again later!'));
        }
    }
}
