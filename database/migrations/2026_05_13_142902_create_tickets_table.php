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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // Relaciones con las otras tablas
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('parking_space_id')->constrained('parking_spaces')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->comment('Cajero que registró la entrada');

            // Datos de tiempo
            $table->dateTime('entry_time'); // Reemplaza fecha_ingreso y hora_ingreso
            $table->dateTime('exit_time')->nullable();

            $table->enum('status', ['ACTIVO', 'FINALIZADO'])->default('ACTIVO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
