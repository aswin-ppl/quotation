<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $this->authorize('view-roles-&-permission');
        
        $roles = Role::with('permissions')->get();
        return view('user-and-permissions.roles.index', compact('roles'));
    }

    public function create()
    {
        $this->authorize('create-roles-&-permission');

        $permissions = Permission::all()->groupBy(function ($permission) {
            // Extract module name (everything after the dash)
            return ucfirst(explode('-', $permission->name)[1] ?? 'Misc');
        });

        return view('user-and-permissions.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->authorize('create-roles-&-permission');

        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->name]);

        // Convert permission IDs → names before syncing
        $permissions = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    public function edit(Role $role)
    {
        $this->authorize('edit-roles-&-permission');

        $permissions = Permission::all()->groupBy(function ($permission) {
            // Extract module name (everything after the dash)
            return ucfirst(explode('-', $permission->name)[1] ?? 'Misc');
        });

        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('user-and-permissions.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $this->authorize('edit-roles-&-permission');

        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'array|required'
        ]);

        $role->update(['name' => $request->name]);

        // Convert permission IDs to names
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();

        $role->syncPermissions($permissionNames);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete-roles-&-permission');

        // Optional: block delete if role is assigned to users
        if (method_exists($role, 'users') && $role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete a role that is assigned to users.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}
