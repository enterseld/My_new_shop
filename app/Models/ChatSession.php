<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatSession extends Model
{
    protected $fillable = ['user_id', 'quantity', 'product_id', 'guest_token'];

    use HasFactory;

    public function chatMessages()
    {
        return $this->hasMany(ChatMessages::class, 'session_id');
    }

}
