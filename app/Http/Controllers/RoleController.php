<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view("admin.roles.index", compact("roles"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*$datos = $request->all();
        return response()->json($datos);*/
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $rol=new Role();
        $rol->name=$request->name;
        $rol->guard_name='web';
        $rol->save();

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->name = $request->name;
        $role->save();

        return redirect()->route('admin.roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Rol eliminado correctamente.');
    }
}
