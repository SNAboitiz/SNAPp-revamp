<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->text('business_address')->nullable();
            $table->text('facility_address')->nullable();
            $table->string('certificate_of_contestability_number')->nullable();
            $table->string('customer_category')->nullable();
            $table->string('cooperation_period_start_date')->nullable();
            $table->string('cooperation_period_end_date')->nullable();
            $table->string('contract_price')->nullable();
            $table->string('contracted_demand')->nullable();
            $table->text('other_information')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('designation')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile_number', 20)->nullable();
            $table->string('contact_name_1')->nullable();
            $table->string('designation_1')->nullable();
            $table->string('mobile_number_1')->nullable();
            $table->string('email_1')->nullable();
            $table->string('account_executive')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
