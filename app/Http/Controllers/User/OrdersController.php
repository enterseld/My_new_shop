<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(Request $request)
    {
        $products = json_decode($request->products, true);

        $order = new Order();
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->middle_name = $request->middle_name;
        if ($request->email) {
            $order->email = $request->email;
        }
        $order->total_price = $request->total_price;
        $order->status = "Open";
        $order->session_id = "1";
        $order->mobile_phone = $request->mobile_phone;
        $order->shipping_city = $request->shipping_city;
        $order->shipping_warehouse = $request->shipping_warehouse;
        $order->created_by = $request->user_id;
        $order->updated_by = $request->user_id;
        $order->save();


        foreach ($products as $product) {

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],

                'quantity' => $product['quantity'],

                'unit_price' => "200",
            ]);
        }

        CartItem::where(['user_id' => $request->user_id])->delete();
        Cart::deleteCookieCartItems();

        return response()->json([
            'message' => 'Order created successfully',
            'order_id' => $order->id,
        ]);
    }
}
