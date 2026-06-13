<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Actualizamos la columna para que acepte 'ANULADO' usando SQL puro
        // (ya que modificar ENUMs con Laravel nativo a veces da problemas)
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('ACTIVO', 'FINALIZADO', 'ANULADO') DEFAULT 'ACTIVO'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN status ENUM('ACTIVO', 'FINALIZADO') DEFAULT 'ACTIVO'");
    }
};
