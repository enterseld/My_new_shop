<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['total_price', 'status', 'session_id', 'mobile_phone', 'shipping_city', 'shipping_warehouse', 'created_by', 'updated_by'];
    use HasFactory;
    function order_items()  {
        return $this->hasMany(OrderItem::class);
    }
    function order()  {
        return $this->belongsTo(User::class);
    }
}