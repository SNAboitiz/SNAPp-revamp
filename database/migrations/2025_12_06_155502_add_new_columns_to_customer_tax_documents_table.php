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
        Schema::table('customer_tax_documents', function (Blueprint $table) {
            $table->string('billing_period')->after('facility_id');
            $table->string('official_receipt')->after('billing_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_tax_documents', function (Blueprint $table) {
            $table->dropColumn('billing_period');
            $table->dropColumn('official_receipt');
        });
    }
};
