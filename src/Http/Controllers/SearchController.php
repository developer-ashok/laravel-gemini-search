<?php

namespace Ashok\GeminiSearch\Http\Controllers;

use Illuminate\Http\Request;
use Ashok\GeminiSearch\Services\GeminiSearchService;

class SearchController
{
    public function search(Request $request, GeminiSearchService $service)
    {
        $prompt = $request->input('query');
        $response = $service->runSearch($prompt);
        return response()->json($response);
    }
}
