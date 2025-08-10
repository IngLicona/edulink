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
        Schema::create('formacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personal_id')->constrained('personals')->onDelete('cascade');
            $table->string('titulo');
            $table->string('institucion');
            $table->enum('nivel', ['Primaria', 'Secundaria', 'Bachillerato', 'Técnico', 'Licenciatura', 'Especialidad', 'Maestría', 'Doctorado']);
            $table->date('fecha_graduacion');
            $table->string('archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formacions');
    }
};