<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_session_id');
            $table->enum('sender_type', ['user', 'consultant']);
            $table->unsignedBigInteger('sender_id')->nullable(); // ID відправника
            $table->text('message_text');
            $table->timestamps();

            $table->foreign('chat_session_id')->references('id')->on('chat_sessions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};