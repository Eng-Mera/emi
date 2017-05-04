<?php


namespace App\Http\Requests;

use App\Permission;
use App\Http\Requests\Request;
use App\Report;
use App\User;
use Illuminate\Support\Facades\Route;

class ReportRequest extends Request
{

    protected static $_fields = [
        'report_type',
        'report_subject',
        'reported_id',
        'user_id',
        'details'
    ];

    protected $_rules = [
        'user_id' => 'required',
        'report_type' => 'required',
        'report_subject' => 'required',
        'reported_id' => 'required',
        'details' => 'max:800'
    ];

    public function setObject(){

        return true;
    }

    protected function extendedCRUDValidation(\Illuminate\Http\Request $request, $currentUser)
    {
        return true;
    }

    public static function getFields()
    {

        return self::$_fields;
    }

    public function rules(){
        return [];
    }
}
