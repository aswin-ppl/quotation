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
        Schema::create('downloaded_quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->foreignId('downloaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('download_count')->default(1);
            $table->string('download_ip', 45)->nullable();
            $table->timestamp('downloaded_at')->useCurrent();
            $table->text('file_path')->nullable();
            $table->string('file_format', 10)->default('pdf');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloaded_quotations');
    }
};
