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
        $data = [
            'paralelos' => Paralelo::count(),
            'turnos' => Turno::count(),
            'materias' => Materia::count(),
            'roles' => 9, // Asumiendo que tienes 9 roles como en la imagen
            'administrativos' => Personal::where('tipo', 'Administrativo')->count(),
            'docentes' => Personal::where('tipo', 'Docente')->count(),
            'ppff' => Ppff::count(),
            'estudiantes' => Estudiante::count(),
        ];

        // Datos para la gráfica de estudiantes matriculados
        $matriculados = Matriculacion::selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Datos para la gráfica de pagos por mes
        $pagos = Pago::selectRaw('MONTH(created_at) as month, SUM(monto) as total')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('F'),
                    'total' => $item->total
                ];
            });

        return view('home', compact('data', 'matriculados', 'pagos'));
    }
}
