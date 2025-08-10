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
        Schema::create('personals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->enum('tipo', ['docente','administrativo']);
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno');
            $table->string('ci')->unique(); // Agregado unique para evitar CIs duplicados
            $table->date('fecha_nacimiento'); // Cambiado a tipo date
            $table->text('direccion'); // Cambiado a text para más espacio
            $table->string('telefono');
            $table->string('profesion');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personals');
    }
};