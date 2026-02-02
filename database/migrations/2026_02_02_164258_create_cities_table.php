<?php

use Lunar\Base\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->prefix .'cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('state_id')->nullable()->constrained($this->prefix.'states');
            $table->foreignId('country_id')->nullable()->constrained($this->prefix.'countries');
            $table->string('name');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
