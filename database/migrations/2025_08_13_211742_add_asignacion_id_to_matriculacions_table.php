<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('matriculacions', function (Blueprint $table) {
            $table->foreignId('asignacion_id')->after('paralelo_id')->nullable();
        });

        // Buscar asignaciones que coincidan con los datos de matriculación
        $matriculaciones = DB::table('matriculacions')->get();
        foreach ($matriculaciones as $matriculacion) {
            $asignacion = DB::table('asignacions')
                ->where('gestion_id', $matriculacion->gestion_id)
                ->where('nivel_id', $matriculacion->nivel_id)
                ->where('grado_id', $matriculacion->grado_id)
                ->where('paralelo_id', $matriculacion->paralelo_id)
                ->first();

            if ($asignacion) {
                DB::table('matriculacions')
                    ->where('id', $matriculacion->id)
                    ->update(['asignacion_id' => $asignacion->id]);
            }
        }

        // Después de actualizar los registros, añadir la restricción de clave foránea
        Schema::table('matriculacions', function (Blueprint $table) {
            $table->foreign('asignacion_id')->references('id')->on('asignacions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matriculacions', function (Blueprint $table) {
            $table->dropForeign(['asignacion_id']);
            $table->dropColumn('asignacion_id');
        });
    }
};
