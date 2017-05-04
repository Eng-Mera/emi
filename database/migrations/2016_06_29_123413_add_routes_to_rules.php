<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoutesToRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Dingo APIs Routes
        $api = app('Dingo\Api\Routing\Router');

        $routes = [];

        foreach ($api->getRoutes() as $collection) {

            foreach ($collection->getRoutes() as $route) {

                $routes[] = ltrim($route->getPath(), '/');

                $data = [
                    'path' => ltrim($route->getPath(), '/'),
                    'method' => $route->methods()[0],
                ];

                \App\Route::firstOrCreate($data);
            }
        }

        //Laravel Routes
        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {

            $routes[] = ltrim($route->getPath(), '/');

            $data = [
                'path' => ltrim($route->getPath(), '/'),
                'method' => $route->methods()[0],
            ];

            \App\Route::firstOrCreate($data);
        }

        if ($routes) {
            \App\Route::whereNotIn('path', $routes)->delete();
        }

        $role = \App\Role::whereName(\App\Role::SUPER_ADMIN)->first();

        $routes = \App\Route::all()->pluck('id')->toArray();

        $role->roleRoutes()->sync($routes);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
