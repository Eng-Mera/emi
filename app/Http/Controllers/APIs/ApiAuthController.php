<?php

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Helpers\FileTrait;
use App\Http\Helpers\UserTrait;
use App\Http\Requests\AuthRequest;
use App\Profile;
use App\Role;
use App\User;

use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Dingo\Api\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

/**
 * Register new account.
 * Create Access token.
 * Reset Account password
 *
 * @Resource("Auth")
 */
class ApiAuthController extends Controller
{
    public $lockoutTime = 60;
    public $maxLoginAttempts = 3;

    use Helpers, ThrottlesLogins, UserTrait, FileTrait;

    use AuthenticatesAndRegistersUsers, ResetsPasswords {
        ResetsPasswords::guestMiddleware insteadof AuthenticatesAndRegistersUsers;
        ResetsPasswords::getGuard insteadof AuthenticatesAndRegistersUsers;
        ResetsPasswords::redirectPath insteadof AuthenticatesAndRegistersUsers;
    }

    /**
     * Create New User
     *
     * @Post("/api/v1/user/register")
     * @Versions({"v1"})
     * @Parameters({
     *      @Parameter("name", type="string", required=true, description="The name of registered user.", default=""),
     *      @Parameter("username", type="string", required=true, description="the username of registered user.", default=""),
     *      @Parameter("email", type="string", required=true, description="The email of registered user", default=""),
     *      @Parameter("mobile", type="string", required=true, description="The mobile number of registered user", default=""),
     *      @Parameter("password", type="string", required=true, description="Password of account.", default=""),
     *      @Parameter("password_confirmation", type="string", required=true, description="Password Repeat.", default=""),
     *      @Parameter("profile_image", type="string", required=true, description="Profile picture in base64 encode format.", default=""),
     *      @Parameter("fb_id", type="integer", required=false, description="The facebook id.", default=""),
     *      @Parameter("google_id", type="integer", required=false, description="The google account id.", default=""),
     *      @Parameter("intgm_id", type="integer", required=false, description="The instagram id.", default=""),
     *      @Parameter("user_type", type="string", required=false, description="User type job-seaker, restaurant-manager.", default=""),
     * })
     */
    public function register(AuthRequest $request)
    {
        $fields = $request->all();

        \DB::beginTransaction();

        $user = $this->create($fields);

        if (!$user) {

            \DB::commit();

            return $this->response->errorBadRequest(trans('Something went wrong please try again later!'));
        }

        $this->assignRole($user, @$fields['role']);

        $this->assignUserTypeRole($user, @$fields['user_type']);

        $this->createProfile($user, $request->get('mobile', ''));

        $this->saveProfileImage($request, $user);

        $user = User::with(['profilePicture', 'profile', 'roles'])->whereId($user->id)->first();

        $user->mobile = @$user->profile->mobile;

        \DB::commit();

        return $this->response->created('', $user);

    }

    public function postAuthorization()
    {
        $params = Authorizer::getAuthCodeRequestParams();
        $params['user_id'] = Auth::user()->id;
        $redirectUri = '/';

        $request = app(Request::class);

        // If the user has allowed the client to access its data, redirect back to the client with an auth code.
        if ($request->has('approve')) {
            $redirectUri = Authorizer::issueAuthCode('user', $params['user_id'], $params);
        }

        // If the user has denied the client to access its data, redirect back to the client with an error message.
        if ($request->has('deny')) {
            $redirectUri = Authorizer::authCodeRequestDeniedRedirectUri();
        }

        return Redirect::to($redirectUri);
    }

    public function getAuthorization()
    {
        $authParams = Authorizer::getAuthCodeRequestParams();

        $formParams = array_except($authParams, 'client');

        $formParams['client_id'] = $authParams['client']->getId();

        $formParams['scope'] = implode(config('oauth2.scope_delimiter'), array_map(function ($scope) {
            return $scope->getId();
        }, $authParams['scopes']));

        return View::make('auth.authorization-form', ['params' => $formParams, 'client' => $authParams['client']]);
    }

    /**
     * Get Access Token by Client Credentials
     *
     * @Post("/oauth/access_token")
     * @Version({"v1"})
     * @Parameters({
     *      @Parameter("grant_type",type="string", required=true,description="The type of grant values= password,social_media,social_media_update"),
     *      @Parameter("client_id",type="string", required=true,description="The oauth client id"),
     *      @Parameter("client_secret",type="string", required=true,description="The oauth client secret"),
     *      @Parameter("username",type="string", required=true,description="The email or username of user"),
     *      @Parameter("password",type="string", required=true,description="The Password of user"),
     *      @Parameter("social_type",type="string", required=true,description="In case of grant type is social_media_update The types of social media facebook, google, instagram"),
     *      @Parameter("social_id",type="string", required=true,description="In case of grant type is social_media The id of account of social media account"),
     *      @Parameter("email",type="string", required=true,description="In case of grant type is social_media_update The email user account"),
     *      @Parameter("refresh_token",type="string", required=false,description="After expiration of access token we can use this key to refresh the token")
     * })
     */
    public function accessToken()
    {
        try {
            $token = \Authorizer::issueAccessToken();

            return Response::json($token);

        } catch (\Exception $e) {
            return Response::json([
                'message' => $e->getMessage()
            ], isset($e->httpStatusCode) ? $e->httpStatusCode : 400);
        }
    }

    /**
     * Reset account Password
     *
     * @Post("/password/reset")
     * @Version({"v1"})
     * @Parameters({
     *      @Parameter("email",type="string", required=true,description="The email of account to be reset."),
     *      @Parameter("reset_url", type="string", required=false,description="The url which the application will send email to. http://{{your_url}}/{token}. Please note that the url must contain token word")
     * })
     */
    public function resetPassword(Request $request)
    {

        $validator = $this->getValidationFactory()->make($request->all(), ['email' => 'required|email', 'reset_url' => 'url']);

        $resetUrl = $request->get('reset_url', false);

        if ($validator->fails() || ($resetUrl && strpos($resetUrl, '{token}') === false)) {

            $errors = $validator->errors();

            if ($resetUrl && strpos($resetUrl, '{token}') === false) {
                $errors->add('reset_url', trans('Url isn\'t valid plase make sure its contains {token} word'));
            }

            throw new Exception\ValidationHttpException($errors);
        }

        $broker = $this->getBroker();

        if ($resetUrl) {
            config(['auth.passwords.users.email' => ['auth.emails.custom-password', 'auth.emails.custom-password']]);
        }

        $response = Password::broker($broker)->sendResetLink(
            $request->only('email'), $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->response->created('', ['message' => trans('An email has been sent to your email!')]);

            case Password::INVALID_USER:
            default:
                return $this->response->errorNotFound(trans('The email address you entered cound not be found!'));
        }

    }

    /**
     * Complete Reset Password
     *
     * @Post("/password/complete-reset")
     * @Version({"v1"})
     * @Parameters({
     *      @Parameter("email",type="string", required=true,description="The email of account to be reset."),
     *      @Parameter("token",type="string", required=true,description="The token that sent to user through email"),
     *      @Parameter("password",type="string", required=true,description="The new password"),
     *      @Parameter("password_confirmation",type="string", required=true,description="New Password confirmation
     * ")
     * })
     */
    public function completeResetPassword()
    {
        $request = $request = app(Request::class);

        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );

        $credentials = $this->getResetCredentials($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return $this->response->created('', [
                    'message' => trans('Your password has been reset')
                ]);
            default:
                return $this->response->errorBadRequest(trans('The token is no longer valid'));
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {

        $currentUser = @User::getCurrentUser()->id;

        $restaurant = @User::getManagersRestaurant()->id;

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'username' => @$data['username'],
            'dob' => @$data['dob'],
            'gender' => @$data['gender'],
            'created_by' => $currentUser,
            'manage_restaurant_id' => $restaurant,
            'fb_id' => @$data['fb_id'],
            'google_id' => @$data['google_id'],
            'intgm_id' => @$data['intgm_id'],
        ]);
    }
}
