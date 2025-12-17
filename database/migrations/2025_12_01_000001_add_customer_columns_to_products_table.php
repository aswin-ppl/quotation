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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id')->nullable()->after('product_price');
            $table->unsignedBigInteger('address_id')->nullable()->after('customer_id');

            // Foreign key constraint for customer_id
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('set null');

            // Foreign key constraint for address_id
            $table->foreign('address_id')
                ->references('id')
                ->on('customer_addresses')
                ->onDelete('set null');

            // Index for faster queries
            $table->index('customer_id');
            $table->index('address_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['address_id']);
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['address_id']);
            $table->dropColumn(['customer_id', 'address_id']);
        });
    }
};
