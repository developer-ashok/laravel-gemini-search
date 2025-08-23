# Laravel Gemini Search

AI-powered natural language database search using Google Gemini in Laravel.

## Requirements

- **PHP**: ^8.2
- **Laravel**: ^11.0
- **Google Gemini API**: Active API key

## Dependencies

This package automatically includes the Google Gemini API PHP client as a dependency. **No manual installation required!**

When you install our package, Composer will automatically install:
- `coderubix/laravel-gemini-search` (our package)
- `gemini-api-php/client` (Google Gemini API client)
- All required dependencies

## Installation

Simply install our package:

```bash
composer require coderubix/laravel-gemini-search
```

**That's it!** All dependencies are automatically resolved.

### Publish Configuration

```bash
php artisan vendor:publish --tag=gemini-search-config
```

### Add API Key

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

## Configuration

You can customize these settings in your `.env` file:

```env
GEMINI_API_KEY=your-api-key
GEMINI_MODEL=gemini-1.5-flash
GEMINI_MAX_TOKENS=1000
GEMINI_TEMPERATURE=0.1
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

## Troubleshooting

### Common Issues

1. **"Class Gemini\Client not found"**: Make sure `gemini-api-php/client` is installed
2. **"Invalid API key"**: Verify your `GEMINI_API_KEY` in `.env`
3. **"Unsafe query generated"**: The AI generated a non-SELECT query (safety feature)

### Debug Mode

Enable debug mode in your `.env`:
```env
APP_DEBUG=true
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
