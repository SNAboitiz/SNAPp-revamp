<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermission extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::get();
        $permissions = Permission::get();

        return view('role-permissions.role-permissions', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $permissionsByRole = [];

        // Gather selected permissions for each role
        foreach ($request->input('permission', []) as $roleId => $permissionIds) {
            foreach ($permissionIds as $permissionId) {
                // Get the Permission instance by ID
                $permission = Permission::find($permissionId); // Use the actual Permission model here
                if ($permission) {
                    $permissionsByRole[$roleId][] = $permission; // Store the Permission model
                }
            }
        }

        // Update permissions for each role
        foreach ($permissionsByRole as $roleId => $permissions) {
            $role = Role::find($roleId);
            $role->syncPermissions($permissions); // Pass the Permission models instead of IDs
        }

        // Clear permissions for roles with no checkboxes
        $allRoles = Role::all();
        foreach ($allRoles as $role) {
            if (! isset($permissionsByRole[$role->id])) {
                $role->syncPermissions([]); // Clear permissions for this role
            }
        }

        // Return to the view with success message and updated roles & permissions
        $roles = Role::all();
        $permissions = Permission::all();

        return view('role-permissions.role-permissions', compact('roles', 'permissions'))
            ->with('success', 'Permissions updated successfully.');
    }

    public function destroyRole($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a specific permission.
     */
    public function destroyPermission($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return response()->json(['success' => true]);
    }
}
