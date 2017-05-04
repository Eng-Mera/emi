<?php
/**
 * Created by PhpStorm.
 * User: m
 * Date: 4/25/16
 * Time: 11:09 AM
 */

namespace App\Http\Helpers;


use App\File;
use App\User;
use Illuminate\Support\Facades\Config;

trait FileTrait
{
    /**
     * Handle and  save images.
     *
     * @param $request
     * @param $restaurant
     * @return bool
     */
    protected function RestaurantStoreImages($request, $restaurant)
    {
        if (!empty($request->get('logo')) || !empty($request->file('logo'))) {

            //Update Logo
            $logo = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $request->get('logo'),
                    'internal_user_id' => $restaurant->owner_id ? $restaurant->owner_id : User::getCurrentUser()->id
                ]);

            if ($logo) {
                $restaurant->logo()->delete();
                $logo->category = File::getCategorySlug('restaurant_logo');
                $restaurant->logo()->save($logo);
            }
        }

        if (!empty($request->get('featured_image')) || !empty($request->file('featured_image'))) {

            //Create Featured Image
            $featured = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $request->get('featured_image'),
                    'internal_user_id' => $restaurant->owner_id
                ]);

            if ($featured) {
                $restaurant->featured()->delete();
                $featured->category = File::getCategorySlug('restaurant_featured');
                $restaurant->featured()->save($featured);
            }
        }

        return true;
    }

    /**
     * Store Movies poster.
     *
     * @param $request
     * @param $restaurant
     * @return bool
     */
    protected function storeMovieImages($file, $movie, $category, $callable)
    {
        if (!empty($file)) {

            $uploadedImage = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $file,
                    'internal_user_id' => $movie->user_id
                ]);

            if ($uploadedImage) {

                //Remove Old One
                $movie->$callable()->delete();

                $uploadedImage->category = $category;

                $movie->$callable()->save($uploadedImage);
            }
        }

        return true;
    }

    /**
     * Store Admin Reviews images
     *
     * @param $request
     * @param $adminReview
     */
    protected function storeAdminReviewsImages($request, $adminReview)
    {
        $images = $request->get('images');

        if (!empty($images) && is_array($images)) {

            foreach ($images as $image) {

                $file = $this->api
                    ->version(env('API_VERSION', 'v1'))
                    ->header('webAuthKey', Config::get('api.webAuthKey'))
                    ->post(env('API_VERSION', 'v1') . '/file', [
                        'uploaded_file' => $image,
                        'internal_user_id' => $adminReview->user_id
                    ]);

                if ($file) {
                    $file->category = File::getCategorySlug('admin_reviews');
                    $adminReview->images()->save($file);
                }
            }

        }
    }

    /**
     * Delete Admin Reviews Images
     * @param $request
     * @param $adminReview
     */
    protected function deleteAdminReviewsImages($request, $adminReview)
    {
        $images = $request->get('removed_images_ids', false);

        $imagesIds = explode(',', $images);

        if (!empty($imagesIds)) {

            foreach ($imagesIds as $imagesId) {

                $image = $adminReview->images()->whereId($imagesId)->first();

                if ($image) {

                    $file = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . '/' . $image->filename;

                    if (file_exists($file)) {
                        unlink($file);
                    }

                    $image->delete();
                }


            }

        }
    }

    /**
     * Save Profile Image
     *
     * @param $request
     * @param User $user
     */
    protected function saveProfileImage($request, User $user)
    {
        $profilePicture = $request->file('uploaded_file');

        if ($profilePicture) {

            $file = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->attach($request->allFiles())
                ->post(env('API_VERSION', 'v1') . '/file', ['internal_user_id' => $user->id]);

            if ($file) {
                $user->files()->delete();
                $file->category = File::getCategorySlug('user_profile_picture');
                $user->files()->save($file);
            }

        } else if ($request->has('uploaded_file')) {


            $file = $this->api->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $request->get('uploaded_file'),
                    'internal_user_id' => $user->id
                ]);

            if ($file) {
                $user->files()->delete();
                $file->category = File::getCategorySlug('user_profile_picture');
                $user->files()->save($file);
            }

        } else if ($request->has('driver')) {

            $socialUser = new \stdClass();
            $socialUser->id = $request->get('pid');
            $socialUser->token = $request->get('token');

            $this->saveSocailProvider($socialUser, $user, $request->get('driver'));

            if ($request->get('uploaded_file') && !$profilePicture) {

                $imgType = get_headers($request->get('uploaded_file'), 1)["Content-Type"];

                if (is_array($imgType)) {
                    $imgType = $imgType[0];
                }

                if ($imgType) {

                    $file = $this->api->version(env('API_VERSION', 'v1'))->header('webAuthKey', Config::get('api.webAuthKey'))->post(env('API_VERSION', 'v1') . '/file', [
                        'uploaded_file' => 'data:' . $imgType . ';base64,' . base64_encode(@file_get_contents($request->get('uploaded_file')))
                    ]);

                    if ($file) {
                        $user->files()->delete();
                        $file->category = File::getCategorySlug('user_profile_picture');
                        $user->files()->save($file);
                    }
                }
            }

        }

    }


    protected function updateProfileImage($request, User $user)
    {
        $profilePictureAasFile = $request->file('uploaded_file');
        $profilePictureAaData = $request->get('uploaded_file');

        if ($profilePictureAasFile) {

            $file = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->attach($request->allFiles())
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'internal_user_id' => $user->id
                ]);

            if ($file) {
                $user->files()->delete();
                $file->category = File::getCategorySlug('user_profile_picture');
                $user->files()->save($file);
            }

        } else if ($profilePictureAaData) {

            $file = $this->api
                ->version(env('API_VERSION', 'v1'))
                ->header('webAuthKey', Config::get('api.webAuthKey'))
                ->post(env('API_VERSION', 'v1') . '/file', [
                    'uploaded_file' => $profilePictureAaData,
                    'internal_user_id' => $user->id
                ]);

            if ($file) {
                $user->files()->delete();
                $file->category = File::getCategorySlug('user_profile_picture');
                $user->files()->save($file);
            }

        }
    }
}
