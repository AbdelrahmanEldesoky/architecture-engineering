<?php

use App\Models\Employee\Employee;
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
        Schema::create('order_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Hr\Management::class);
            $table->foreignIdFor(Employee::class);
            $table->integer('status');
            $table->integer('period')->default(0);
            $table->integer('form_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_steps');
    }
};
