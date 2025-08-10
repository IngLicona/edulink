<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;

class MateriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materias = Materia::all();
        return view("admin.materias.index", compact("materias"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.materias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        Materia::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('materias.index')->with('success', 'Materia registrada correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Materia $materia)
    {
        return view('admin.materias.edit', compact('materia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        /*$datos = request()->all();
        return response()->json($datos);*/
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $materia = Materia::find($id);
        $materia->nombre = $request->nombre;
        $materia->save();

        return redirect()->route('materias.index')->with('success', 'Materia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $materia = Materia::find($id);
        $materia->delete();

        return redirect()->route('materias.index')->with('success', 'Materia eliminada correctamente.');
    }
}
