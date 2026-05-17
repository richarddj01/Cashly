<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_negocio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cuenta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('categoria_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('empleado_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('area', [
                'papeleria',
                'impresiones',
                'compartido',
            ]);

            $table->enum('tipo', [
                'venta_efectivo',
                'deposito_banco',
                'pago_empleado',
                'servicio_basico',    // internet, energía, agua
                'factura_proveedor',
                'gasto_insumos',      // tinta, papel, etc.
                'gasto_varios',
                'prestamo_personal',  // dinero entre tu bolsillo y el negocio
                'transferencia',      // entre tus propias cuentas
            ]);

            $table->enum('direccion', ['entrada', 'salida']);
            $table->decimal('monto', 10, 2);
            $table->string('descripcion')->nullable();
            $table->date('fecha');
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pagado');
            $table->date('fecha_vencimiento')->nullable(); // solo para facturas
            $table->string('beneficiario')->nullable();    // para pagos de empleados u otros
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_negocio');
    }
};
