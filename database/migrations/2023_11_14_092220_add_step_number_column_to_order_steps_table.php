<?php

use App\Models\Client;
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
        Schema::table('order_step_forms', function (Blueprint $table) {
          $table->integer('step_number')->default(0)->after('order_step_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('order_step_forms', function (Blueprint $table) {
            $table->dropColumn('step_number');
        });
    }
};
