<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\FavoriteProducts;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteProductsController extends Controller
{
    public function index(Request $request, $user_id)
    {
        $user = User::find($user_id);

        return response()->json([
            'favorites' => $user->favorites
        ]);
    }

    public function store(Request $request)
    {
        $product = new FavoriteProducts;
        // Check if a record with the same user_id and product_id already exists
        $existingProduct = FavoriteProducts::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($existingProduct) {
            // Handle the case where the product already exists
            return redirect()->route('product.show', $request->product_id);
        } else {
            // No existing product, proceed to save
            $product->user_id = $request->user_id;
            $product->product_id = $request->product_id;
            $product->save();
            return redirect()->route('product.show', $product->product_id);
        }
    }

    public function delete(Request $request)
    {
        $product = FavoriteProducts::where("product_id", $request->product_id)->where("user_id", $request->user_id);
        $product->delete();
        return redirect()->route('product.show', $request->product_id);
    }
}
