<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LiqPayController extends Controller
{   
    public function __construct(protected LiqPayService $liqpayService) {}

    public function getPaymentForm(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'order_id' => 'required|numeric|exists:orders,id',
        ]);

        $payload = $this->liqpayService->generateCheckoutPayload(
            $request->amount,
            $request->order_id
        );

        return response()->json($payload);
    }

    public function callback(Request $request)
    {
        $data = $request->input('data');
        $signature = $request->input('signature');

        // Логуємо отримані дані для налагодження
        Log::info('LiqPay callback received', [
            'data' => $data,
            'signature' => $signature
        ]);

        if (!$data || !$signature) {
            Log::error('LiqPay callback: Missing data or signature');
            return response('Bad Request', 400);
        }

        if (!$this->liqpayService->validateSignature($data, $signature)) {
            Log::error('LiqPay callback: Invalid signature');
            return response('Invalid signature', 403);
        }

        $info = $this->liqpayService->decodeCallbackData($data);
        
        if (!$info) {
            Log::error('LiqPay callback: Failed to decode data');
            return response('Invalid data format', 400);
        }

        $orderId = $info['order_id'] ?? null;
        if (!$orderId) {
            Log::error('LiqPay callback: Missing order_id in callback data');
            return response('Missing order_id', 400);
        }

        $order = Order::find($orderId);
        if (!$order) {
            Log::error('LiqPay callback: Order not found', ['order_id' => $orderId]);
            return response('Order not found', 404);
        }

        // Перевіряємо статус платежу
        $status = $info['status'] ?? null;
        if ($status === 'success') {
            $order->status = 'Paid';
            $order->save();
            
            Log::info('LiqPay callback: Payment successful', [
                'order_id' => $orderId,
                'amount' => $info['amount'] ?? 'unknown'
            ]);
        } else {
            Log::warning('LiqPay callback: Payment not successful', [
                'order_id' => $orderId,
                'status' => $status
            ]);
        }

        return response('OK');
    }
}