<?php

namespace App\Http\Controllers;

use App\Models\Ppff;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PpffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ppffs = Ppff::with(['personal', 'estudiantes'])->get();
        $personals = Personal::where('tipo', 'padre_familia')->get();
        return view('admin.ppffs.index', compact('ppffs', 'personals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'personal_id' => 'nullable|exists:personals,id',
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:ppffs,ci',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'telefono' => 'required|string|max:20',
            'parentesco' => 'required|in:padre,madre,tutor,abuelo,abuela,tio,tia,hermano,hermana',
            'ocupacion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:500',
        ], [
            'ci.required' => 'La cédula de identidad es obligatoria',
            'ci.unique' => 'Esta cédula de identidad ya está registrada',
            'ci.max' => 'La cédula de identidad no puede tener más de 20 caracteres',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'parentesco.in' => 'Seleccione un parentesco válido',
            'parentesco.required' => 'El parentesco es obligatorio',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'paterno.required' => 'El apellido paterno es obligatorio',
            'paterno.max' => 'El apellido paterno no puede tener más de 255 caracteres',
            'materno.max' => 'El apellido materno no puede tener más de 255 caracteres',
            'ocupacion.max' => 'La ocupación no puede tener más de 255 caracteres',
            'direccion.max' => 'La dirección no puede tener más de 500 caracteres',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error en la validación de datos');
        }

        try {
            DB::beginTransaction();

            // Limpiar y formatear los datos antes de crear
            $data = $request->all();
            
            // Limpiar espacios en blanco
            $data['nombre'] = trim($data['nombre']);
            $data['paterno'] = trim($data['paterno']);
            $data['materno'] = $data['materno'] ? trim($data['materno']) : null;
            $data['ci'] = trim($data['ci']);
            $data['telefono'] = trim($data['telefono']);
            $data['ocupacion'] = $data['ocupacion'] ? trim($data['ocupacion']) : null;
            $data['direccion'] = $data['direccion'] ? trim($data['direccion']) : null;

            Ppff::create($data);

            DB::commit();

            return redirect()->route('admin.ppff.index')
                ->with('success', 'PPFF registrado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar PPFF: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Ppff $ppff)
    {
        $ppff->load('personal', 'estudiantes');
        return view('admin.ppffs.show', compact('ppff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppff $ppff)
    {
        $validator = Validator::make($request->all(), [
            'personal_id' => 'nullable|exists:personals,id',
            'nombre' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'ci' => 'required|string|max:20|unique:ppffs,ci,' . $ppff->id,
            'fecha_nacimiento' => 'nullable|date|before:today',
            'telefono' => 'required|string|max:20',
            'parentesco' => 'required|in:padre,madre,tutor,abuelo,abuela,tio,tia,hermano,hermana',
            'ocupacion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:500',
        ], [
            'ci.required' => 'La cédula de identidad es obligatoria',
            'ci.unique' => 'Esta cédula de identidad ya está registrada',
            'ci.max' => 'La cédula de identidad no puede tener más de 20 caracteres',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'parentesco.in' => 'Seleccione un parentesco válido',
            'parentesco.required' => 'El parentesco es obligatorio',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'paterno.required' => 'El apellido paterno es obligatorio',
            'paterno.max' => 'El apellido paterno no puede tener más de 255 caracteres',
            'materno.max' => 'El apellido materno no puede tener más de 255 caracteres',
            'ocupacion.max' => 'La ocupación no puede tener más de 255 caracteres',
            'direccion.max' => 'La dirección no puede tener más de 500 caracteres',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Error en la validación de datos');
        }

        try {
            DB::beginTransaction();

            // Limpiar y formatear los datos antes de actualizar
            $data = $request->all();
            
            // Limpiar espacios en blanco
            $data['nombre'] = trim($data['nombre']);
            $data['paterno'] = trim($data['paterno']);
            $data['materno'] = $data['materno'] ? trim($data['materno']) : null;
            $data['ci'] = trim($data['ci']);
            $data['telefono'] = trim($data['telefono']);
            $data['ocupacion'] = $data['ocupacion'] ? trim($data['ocupacion']) : null;
            $data['direccion'] = $data['direccion'] ? trim($data['direccion']) : null;

            $ppff->update($data);

            DB::commit();

            return redirect()->route('admin.ppff.index')
                ->with('success', 'PPFF actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar PPFF: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ppff $ppff)
    {
        try {
            DB::beginTransaction();

            // Verificar si tiene estudiantes asociados
            if ($ppff->estudiantes()->count() > 0) {
                return redirect()->route('admin.ppff.index')
                    ->with('error', 'No se puede eliminar el PPFF porque tiene estudiantes asociados');
            }

            $ppff->delete();

            DB::commit();

            return redirect()->route('admin.ppff.index')
                ->with('success', 'PPFF eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al eliminar PPFF: ' . $e->getMessage());
        }
    }

    /**
     * Search PPFF for AJAX requests
     */
    public function search(Request $request)
    {
        $term = $request->get('term', '');
        
        $ppffs = Ppff::search($term)
            ->with(['estudiantes' => function($query) {
                $query->select('id', 'ppffs_id', 'nombre', 'paterno', 'materno');
            }])
            ->limit(10)
            ->get();

        return response()->json($ppffs->map(function ($ppff) {
            return [
                'id' => $ppff->id,
                'text' => $ppff->nombre_completo . ' - CI: ' . $ppff->ci . ' - ' . $ppff->telefono,
                'nombre_completo' => $ppff->nombre_completo,
                'ci' => $ppff->ci,
                'telefono' => $ppff->telefono,
                'parentesco' => $ppff->parentesco,
                'ocupacion' => $ppff->ocupacion,
                'estudiantes_count' => $ppff->estudiantes->count(),
                'estudiantes' => $ppff->estudiantes->map(function($estudiante) {
                    return [
                        'id' => $estudiante->id,
                        'nombre_completo' => $estudiante->nombre_completo
                    ];
                })
            ];
        }));
    }

    /**
     * API endpoint para obtener PPFF con información detallada
     */
    public function getPpffDetails($id)
    {
        $ppff = Ppff::with(['estudiantes' => function($query) {
            $query->select('id', 'ppffs_id', 'nombre', 'paterno', 'materno', 'ci');
        }])->findOrFail($id);

        return response()->json([
            'id' => $ppff->id,
            'nombre_completo' => $ppff->nombre_completo,
            'ci' => $ppff->ci,
            'telefono' => $ppff->telefono,
            'parentesco' => $ppff->parentesco,
            'parentesco_formateado' => $ppff->parentesco_formateado,
            'ocupacion' => $ppff->ocupacion,
            'direccion' => $ppff->direccion,
            'fecha_nacimiento' => $ppff->fecha_nacimiento ? $ppff->fecha_nacimiento->format('Y-m-d') : null,
            'edad' => $ppff->edad,
            'estudiantes' => $ppff->estudiantes->map(function($estudiante) {
                return [
                    'id' => $estudiante->id,
                    'nombre_completo' => $estudiante->nombre_completo,
                    'ci' => $estudiante->ci
                ];
            })
        ]);
    }
}