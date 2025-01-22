<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function store(Request $request)
    {   

        $order = new Order();
        $order->total_price = $request->total_price;
        $order->status = "Open";
        $order->session_id = "1";
        $order->mobile_phone = $request->mobile_phone;
        $order->shipping_city = $request->shipping_city;
        $order->shipping_warehouse = $request->shipping_warehouse;
        $order->created_by = $request->user_id;
        $order->updated_by = $request->user_id;
        $order->save();

        return redirect()->back()->with('success', 'Order sent successfully!');
    }
}
