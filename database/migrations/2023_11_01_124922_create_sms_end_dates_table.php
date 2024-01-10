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
        Schema::create('sms_end_dates', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('type');
            $table->date('type_date');
            $table->integer('is_send');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_end_dates');
    }
};
