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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('ppffs_id');
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno');
            $table->string('ci')->unique();
            $table->date('fecha_nacimiento');
            $table->text('direccion');
            $table->enum('genero', ['masculino', 'femenino']);
            $table->string('telefono');
            $table->string('foto')->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->default('activo');
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('ppffs_id')->references('id')->on('ppffs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};