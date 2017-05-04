<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Route;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Two\GoogleProvider;

class AdminPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Illuminate\Support\Facades\Request::ajax()) {

            $sortingPaginationData = $this->getDatatablePaging();

            $perPage = $sortingPaginationData['per_page'];

            $orderBy = !empty($sortingPaginationData['order']) ? $sortingPaginationData['order'] : 'id';
            $orderDir = !empty($sortingPaginationData['order_type']) ? $sortingPaginationData['order_type'] : 'desc';

            $searchParams = [
                isset($sortingPaginationData['search']) ? $sortingPaginationData['search'] : false,
            ];

            if (array_filter($searchParams)) {
                $searchQuery = implode(' ', $searchParams);
            } else {
                $searchQuery = false;
            }

            if ($searchQuery) {
                $permissions = Permission::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $permissions = Permission::orderBy($orderBy, $orderDir)->paginate($perPage);
            }

            return $this->datatables($permissions);
        }

        return view('admin.permission.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $routesModel = \App\Route::where('except', '<>', 1)->get();

        $routes = [];

        foreach ($routesModel as $route) {
            $routes[$route->id] = '[' . $route->method . '] ' . $route->path;
        }

        return view('admin.permission.create')->with(['permission' => new Permission(), 'routes' => $routes, 'selected' => []]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatorRules = [
            'display_name' => 'required|max:255|unique:permissions',
            'name' => 'required|max:255|unique:permissions',
            'description' => 'max:600',
        ];

        $inputs = $request->only('display_name', 'description', 'routes');

        $inputs['name'] = str_slug($inputs['display_name']);

        $validator = Validator::make($inputs, $validatorRules);

        if ($validator->fails()) {
            return redirect('admin/permission/create')
                ->withErrors($validator)
                ->withInput();
        }

        $permission = new Permission();

        $permission->name = $inputs['name'];
        $permission->display_name = $inputs['display_name'];
        $permission->description = $inputs['description'];

        $permission->save();

        $permission->permissionRoutes()->sync($inputs['routes']);

        return redirect('admin/permission/' . $permission->name)->with('content-message', 'A new Permission has been created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $permission = Permission::whereName($name)->firstOrFail();

        return view('admin.permission.show')->with('permission', $permission);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function edit($name)
    {
        $routesModel = \App\Route::where('except', '<>', 1)->get();

        $routes = [];

        foreach ($routesModel as $route) {
            $routes[$route->id] = '[' . $route->method . '] ' . $route->path;
        }

        $permission = Permission::whereName($name)->firstOrFail();

        $selected = $permission->permissionRoutes ? $permission->permissionRoutes->pluck('id')->toArray() : [];

        return view('admin.permission.edit')->with(['permission' => $permission, 'routes' => $routes, 'selected' => $selected]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $name
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $name)
    {
        $permission = Permission::whereName($name)->firstOrFail();

        $validatorRules = [
            'display_name' => 'required|max:255|unique:permissions,display_name,' . $permission->display_name . ',display_name',
            'description' => 'max:600'
        ];

        $inputs = $request->only('display_name', 'description', 'routes');

        $validator = Validator::make($inputs, $validatorRules);

        if ($validator->fails()) {
            return redirect('admin/permission/' . $permission->name . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $permission->display_name = $inputs['display_name'];
        $permission->description = $inputs['description'];

        $permission->save();
        $permission->permissionRoutes()->sync($inputs['routes']);

        return redirect('admin/permission/' . $permission->name . '/edit')->with('content-message', 'The Permission has been updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $permission = Permission::whereName($name)->firstOrFail();

        if ($permission->delete()) {
            return redirect('admin/permission/')->with('content-message', 'The Permission has been deleted successfully');
        }

        return redirect('admin/permission/')->with('error-message', 'Something went wrong please try again later!');
    }

    public function flushRoutes()
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

                Route::firstOrCreate($data);
            }
        }

        //Laravel Routes
        foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {

            $routes[] = ltrim($route->getPath(), '/');

            $data = [
                'path' => ltrim($route->getPath(), '/'),
                'method' => $route->methods()[0],
            ];

            Route::firstOrCreate($data);
        }

        if ($routes) {
            Route::whereNotIn('path', $routes)->delete();
        }

        return redirect('admin/role')->with('content-message', 'Routes flushed successfully');
    }
}
