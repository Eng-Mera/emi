<?php

namespace App\Http\Controllers;

use App\User;
use Dingo\Api\Routing\Helpers;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables($obj)
    {

        if ($obj && is_object($obj)) {
            $obj = $obj->toArray();
        }else {
            return [
                'recordsFiltered' => 0,
                'iTotalRecords' => 0,
                'data' => []
            ];
        }

        $data = [];
        $data['recordsFiltered'] = @$obj['total'];
        $data['iTotalRecords'] = @$obj['total'];
        $data['data'] = @$obj['data'];

        return $data;
    }

    public function getDatatablePaging($orderColumns = [])
    {

        $orderCloums = !is_null(Request::get('order')[0]['column']) ? Request::get('order')[0]['column'] : false;
        $orderType = !empty(Request::get('order')[0]['dir']) ? Request::get('order')[0]['dir'] : false;

        if ($orderCloums !== false && array_key_exists($orderCloums, $orderColumns)) {
            $orderCloums = $orderColumns[$orderCloums];
        } else {
            $orderCloums = false;
        }

        return [
            'page' => intval(Request::get('start', 1) / Request::get('length', 1)) + 1,
            'per_page' => Request::get('length', 10),
            'order' => $orderCloums,
            'order_type' => $orderType,
            'search' => !empty(Request::get('search')['value']) ? Request::get('search')['value'] : ''
        ];
    }

    protected function can($permission)
    {
        if (!User::getCurrentUser()->can($permission)) {
            abort(401);
        }

        return true;
    }
}
