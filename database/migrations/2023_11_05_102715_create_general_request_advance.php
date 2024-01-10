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
        Schema::create('general_request_advance', function (Blueprint $table) {
            $table->id();
            $table->date('exchange_date');
            $table->double('amount');
            $table->longText('description');
            $table->string('duration');
            $table->unsignedBigInteger('employee_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_request_advance');
    }
};
