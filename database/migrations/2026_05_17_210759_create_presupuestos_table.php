<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categoria_id')->constrained()->cascadeOnDelete();
            $table->decimal('monto_limite', 10, 2);   // cuánto puedes gastar
            $table->integer('mes');                    // 1 al 12
            $table->integer('anio');                   // 2024, 2025, etc.
            $table->timestamps();

            // No puede haber dos presupuestos para la misma categoría en el mismo mes/año
            $table->unique(['user_id', 'categoria_id', 'mes', 'anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presupuestos');
    }
};
