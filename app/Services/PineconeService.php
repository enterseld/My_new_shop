<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PineconeService
{
    protected string $apiKey;
    protected string $indexName;
    protected string $region;

    public function __construct()
    {
        $this->apiKey = env('PINECONE_API_KEY');
        $this->indexName = env('PINECONE_INDEX_NAME', 'test-chat');
        $this->region = env('PINECONE_REGION', 'us-east-1');
    }

    public function query(array $vector, int $topK = 5)
    {
        $url = "https://test-chat-ree2g3y.svc.aped-4627-b74a.pinecone.io/query";

        $response = Http::withHeaders([
            'Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vector' => $vector,
            'topK' => $topK,
            'includeMetadata' => true,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Pinecone query failed: ' . $response->body());
    }

    public function query_tos(array $vector, int $topK = 3)
    {
        $url = "https://tos-question-ree2g3y.svc.aped-4627-b74a.pinecone.io/query";

        $response = Http::withHeaders([
            'Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vector' => $vector,
            'topK' => $topK,
            'includeMetadata' => true,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Pinecone query failed: ' . $response->body());
    }
}
