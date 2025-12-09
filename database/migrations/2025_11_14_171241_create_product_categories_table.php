<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();

            // kategori per toko
            $table->foreignId('store_id')
                  ->constrained('stores')
                  ->cascadeOnDelete(); // kalau toko dihapus, kategori ikut hilang

            // parent kategori (opsional)
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('product_categories')
                  ->cascadeOnDelete();

            $table->string('image')->nullable();
            $table->string('name');

            // slug harus unique berdasarkan toko, bukan global
            $table->string('slug');

            $table->string('tagline')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            // slug unique per store
            $table->unique(['store_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
