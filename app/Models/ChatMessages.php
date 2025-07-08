<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    protected $fillable = ['session_id', 'message'];

    use HasFactory;
    
    

    public function chatSession()
    {
        return $this->belongsTo(ChatSession::class, 'session_id');
    }
}
