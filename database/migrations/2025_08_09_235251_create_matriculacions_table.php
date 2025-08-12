<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matriculacions', function (Blueprint $table) {
            $table->id();
            
            // Claves foráneas
            $table->foreignId('estudiante_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('cascade');
            $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade');
            $table->foreignId('paralelo_id')->constrained('paralelos')->onDelete('cascade');
            
            // Campos adicionales
            $table->date('fecha_matriculacion');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            
            // Timestamps
            $table->timestamps();

            // Índice único para evitar matriculacions duplicadas en la misma gestión
            $table->unique(['estudiante_id', 'gestion_id'], 'unique_estudiante_gestion');
            
            // Índices adicionales para optimizar consultas
            $table->index(['gestion_id', 'nivel_id', 'grado_id']);
            $table->index('fecha_matriculacion');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // Deshabilita la verificación de claves foráneas
        Schema::disableForeignKeyConstraints();

        // Borra la tabla
        Schema::dropIfExists('matriculacions');

        // Vuelve a habilitar la verificación de claves foráneas
        Schema::enableForeignKeyConstraints();
    }
};