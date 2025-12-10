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
        Schema::create('products', function (Blueprint $table) {
            $table->id()->primary();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->enum('condition', ['new', 'second']);
            $table->decimal('price', 26, 2);
            $table->integer('weight');
            $table->integer('stock');
            // Support for Shoe Store
            $table->string('material')->nullable();
            $table->json('sizes')->nullable(); // Stores ["39", "40", "41"]
            $table->boolean('is_on_sale')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
