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
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('warehouse')->default("")->after('city');
            $table->dropColumn('type');
            $table->dropColumn('address1');
            $table->dropColumn('address2');
            $table->dropColumn('state');
            $table->dropColumn('zipcode');
            $table->dropColumn('country_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn('warehouse');
        });
    }
};
