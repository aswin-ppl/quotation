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
        // Add quotation_id to products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->onDelete('cascade')->after('address_id');
        });

        // Remove product_id from quotations table and keep quotation_number
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            $table->dropColumn('quotation_id');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->after('quotation_number');
        });
    }
};
