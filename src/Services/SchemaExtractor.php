<?php

namespace Ashok\GeminiSearch\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaExtractor
{
    public static function getSchema(): array
    {
        $tables = DB::select('SHOW TABLES');
        $schema = [];

        foreach ($tables as $tableObj) {
            $table = array_values((array)$tableObj)[0];
            $columns = Schema::getColumnListing($table);
            $schema[$table] = $columns;
        }

        return $schema;
    }
}
