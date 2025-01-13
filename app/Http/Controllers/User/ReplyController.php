<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CommentsReplies;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request)
    {   
        $reply = new CommentsReplies();
        $reply->reply = $request->reply;
        $reply->product_comments_id = $request->comment_id;
        $reply->user_name = $request->user_name;
        $reply->save();

        return redirect()->back()->with('success', 'reply sent successfully');
    }
}
