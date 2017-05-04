<?php

/*
  |--------------------------------------------------------------------------
  | Routes File
  |--------------------------------------------------------------------------
  |
  | Here is where you will register all of the routes in an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/', ['middleware' => 'web', function () {
    return redirect('/login');
}]);

Route::get('/apple-app-site-association', ['middleware' => 'web', function () {

    $data = (object)[
        'applinks' => (object)[
            'apps' => [],
            'details' => [
                (object)[
                    "appID" => "Z8HFLT9ZAM.com.meunity.htr.ios",
                    "paths" => ["*"]
                ]
            ]
        ]
    ];

    return response()->json($data,200,[], JSON_PRETTY_PRINT);

}]);

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | This route group applies the "web" middleware group to every route
  | it contains. The "web" middleware group is defined in your HTTP
  | kernel and includes session state, CSRF protection, and more.
  |`
 */

Route::get('oauth/authorize', ['as' => 'oauth.authorize.get', 'middleware' => ['web', 'auth', 'check-authorization-params'], 'uses' => '\App\Http\Controllers\APIs\ApiAuthController@getAuthorization']);

Route::post('oauth/authorize', ['as' => 'oauth.authorize.post', 'middleware' => ['web', 'auth', 'check-authorization-params'], 'uses' => '\App\Http\Controllers\APIs\ApiAuthController@postAuthorization']);

Route::post('oauth/access_token', '\App\Http\Controllers\APIs\ApiAuthController@accessToken');

/**
 * Resource Controller Example
 * ------------------------------------------------------------------------------
 * Verb        |      Path                  |   Action      |    Route Name     |
 * -----------------------------------------------------------------------
 * GET         |     /photo                 |   index       |    photo.index    |
 * GET         |     /photo/                |   create      |    photo.create   |
 * POST        |     /photo                 |   store       |    photo.store    |
 * GET         |     /photo/{photo}         |   show        |    photo.show     |
 * GET         |     /photo/{photo}/edit    |   edit        |    photo.edit     |
 * PUT/PATCH   |     /photo/{photo}         |   update      |    photo.update   |
 * DELETE      |     /photo/{photo}         |   destroy     |    photo.destroy  |
 * ------------------------------------------------------------------------------
 */

$api = app('Dingo\Api\Routing\Router');


$api->version('v1', ['protected' => false], function ($api) {
    $api->group(['prefix' => 'v1'], function ($api) {
        $api->post('register', '\App\Http\Controllers\APIs\ApiAuthController@register');
        $api->post('password/reset', '\App\Http\Controllers\APIs\ApiAuthController@resetPassword');
        $api->post('password/complete-reset', '\App\Http\Controllers\APIs\ApiAuthController@completeResetPassword');
        $api->get('profile', '\App\Http\Controllers\APIs\ApiProfileController@show');
        $api->get('site-options', '\App\Http\Controllers\APIs\ApiGeneralController@index');
    });
});

$api->version('v1', ['middleware' => ['session', 'api.auth'], 'protected' => false], function ($api) {

    $api->group(['prefix' => 'v1',], function ($api) {

        $api->resource('user', '\App\Http\Controllers\APIs\ApiUsersController', ['only' => ['update', 'index', 'destroy', 'show']]);
        $api->get('user/{username}/favorite-restaurants', '\App\Http\Controllers\APIs\ApiUsersController@favoriteRestaurant');
        $api->get('user/{username}/restaurants-images', '\App\Http\Controllers\APIs\ApiUsersController@restaurantsImages');
        $api->get('user/users-by-role/{role_name}', '\App\Http\Controllers\APIs\ApiUsersController@usersByRole');
        $api->get('user/{username}/reviews', '\App\Http\Controllers\APIs\ApiUsersController@userReviews');
        $api->get('manager-restaurant', '\App\Http\Controllers\APIs\ApiUsersController@managerRestaurant');


        $api->resource('driver', '\App\Http\Controllers\APIs\ApiDriverController', [
            'only' => ['index', 'store', 'show', 'update', 'destroy'],
            'parameters' => ['singular']
        ]);


        $api->resource('profile', '\App\Http\Controllers\APIs\ApiProfileController', ['only' => ['update', 'show', 'edit', 'destroy']]);
        $api->get('profile/{username}/edit', ['as' => 'api.v1.profile.edit', 'uses' => '\App\Http\Controllers\APIs\ApiProfileController@edit']);

        $api->resource('file.comment', '\App\Http\Controllers\APIs\ApiPhotoCommentController', [
            'only' => ['index', 'store', 'show', 'update', 'destroy'],
            'parameters' => ['singular']
        ]);


        $api->get('user/lang/{id}', '\App\Http\Controllers\APIs\ApiUsersController@getLang');
        $api->get('user/setlang/{id}/{lang}', '\App\Http\Controllers\APIs\ApiUsersController@setLang');


        /** Payments */
        $api->post('reservation/{reservation}/dopay', '\App\Http\Controllers\APIs\ApiReservationController@doPay');

        /** Push Notifications */
        $api->resource('device', '\App\Http\Controllers\APIs\ApiDeviceController');

        /** User */
        $api->get('autocomplete/user', '\App\Http\Controllers\APIs\ApiUsersController@autocomplete');

    });

});

//Administration Routes

Route::group(['prefix' => 'admin', 'middleware' => ['web', 'role:' . \App\Role::getAdminRouteRoles()]], function () {

    Route::resource('user', '\App\Http\Controllers\Admin\AdminUserController');
    Route::resource('category', '\App\Http\Controllers\Admin\AdminCategoryController');
    Route::resource('dish-category', '\App\Http\Controllers\Admin\AdminDishCategoryController');
    Route::resource('city', '\App\Http\Controllers\Admin\AdminCityController');
    Route::resource('job-title', '\App\Http\Controllers\Admin\AdminJobTitleController');
    Route::resource('role', '\App\Http\Controllers\Admin\AdminRoleController');
    Route::get('role/export/{name}', '\App\Http\Controllers\Admin\AdminRoleController@export');
    Route::post('role/import/{name}', '\App\Http\Controllers\Admin\AdminRoleController@import');
    Route::resource('facility', '\App\Http\Controllers\Admin\AdminFacilityController');
    Route::resource('permission', '\App\Http\Controllers\Admin\AdminPermissionController');
    Route::resource('restaurant', '\App\Http\Controllers\Admin\AdminRestaurantController');
    Route::resource('restaurant.menu-item', '\App\Http\Controllers\Admin\AdminMenuItemController', ['parameters' => 'singular']);
    Route::resource('restaurant.gallery', '\App\Http\Controllers\Admin\AdminGalleryController', ['parameters' => 'singular']);
    Route::resource('restaurant.opening-days', '\App\Http\Controllers\Admin\AdminOpeningDayController', ['parameters' => 'singular']);
    Route::resource('restaurant.job-vacancy', '\App\Http\Controllers\Admin\AdminJobVacancyController', ['parameters' => 'singular']);
    Route::resource('restaurant.reservation-policy', '\App\Http\Controllers\Admin\AdminReservationPolicyController', ['parameters' => 'singular']);
    Route::get('restaurant/{restaurant_slug}/applied-users/{job_id}', '\App\Http\Controllers\Admin\AdminJobVacancyController@list');

    Route::resource('restaurant.rates', '\App\Http\Controllers\Admin\AdminRateReviewController', ['parameters' => 'singular']);

    /*
     * Reply on Reviews 
     * 
     */

    /* Create Routes */
    Route::get('reply-review/{restaurant}/{review_id}/create', '\App\Http\Controllers\Admin\AdminRateReviewController@createReply')->name('reply-review');
    Route::post('reply-review/store', '\App\Http\Controllers\Admin\AdminRateReviewController@replyStore')->name('reply-review.store');
    /* Update Routes */
    Route::get('reply-review/{review_id}/{reply_id}/edit', '\App\Http\Controllers\Admin\AdminRateReviewController@editReply')->name('reply-review');
    Route::patch('reply-review/update', '\App\Http\Controllers\Admin\AdminRateReviewController@replyUpdate')->name('reply-review.update');

    /* Show Route */
    Route::get('reply-review/{review_id}/{reply_id}', '\App\Http\Controllers\Admin\AdminRateReviewController@showReply')->name('reply-review.show');

    /* Delete Route */
    Route::delete('reply-review/{review_id}/{reply_id}', '\App\Http\Controllers\Admin\AdminRateReviewController@deleteReply')->name('reply-review.delete');


    Route::resource('restaurant.facility', '\App\Http\Controllers\Admin\AdminFacilityController', ['parameters' => 'singular']);

    Route::resource('restaurant.branch', '\App\Http\Controllers\Admin\AdminBranchController', ['parameters' => 'singular']);

    Route::get('flush-routes', '\App\Http\Controllers\Admin\AdminPermissionController@flushRoutes');

    Route::resource('report', '\App\Http\Controllers\Admin\AdminReportController');
    Route::resource('movie', '\App\Http\Controllers\Admin\AdminMovieController');
    Route::resource('admin-review', '\App\Http\Controllers\Admin\AdminAdminReviewController');

    Route::get('report/reported/{type}', '\App\Http\Controllers\Admin\AdminReportController@reportedIds');
    Route::get('reviews/star/{id}', '\App\Http\Controllers\Admin\AdminRateReviewController@userStar');
    Route::get('user/lang/{id}', '\App\Http\Controllers\Admin\AdminUserController@getLang');
    Route::get('user/setlang/{id}/{lang}', '\App\Http\Controllers\Admin\AdminUserController@setLang');
    Route::resource('claim', '\App\Http\Controllers\Admin\AdminClaimController', ['only' => ['index', 'store', 'show', 'edit', 'update']]);


    Route::get('claim/{id}/cancel', '\App\Http\Controllers\Admin\AdminClaimController@cancel');


    /**
     * Cart Module
     */
    Route::resource('reservation', '\App\Http\Controllers\Admin\AdminReservationController');
    Route::get('reservation/edit/{reservation}', '\App\Http\Controllers\Admin\AdminReservationController@edit');
    Route::patch('reservation/{reservation}/change', '\App\Http\Controllers\Admin\AdminReservationController@change')->name('reservation-change');
    Route::patch('reservation/{reservation}/accept', '\App\Http\Controllers\Admin\AdminReservationController@accept');
    Route::patch('reservation/{reservation}/reject', '\App\Http\Controllers\Admin\AdminReservationController@reject');
    Route::patch('reservation/{reservation}/arrived', '\App\Http\Controllers\Admin\AdminReservationController@arrived');

    Route::get('coupon/show/{coupon}', '\App\Http\Controllers\Admin\AdminCouponController@show')->name('show_coupon');
    Route::get('coupon/edit/{coupon}', '\App\Http\Controllers\Admin\AdminCouponController@edit')->name('edit_coupon');
    Route::patch('coupon/update/{coupon}', '\App\Http\Controllers\Admin\AdminCouponController@update')->name('update_coupon');
    Route::delete('coupon/delete/{coupon}', '\App\Http\Controllers\Admin\AdminCouponController@destroy')->name('delete_coupon');
    Route::get('coupon/create/{user}', '\App\Http\Controllers\Admin\AdminCouponController@create')->name('create_coupon');
    Route::post('coupon/store', '\App\Http\Controllers\Admin\AdminCouponController@store')->name('store_coupon');
    Route::get('coupon', '\App\Http\Controllers\Admin\AdminCouponController@index')->name('list_coupons');

    Route::get('autocomplete/user', '\App\Http\Controllers\Admin\AdminUserController@autocomplete');
    Route::get('reservation/request-review/{restaurant}/{user}', '\App\Http\Controllers\Admin\AdminReservationController@requestReview');

    /**
     * Uploading Excel
     */

    Route::get('importExport', '\App\Http\Controllers\Admin\AdminUploadingExcelController@importExport');
    Route::get('downloadExcel/{type}', '\App\Http\Controllers\Admin\AdminUploadingExcelController@downloadExcel');
    Route::post('importExcel', '\App\Http\Controllers\Admin\AdminUploadingExcelController@importExcel');

    Route::get('importMenu', '\App\Http\Controllers\Admin\AdminUploadMenuItemController@importExport');
    Route::post('importItems', '\App\Http\Controllers\Admin\AdminUploadMenuItemController@importExcel');
    Route::post('importBranches', '\App\Http\Controllers\Admin\AdminUploadingExcelController@importBranches');
});

Route::group(['middleware' => 'web'], function () {

    Route::get('auth/{driver}', 'Auth\AuthController@social');
    Route::get('auth/callback/{driver}', 'Auth\AuthController@socialCallback');

    Route::resource('profile', '\App\Http\Controllers\ProfileController', ['only' => ['show', 'edit', 'update']]);
    Route::resource('user', '\App\Http\Controllers\UsersController', ['only' => ['update']]);

    Route::resource('file', '\App\Http\Controllers\FileController', ['only' => ['index', 'store', 'show']]);
    Route::get('file/resize/{width}/{name}', '\App\Http\Controllers\FileController@show');

    // resumable.js routes
    Route::get('gallery-uploader', '\App\Http\Controllers\Admin\AdminGalleryController@resumableUpload');
    Route::post('gallery-uploader', '\App\Http\Controllers\Admin\AdminGalleryController@resumableUpload');
});
