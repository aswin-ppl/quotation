<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();

            // link to customer
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');

            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();

            // relational columns instead of plain strings
            $table->foreignId('city_id')->constrained('cities')->onDelete('restrict');
            $table->foreignId('district_id')->constrained('districts')->onDelete('restrict');
            $table->foreignId('state_id')->constrained('states')->onDelete('restrict');
            $table->foreignId('pincode_id')->constrained('pincodes')->onDelete('restrict');

            $table->string('country')->default('Unknown');
            $table->enum('type', ['home', 'work', 'billing', 'shipping'])->default('home');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
