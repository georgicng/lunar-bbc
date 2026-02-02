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
        Schema::create($this->prefix .'city_shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained($this->prefix . 'cities');
            $table->foreignId('state_id')->nullable()->constrained($this->prefix . 'states');
            $table->foreignId('country_id')->nullable()->constrained($this->prefix . 'countries');
            $table->string('name');
            $table->float('rate')->default('0.00');
            $table->boolean('status')->default(1);
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_shippings');
    }
};
