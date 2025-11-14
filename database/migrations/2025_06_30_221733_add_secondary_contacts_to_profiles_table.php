<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('contact_name_1')->nullable()->after('mobile_number');
            $table->string('designation_1')->nullable()->after('contact_name_1');
            $table->string('mobile_number_1')->nullable()->after('designation_1');
            $table->string('email_1')->nullable()->after('mobile_number_1');
            $table->string('account_executive')->nullable()->after('email_1');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name_1',
                'designation_1',
                'mobile_number_1',
                'email_1',
                'account_executive',
            ]);
        });
    }
};
