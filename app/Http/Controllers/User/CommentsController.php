<?php

namespace App\Http\Controllers\User;

use App\Helper\ProductHelper;
use App\Http\Controllers\Controller;
use App\Models\CommentsReplies;
use App\Models\ProductComments;

use Illuminate\Http\Request;
use Inertia\Inertia;

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

        $rating = ProductComments::sum("rating")->where("published", "==", "1")/ProductComments::count("rating")->where("published", "==", "1");

        ProductHelper::setRating($request, $comment->product_id, $rating);

        return redirect()->back()->with('success', 'comment sent successfully');
    }

}
