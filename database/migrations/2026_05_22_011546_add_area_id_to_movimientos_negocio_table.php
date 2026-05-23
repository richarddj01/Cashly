<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimientos_negocio', function (Blueprint $table) {
            // Agrega la nueva columna area_id
            $table->foreignId('area_id')
                  ->nullable()
                  ->after('empleado_id')
                  ->constrained('areas_negocio')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('movimientos_negocio', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
        });
    }
};
