<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoriteProducts extends Model
{
    protected $fillable = ['product_id', 'user_id'];
    use HasFactory;
    function product()  {
        return $this->belongsTo(Product::class);
    }
    function user()  {
        return $this->belongsTo(User::class);
    }
}
