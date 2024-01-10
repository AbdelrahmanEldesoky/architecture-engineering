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
        Schema::table('order_clients', function (Blueprint $table) {
          $table->integer('management_id')->nullable()->after('period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('order_clients', function (Blueprint $table) {
            $table->dropColumn('management_id');
        });
    }
};
