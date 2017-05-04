<?php

namespace App\Http\Middleware;

use App\City;
use App\Http\Helpers\MenuTrait;
//use App\Http\Requests\Request;
use App\Role;
use App\Route;
use App\User;
use App\UserLang;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Exception\DumpException;
use Request;


if (!function_exists('getallheaders')) {
    function getallheaders()
    {
        $headers = '';
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}

class Authenticate
{

    use MenuTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */


    public function handle($request, Closure $next, $guard = null)
    {

        $this->loadLocale();

        $currentRoute = ltrim($request->route()->uri(), '/');

        $method = $request->route()->methods();

        $route = Route::with('routePermissions')->wherePath($currentRoute)->whereIn('method', $method)->first();

        if (!$route || empty($route->routePermissions)) {
            throw new NotFoundHttpException();
        }

        $permissions = $route->routePermissions->pluck('name')->toArray();

        $roles = $route->routeRoles->pluck('name')->toArray();

        $currentUser = User::getCurrentUser();

        if ($currentUser) {

            if (!$route->except && !$currentUser->ability($roles, $permissions, false)) {

                if (App::environment('local', 'staging')) {

                    $userRole = $currentUser->roles->pluck('name')->toArray();
                    $routeName = '[' . $route->method . '] ' . $route->path;
                    throw new DumpException('The following Roles "' . implode(', ', $userRole) . '" need to have access to the following route: ' . $routeName);
                }

                throw new AccessDeniedHttpException('You aren\'t allowed to be here');
            }

        } elseif (in_array(Role::GUEST, $roles)) {

            $roleRoutes = Role::with(['roleRoutes'])->first()->roleRoutes()->where('path', $currentRoute)->count();

            if (!$roleRoutes) {
//                return redirect('/login');
            }

        } else {

            if (App::environment('local', 'staging')) {

                $userRole = !empty($currentUser->roles) ? @implode(',', $currentUser->roles->pluck('name')->toArray()) : 'guest';

                $routeName = '[' . $route->method . '] ' . $route->path;

                throw new DumpException('The following Roles "' . $userRole . '" need to have access to the following route: ' . $routeName);
            }

            throw new AccessDeniedHttpException('You aren\'t allowed to do this action');
        }


        return $next($request);
    }

    /**
     * Change Translatable Config file to the locale the user choose
     */
    public function loadLocale()
    {

        $user = User::getCurrentUser();

        if (!$user)
        {
            $locale = Request::header('Locale');

            \Config::set('translatable.locale', $locale);
        }
        else
        {
            if(Request::hasHeader('Locale'))
            {
                $locale = Request::header('Locale');
            }
            else
            {
                $lang = UserLang::Where('user_id', $user->id)->first();

                if ($lang) {
                    $locale = $lang->lang ? $lang->lang : config('translatable.locale');
                }
            }
            \Config::set('translatable.locale', @$locale);
        }
    }
}
