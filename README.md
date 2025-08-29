# Laravel Gemini Search

AI-powered natural language database search using Google Gemini in Laravel with **multi-query support** and **intelligent result descriptions**.

## Requirements

- **PHP**: ^8.2
- **Laravel**: ^11.0
- **Google Gemini API**: Active API key

## Dependencies

This package automatically includes the Google Gemini API PHP client as a dependency. **No manual installation required!**

When you install our package, Composer will automatically install:
- `coderubix/laravel-gemini-search` (our package)
- `google-gemini-php/client` (Google Gemini API client)
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

$results = GeminiSearch::runSearch("Find all active users and count total orders");
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
    "query": "Find users who registered this month and count their orders"
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

## Features

- **Natural Language Processing**: Convert plain English to SQL queries
- **AI-Powered**: Uses Google Gemini 1.5 Flash model for intelligent query generation
- **Multi-Query Support**: Handles complex requests with multiple SQL queries
- **Performance Optimized**: Automatically limits to 5 queries for optimal speed
- **SQL Injection Protection**: Only allows SELECT queries with built-in validation
- **Smart Descriptions**: AI-generated descriptions for each result (SQL hidden from users)
- **Auto-suggestions**: Generates related search suggestions
- **Schema Awareness**: Automatically detects and uses your database structure
- **Structured Responses**: Clean, professional JSON output format

## Response Format

The package now returns structured JSON responses with AI-generated descriptions:

```json
{
  "summary": "Processed 3 queries for user and order information",
  "performance_note": "Limited to 5 queries for optimal speed",
  "results": [
    {
      "description": "Count of total users in the system",
      "data": [{"count": 150}],
      "type": "count",
      "safe": true
    },
    {
      "description": "List of active users with registration dates",
      "data": [{"id": 1, "name": "John Doe", "active": true}],
      "type": "select",
      "safe": true
    }
  ],
  "suggestions": [
    "Show users with recent orders",
    "Find inactive users",
    "List users by order count"
  ],
  "total_queries_processed": 2,
  "queries_ignored": 0,
  "all_safe": true
}
```

### Key Benefits:

✅ **SQL Queries Hidden**: End users never see actual SQL syntax
✅ **AI Descriptions**: Natural language explanations of each result
✅ **Multi-Query Support**: Handle complex requests efficiently
✅ **Performance Control**: 5-query limit ensures optimal speed
✅ **Professional Output**: Clean, structured JSON responses

## Testing

### Package Testing

Run the test suite:

```bash
composer test
```

Or with PHPUnit directly:

```bash
./vendor/bin/phpunit
```

### Local Testing

For development and testing, you can use our enhanced test script:

```bash
# Install dependencies
composer require google-gemini-php/client

# Create .env with your API key
echo "GEMINI_API_KEY=your-real-api-key" > .env

# Run comprehensive tests including multi-query functionality
php test-package.php
```

## Security

The package includes built-in SQL injection protection by:
- Only allowing SELECT queries
- Validating query types before execution
- Using parameterized queries where possible
- AI prompt optimization for clean SQL output
- Multi-query validation and safety checks

## Performance

- **Query Limit**: Maximum 5 queries per request for optimal performance
- **Smart Parsing**: Efficient query parsing and validation
- **Error Handling**: Graceful handling of failed queries
- **Performance Notes**: Automatic warnings when limits are reached
- **Configurable Limit**: Set via `MAX_QUERIES` constant in service class

## Troubleshooting

### Common Issues

1. **"Class Gemini\Client not found"**: The package will automatically install `google-gemini-php/client`
2. **"Invalid API key"**: Verify your `GEMINI_API_KEY` in `.env` (no quotes needed)
3. **"Unsafe query generated"**: The AI generated a non-SELECT query (safety feature)
4. **"Target class [config] does not exist"**: This is normal in standalone testing, will work in Laravel
5. **Multiple queries ignored**: Performance optimization limits to 5 queries (check performance_note)

### Debug Mode

Enable debug mode in your `.env`:
```env
APP_DEBUG=true
```


---

## 📞 Contact Information

**Developer:** Ashok Chandrapal 👨‍💻  
**Phone:** +91 9033359874 📱  
**Email:** developer7039@gmail.com ✉️  
**GitHub:** [github.com/developer-ashok](https://github.com/developer-ashok) 🐙  
**LinkedIn:** [linkedin.com/in/ashok-chandrapal](https://linkedin.com/in/ashok-chandrapal) 💼

---


## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Submit a pull request

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
