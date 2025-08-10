<?php

namespace App\Http\Controllers;

use App\Models\Grado;
use App\Models\Nivel;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function index()
    {
        $niveles = Nivel::with('grados')->orderBy('nombre', 'asc')->get();
        return view('admin.grados.index', compact('niveles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel_id' => 'required|exists:nivels,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nivel_id.required' => 'Debe seleccionar un nivel.',
        ]);

        $existe = Grado::where('nombre', $request->nombre)
                        ->where('nivel_id', $request->nivel_id)
                        ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['nombre' => 'Este grado ya está registrado en este nivel.'])
                ->withInput();
        }

        Grado::create([
            'nombre' => $request->nombre,
            'nivel_id' => $request->nivel_id,
        ]);

        return redirect()->route('admin.grados.index')
            ->with('mensaje', 'El grado se ha creado correctamente.')
            ->with('icono', 'success');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel_id' => 'required|exists:nivels,id',
        ]);

        $existe = Grado::where('nombre', $request->nombre)
                        ->where('nivel_id', $request->nivel_id)
                        ->where('id', '!=', $id)
                        ->exists();

        if ($existe) {
            return redirect()->back()
                ->withErrors(['nombre' => 'Este grado ya está registrado en este nivel.'])
                ->withInput();
        }

        $grado = Grado::findOrFail($id);
        $grado->update([
            'nombre' => $request->nombre,
            'nivel_id' => $request->nivel_id,
        ]);

        return redirect()->route('admin.grados.index')
            ->with('mensaje', 'El grado se ha actualizado correctamente.')
            ->with('icono', 'success');
    }

    public function destroy($id)
    {
        $grado = Grado::findOrFail($id);
        $grado->delete();

        return redirect()->route('admin.grados.index')
            ->with('mensaje', 'El grado se ha eliminado correctamente.')
            ->with('icono', 'success');
    }
}
