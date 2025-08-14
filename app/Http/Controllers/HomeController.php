<?php

namespace App\Http\Controllers;

use App\Models\Paralelo;
use App\Models\Turno;
use App\Models\Materia;
use App\Models\User;
use App\Models\Personal;
use App\Models\Ppff;
use App\Models\Estudiante;
use App\Models\Matriculacion;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name');

        // Redirigir según el rol del usuario
        if ($roles->contains('ESTUDIANTE')) {
            return $this->estudianteHome();
        } elseif ($roles->contains('DOCENTE')) {
            return $this->docenteHome();
        } elseif ($roles->contains('CAJERO/A')) {
            return $this->cajeroHome();
        } else {
            // Dashboard para administrador y director
            return $this->adminDashboard();
        }
    }

    /**
     * Dashboard para administrador y director
     */
    private function adminDashboard()
    {
        $total_gestiones = \App\Models\Gestion::count();
        $total_periodos = \App\Models\Periodo::count();
        $total_niveles = \App\Models\Nivel::count();
        $total_grados = \App\Models\Grado::count();
        $total_paralelos = Paralelo::count();
        $total_turnos = Turno::count();
        $total_materias = Materia::count();
        $total_roles = \Spatie\Permission\Models\Role::count();
        $total_personal_administrativo = Personal::where('tipo', 'Administrativo')->count();
        $total_personal_docente = Personal::where('tipo', 'Docente')->count();
        $total_ppff = Ppff::count();
        $total_estudiantes = Estudiante::count();

        // Datos para gráficas
        $estudiantes_por_anio = Matriculacion::selectRaw('YEAR(created_at) as anio, COUNT(*) as total')
            ->groupBy('anio')
            ->orderBy('anio')
            ->get();

        $pagos_por_mes = Pago::selectRaw('MONTH(created_at) as mes_num, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('mes_num')
            ->orderBy('mes_num')
            ->get()
            ->map(function($item) {
                return [
                    'mes' => Carbon::create()->month($item->mes_num)->format('F'),
                    'total' => $item->total
                ];
            });

        return view('admin.index', compact(
            'total_gestiones', 'total_periodos', 'total_niveles', 'total_grados', 
            'total_paralelos', 'total_turnos', 'total_materias', 'total_roles', 
            'total_personal_administrativo', 'total_personal_docente', 'total_estudiantes', 
            'total_ppff', 'estudiantes_por_anio', 'pagos_por_mes'
        ));
    }

    /**
     * Dashboard para estudiante
     */
    public function estudianteHome()
    {
        $user = Auth::user();
        $estudiante = $user->estudiante;
        
        if (!$estudiante) {
            return redirect()->route('login')->with('error', 'No se encontró información del estudiante');
        }

        // Obtener matriculación activa
        $matriculacion = $estudiante->matriculaciones()
            ->where('estado', 'activo')
            ->with(['gestion', 'nivel', 'grado', 'paralelo', 'turno'])
            ->first();

        // Datos del usuario
        $datos_usuario = [
            'nombre' => $estudiante->nombre,
            'apellidos' => $estudiante->paterno . ' ' . $estudiante->materno,
            'ci' => $estudiante->ci,
            'fecha_nacimiento' => $estudiante->fecha_nacimiento ? Carbon::parse($estudiante->fecha_nacimiento)->format('Y-m-d') : null,
            'telefono' => $estudiante->telefono,
            'direccion' => $estudiante->direccion,
            'edad' => $estudiante->fecha_nacimiento ? Carbon::parse($estudiante->fecha_nacimiento)->age : null
        ];

        return view('estudiante.dashboard', compact('estudiante', 'matriculacion', 'datos_usuario'));
    }

    /**
     * Dashboard para docente
     */
    public function docenteHome()
    {
        $user = Auth::user();
        $personal = $user->personal;
        
        if (!$personal) {
            return redirect()->route('login')->with('error', 'No se encontró información del personal docente');
        }

        // Obtener asignaciones activas del docente
        $asignaciones = \App\Models\Asignacion::where('docente_id', $personal->id)
            ->where('estado', 'activo')
            ->with(['gestion', 'nivel', 'grado', 'paralelo', 'materia', 'turno'])
            ->get();

        // Datos del usuario
        $datos_usuario = [
            'nombre' => $personal->nombre,
            'apellidos' => $personal->paterno . ' ' . $personal->materno,
            'ci' => $personal->ci,
            'fecha_nacimiento' => $personal->fecha_nacimiento ? Carbon::parse($personal->fecha_nacimiento)->format('Y-m-d') : null,
            'telefono' => $personal->telefono,
            'direccion' => $personal->direccion,
            'especialidad' => $personal->especialidad,
            'tipo' => $personal->tipo
        ];

        return view('docente.dashboard', compact('personal', 'asignaciones', 'datos_usuario'));
    }

    /**
     * Dashboard para cajero
     */
    public function cajeroHome()
    {
        $user = Auth::user();
        $personal = $user->personal;
        
        if (!$personal) {
            return redirect()->route('login')->with('error', 'No se encontró información del personal');
        }

        // Estadísticas de pagos del día
        $pagos_hoy = Pago::whereDate('created_at', today())->count();
        $monto_hoy = Pago::whereDate('created_at', today())->sum('monto');
        
        // Pagos del mes
        $pagos_mes = Pago::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $monto_mes = Pago::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('monto');

        // Datos del usuario
        $datos_usuario = [
            'nombre' => $personal->nombre,
            'apellidos' => $personal->paterno . ' ' . $personal->materno,
            'ci' => $personal->ci,
            'fecha_nacimiento' => $personal->fecha_nacimiento ? Carbon::parse($personal->fecha_nacimiento)->format('Y-m-d') : null,
            'telefono' => $personal->telefono,
            'direccion' => $personal->direccion,
            'tipo' => $personal->tipo
        ];

        $estadisticas = [
            'pagos_hoy' => $pagos_hoy,
            'monto_hoy' => $monto_hoy,
            'pagos_mes' => $pagos_mes,
            'monto_mes' => $monto_mes
        ];

        return view('cajero.dashboard', compact('personal', 'datos_usuario', 'estadisticas'));
    }
}
