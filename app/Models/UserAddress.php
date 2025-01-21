<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{   
    protected $fillable = ['city', 'warehouse', 'isMain', 'user_id'];
    use HasFactory;

    function user() {
        return $this->belongsTo(User::class);
    }
}