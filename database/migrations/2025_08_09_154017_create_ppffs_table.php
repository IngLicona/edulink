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
        Schema::create('ppffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_id')->nullable();
            $table->string('nombre');
            $table->string('paterno');
            $table->string('materno')->nullable();
            $table->string('ci')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono');
            $table->enum('parentesco', ['padre', 'madre', 'tutor', 'abuelo', 'abuela', 'tio', 'tia', 'hermano', 'hermana']);
            $table->string('ocupacion')->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();

            $table->foreign('personal_id')->references('id')->on('personals')->onDelete('set null');
            
            // Índices para optimizar búsquedas
            $table->index(['nombre', 'paterno']);
            $table->index('telefono');
            $table->index('parentesco');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppffs');
    }
};