<?php

namespace App\Http\Controllers\APIs;

//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

use App\Http\Requests;


use App\User;
use Illuminate\Support\Facades\Request;

class ApiTestController extends Controller
{
    use Helpers;

    public function index(Request $request)
    {

        
        $currentUser = User::getCurrentUser();
        $user = new User;
//        dd(Authorizer::getAccessToken());
        dd(Authorizer::getResourceOwnerId());

        return $this->response->created('', $currentUser);
    }
}
