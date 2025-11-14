<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // code here
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role-permissions.form-create-role');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|unique:roles,name', // Adjust if 'title' is a separate column
        ]);

        Role::create([
            'name' => $validated['title'], // Use 'title' as 'name' if no separate title column
            'guard_name' => 'web',
        ]);
        session()->flash('message', 'Post successfully updated.');

        return redirect()->route('role.permission.list')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // code here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        // Load the edit form from resources/views/admin.role-permission/form-edit-role.blade.php
        $view = view('admin.role-permission.form-edit-role', compact('role'))->render();

        return response()->json(['data' => $view, 'status' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Retrieve the role or fail if not found
        $role = Role::findOrFail($id);

        // Validate the input; ignore the current role when checking for unique names
        $validated = $request->validate([
            'title' => "required|string|unique:roles,name,{$id}",
            'status' => 'required|boolean',
        ]);

        // Update the role with the new title (and handle status if needed)
        $role->update([
            'name' => $validated['title'],
            // If you have a column for status, update it accordingly:
            // 'status' => $validated['status'],
        ]);

        // Redirect back to the admin.role-permission list with a success message
        return redirect()->route('role.permission.list')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $role = Role::findOrFail($id);
        // $role->delete();
        // return redirect()->route('role.permission.list')->with('success', 'Role deleted successfully.');
    }
}
