<?php

namespace App\Http\Controllers\APIs;

use Dingo\Api\Routing\Helpers;


use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * General Site Options
 *
 * @Resource("Site Options", uri="/api/v1/site-options")
 */
class ApiGeneralController extends Controller
{
    use Helpers;

    public function index()
    {
        $request = app(Request::class);
        $option_name = $request->get('option_name');
        $options = [
            'site_url' => env('FRONT_URL'),
            'reset_password_front' => env('FRONT_URL') . 'password/reset/{token}',
        ];
        if (!empty($option_name) && array_key_exists($option_name,$options))
        {
            return $this->response->created('',$options[$option_name]);
        }
        return $this->response->created('',$options);
    }
}
