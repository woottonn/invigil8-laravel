<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $title = 'Roles';
        $roles = Role::orderBy('name')->get();
        return view('roles.index', compact('title', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $role = New Role();
        $permissions = Permission::all()->sortBy('name');
        return view('roles.create', compact('role', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $role = $this->validate($request, [
                'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            ]
        );

        $role = Role::create([
            'name' => $role['name'],
            'guard_name' => 'web'
        ]);

        // assign permissions
        if(!empty($request->permissions)){
            $permissions = $request->permissions;
            foreach($permissions as $permission){
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->sortBy('name');

        if(old('permissions')) { //if form data has old values
            $role_permissions = old('permissions'); // set them
        }else{ // otherwise
            $role_permissions = $role->getAllPermissions()->pluck('id')->toArray(); //get users roles
        }

        return view('roles.edit', compact('role', 'permissions', 'role_permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Role $role)
    {
        $role->update($this->validate($request, [
                'name' => ['required', 'string', 'max:255', 'unique:roles,name,'.$role->id],
            ]
        ));

        // assign permissions
        $role->syncPermissions($request->permissions);

        return redirect()->route('roles.index')->with('success', 'Role edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully');
    }
}
