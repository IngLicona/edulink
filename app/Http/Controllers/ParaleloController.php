<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use App\Models\Grado;
use Illuminate\Http\Request;

class ParaleloController extends Controller
{
    public function index()
    {
    $paralelos = Paralelo::with('grado.nivel')
        ->get()
        ->sortBy([
            fn($a, $b) => strcmp($a->grado->nivel->nombre, $b->grado->nivel->nombre),
            fn($a, $b) => strcmp($a->grado->nombre, $b->grado->nombre),
        ]);

    // Agrupar los grados por nivel
    $gradosAgrupados = Grado::with('nivel')
        ->get()
        ->sortBy([
            fn($a, $b) => strcmp($a->nivel->nombre, $b->nivel->nombre),
            fn($a, $b) => strcmp($a->nombre, $b->nombre),
        ])
        ->groupBy(fn($grado) => $grado->nivel->nombre);

    return view('admin.paralelos.index', [
        'paralelos' => $paralelos,
        'gradosAgrupados' => $gradosAgrupados
    ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grado_id' => 'required|exists:grados,id',
        ]);

        Paralelo::create([
            'nombre' => $request->nombre,
            'grado_id' => $request->grado_id,
        ]);

        return redirect()->route('admin.paralelos.index')
            ->with('mensaje', 'El paralelo se ha creado correctamente')
            ->with('icono', 'success');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'grado_id' => 'required|exists:grados,id',
        ]);

        $paralelo = Paralelo::findOrFail($id);
        $paralelo->update([
            'nombre' => $request->nombre,
            'grado_id' => $request->grado_id,
        ]);

        return redirect()->route('admin.paralelos.index')
            ->with('mensaje', 'El paralelo se ha actualizado correctamente')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $paralelo = Paralelo::findOrFail($id);
        $paralelo->delete();

        return redirect()->route('admin.paralelos.index')
            ->with('mensaje', 'El paralelo se ha eliminado correctamente')
            ->with('icono', 'success');
    }
}
