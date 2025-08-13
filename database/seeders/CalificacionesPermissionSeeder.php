<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CalificacionesPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permisos para el módulo de calificaciones
        $permisos = [
            'admin.calificaciones.index',
            'admin.calificaciones.create',
            'admin.calificaciones.store',
            'admin.calificaciones.show',
            'admin.calificaciones.show_estudiante',
            'admin.calificaciones.show_admin',
            'admin.calificaciones.edit',
            'admin.calificaciones.update',
            'admin.calificaciones.destroy',
            'admin.calificaciones.reporte',
            'admin.calificaciones.generar-reporte'
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso], ['guard_name' => 'web']);
        }
    }
}
