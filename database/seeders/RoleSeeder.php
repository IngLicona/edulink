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
        // Limpiar la caché de permisos para evitar errores de asignación
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        // Crear roles básicos
        $adminRole = Role::firstOrCreate(['name' => 'ADMINISTRADOR']);
        $directorRole = Role::firstOrCreate(['name' => 'DIRECTOR/A GENERAL']);
        $secretarioRole = Role::firstOrCreate(['name' => 'SECRETARIO/A']);
        $docenteRole = Role::firstOrCreate(['name' => 'DOCENTE']);
        $estudianteRole = Role::firstOrCreate(['name' => 'ESTUDIANTE']);
        $cajeroRole = Role::firstOrCreate(['name' => 'CAJERO/A']);

        // Crear permisos específicos por módulo
        $permissions = [
            // Configuración del sistema
            'admin.configuracion.index',
            'admin.configuracion.create',
            'admin.configuracion.store',
            
            // Gestiones
            'admin.gestiones.index',
            'admin.gestiones.create',
            'admin.gestiones.store',
            'admin.gestiones.edit',
            'admin.gestiones.update',
            'admin.gestiones.delete',
            
            // Periodos
            'admin.periodos.index',
            'admin.periodos.create',
            'admin.periodos.store',
            'admin.periodos.edit',
            'admin.periodos.update',
            'admin.periodos.delete',
            
            // Niveles
            'admin.niveles.index',
            'admin.niveles.create',
            'admin.niveles.store',
            'admin.niveles.edit',
            'admin.niveles.update',
            'admin.niveles.delete',
            
            // Grados
            'admin.grados.index',
            'admin.grados.create',
            'admin.grados.store',
            'admin.grados.edit',
            'admin.grados.update',
            'admin.grados.delete',
            
            // Paralelos
            'admin.paralelos.index',
            'admin.paralelos.create',
            'admin.paralelos.store',
            'admin.paralelos.edit',
            'admin.paralelos.update',
            'admin.paralelos.delete',
            
            // Turnos
            'admin.turnos.index',
            'admin.turnos.create',
            'admin.turnos.store',
            'admin.turnos.edit',
            'admin.turnos.update',
            'admin.turnos.delete',
            
            // Materias
            'admin.materias.index',
            'admin.materias.create',
            'admin.materias.store',
            'admin.materias.edit',
            'admin.materias.update',
            'admin.materias.delete',
            
            // Roles
            'admin.roles.index',
            'admin.roles.create',
            'admin.roles.store',
            'admin.roles.edit',
            'admin.roles.update',
            'admin.roles.delete',
            'admin.roles.permisos',
            
            // Personal
            'admin.personal.index',
            'admin.personal.create',
            'admin.personal.store',
            'admin.personal.show',
            'admin.personal.edit',
            'admin.personal.update',
            'admin.personal.delete',
            
            // Formaciones
            'admin.formaciones.index',
            'admin.formaciones.create',
            'admin.formaciones.store',
            'admin.formaciones.show',
            'admin.formaciones.edit',
            'admin.formaciones.update',
            'admin.formaciones.delete',
            
            // Estudiantes
            'admin.estudiantes.index',
            'admin.estudiantes.create',
            'admin.estudiantes.store',
            'admin.estudiantes.show',
            'admin.estudiantes.edit',
            'admin.estudiantes.update',
            'admin.estudiantes.delete',
            
            // Padres de familia
            'admin.ppffs.index',
            'admin.ppffs.create',
            'admin.ppffs.store',
            'admin.ppffs.show',
            'admin.ppffs.edit',
            'admin.ppffs.update',
            'admin.ppffs.delete',
            
            // Asignaciones
            'admin.asignaciones.index',
            'admin.asignaciones.create',
            'admin.asignaciones.store',
            'admin.asignaciones.show',
            'admin.asignaciones.edit',
            'admin.asignaciones.update',
            'admin.asignaciones.delete',
            
            // Matriculaciones
            'admin.matriculaciones.index',
            'admin.matriculaciones.create',
            'admin.matriculaciones.store',
            'admin.matriculaciones.show',
            'admin.matriculaciones.edit',
            'admin.matriculaciones.update',
            'admin.matriculaciones.delete',
            'admin.matriculaciones.buscar_grados',
            'admin.matriculaciones.buscar_paralelos',

            // Pagos
            'admin.pagos.index',
            'admin.pagos.ver_pagos',
            'admin.pagos.store',
            'admin.pagos.comprobante',
            'admin.pagos.destroy',

            // Asistencias - AGREGAR ESTOS PERMISOS
            'admin.asistencias.index',
            'admin.asistencias.create',
            'admin.asistencias.store',
            'admin.asistencias.show',
            'admin.asistencias.edit',
            'admin.asistencias.update',
            'admin.asistencias.delete',
            'admin.asistencias.reporte',
                // Calificaciones
                'admin.calificaciones.index',
                'admin.calificaciones.create',
                'admin.calificaciones.store',
                'admin.calificaciones.show_estudiante',
                'admin.calificaciones.show_admin',
                'admin.calificaciones.update',
                'admin.calificaciones.destroy',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Asignar todos los permisos al administrador
        $adminRole->givePermissionTo(Permission::all());

        // Asignar permisos específicos a director
        $directorPermissions = [
            'admin.gestiones.index',
            'admin.gestiones.create',
            'admin.gestiones.store',
            'admin.periodos.index',
            'admin.periodos.create',
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

        // Asignar permisos específicos a secretario
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
            'admin.ppffs.show',
            'admin.ppffs.edit',
            'admin.ppffs.update',
            'admin.matriculaciones.index',
            'admin.matriculaciones.create',
            'admin.matriculaciones.store',
            'admin.matriculaciones.show',
        ];
        $secretarioRole->givePermissionTo($secretarioPermissions);

        // Asignar permisos específicos a docente
        $docentePermissions = [
            'admin.estudiantes.index',
            'admin.estudiantes.show',
            'admin.asignaciones.index',
            'admin.asignaciones.show',
            'admin.materias.index',
        ];
        $docenteRole->givePermissionTo($docentePermissions);

        // Asignar permisos específicos a cajero
        $cajeroPermissions = [
            'admin.pagos.index',
            'admin.pagos.ver_pagos',
            'admin.pagos.store',
            'admin.pagos.comprobante',
            'admin.pagos.destroy',
            'admin.matriculaciones.index',
            'admin.matriculaciones.show'
        ];
        $cajeroRole->givePermissionTo($cajeroPermissions);

        //asignar permisos especificos a asistencias
        $asistenciaPermissions = [
        'admin.asistencias.index',
        'admin.asistencias.create',
        'admin.asistencias.store',
        'admin.asistencias.show',
        'admin.asistencias.edit',
        'admin.asistencias.update',
        'admin.asistencias.delete',
        'admin.asistencias.reporte'
    ];
        $docenteRole->givePermissionTo($asistenciaPermissions);
        $directorRole->givePermissionTo($asistenciaPermissions);
        $secretarioRole->givePermissionTo($asistenciaPermissions);

        //asignar permisos especificos a calificaciones
        $calificacionPermissions = [
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
        
        // El administrador tiene todos los permisos
        $adminRole->givePermissionTo($calificacionPermissions);

        // El docente puede ver, crear, editar y generar reportes
        $docenteRole->givePermissionTo([
            'admin.calificaciones.index',
            'admin.calificaciones.create',
            'admin.calificaciones.store',
            'admin.calificaciones.edit',
            'admin.calificaciones.update',
            'admin.calificaciones.show',
            'admin.calificaciones.show_admin',
            'admin.calificaciones.reporte',
            'admin.calificaciones.generar-reporte'
        ]);

        // El estudiante solo puede ver sus calificaciones
        $estudianteRole->givePermissionTo([
            'admin.calificaciones.index',
            'admin.calificaciones.show_estudiante'
        ]);

        // El director puede ver todo y generar reportes
        $directorRole->givePermissionTo([
            'admin.calificaciones.index',
            'admin.calificaciones.show',
            'admin.calificaciones.show_admin',
            'admin.calificaciones.show_estudiante',
            'admin.calificaciones.reporte',
            'admin.calificaciones.generar-reporte'
        ]);
    }
}