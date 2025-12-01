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
        Schema::table('quotations', function (Blueprint $table) {
            // Drop all foreign key constraints first
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['created_by']);
            
            // Drop all the old columns we don't need
            $table->dropColumn(['date', 'expiry', 'customer_id', 'sub_total', 'discount', 'tax', 'grand_total', 'status', 'notes', 'created_by']);
        });

        Schema::table('quotations', function (Blueprint $table) {
            // Add the new columns
            $table->string('quotation_number')->unique()->after('id');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade')->after('quotation_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['quotation_number', 'product_id']);
        });

        Schema::table('quotations', function (Blueprint $table) {
            // Restore the old columns
            $table->date('date');
            $table->date('expiry')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->decimal('sub_total', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        });
    }
};
