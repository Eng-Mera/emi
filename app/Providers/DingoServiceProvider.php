<?php

namespace App\Providers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use Illuminate\Support\Facades\Config;
use League\OAuth2\Server\ResourceServer;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class DingoServiceProvider implements Provider
{
    public function authenticate(Request $request, Route $route)
    {
        return $request;
    }
}