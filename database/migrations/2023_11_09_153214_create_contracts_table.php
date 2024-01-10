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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('period')->default(0);
            $table->date('date')->nullable();
            $table->string('card_image')->nullable();
            $table->longText('details')->nullable();     
            $table->string('type')->nullable();
            $table->double('amount')->default(0);
            $table->foreignIdFor(\App\Models\Contract\ContractType::class)->nullable();
            $table->foreignIdFor(\App\Models\Client::class)->nullable();
            $table->foreignIdFor(\App\Models\Hr\Branch::class);
            $table->foreignIdFor(\App\Models\Hr\Management::class);
            $table->foreignIdFor(\App\Models\Status::class)->nullable();
            $table->foreignIdFor(\App\Models\Employee\Employee::class)->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
