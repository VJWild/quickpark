<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            // Relacionamos la factura con el ticket original
            $table->foreignId('ticket_id')->constrained('tickets');
            $table->foreignId('user_id')->constrained('users'); // Cajero que cobró

            $table->string('invoice_number')->unique(); // nro_factura
            $table->timestamp('exit_time'); // Unifica fecha_salida y hora_salida
            $table->string('total_time'); // Ej: "0 días con 2 horas"

            // Detalles de cobro
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_amount', 10, 2); // monto_total
            $table->string('amount_literal'); // monto_literal

            $table->text('qr_code_data')->nullable(); // Los datos para generar el QR

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
