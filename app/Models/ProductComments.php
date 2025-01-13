<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComments extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'comment',
        'rating',];

    function product() {
        return $this->belongsTo(Product::class);
    }

    public function comments_replies()
    {
        return $this->hasMany(CommentsReplies::class);
    }
}
