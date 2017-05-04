<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Permission;
use App\Role;
use App\Route;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Excel;


class AdminRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Illuminate\Support\Facades\Request::ajax()) {

            $sortingPaginationData = $this->getDatatablePaging(['id', 'name', 'display_name','description', 'created_at', 'updated_at']);

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
                $roles = Role::search($searchQuery)->orderBy($orderBy, $orderDir)->paginate($perPage);
            } else {
                $roles = Role::orderBy($orderBy, $orderDir)->paginate($perPage);
            }

            return $this->datatables($roles);
        }

        return view('admin.role.index');
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

        return view('admin.role.create')->with(['role' => new Role(), 'routes' => $routes, 'selected' => []]);
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
            'display_name' => 'required|max:255|unique:roles',
            'name' => 'required|max:255|unique:roles',
            'color' => 'required',
            'description' => 'max:600',
        ];

        $inputs = $request->only('display_name', 'description', 'color', 'routes');

        $inputs['name'] = str_slug($inputs['display_name']);

        $validator = Validator::make($inputs, $validatorRules);

        if ($validator->fails()) {
            return redirect('admin/role/create')
                ->withErrors($validator)
                ->withInput();
        }

        $role = new role();

        $role->name = $inputs['name'];
        $role->display_name = $inputs['display_name'];
        $role->color = $inputs['color'];
        $role->description = $inputs['description'];

        $role->save();

        if ($inputs['routes']) {
            $role->roleRoutes()->sync($inputs['routes']);
        }

        $newPermissions = Permission::find($request->get('permissions'));

        if ($newPermissions) {
            $role->attachPermissions($newPermissions);
        }

        return redirect('admin/role/' . $role->name)->with('content-message', 'A new role has been created successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function show($name)
    {
        $role = Role::with(['roleRoutes'])->whereName($name)->firstOrFail();

        $routes = $role->roleRoutes ? $role->roleRoutes->pluck('method', 'path')->toArray() : [];

        return view('admin.role.show')->with(['role' => $role, 'route' => $routes]);
    }

    /**
     * @return string
     */
    public function export($name)
    {
        $role = Role::with(['roleRoutes'])->whereName($name)->firstOrFail();
//        $routes = $role->roleRoutes ? $role->roleRoutes->pluck('path', 'id')->toArray() : [];
        $routes = $role->roleRoutes ? $role->roleRoutes : [];

        foreach ($routes as $route)
        {
            $data[] = ['role'=>$role->id , 'route_id' => $route->id , 'method' => $route->method  , 'path' => $route->path];
        }

        return Excel::create('roles_routes', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download('csv');



    }

    public function import(Request $request , $name)
    {
        if(Input::hasFile('import_file'))
        {

            $path = Input::file('import_file')->getRealPath();

            $data = Excel::load($path, function($reader) {
            })->get();

            if(!empty($data) && $data->count())
            {
                try
                {
                    $role = Role::whereName($name)->firstOrFail();
                    $inputs = [];
                    foreach ($data as $key => $value)
                    {
                      try
                      {
                          if (!empty($value->path))
                          {
                              $route = Route::where(['method' => $value->method , 'path' => $value->path])->firstOrFail();
                              $inputs['routes'][] = $route->id;
                          }
                          else
                          {
                              $route = Route::where(['method' => $value->method , 'path' => ""])->firstOrFail();
                              $inputs['routes'][] = $route->id;
                          }
                      }
                      catch (\Exception $e)
                      {
                      }
                    }

                    if ($inputs['routes']) {
                        $role->roleRoutes()->sync($inputs['routes']);
                    }
                  return redirect('admin/role/' . $role->name)->with('content-message', 'The routes have been uploaded successfully');
                }
                catch (\Exception $e)
                {
                }
            }
        }
        return back();
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

        $role = Role::whereName($name)->firstOrFail();

        $selected = $role->roleRoutes ? $role->roleRoutes->pluck('id')->toArray() : [];

        return view('admin.role.edit')->with(['role' => $role, 'routes' => $routes, 'selected' => $selected]);
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

        $role = Role::whereName($name)->firstOrFail();

        $validatorRules = [
            'display_name' => 'required|max:255|unique:roles,display_name,' . $role->display_name . ',display_name',
            'permissions' => 'array',
            'description' => 'max:600',
            'color' => 'required',
        ];

        $inputs = $request->only('display_name', 'description', 'permissions', 'color', 'routes');

        $validator = Validator::make($inputs, $validatorRules);

        if ($validator->fails()) {
            return redirect('admin/role/' . $role->name . '/edit')
                ->withErrors($validator)
                ->withInput();
        }

        $newPermissions = Permission::find($request->get('permissions'));

        if ($newPermissions) {

            if ($role->perms()) {
                $role->perms()->sync([]);
            }

            $role->attachPermissions($newPermissions);
        }

        $role->display_name = $inputs['display_name'];
        $role->color = $inputs['color'];
        $role->description = $inputs['description'];

        $role->save();

        if($inputs['routes']){
            $role->roleRoutes()->sync($inputs['routes']);
        }

        return redirect('admin/role/' . $role->name . '/edit')->with('content-message', 'The Role has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $name
     * @return \Illuminate\Http\Response
     */
    public function destroy($name)
    {
        $role = Role::whereName($name)->firstOrFail();

        if ($role->delete()) {

            $role->users()->sync([]); // Delete relationship data
            $role->perms()->sync([]); // Delete relationship data

            return redirect('admin/role/')->with('content-message', 'The role has been deleted successfully');
        }

        return redirect('admin/role/')->with('error-message', 'Something went wrong please try again later!');
    }
}
