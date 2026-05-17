<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribuidoras_recarga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');                           // "Tigo", "Claro", etc.
            $table->decimal('saldo_actual', 10, 2)->default(0); // cuánto tienes disponible
            $table->decimal('saldo_minimo', 10, 2)->default(100); // alerta cuando baje de aquí
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribuidoras_recarga');
    }
};
