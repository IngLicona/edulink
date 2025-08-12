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
        // Crear roles básicos
        $adminRole = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $directorRole = Role::firstOrCreate(['name' => 'DIRECTOR/A GENERAL']);
        $secretarioRole = Role::firstOrCreate(['name' => 'SECRETARIO/A']);
        $docenteRole = Role::firstOrCreate(['name' => 'DOCENTE']);
        $estudianteRole = Role::firstOrCreate(['name' => 'ESTUDIANTE']);

        // Crear permisos específicos por módulo - CORREGIDO
        $permissions = [
            // Configuración del sistema
            'admin.configuracion.index',
            'admin.configuracion.create',
            'admin.configuracion.store',
            
            // Gestiones - CORREGIDO: agregado 'create' que faltaba
            'admin.gestiones.index',
            'admin.gestiones.create',
            'admin.gestiones.store',
            'admin.gestiones.edit',
            'admin.gestiones.update',
            'admin.gestiones.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Periodos - CORREGIDO: agregado permisos que faltaban
            'admin.periodos.index',
            'admin.periodos.create',
            'admin.periodos.store',
            'admin.periodos.edit',
            'admin.periodos.update',
            'admin.periodos.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Niveles - CORREGIDO: agregado permisos que faltaban
            'admin.niveles.index',
            'admin.niveles.create',
            'admin.niveles.store',
            'admin.niveles.edit',
            'admin.niveles.update',
            'admin.niveles.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Grados - CORREGIDO: agregado permisos que faltaban
            'admin.grados.index',
            'admin.grados.create',
            'admin.grados.store',
            'admin.grados.edit',
            'admin.grados.update',
            'admin.grados.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Paralelos - CORREGIDO: agregado permisos que faltaban
            'admin.paralelos.index',
            'admin.paralelos.create',
            'admin.paralelos.store',
            'admin.paralelos.edit',
            'admin.paralelos.update',
            'admin.paralelos.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Turnos - CORREGIDO
            'admin.turnos.index',
            'admin.turnos.create',
            'admin.turnos.store',
            'admin.turnos.edit',
            'admin.turnos.update',
            'admin.turnos.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Materias - CORREGIDO: agregado permisos que faltaban
            'admin.materias.index',
            'admin.materias.create',
            'admin.materias.store',
            'admin.materias.edit',
            'admin.materias.update',
            'admin.materias.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Roles - CORREGIDO
            'admin.roles.index',
            'admin.roles.create',
            'admin.roles.store',
            'admin.roles.edit',
            'admin.roles.update',
            'admin.roles.delete', // CAMBIADO de 'destroy' a 'delete'
            'admin.roles.permisos',
            
            // Personal - CORREGIDO
            'admin.personal.index',
            'admin.personal.create',
            'admin.personal.store',
            'admin.personal.show',
            'admin.personal.edit',
            'admin.personal.update',
            'admin.personal.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Formaciones - CORREGIDO
            'admin.formaciones.index',
            'admin.formaciones.create',
            'admin.formaciones.store',
            'admin.formaciones.show',
            'admin.formaciones.edit',
            'admin.formaciones.update',
            'admin.formaciones.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Estudiantes - CORREGIDO
            'admin.estudiantes.index',
            'admin.estudiantes.create',
            'admin.estudiantes.store',
            'admin.estudiantes.show',
            'admin.estudiantes.edit',
            'admin.estudiantes.update',
            'admin.estudiantes.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Padres de familia - CORREGIDO
            'admin.ppffs.index',
            'admin.ppffs.create',
            'admin.ppffs.store',
            'admin.ppffs.show',
            'admin.ppffs.edit',
            'admin.ppffs.update',
            'admin.ppffs.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Asignaciones - CORREGIDO
            'admin.asignaciones.index',
            'admin.asignaciones.create',
            'admin.asignaciones.store',
            'admin.asignaciones.show',
            'admin.asignaciones.edit',
            'admin.asignaciones.update',
            'admin.asignaciones.delete', // CAMBIADO de 'destroy' a 'delete'
            
            // Matriculaciones - CORREGIDO
            'admin.matriculaciones.index',
            'admin.matriculaciones.create',
            'admin.matriculaciones.store',
            'admin.matriculaciones.show',
            'admin.matriculaciones.edit',
            'admin.matriculaciones.update',
            'admin.matriculaciones.delete', // CAMBIADO de 'destroy' a 'delete'
            'admin.matriculaciones.buscar_grados',
            'admin.matriculaciones.buscar_paralelos',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al administrador
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos específicos a director - CORREGIDO
        $directorPermissions = [
            'admin.gestiones.index',
            'admin.gestiones.create',
            'admin.gestiones.store',
            'admin.periodos.index',
            'admin.periodos.create', // AGREGADO
            'admin.periodos.store',
            'admin.niveles.index',
            'admin.grados.index',
            'admin.paralelos.index',
            'admin.turnos.index',
            'admin.materias.index',
            'admin.personal.index',
            'admin.personal.show',
            'admin.estudiantes.index',
            'admin.estudiantes.show',
            'admin.asignaciones.index',
            'admin.matriculaciones.index',
        ];
        $directorRole->givePermissionTo($directorPermissions);

        // Asignar permisos específicos a secretario - CORREGIDO
        $secretarioPermissions = [
            'admin.estudiantes.index',
            'admin.estudiantes.create',
            'admin.estudiantes.store',
            'admin.estudiantes.show',
            'admin.estudiantes.edit',
            'admin.estudiantes.update',
            'admin.ppffs.index',
            'admin.ppffs.create',
            'admin.ppffs.store',
            'admin.ppffs.show', // AGREGADO
            'admin.ppffs.edit', // AGREGADO
            'admin.ppffs.update', // AGREGADO
            'admin.matriculaciones.index',
            'admin.matriculaciones.create',
            'admin.matriculaciones.store',
            'admin.matriculaciones.show', // AGREGADO
        ];
        $secretarioRole->givePermissionTo($secretarioPermissions);

        // Asignar permisos específicos a docente
        $docentePermissions = [
            'admin.estudiantes.index',
            'admin.estudiantes.show',
            'admin.asignaciones.index',
            'admin.asignaciones.show', // AGREGADO
            'admin.materias.index',
        ];
        $docenteRole->givePermissionTo($docentePermissions);

        // Los estudiantes no necesitan permisos administrativos por ahora
    }
}