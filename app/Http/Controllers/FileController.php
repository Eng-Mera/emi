<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers;

use App\Http\Controllers\APIs\ApiFileController;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class FileController extends ApiFileController
{

    public function responsive($height, $width)
    {

    }

    public function show($filename, $width = null)
    {

        return parent::show($filename, $width);
    }

    public function store(Requests\FileRequest $request)
    {

        $user = json_decode(parent::store($request)->getContent());

        return Redirect::route('profile.edit', $user->username)->with('content-message', trans('Profile picture has been updated Successfully'));

    }

}