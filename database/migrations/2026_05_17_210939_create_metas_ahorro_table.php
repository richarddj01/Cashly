<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metas_ahorro', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cuenta_id')->constrained()->cascadeOnDelete(); // en qué cuenta se guarda
            $table->string('nombre');                          // "Vacaciones", "Laptop nueva", etc.
            $table->decimal('monto_objetivo', 10, 2);         // cuánto quieres ahorrar
            $table->decimal('monto_actual', 10, 2)->default(0); // cuánto llevas
            $table->date('fecha_limite')->nullable();          // fecha objetivo
            $table->enum('estado', ['activa', 'completada', 'cancelada'])->default('activa');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metas_ahorro');
    }
};
