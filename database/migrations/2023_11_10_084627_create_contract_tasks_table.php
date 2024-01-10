<?php

use App\Models\Contract\Contract;
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
        Schema::create('contract_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('period')->default(0);
            $table->double('amount')->default(0);
            $table->foreignIdFor(\App\Models\Employee\Employee::class)->nullable();
            $table->foreignIdFor(Contract::class)->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_tasks');
    }
};
