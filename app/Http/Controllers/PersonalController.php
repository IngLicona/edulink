<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PersonalController extends Controller
{
    public function index($tipo = null)
    {
        if ($tipo && in_array($tipo, ['docente', 'administrativo'])) {
            $personals = Personal::with('usuario')->where('tipo', $tipo)->get();
        } else {
            $personals = Personal::with('usuario')->get();
        }
        
        $roles = Role::all();
        return view('admin.personals.index', compact('personals', 'roles', 'tipo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'rol' => 'required|exists:roles,name',
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:200',
            'ci' => 'required|string|unique:personals,ci',
            'fecha_nacimiento' => 'required|date',
            'telefono' => 'required|string|max:20',
            'profesion' => 'required|string|max:100',
            'direccion' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tipo' => 'required|in:docente,administrativo',
        ]);

        // Separar apellidos en paterno y materno
        $apellidos = explode(' ', trim($request->apellidos), 2);
        $paterno = $apellidos[0] ?? '';
        $materno = $apellidos[1] ?? '';

        // Crear usuario automáticamente
        $usuario = new User();
        $usuario->name = $request->apellidos . ' ' . $request->nombre; // Apellidos + Nombres
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->ci); // CI como contraseña
        $usuario->save();

        // Asignar rol al usuario
        $usuario->assignRole($request->rol);

        // Crear personal
        $personal = new Personal();
        $personal->usuario_id = $usuario->id;
        $personal->tipo = $request->tipo;
        $personal->nombre = $request->nombre;
        $personal->paterno = $paterno;
        $personal->materno = $materno;
        $personal->ci = $request->ci;
        $personal->fecha_nacimiento = $request->fecha_nacimiento;
        $personal->direccion = $request->direccion;
        $personal->telefono = $request->telefono;
        $personal->profesion = $request->profesion;

        // Manejar foto
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto');
            $nombreArchivo = 'personal_' . time() . '_' . Str::random(10) . '.' . $fotoPath->getClientOriginalExtension();
            
            // Crear directorio si no existe
            $rutaDestino = public_path('uploads/personal/fotos');
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            
            // Mover archivo
            $fotoPath->move($rutaDestino, $nombreArchivo);
            $personal->foto = $nombreArchivo;
        }

        $personal->save();

        return redirect()->back()->with('success', 'Personal registrado correctamente');
    }

    public function edit($id)
    {
        $personal = Personal::with('usuario')->findOrFail($id);
        $roles = Role::all();
        return view('admin.personals.edit', compact('personal', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $user = $personal->usuario;

        $request->validate([
            'rol' => 'required|exists:roles,name',
            'nombre' => 'required|string|max:100',
            'apellidos' => 'required|string|max:200',
            'ci' => 'required|string|unique:personals,ci,' . $personal->id,
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'profesion' => 'required|string|max:100',
            'foto' => 'nullable|image|max:2048',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'tipo' => 'required|in:docente,administrativo',
        ]);

        // Separar apellidos en paterno y materno
        $apellidos = explode(' ', trim($request->apellidos), 2);
        $paterno = $apellidos[0] ?? '';
        $materno = $apellidos[1] ?? '';

        // Actualizar usuario
        $user->name = $request->apellidos . ' ' . $request->nombre;
        $user->email = $request->email;
        $user->save();

        // Actualizar rol
        $user->syncRoles([$request->rol]);

        // Actualizar personal
        $personal->tipo = $request->tipo;
        $personal->nombre = $request->nombre;
        $personal->paterno = $paterno;
        $personal->materno = $materno;
        $personal->ci = $request->ci;
        $personal->fecha_nacimiento = $request->fecha_nacimiento;
        $personal->direccion = $request->direccion;
        $personal->telefono = $request->telefono;
        $personal->profesion = $request->profesion;

        // Manejar foto
        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($personal->foto && file_exists(public_path('uploads/personal/fotos/' . $personal->foto))) {
                unlink(public_path('uploads/personal/fotos/' . $personal->foto));
            }
            
            $fotoPath = $request->file('foto');
            $nombreArchivo = 'personal_' . time() . '_' . Str::random(10) . '.' . $fotoPath->getClientOriginalExtension();
            
            // Crear directorio si no existe
            $rutaDestino = public_path('uploads/personal/fotos');
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            
            // Mover archivo
            $fotoPath->move($rutaDestino, $nombreArchivo);
            $personal->foto = $nombreArchivo;
        }

        $personal->save();

        return redirect()->back()->with('success', 'Personal actualizado correctamente');
    }

    public function destroy($id)
    {
        $personal = Personal::findOrFail($id);
        
        // Eliminar foto si existe
        if ($personal->foto && file_exists(public_path('uploads/personal/fotos/' . $personal->foto))) {
            unlink(public_path('uploads/personal/fotos/' . $personal->foto));
        }

        // Eliminar usuario (esto también eliminará el personal por cascada)
        $personal->usuario->delete();

        return redirect()->back()->with('success', 'Personal eliminado correctamente');
    }
}