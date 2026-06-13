<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();

            // Relacionamos este pago con la factura
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');

            // El método de pago y el monto de esa fracción
            $table->string('method'); // Ej: 'Efectivo', 'Tarjeta', 'Pago Móvil', 'Divisas'
            $table->decimal('amount', 10, 2); // Ej: 5.00

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
