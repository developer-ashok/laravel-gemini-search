# Laravel Gemini Search

AI-powered natural language database search using Google Gemini in Laravel.

## Requirements

- **PHP**: ^8.2
- **Laravel**: ^11.0
- **Google Gemini API**: Active API key

## Installation

```bash
composer require coderubix/laravel-gemini-search
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=gemini-search-config
```

Add your Gemini API key to your `.env` file:

```env
GEMINI_API_KEY=your-api-key-here
```

## Usage

### Basic Search

```php
use Coderubix\GeminiSearch\Facades\GeminiSearch;

$results = GeminiSearch::runSearch("Find all active users");
```

### Service Injection

```php
use Coderubix\GeminiSearch\Services\GeminiSearchService;

class UserController extends Controller
{
    public function search(Request $request, GeminiSearchService $searchService)
    {
        $results = $searchService->runSearch($request->input('query'));
        return response()->json($results);
    }
}
```

## API Endpoint

The package automatically registers a route at `/api/search`:

```bash
POST /api/search
{
    "query": "Find users who registered this month"
}
```

## Testing

Run the test suite:

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

## Security

The package includes built-in SQL injection protection by:
- Only allowing SELECT queries
- Validating query types before execution
- Using parameterized queries where possible

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
