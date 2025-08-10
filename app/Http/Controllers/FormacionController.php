<?php

namespace App\Http\Controllers;

use App\Models\Formacion;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormacionController extends Controller
{
    public function index($personal_id)
    {
        $personal = Personal::with(['usuario', 'formaciones' => function($query) {
            $query->orderBy('fecha_graduacion', 'desc');
        }])->findOrFail($personal_id);
        
        $niveles = Formacion::getNiveles();
        
        return view('admin.formaciones.index', compact('personal', 'niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'personal_id' => 'required|exists:personals,id',
            'titulo' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'nivel' => 'required|in:Primaria,Secundaria,Bachillerato,Técnico,Licenciatura,Especialidad,Maestría,Doctorado',
            'fecha_graduacion' => 'required|date|before_or_equal:today',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120' // 5MB máximo
        ], [
            'personal_id.required' => 'El personal es requerido.',
            'personal_id.exists' => 'El personal seleccionado no existe.',
            'titulo.required' => 'El título es requerido.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'institucion.required' => 'La institución es requerida.',
            'institucion.max' => 'La institución no puede exceder 255 caracteres.',
            'nivel.required' => 'El nivel es requerido.',
            'nivel.in' => 'El nivel seleccionado no es válido.',
            'fecha_graduacion.required' => 'La fecha de graduación es requerida.',
            'fecha_graduacion.date' => 'La fecha de graduación debe ser una fecha válida.',
            'fecha_graduacion.before_or_equal' => 'La fecha de graduación no puede ser futura.',
            'archivo.file' => 'Debe seleccionar un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser PDF, DOC, DOCX, JPG, JPEG o PNG.',
            'archivo.max' => 'El archivo no puede exceder 5MB.'
        ]);

        $formacion = new Formacion();
        $formacion->personal_id = $request->personal_id;
        $formacion->titulo = $request->titulo;
        $formacion->institucion = $request->institucion;
        $formacion->nivel = $request->nivel;
        $formacion->fecha_graduacion = $request->fecha_graduacion;

        // Manejar archivo
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();
            $nombreArchivo = 'formacion_' . time() . '_' . Str::random(10) . '.' . $extension;
            
            // Crear directorio si no existe
            $rutaDestino = public_path('uploads/personal/formaciones');
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            
            // Mover archivo
            $archivo->move($rutaDestino, $nombreArchivo);
            $formacion->archivo = $nombreArchivo;
        }

        $formacion->save();

        return redirect()->route('admin.formacion.index', $request->personal_id)
                        ->with('success', 'Formación registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $formacion = Formacion::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'institucion' => 'required|string|max:255',
            'nivel' => 'required|in:Primaria,Secundaria,Bachillerato,Técnico,Licenciatura,Especialidad,Maestría,Doctorado',
            'fecha_graduacion' => 'required|date|before_or_equal:today',
            'archivo' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120'
        ], [
            'titulo.required' => 'El título es requerido.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'institucion.required' => 'La institución es requerida.',
            'institucion.max' => 'La institución no puede exceder 255 caracteres.',
            'nivel.required' => 'El nivel es requerido.',
            'nivel.in' => 'El nivel seleccionado no es válido.',
            'fecha_graduacion.required' => 'La fecha de graduación es requerida.',
            'fecha_graduacion.date' => 'La fecha de graduación debe ser una fecha válida.',
            'fecha_graduacion.before_or_equal' => 'La fecha de graduación no puede ser futura.',
            'archivo.file' => 'Debe seleccionar un archivo válido.',
            'archivo.mimes' => 'El archivo debe ser PDF, DOC, DOCX, JPG, JPEG o PNG.',
            'archivo.max' => 'El archivo no puede exceder 5MB.'
        ]);

        $formacion->titulo = $request->titulo;
        $formacion->institucion = $request->institucion;
        $formacion->nivel = $request->nivel;
        $formacion->fecha_graduacion = $request->fecha_graduacion;

        // Manejar archivo
        if ($request->hasFile('archivo')) {
            // Eliminar archivo anterior si existe
            if ($formacion->archivo && file_exists(public_path('uploads/personal/formaciones/' . $formacion->archivo))) {
                unlink(public_path('uploads/personal/formaciones/' . $formacion->archivo));
            }
            
            $archivo = $request->file('archivo');
            $extension = $archivo->getClientOriginalExtension();
            $nombreArchivo = 'formacion_' . time() . '_' . Str::random(10) . '.' . $extension;
            
            // Crear directorio si no existe
            $rutaDestino = public_path('uploads/personal/formaciones');
            if (!file_exists($rutaDestino)) {
                mkdir($rutaDestino, 0777, true);
            }
            
            // Mover archivo
            $archivo->move($rutaDestino, $nombreArchivo);
            $formacion->archivo = $nombreArchivo;
        }

        $formacion->save();

        return redirect()->route('admin.formacion.index', $formacion->personal_id)
                        ->with('success', 'Formación actualizada correctamente.');
    }

    public function destroy($id)
    {
        $formacion = Formacion::findOrFail($id);
        $personal_id = $formacion->personal_id;
        
        // Eliminar archivo si existe
        if ($formacion->archivo && file_exists(public_path('uploads/personal/formaciones/' . $formacion->archivo))) {
            unlink(public_path('uploads/personal/formaciones/' . $formacion->archivo));
        }

        $formacion->delete();

        return redirect()->route('admin.formacion.index', $personal_id)
                        ->with('success', 'Formación eliminada correctamente.');
    }

    public function download($id)
    {
        $formacion = Formacion::findOrFail($id);
        
        if (!$formacion->archivo) {
            return redirect()->back()->with('error', 'No hay archivo para descargar.');
        }

        $rutaArchivo = public_path('uploads/personal/formaciones/' . $formacion->archivo);
        
        if (!file_exists($rutaArchivo)) {
            return redirect()->back()->with('error', 'El archivo no existe.');
        }

        return response()->download($rutaArchivo, $formacion->titulo . '_' . $formacion->archivo);
    }
}