<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Matriculacion;
use App\Models\Estudiante;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PagoController extends Controller
{
    protected $configuracion;

    public function __construct()
    {
        $this->middleware('auth');
        $this->configuracion = Configuracion::first();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = Pago::with(['matriculacion.estudiante', 'matriculacion.gestion', 'matriculacion.grado', 'matriculacion.paralelo'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        $estudiantes = Estudiante::with('usuario')->where('estado', 'activo')->get();
        $matriculaciones = Matriculacion::with(['estudiante', 'gestion', 'grado', 'paralelo'])->get();
        
        return view('admin.pagos.index', [
            'pagos' => $pagos,
            'estudiantes' => $estudiantes,
            'matriculaciones' => $matriculaciones,
            'configuracion' => $this->configuracion
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'matriculacion_id' => 'required|exists:matriculacions,id',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,deposito,cheque,tarjeta',
            'descripcion' => 'required|string|max:255',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pendiente,completado,cancelado,anulado'
        ], [
            'matriculacion_id.required' => 'Debe seleccionar una matriculación.',
            'matriculacion_id.exists' => 'La matriculación seleccionada no existe.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in' => 'El método de pago no es válido.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede exceder 255 caracteres.',
            'fecha_pago.required' => 'La fecha de pago es obligatoria.',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida.',
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.'
        ]);

        try {
            $pago = Pago::create([
                'matriculacion_id' => $request->matriculacion_id,
                'monto' => $request->monto,
                'metodo_pago' => $request->metodo_pago,
                'descripcion' => $request->descripcion,
                'fecha_pago' => $request->fecha_pago,
                'estado' => $request->estado
            ]);

            return redirect()->route('admin.pagos.index')
                           ->with('success', 'Pago registrado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $request->validate([
            'matriculacion_id' => 'required|exists:matriculacions,id',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,deposito,cheque,tarjeta',
            'descripcion' => 'required|string|max:255',
            'fecha_pago' => 'required|date',
            'estado' => 'required|in:pendiente,completado,cancelado,anulado'
        ], [
            'matriculacion_id.required' => 'Debe seleccionar una matriculación.',
            'matriculacion_id.exists' => 'La matriculación seleccionada no existe.',
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'metodo_pago.required' => 'Debe seleccionar un método de pago.',
            'metodo_pago.in' => 'El método de pago no es válido.',
            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede exceder 255 caracteres.',
            'fecha_pago.required' => 'La fecha de pago es obligatoria.',
            'fecha_pago.date' => 'La fecha de pago debe ser una fecha válida.',
            'estado.required' => 'Debe seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.'
        ]);

        try {
            $pago->update([
                'matriculacion_id' => $request->matriculacion_id,
                'monto' => $request->monto,
                'metodo_pago' => $request->metodo_pago,
                'descripcion' => $request->descripcion,
                'fecha_pago' => $request->fecha_pago,
                'estado' => $request->estado
            ]);

            return redirect()->route('admin.pagos.index')
                           ->with('success', 'Pago actualizado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Error al actualizar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $pago = Pago::findOrFail($id);
            $pago->delete();

            return redirect()->route('admin.pagos.index')
                           ->with('success', 'Pago eliminado exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Ver pagos de un estudiante específico
     */
    public function ver_pagos($id)
    {
        $estudiante = Estudiante::with(['matriculaciones.pagos', 'matriculaciones.gestion', 'matriculaciones.grado', 'matriculaciones.paralelo'])
                                ->findOrFail($id);

        $pagos = Pago::whereHas('matriculacion', function($query) use ($id) {
                        $query->where('estudiante_id', $id);
                    })
                    ->with(['matriculacion.gestion', 'matriculacion.grado', 'matriculacion.paralelo'])
                    ->orderBy('fecha_pago', 'desc')
                    ->get();

        return view('admin.pagos.ver_pagos', [
            'estudiante' => $estudiante,
            'pagos' => $pagos,
            'configuracion' => $this->configuracion
        ]);
    }

    /**
     * Generar comprobante de pago en PDF
     */
    public function comprobante($id)
    {
        try {
            $pago = Pago::with([
                'matriculacion.estudiante', 
                'matriculacion.gestion', 
                'matriculacion.grado', 
                'matriculacion.paralelo'
            ])->findOrFail($id);

            $pdf = PDF::loadView('admin.pagos.comprobante', [
                'pago' => $pago,
                'configuracion' => $this->configuracion
            ]);
            
            $nombreArchivo = 'comprobante_pago_' . str_pad($pago->id, 8, '0', STR_PAD_LEFT) . '.pdf';
            
            return $pdf->stream($nombreArchivo);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al generar comprobante: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el comprobante: ' . $e->getMessage());
        }
    }

    /**
     * Obtener matriculaciones por estudiante (AJAX)
     */
    public function getMatriculacionesByEstudiante(Request $request)
    {
        $estudianteId = $request->estudiante_id;
        
        $matriculaciones = Matriculacion::with(['gestion', 'grado', 'paralelo'])
                                       ->where('estudiante_id', $estudianteId)
                                       ->get();

        return response()->json($matriculaciones);
    }

    /**
     * Generar reportes de pagos
     */
    public function reportes(Request $request)
    {
        try {
            $query = Pago::with([
                'matriculacion.estudiante', 
                'matriculacion.gestion', 
                'matriculacion.grado', 
                'matriculacion.paralelo'
            ]);

            // Aplicar filtros
            if ($request->fecha_inicio) {
                $query->whereDate('fecha_pago', '>=', $request->fecha_inicio);
            }

            if ($request->fecha_fin) {
                $query->whereDate('fecha_pago', '<=', $request->fecha_fin);
            }

            if ($request->estado) {
                $query->where('estado', $request->estado);
            }

            if ($request->metodo_pago) {
                $query->where('metodo_pago', $request->metodo_pago);
            }

            $pagos = $query->orderBy('fecha_pago', 'desc')->get();

            if ($request->tipo == 'pdf') {
                $pdf = PDF::loadView('admin.pagos.reporte_pdf', [
                    'pagos' => $pagos,
                    'configuracion' => $this->configuracion,
                    'request' => $request
                ]);

                $nombreArchivo = 'reporte_pagos_' . now()->format('Y-m-d_H-i-s') . '.pdf';
                
                return $pdf->stream($nombreArchivo);
            } else {
                // Implementar Excel si es necesario
                return redirect()->back()->with('info', 'Funcionalidad de Excel en desarrollo.');
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al generar reporte: ' . $e->getMessage());
            return back()->with('error', 'Error al generar el reporte: ' . $e->getMessage());
        }
    }
}