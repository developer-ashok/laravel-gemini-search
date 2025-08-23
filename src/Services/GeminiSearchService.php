<?php

namespace Ashok\GeminiSearch\Services;

use Illuminate\Support\Facades\DB;
use Gemini\Facades\Gemini;

class GeminiSearchService
{
    public function generateQuery(string $userPrompt): string
    {
        $schema = json_encode(SchemaExtractor::getSchema());

        $prompt = "You are an SQL assistant for a Laravel app. Schema: $schema. " .
                  "Task: Convert the user's request into a safe SELECT query only. " .
                  "User Request: $userPrompt. IMPORTANT: Only return raw SQL.";

        $response = Gemini::generateText($prompt);
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
        $response = Gemini::generateText(
            "Based on the user request '$userPrompt', suggest 3 related search queries (short phrases)."
        );
        return array_filter(explode("\n", trim($response->text())));
    }
}
