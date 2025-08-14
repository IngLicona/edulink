<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Gestion;
use App\Models\Grado;
use App\Models\Materia;
use App\Models\Matriculacion;
use App\Models\Nivel;
use App\Models\Pago;
use App\Models\Paralelo;
use App\Models\Periodo;
use App\Models\Personal;
use App\Models\Ppff;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Si es estudiante
        if ($user->esEstudiante()) {
            return view('estudiante.index');
        }

        // Si es docente
        if ($user->esDocente()) {
            return view('docente.index');
        }

        // Si es cajero
        if ($user->hasRole('CAJERO/A')) {
            $pagos_por_mes = Pago::selectRaw('MONTH(created_at) as mes, SUM(monto) as total')
                ->whereYear('created_at', Carbon::now()->year)
                ->groupBy('mes')
                ->orderBy('mes')
                ->get()
                ->map(function($item) {
                    return [
                        'mes' => Carbon::create()->month($item->mes)->locale('es')->monthName,
                        'total' => $item->total
                    ];
                });

            $pagos_hoy = Pago::whereDate('created_at', Carbon::today())
                ->selectRaw('tipo_pago, SUM(monto) as total')
                ->groupBy('tipo_pago')
                ->pluck('total', 'tipo_pago')
                ->toArray();

            return view('cajero.index', compact('pagos_por_mes', 'pagos_hoy'));
        }

        // Si es director o administrador, mostrar el dashboard completo
        if ($user->esAdmin() || $user->esDirector()) {
            $total_gestiones = Gestion::count();
            $total_periodos = Periodo::count();
            $total_niveles = Nivel::count();
            $total_grados = Grado::count();
            $total_paralelos = Paralelo::count();
            $total_turnos = Turno::count();
            $total_materias = Materia::count();
            $total_roles = Role::count();
            $total_estudiantes = Estudiante::count();
            $total_ppff = Ppff::count();
            $total_personal_administrativo = Personal::where('tipo','administrativo')->count();
            $total_personal_docente = Personal::where('tipo','docente')->count();

        // Datos para la gráfica de estudiantes matriculados por año
        $estudiantes_por_anio = DB::table('matriculacions')
            ->selectRaw('YEAR(fecha_matriculacion) as anio, COUNT(*) as total')
            ->whereNotNull('fecha_matriculacion')
            ->where('estado', 'ACTIVO')
            ->groupBy(DB::raw('YEAR(fecha_matriculacion)'))
            ->orderBy('anio')
            ->get();

        // Datos para la gráfica de pagos por mes del año actual
        $pagos_por_mes = Pago::selectRaw('MONTH(created_at) as mes, SUM(monto) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get()
            ->map(function($item) {
                return [
                    'mes' => Carbon::create()->month($item->mes)->locale('es')->monthName,
                    'total' => $item->total
                ];
            });

        return view("admin.index", compact(
            "total_gestiones", 'total_periodos', 'total_niveles', 'total_grados', 
            'total_paralelos', 'total_turnos', 'total_materias', 'total_roles', 
            'total_personal_administrativo', 'total_personal_docente', 'total_estudiantes', 
            'total_ppff', 'estudiantes_por_anio', 'pagos_por_mes'
        ));
    }
}
