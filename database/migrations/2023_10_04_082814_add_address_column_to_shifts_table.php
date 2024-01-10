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
        Schema::table('shifts', function (Blueprint $table) {
            $table->decimal('latitude',12,9)->nullable()->after('timezone');
            $table->decimal('longitude',12,9)->nullable()->after('latitude');
            $table->string('address')->nullable()->after('longitude');
            $table->string('type')->default('general')->after('note');
            $table->decimal('overtime_cost',6,2)->nullable()->default(0)->after('note');
            $table->decimal('late_cost',6,2)->nullable()->default(0)->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('address');
            $table->dropColumn('type');
            $table->dropColumn('overtime_cost');
            $table->dropColumn('late_cost');
        });
    }
};
