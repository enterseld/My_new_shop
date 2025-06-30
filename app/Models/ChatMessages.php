<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    protected $fillable = ['session_id', 'message'];

    use HasFactory;
    
    

    public function chatSessions()
    {
        return $this->belongsTo(ChatSessions::class);
    }
}
