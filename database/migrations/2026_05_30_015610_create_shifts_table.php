<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->dateTime('start_time'); // Hora de apertura
            $table->dateTime('end_time')->nullable(); // Hora de cierre
            $table->decimal('opening_amount', 10, 2)->default(0); // Fondo de caja inicial
            $table->decimal('system_amount', 10, 2)->default(0); // Lo que el sistema sumó en facturas
            $table->decimal('declared_amount', 10, 2)->nullable(); // Lo que el cajero dice que tiene al final
            $table->decimal('difference', 10, 2)->nullable(); // declared_amount - (opening_amount + system_amount)
            $table->enum('status', ['ABIERTO', 'CERRADO'])->default('ABIERTO');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
