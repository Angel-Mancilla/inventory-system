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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_model_id')->constrained();
            $table->foreignId('warehouse_id')->constrained();
            $table->foreignId('condition_id')->constrained();
            $table->string('sku', 60)->unique();
            $table->string('barcode', 60)->nullable();
            $table->text('description')->nullable();
            $table->integer('stock')->default(0); // cache, se recalcula desde stock_transactions
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
