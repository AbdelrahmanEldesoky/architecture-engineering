<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Projects\Entities\Contract;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contract_levers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('period')->default(0);
            $table->string('type');
            $table->longText('card_image');
            $table->foreignIdFor(Contract::class);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_levers');
    }
};
