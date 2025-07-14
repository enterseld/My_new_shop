<?php

namespace App\Http\Controllers;

use App\Providers\PineconeService;
use Illuminate\Http\Request;

class PineconeSearchController extends Controller
{
    public function search(Request $request, PineconeService $pinecone)
    {
        $vector = $request->input('vector');
        if (!is_array($vector)) {
            return response()->json(['error' => 'Vector is required'], 422);
        }

        try {
            $results = $pinecone->query($vector);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function search_tos(Request $request, PineconeService $pinecone)
    {
        $vector = $request->input('vector');
        if (!is_array($vector)) {
            return response()->json(['error' => 'Vector is required'], 422);
        }

        try {
            $results = $pinecone->query_tos($vector);
            return response()->json($results);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
