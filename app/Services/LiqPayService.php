<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;


class LiqPayService
{
    protected string $publicKey;
    protected string $privateKey;

    public function __construct()
    {
        $this->publicKey = config('services.liqpay.public_key');
        $this->privateKey = config('services.liqpay.private_key');
    }

    public function generateCheckoutPayload(float $amount, int $order_id): array
    {
        $params = [
            'action'         => 'pay',
            'version'        => '7',
            'public_key'     => config('services.liqpay.public_key'),
            'amount'         => $amount,
            'currency'       => 'UAH',
            'description'    => 'Оплата замовлення №' . $order_id,
            'order_id'       => $order_id,
            'receiver_last_name'  => 'LastName',
            'receiver_first_name'  => 'FirstName'
        ];

        $json = json_encode($params);
        $data = base64_encode($json);
        $signature = base64_encode(sha1($this->privateKey . $data . $this->privateKey, true));

        return [
            'data' => $data,
            'signature' => $signature,
        ];
    }

    public function validateSignature(string $data, string $signature): bool
    {
        $expected = base64_encode(sha1($this->privateKey . $data . $this->privateKey, true));
        return $expected === $signature;
    }

    public function decodeCallbackData(string $data): array
    {
        return json_decode(base64_decode($data), true);
    }
}
