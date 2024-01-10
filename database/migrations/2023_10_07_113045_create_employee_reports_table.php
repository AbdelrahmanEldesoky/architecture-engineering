<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Employee\Employee;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class);
            $table->string('start_in')->nullable();
            $table->string('end_in')->nullable();
            $table->string('check_in')->nullable();
            $table->string('check_out')->nullable();
            $table->double('work_hours')->default(0);
            $table->double('hourly_value')->default(0);
            $table->double('late_hours')->default(0);
            $table->double('late_hour_value')->default(0);
            $table->double('overtime_hours')->default(0);
            $table->double('overtime_hour_value')->default(0);
            $table->double('total')->default(0);
            $table->string('currency')->default('EGP');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_reports');
    }
};
