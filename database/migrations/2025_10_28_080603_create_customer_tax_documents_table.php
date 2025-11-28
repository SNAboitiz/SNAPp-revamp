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
        Schema::create('customer_tax_documents', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('facility_id')->nullable();

            $table->string('document_number');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->nullOnDelete();

            $table->foreign('facility_id')
                ->references('id')->on('facilities')
                ->nullOnDelete();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_tax_documents');
    }
};
