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
        Schema::create($this->prefix . 'product_customisations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained($this->prefix . 'products');
            $table->foreignId('attribute_id')->constrained($this->prefix . 'attributes');
            $table->json('attribute_data');
            $table->integer('position')->default(0);
            $table->boolean('required')->default(false);
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->prefix .'product_customisation');
    }
};
