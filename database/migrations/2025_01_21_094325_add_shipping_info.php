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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('mobile_phone')->default("")->after('session_id');
            $table->string('shipping_city')->default("")->after('mobile_phone');
            $table->string('shipping_warehouse')->default("")->after('shipping_city');
            $table->dropForeign(['user_address_id']);
            $table->dropColumn('user_address_id');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('mobile_phone');
            $table->dropColumn('shipping_city');
            $table->dropColumn('shipping_warehouse');
            $table->dropColumn('user_id');
        });
    }
};
