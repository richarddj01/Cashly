<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deudas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('acreedor');                        // ¿a quién le debes? banco, persona, etc.
            $table->decimal('monto_total', 10, 2);             // deuda original
            $table->decimal('monto_pagado', 10, 2)->default(0); // cuánto has pagado
            $table->decimal('interes', 5, 2)->default(0);      // % de interés
            $table->date('fecha_inicio');
            $table->date('fecha_vencimiento')->nullable();
            $table->enum('tipo', [
                'prestamo',
                'tarjeta',
                'cuota',
                'otro',
            ]);
            $table->enum('estado', ['activa', 'pagada', 'vencida'])->default('activa');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deudas');
    }
};
