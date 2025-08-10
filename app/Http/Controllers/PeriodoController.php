<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Gestion;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PeriodoController extends Controller
{
    public function index()
{
    $gestiones = \App\Models\Gestion::with('periodos')->get(); // Cargamos las gestiones con sus periodos
    return view('admin.periodos.index', compact('gestiones'));
}


    public function store(Request $request)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:255',
            Rule::unique('periodos')->where(function ($query) use ($request) {
                return $query->where('gestion_id', $request->gestion_id);
            }),
        ],
        'gestion_id' => 'required|exists:gestions,id',
    ]);

    $periodo = new Periodo();
    $periodo->nombre = $request->nombre;
    $periodo->gestion_id = $request->gestion_id;
    $periodo->save();

    return redirect()->route('admin.periodos.index')
        ->with('mensaje', 'Periodo creado correctamente')
        ->with('icono', 'success');
}

   public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => [
            'required',
            'string',
            'max:255',
            Rule::unique('periodos')->ignore($id)->where(function ($query) use ($request) {
                return $query->where('gestion_id', $request->gestion_id);
            }),
        ],
        'gestion_id' => 'required|exists:gestions,id',
    ]);

    $periodo = Periodo::findOrFail($id);
    $periodo->nombre = $request->nombre;
    $periodo->gestion_id = $request->gestion_id;
    $periodo->save();

    return redirect()->route('admin.periodos.index')
        ->with('mensaje', 'Periodo actualizado correctamente')
        ->with('icono', 'success');
}


    public function destroy($id)
    {
        $periodo = Periodo::findOrFail($id);
        $periodo->delete();

        return redirect()->route('admin.periodos.index')
            ->with('mensaje', 'Periodo eliminado correctamente')
            ->with('icono', 'success');
    }
}
