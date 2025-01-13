<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProductComments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request)
    {   
        $comment = new ProductComments;
        $comment->comment = $request->comment;
        $comment->rating = $request->rating;
        $comment->product_id = $request->product_id;
        $comment->user_name = $request->user_name;
        $comment->save();

        return redirect()->back()->with('success', 'comment sent successfully');
    }

}
