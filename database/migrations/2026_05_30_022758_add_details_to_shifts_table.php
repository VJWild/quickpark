<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->json('system_details')->nullable()->after('system_amount');
            $table->json('declared_details')->nullable()->after('declared_amount');
            $table->json('differences_details')->nullable()->after('difference');
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn(['system_details', 'declared_details', 'differences_details']);
        });
    }
};
