<?php

use App\Models\Contract\OrderStep;
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
        Schema::create('order_step_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('status');
            $table->string('note');
            $table->foreignIdFor(OrderStep::class);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_step_forms');
    }
};
