<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nivel;

class NivelController extends Controller
{
    public function index()
    {
        $niveles = Nivel::all();
        return view('admin.niveles.index', compact('niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255|unique:nivels,nombre',
        ]);

        $nivel = new Nivel();
        $nivel->nombre = $request->nombre;
        $nivel->save();

        return redirect()->route('admin.niveles.index')
            ->with('mensaje', 'Nivel creado correctamente')
            ->with('icono', 'success');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'=> 'required|max:255|unique:nivels,nombre,'.$id,
        ]);
        $nivel = Nivel::find($id);
        $nivel->nombre = $request->nombre;
        $nivel->save();

        return redirect()->route('admin.niveles.index')
        ->with('mensaje', 'El nivel se ah actualizado correctamente')
        ->with('icono','success');

    }
    public function destroy(Request $request, $id)
    {
    $nivel = Nivel::findOrFail($id); // Asegura que existe o lanza 404
    $nivel->delete();

    return redirect()->route('admin.niveles.index') // Redirige a niveles
        ->with('mensaje', 'El nivel se ha eliminado correctamente')
        ->with('icono', 'success');
    }



}
