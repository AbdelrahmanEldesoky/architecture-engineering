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
        Schema::table('employee_infos', function (Blueprint $table) {
          $table->string('bank_account')->nullable()->after('end_medical_insurance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_infos', function (Blueprint $table) {
            $table->dropColumn('bank_account');
        });
    }
};
