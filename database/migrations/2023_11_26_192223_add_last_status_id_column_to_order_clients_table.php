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
          $table->integer('last_status_id')->default(0)->after('form_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('order_clients', function (Blueprint $table) {
            $table->dropColumn('last_status_id');
        });
    }
};
