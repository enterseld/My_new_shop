<?php

namespace App\Http\Controllers\Admin;

use App\Helper\ProductHelper;
use App\Http\Controllers\Controller;
use App\Models\CommentsReplies;
use App\Models\ProductComments;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminCommentsController extends Controller
{
    public function index()
    {
        $comments = ProductComments::get()->where("published", "!=", "1");
        $replies = CommentsReplies::get()->where("published", "!=", "1");


        return Inertia::render('Admin/Comments/CommentsReplies', 
        ['comments' => $comments, 
        'replies' => $replies]);
    }

    public function update(Request $request, $id)
    {
        
        if($request->type == "Comment"){
            $comment = ProductComments::findOrFail($id);
            $comment->published = 1;
            

            $rating = ProductComments::where("published", 1)->where("product_id", $request->product_id) ->sum("rating")/ProductComments::where("published", 1)->where("product_id", $request->product_id)->count("rating");

            ProductHelper::setRating($request, $request->product_id, $rating);
            $comment->update();
            return redirect()->route('admin.comments.index')->with('success', 'Comment updated successfully.');
        }
        if($request->type == "Reply"){
            $reply = CommentsReplies::findOrFail($id);
            $reply->published = 1;
            $reply->update();
            return redirect()->route('admin.comments.index')->with('success', 'Comment updated successfully.');
        }
    }


}
