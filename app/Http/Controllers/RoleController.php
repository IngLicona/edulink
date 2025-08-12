<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
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
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $rol = new Role();
        $rol->name = $request->name;
        $rol->guard_name = 'web';
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

    public function permisos($id)
    {
        $rol = Role::findOrFail($id);
        $permisos = Permission::all();
        
        // Organizar permisos por categorías
        $permisosPorCategoria = $this->organizarPermisosPorCategoria($permisos);
        
        return view('admin.roles.permisos', compact('rol', 'permisosPorCategoria'));
    }

    public function asignarPermisos(Request $request, $id)
    {
        $rol = Role::findOrFail($id);
        
        // Obtener los permisos seleccionados
        $permisosSeleccionados = $request->input('permisos', []);
        
        // Sincronizar permisos (elimina los que no están y agrega los nuevos)
        $rol->syncPermissions($permisosSeleccionados);
        
        return redirect()->route('admin.roles.index')->with('success', 'Permisos asignados correctamente.');
    }

    private function organizarPermisosPorCategoria($permisos)
    {
        $categorias = [
            'Configuración del sistema' => [],
            'Gestiones' => [],
            'Periodos' => [],
            'Niveles' => [],
            'Grados' => [],
            'Paralelos' => [],
            'Turnos' => [],
            'Materias' => [],
            'Roles' => [],
            'Personal docente y administrativo' => [],
            'Formaciones del personal' => [],
            'Estudiantes' => [],
            'Padres de familia' => [],
            'Asignaciones' => [],
            'Matriculaciones' => [],
            'Pagos' => []
        ];

        foreach ($permisos as $permiso) {
            $nombre = $permiso->name;
            
            if (str_contains($nombre, 'configuracion')) {
                $categorias['Configuración del sistema'][] = $permiso;
            } elseif (str_contains($nombre, 'gestiones')) {
                $categorias['Gestiones'][] = $permiso;
            } elseif (str_contains($nombre, 'periodos')) {
                $categorias['Periodos'][] = $permiso;
            } elseif (str_contains($nombre, 'niveles')) {
                $categorias['Niveles'][] = $permiso;
            } elseif (str_contains($nombre, 'grados')) {
                $categorias['Grados'][] = $permiso;
            } elseif (str_contains($nombre, 'paralelos')) {
                $categorias['Paralelos'][] = $permiso;
            } elseif (str_contains($nombre, 'turnos')) {
                $categorias['Turnos'][] = $permiso;
            } elseif (str_contains($nombre, 'materias')) {
                $categorias['Materias'][] = $permiso;
            } elseif (str_contains($nombre, 'roles')) {
                $categorias['Roles'][] = $permiso;
            } elseif (str_contains($nombre, 'personal')) {
                $categorias['Personal docente y administrativo'][] = $permiso;
            } elseif (str_contains($nombre, 'formaciones')) {
                $categorias['Formaciones del personal'][] = $permiso;
            } elseif (str_contains($nombre, 'estudiantes')) {
                $categorias['Estudiantes'][] = $permiso;
            } elseif (str_contains($nombre, 'ppffs')) {
                $categorias['Padres de familia'][] = $permiso;
            } elseif (str_contains($nombre, 'asignaciones')) {
                $categorias['Asignaciones'][] = $permiso;
            } elseif (str_contains($nombre, 'matriculaciones')) {
                $categorias['Matriculaciones'][] = $permiso;
            } elseif (str_contains($nombre, 'pagos')) {
                $categorias['Pagos'][] = $permiso;
            }
        }

        return $categorias;
    }
}