<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nombre');                          // "Alimentación", "Transporte", etc.
            $table->enum('tipo', ['ingreso', 'egreso']);       // ¿para qué tipo de movimiento?
            $table->enum('contexto', ['personal', 'negocio', 'ambos']); // ¿dónde aplica?
            $table->string('color', 7)->default('#6c757d');   // color hex para la UI
            $table->string('icono')->nullable();               // nombre del icono Bootstrap
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
