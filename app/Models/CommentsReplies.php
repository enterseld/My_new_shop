<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentsReplies extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment_id',
        'reply',
    ];
    
    function product() {
        return $this->belongsTo(ProductComments::class);
    }
}
