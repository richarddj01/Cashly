<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_personales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cuenta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('tipo', [
                'salario',
                'freelance',
                'otro_ingreso',
                'gasto_hogar',
                'servicio',
                'deuda',
                'ahorro',
                'gasto_varios',
                'prestamo_negocio', // dinero que pasas al negocio o recibes de él
            ]);

            $table->enum('direccion', ['entrada', 'salida']);
            $table->decimal('monto', 10, 2);
            $table->string('descripcion')->nullable();
            $table->date('fecha');
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pagado');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_personales');
    }
};
