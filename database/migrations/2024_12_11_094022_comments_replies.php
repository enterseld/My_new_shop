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
        Schema::create('comments_replies', function (Blueprint $table) {
            $table->id();
            $table->string('reply');
            $table->unsignedBigInteger('product_comments_id');
            $table->string('user_name');
            // Define the foreign key relationship and cascading delete
            $table->foreign('product_comments_id')
                ->references('id')
                ->on('product_comments')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments_replies');
    }
};
