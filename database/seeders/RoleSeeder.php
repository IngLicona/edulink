<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear roles básicos - USANDO LOS NOMBRES QUE USAS EN DatabaseSeeder
        $adminRole = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $directorRole = Role::firstOrCreate(['name' => 'DIRECTOR/A GENERAL']);
        $secretarioRole = Role::firstOrCreate(['name' => 'SECRETARIO/A']);
        $docenteRole = Role::firstOrCreate(['name' => 'DOCENTE']);
        $estudianteRole = Role::firstOrCreate(['name' => 'ESTUDIANTE']);

        // Crear permisos básicos
        $permissions = [
            'gestionar_configuracion',
            'gestionar_gestiones',
            'gestionar_periodos',
            'gestionar_niveles',
            'gestionar_grados',
            'gestionar_paralelos',
            'gestionar_turnos',
            'gestionar_materias',
            'gestionar_roles',
            'gestionar_personal',
            'gestionar_estudiantes',
            'gestionar_formaciones',
            'ver_dashboard',
            'ver_reportes',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al administrador
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos específicos a director
        $directorRole->givePermissionTo([
            'ver_dashboard',
            'ver_reportes',
            'gestionar_estudiantes',
            'gestionar_personal',
            'gestionar_gestiones',
            'gestionar_periodos',
        ]);

        // Asignar permisos específicos a secretario
        $secretarioRole->givePermissionTo([
            'ver_dashboard',
            'gestionar_estudiantes',
            'ver_reportes',
        ]);

        // Asignar permisos específicos a docente
        $docenteRole->givePermissionTo([
            'ver_dashboard',
            'ver_reportes',
            'gestionar_estudiantes',
        ]);

        // Asignar permisos básicos a estudiante
        $estudianteRole->givePermissionTo([
            'ver_dashboard',
        ]);
    }
}