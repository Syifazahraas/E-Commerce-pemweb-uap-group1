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
        Schema::table('product_reviews', function (Blueprint $table) {
            // Add user_id first, nullable initially to handle existing data
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('cascade');
            
            // Make transaction_id nullable
            $table->foreignId('transaction_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            // Revert transaction_id to not null (might fail if we have nulls, but fine for dev)
            $table->foreignId('transaction_id')->nullable(false)->change();
        });
    }
};
