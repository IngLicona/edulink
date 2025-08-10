<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Gestion;
use App\Models\Grado;
use App\Models\Materia;
use App\Models\Nivel;
use App\Models\Paralelo;
use App\Models\Periodo;
use App\Models\Personal;
use App\Models\Ppff;
use App\Models\Turno;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $total_gestiones = Gestion::count();
        $total_periodos = Periodo::count();
        $total_niveles = Nivel::count();
        $total_grados = Grado::count();
        $total_paralelos = Paralelo::count();
        $total_turnos = Turno::count();
        $total_materias = Materia::count();
        $total_roles = Role::count();
        $total_estudiantes= Estudiante::count();
        $total_ppff = Ppff::count();
        $total_personal_administrativo = Personal::where('tipo','administrativo')->count();
        $total_personal_docente = Personal::where('tipo','docente')->count();
        return view("admin.index", compact("total_gestiones", 'total_periodos',
                    'total_niveles', 'total_grados', 'total_paralelos', 'total_turnos', 'total_materias', 'total_roles', 
                    'total_personal_administrativo', 'total_personal_docente', 'total_estudiantes', 'total_ppff'));
    }
}
