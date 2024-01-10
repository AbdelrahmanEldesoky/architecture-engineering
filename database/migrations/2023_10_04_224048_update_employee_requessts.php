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
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->string('time_valid_to')->after('time_to')->nullable();
            $table->string('time_valid_in_seconds')->after('time_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->dropColumn('time_valid_to');
            $table->dropColumn('time_valid_in_seconds');
        });
    }
};
