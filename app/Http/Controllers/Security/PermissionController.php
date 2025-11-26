<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
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
    public function create(Request $request)
    {
        $data = $request->all();
        $view = view('admin.role-permission.form-permission')->render();

        return response()->json(['data' => $view, 'status' => true]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|unique:permissions,name', // Adjust if 'title' is a separate column
        ]);

        Permission::create([
            'name' => $validated['title'], // Use 'title' as 'name' if no separate title column
            'guard_name' => 'web',
        ]);

        return redirect()->route('role.permission.list')->with('success', 'Permission created successfully.');
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
        // Retrieve the permission record
        $permission = \Spatie\Permission\Models\Permission::findOrFail($id);

        // Render the edit form located in resources/views/admin.role-permission/form-edit-permission.blade.php
        $view = view('admin.role-permission.form-edit-permission', compact('permission'))->render();

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
        // Retrieve the permission record
        $permission = \Spatie\Permission\Models\Permission::findOrFail($id);

        // Validate the input; ignore the current permission when checking for uniqueness
        $validated = $request->validate([
            'title' => "required|string|unique:permissions,name,{$id}",
        ]);

        // Update the permission record
        $permission->update([
            'name' => $validated['title'],
        ]);

        // Redirect back to the list with a success message
        return redirect()->route('role.permission.list')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
