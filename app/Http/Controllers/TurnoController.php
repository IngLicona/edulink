<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;

class TurnoController extends Controller
{
    /**
     * Muestra la lista de turnos.
     */
    public function index()
    {
        $turnos = Turno::all();
        return view("admin.turnos.index", compact("turnos"));
    }

    /**
     * Guarda un nuevo turno en la base de datos.
     */
  public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:255|unique:turnos,nombre',
    ]);

    Turno::create([
        'nombre' => $request->nombre,
    ]);

    return redirect()->route('admin.turnos.index')
        ->with('mensaje', 'Turno creado correctamente')
        ->with('icono', 'success');
}

public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255|unique:turnos,nombre,' . $id,
    ]);

    $turno = Turno::findOrFail($id);
    $turno->update([
        'nombre' => $request->nombre,
    ]);

    return redirect()->route('admin.turnos.index')
        ->with('mensaje', 'Turno actualizado correctamente')
        ->with('icono', 'success');
}

public function destroy($id)
{
    $turno = Turno::findOrFail($id);
    $turno->delete();

    return redirect()->route('admin.turnos.index')
        ->with('mensaje', 'Turno eliminado correctamente')
        ->with('icono', 'success');
}

}
