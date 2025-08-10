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
        Schema::create('asignacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('docente_id')->constrained('personals')->onDelete('cascade');
            $table->foreignId('gestion_id')->constrained('gestions')->onDelete('cascade');
            $table->foreignId('nivel_id')->constrained('nivels')->onDelete('cascade');
            $table->foreignId('grado_id')->constrained('grados')->onDelete('cascade');
            $table->foreignId('paralelo_id')->constrained('paralelos')->onDelete('cascade');
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('turno_id')->constrained('turnos')->onDelete('cascade');
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->date('fecha_asignacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacions');
    }
};
