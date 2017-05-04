<?php

/**
 * User resource representation.
 *
 * @Resource("Users", uri="/users")
 */

namespace App\Http\Controllers\APIs;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Controllers\FileController;
use App\Role;
use App\User;
use Dingo\Api\Http\InternalRequest;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class ApiFileController extends Controller
{
    use Helpers;

    public function responsive($height, $width)
    {

    }

    public function show($filename, $width = null)
    {

        if (!empty($width)) {
            list($width, $filename) = array($filename, $width);
        }

        $entry = File::where('filename', '=', $filename)->firstOrFail();

        try {
            $file = Storage::disk('local')->get($entry->filename);
        } catch (\Exception $e) {
            return $this->response->errorNotFound();
        }
        $img = Image::make($file);

        if ($width) {
            $img->resize(null, $width, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        return $img->response();

    }

    public function store(Requests\FileRequest $request)
    {

        $file = $request->file('uploaded_file', false);

        if (!$file && $file = $request->has('uploaded_file')) {
            $file = $this->base64ToFile($request->get('uploaded_file'));
        }

        if (!$file) {
            return $this->response->created();
        }

        $user = User::find($request->get('internal_user_id'));

        if (!$user) {
            return $this->response->errorUnauthorized();
        }

        $extension = $file->getClientOriginalExtension();

        $newFileName = md5(time()) . '_' . rand(111111, 999999) . '.' . $extension;

        Storage::disk('local')->put($newFileName, \Illuminate\Support\Facades\File::get($file));

        $mediaFile = new \App\File();
        $mediaFile->mime = $file->getClientMimeType();
        $mediaFile->original_filename = $file->getClientOriginalName();
        $mediaFile->filename = $newFileName;
        $mediaFile->user_id = $user->id;

        return $this->response->created('', $mediaFile);
    }

    public function base64ToFile($base64Content)
    {

        $img = substr($base64Content, strpos($base64Content, ",") + 1);
        $data = base64_decode($img);

        $image_name = md5(time()) . rand(111111, 9999999) . str_replace('image/', '.', finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE));

        $tmpPath = sys_get_temp_dir() . '/' . $image_name;

        @file_put_contents($tmpPath, $data);
        $result = new \Illuminate\Http\UploadedFile($tmpPath, $image_name);
        return $result;
    }

    public function destroy($id)
    {
        $file = File::find($id);

        $fileName = storage_path($file->filename);

        if (file_exists($fileName)) {
            unlink($fileName);
        }

        $file->delete();

        return $this->response->created('', [true]);
    }
}
