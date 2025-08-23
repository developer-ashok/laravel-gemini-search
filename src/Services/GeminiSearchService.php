<?php

namespace Coderubix\GeminiSearch\Services;

use Illuminate\Support\Facades\DB;
use GeminiAPI\Client;
use GeminiAPI\GenerativeModel;

class GeminiSearchService
{
    protected $model;

    public function __construct()
    {
        $apiKey = config('gemini-search.api_key');
        $client = new Client($apiKey);
        $this->model = new GenerativeModel('gemini-1.5-flash', $client);
    }

    public function generateQuery(string $userPrompt): string
    {
        $schema = json_encode(SchemaExtractor::getSchema());

        $prompt = "You are an SQL assistant for a Laravel app. Schema: $schema. " .
                  "Task: Convert the user's request into a safe SELECT query only. " .
                  "User Request: $userPrompt. IMPORTANT: Only return raw SQL.";

        $response = $this->model->generateContent($prompt);
        return trim($response->text());
    }

    public function runSearch(string $userPrompt): array
    {
        $query = $this->generateQuery($userPrompt);

        if (!QueryValidator::isSafe($query)) {
            throw new \Exception("Unsafe query generated!");
        }

        $results = DB::select($query);

        return [
            'query' => $query,
            'results' => $results,
            'suggestions' => $this->generateSuggestions($userPrompt),
        ];
    }

    private function generateSuggestions(string $userPrompt): array
    {
        $response = $this->model->generateContent(
            "Based on the user request '$userPrompt', suggest 3 related search queries (short phrases)."
        );
        return array_filter(explode("\n", trim($response->text())));
    }
}
