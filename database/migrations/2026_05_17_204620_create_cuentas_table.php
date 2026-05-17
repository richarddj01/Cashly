<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');                          // "Caja", "Banrural", "Tigo Money"
            $table->enum('tipo', ['efectivo', 'banco', 'digital']);
            $table->enum('contexto', ['personal', 'negocio']); // ¿a qué módulo pertenece?
            $table->decimal('saldo_inicial', 10, 2)->default(0);
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
