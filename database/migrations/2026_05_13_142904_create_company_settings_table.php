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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // nombre_parqueo
            $table->string('business_activity')->nullable(); // actividad_empresa
            $table->string('branch')->nullable(); // sucursal
            $table->string('address'); // direccion
            $table->string('zone')->nullable(); // zona
            $table->string('phone')->nullable(); // telefono
            $table->string('city')->nullable(); // departamento_ciudad
            $table->string('country')->nullable(); // pais

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_settings');
    }
};
