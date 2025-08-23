<?php

namespace Coderubix\GeminiSearch\Services;

use Illuminate\Support\Facades\DB;
use Gemini\Client;
use Gemini\Resources\GenerativeModel;

class GeminiSearchService
{
    protected $model;
    protected const MAX_QUERIES = 5;

    public function __construct()
    {
        $apiKey = config('gemini-search.api_key');
        
        if (empty($apiKey)) {
            throw new \Exception(
                'Gemini API key not found. Please set GEMINI_API_KEY in your .env file and publish the config with: php artisan vendor:publish --tag=gemini-search-config'
            );
        }
        
        $client = \Gemini::client($apiKey);
        $this->model = $client->generativeModel('gemini-1.5-flash');
    }

    public function generateQuery(string $userPrompt): string
    {
        $schema = json_encode(SchemaExtractor::getSchema());

        $prompt = "You are an SQL assistant for a Laravel app. Schema: $schema. " .
                  "Task: Convert the user's request into safe SELECT queries only. " .
                  "User Request: $userPrompt. IMPORTANT: Only return raw SQL. Return only final query raw query no prefix or postfix";

        $response = $this->model->generateContent($prompt);
        return trim($response->text());
    }

    public function runSearch(string $userPrompt): array
    {
        $rawResponse = $this->generateQuery($userPrompt);
        
        // Parse multiple queries and limit to 5
        $queries = $this->parseQueries($rawResponse);
        $limitedQueries = array_slice($queries, 0, self::MAX_QUERIES);
        $queriesIgnored = count($queries) - count($limitedQueries);
        
        $results = [];
        $allSafe = true;
        
        foreach ($limitedQueries as $index => $query) {
            if (!QueryValidator::isSafe($query)) {
                $allSafe = false;
                $results[] = [
                    'description' => "Query " . ($index + 1) . " - Security validation failed",
                    'data' => [],
                    'type' => 'error',
                    'error' => 'Unsafe query detected'
                ];
                continue;
            }
            
            try {
                $queryResults = DB::select($query);
                $description = $this->generateQueryDescription($query, $userPrompt, $index + 1);
                
                $results[] = [
                    'description' => $description,
                    'data' => $queryResults,
                    'type' => $this->determineQueryType($query),
                    'safe' => true
                ];
            } catch (\Exception $e) {
                $allSafe = false;
                $results[] = [
                    'description' => "Query " . ($index + 1) . " - Execution failed",
                    'data' => [],
                    'type' => 'error',
                    'error' => $e->getMessage()
                ];
            }
        }
        
        $suggestions = $this->generateSuggestions($userPrompt);
        
        return [
            'summary' => $this->generateSummary($limitedQueries, $userPrompt),
            'performance_note' => $queriesIgnored > 0 ? "Limited to " . self::MAX_QUERIES . " queries for optimal speed. " . $queriesIgnored . " additional queries ignored." : null,
            'results' => $results,
            'suggestions' => $suggestions,
            'total_queries_processed' => count($limitedQueries),
            'queries_ignored' => $queriesIgnored,
            'all_safe' => $allSafe
        ];
    }

    private function parseQueries(string $rawResponse): array
    {
        // Split by semicolons and clean up
        $queries = array_map('trim', explode(';', $rawResponse));
        
        // Filter out empty queries and non-SQL content
        $queries = array_filter($queries, function($query) {
            $query = trim($query);
            return !empty($query) && stripos($query, 'SELECT') === 0;
        });
        
        return array_values($queries);
    }

    private function generateQueryDescription(string $query, string $userPrompt, int $queryNumber): string
    {
        $prompt = "Given this SQL query: '$query' and user request: '$userPrompt', " .
                  "generate a brief, user-friendly description of what this query does. " .
                  "Keep it under 100 characters. Focus on business value, not technical details.";
        
        try {
            $response = $this->model->generateContent($prompt);
            return trim($response->text());
        } catch (\Exception $e) {
            return "Query " . $queryNumber . " - Data retrieval operation";
        }
    }

    private function generateSummary(array $queries, string $userPrompt): string
    {
        $queryCount = count($queries);
        $prompt = "Given these " . $queryCount . " queries and user request: '$userPrompt', " .
                  "generate a brief summary (under 150 characters) of what was accomplished. " .
                  "Focus on business value, not technical details.";
        
        try {
            $response = $this->model->generateContent($prompt);
            return trim($response->text());
        } catch (\Exception $e) {
            return "Processed " . $queryCount . " queries for your request";
        }
    }

    private function determineQueryType(string $query): string
    {
        $query = strtolower($query);
        
        if (strpos($query, 'count(') !== false) {
            return 'count';
        } elseif (strpos($query, 'distinct') !== false) {
            return 'distinct';
        } elseif (strpos($query, 'limit') !== false) {
            return 'limited';
        } else {
            return 'select';
        }
    }

    private function generateSuggestions(string $userPrompt): array
    {
        try {
            $response = $this->model->generateContent(
                "Based on the user request '$userPrompt', suggest 3 related search queries (short phrases, under 50 characters each). " .
                "Focus on business value and user intent. Return only the suggestions, one per line."
            );
            return array_filter(explode("\n", trim($response->text())));
        } catch (\Exception $e) {
            return [
                "Try refining your search terms",
                "Use more specific criteria",
                "Explore related data categories"
            ];
        }
    }
}
