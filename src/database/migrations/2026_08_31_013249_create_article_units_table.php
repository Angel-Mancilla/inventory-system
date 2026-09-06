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
        Schema::create('article_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained();
            $table->foreignId('sale_article_id')->nullable()->constrained();
            $table->string('serial_number', 100)->nullable()->unique();
            $table->decimal('purchase_cost', 10, 2);
            $table->enum('status', ['in_stock', 'sold', 'returned', 'repair', 'damaged'])->default('in_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_units');
    }
};
