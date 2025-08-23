<?php

namespace Coderubix\GeminiSearch\Http\Controllers;

use Illuminate\Http\Request;
use Coderubix\GeminiSearch\Services\GeminiSearchService;

class SearchController
{
    public function search(Request $request, GeminiSearchService $service)
    {
        $prompt = $request->input('query');
        $response = $service->runSearch($prompt);
        return response()->json($response);
    }
}
