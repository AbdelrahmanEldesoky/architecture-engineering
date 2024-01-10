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
        Schema::create('general_request_steps_of_approval', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('general_request_id');
            $table->unsignedBigInteger('steps_of_approval_id');
            $table->unsignedBigInteger('alternative_employee_id')->nullable();
            $table->unsignedBigInteger('note')->nullable();
            $table->tinyInteger('status');//pending -1

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genaral_request_steps_of_approval');
    }
};
