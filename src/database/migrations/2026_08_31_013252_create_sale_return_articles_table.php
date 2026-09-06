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
        Schema::create('sale_return_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained();
            $table->foreignId('sale_article_id')->constrained();
            $table->integer('quantity');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->text('return_reason')->nullable();
            $table->boolean('warranty_accepted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_return_articles');
    }
};
