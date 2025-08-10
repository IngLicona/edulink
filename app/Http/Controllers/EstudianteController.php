<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Ppff;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with(['usuario', 'ppff'])->get();
        $ppffs = Ppff::all(); // Para el select de padres existentes
        return view('admin.estudiantes.nuevos.index', compact('estudiantes', 'ppffs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Definir reglas base
        $rules = [
            // Datos del estudiante
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:estudiantes,ci',
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:masculino,femenino',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:500',
            'estado' => 'required|in:activo,inactivo',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ppff_option' => 'required|in:existing,new',
        ];

        // Agregar reglas condicionalmente según la opción seleccionada
        if ($request->ppff_option === 'existing') {
            $rules['ppffs_id'] = 'required|exists:ppffs,id';
        } elseif ($request->ppff_option === 'new') {
            $rules = array_merge($rules, [
                'ppff_nombre' => 'required|string|max:255',
                'ppff_paterno' => 'required|string|max:255',
                'ppff_materno' => 'nullable|string|max:255',
                'ppff_ci' => 'required|string|max:20|unique:ppffs,ci',
                'ppff_fecha_nacimiento' => 'nullable|date|before:today',
                'ppff_telefono' => 'required|string|max:20',
                'ppff_parentesco' => 'required|in:padre,madre,tutor,abuelo',
                'ppff_ocupacion' => 'nullable|string|max:255',
                'ppff_direccion' => 'nullable|string|max:500',
            ]);
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error en la validación de datos');
        }

        try {
            DB::beginTransaction();

            // 1. Manejar foto del estudiante
            // 1. Manejar foto del estudiante
$fotoPath = null;
if ($request->hasFile('foto')) {
    $foto = $request->file('foto');
    $extension = $foto->getClientOriginalExtension();
    $nombreFoto = 'estudiante_' . time() . '_' . uniqid() . '.' . $extension;
    
    // Crear directorio si no existe
    $uploadPath = public_path('uploads/estudiantes/fotos');
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    // Mover archivo a public/uploads/estudiantes/fotos
    $foto->move($uploadPath, $nombreFoto);
    $fotoPath = $nombreFoto; // Solo guardamos el nombre del archivo
}

            // 2. Crear usuario para el estudiante
            $emailEstudiante = strtolower($request->ci . '_' . time() . '@estudiante.escuela.com');
            $usuario = User::create([
                'name' => trim($request->nombre . ' ' . $request->paterno . ' ' . ($request->materno ?? '')),
                'email' => $emailEstudiante,
                'password' => Hash::make($request->ci)
            ]);
            
            // Asignar rol de estudiante
            $usuario->assignRole('ESTUDIANTE');

            // 3. Manejar PPFF según la opción seleccionada
            $ppff_id = null;
            
            if ($request->ppff_option === 'existing') {
                // Usar PPFF existente
                $ppff_id = $request->ppffs_id;
            } else {
                // Crear nuevo PPFF
                $nuevoPpff = Ppff::create([
                    'nombre' => $request->ppff_nombre,
                    'paterno' => $request->ppff_paterno,
                    'materno' => $request->ppff_materno,
                    'ci' => $request->ppff_ci,
                    'fecha_nacimiento' => $request->ppff_fecha_nacimiento,
                    'telefono' => $request->ppff_telefono,
                    'parentesco' => $request->ppff_parentesco,
                    'ocupacion' => $request->ppff_ocupacion,
                    'direccion' => $request->ppff_direccion,
                ]);
                $ppff_id = $nuevoPpff->id;
            }

            // 4. Crear estudiante
            $estudiante = Estudiante::create([
                'usuario_id' => $usuario->id,
                'ppffs_id' => $ppff_id,
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'ci' => $request->ci,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'genero' => $request->genero,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'foto' => $fotoPath,
                'estado' => $request->estado ?? 'activo'
            ]);

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('success', 'Estudiante registrado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Eliminar foto si se subió pero falló la transacción
            if ($fotoPath) {
                Storage::disk('public')->delete($fotoPath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $estudiante = Estudiante::with(['usuario', 'ppff'])->findOrFail($id);
        return view('admin.estudiantes.nuevos.show', compact('estudiante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estudiante = Estudiante::with(['usuario', 'ppff'])->findOrFail($id);
        $ppffs = Ppff::all();
        return view('admin.estudiantes.nuevos.edit', compact('estudiante', 'ppffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:estudiantes,ci,' . $id,
            'fecha_nacimiento' => 'required|date|before:today',
            'genero' => 'required|in:masculino,femenino',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'required|string|max:500',
            'estado' => 'required|in:activo,inactivo',
            'ppff_id' => 'required|exists:ppffs,id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error en la validación de datos');
        }

        try {
            DB::beginTransaction();

           // Manejar foto del estudiante
$fotoPath = $estudiante->foto; // Mantener foto actual por defecto
if ($request->hasFile('foto')) {
    // Eliminar foto anterior si existe
    if ($estudiante->foto) {
        $rutaFotoAnterior = public_path('uploads/estudiantes/fotos/' . $estudiante->foto);
        if (file_exists($rutaFotoAnterior)) {
            unlink($rutaFotoAnterior);
        }
    }
    
    $foto = $request->file('foto');
    $extension = $foto->getClientOriginalExtension();
    $nombreFoto = 'estudiante_' . time() . '_' . uniqid() . '.' . $extension;
    
    // Crear directorio si no existe
    $uploadPath = public_path('uploads/estudiantes/fotos');
    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0755, true);
    }
    
    // Mover archivo a public/uploads/estudiantes/fotos
    $foto->move($uploadPath, $nombreFoto);
    $fotoPath = $nombreFoto; // Solo guardamos el nombre del archivo
}

            // Actualizar usuario
            if ($estudiante->usuario) {
                $estudiante->usuario->update([
                    'name' => trim($request->nombre . ' ' . $request->paterno . ' ' . ($request->materno ?? '')),
                ]);
            }

            // Actualizar estudiante
            $estudiante->update([
                'ppffs_id' => $request->ppff_id,
                'nombre' => $request->nombre,
                'paterno' => $request->paterno,
                'materno' => $request->materno,
                'ci' => $request->ci,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'genero' => $request->genero,
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
                'foto' => $fotoPath,
                'estado' => $request->estado,
            ]);

            DB::commit();

            return redirect()->route('admin.estudiantes.index')
                ->with('success', 'Estudiante actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar estudiante: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $estudiante = Estudiante::findOrFail($id);
            
            // Eliminar foto si existe
if ($estudiante->foto) {
    $rutaFoto = public_path('uploads/estudiantes/fotos/' . $estudiante->foto);
    if (file_exists($rutaFoto)) {
        unlink($rutaFoto);
    }
}
            
            // Eliminar usuario asociado
            if ($estudiante->usuario) {
                $estudiante->usuario->delete();
            }
            
            // Eliminar estudiante
            $estudiante->delete();
            
            DB::commit();
            
            return redirect()->route('admin.estudiantes.index')
                ->with('success', 'Estudiante eliminado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar estudiante: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint para buscar PPFF
     */
    public function searchPpff(Request $request)
    {
        $search = $request->get('search', '');
        
        $ppffs = Ppff::where(function($query) use ($search) {
            $query->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('paterno', 'LIKE', "%{$search}%")
                  ->orWhere('materno', 'LIKE', "%{$search}%")
                  ->orWhere('ci', 'LIKE', "%{$search}%")
                  ->orWhere('telefono', 'LIKE', "%{$search}%");
        })
        ->with(['estudiantes' => function($query) {
            $query->select('id', 'ppffs_id', 'nombre', 'paterno', 'materno');
        }])
        ->limit(10)
        ->get();

        return response()->json($ppffs);
    }

    /**
     * Obtener detalles de un PPFF específico
     */
    public function getPpffDetails($id)
    {
        $ppff = Ppff::with(['estudiantes' => function($query) {
            $query->select('id', 'ppffs_id', 'nombre', 'paterno', 'materno');
        }])->findOrFail($id);

        return response()->json($ppff);
    }
}