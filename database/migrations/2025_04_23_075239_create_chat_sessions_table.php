<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Якщо є авторизація
            $table->string('guest_token')->nullable(); // Якщо користувач не зареєстрований
            $table->unsignedBigInteger('consultant_id')->nullable(); // Може бути null, поки консультант не приєднався
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
