<?php

namespace Coderubix\GeminiSearch\Services;

class QueryValidator
{
    public static function isSafe(string $query): bool
    {
        $query = strtolower(trim($query));
        return str_starts_with($query, 'select');
    }
}
