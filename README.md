# Laravel Gemini Search

🚀 Natural language database search for Laravel using **Google Gemini AI**.  
This package lets you query your database in plain English.  
It will automatically generate safe `SELECT` SQL queries, run them, and return results in JSON — plus suggest follow-up queries.

---

## 📦 Installation

```bash
composer require ashok/laravel-gemini-search
```

Publish the config:

```bash
php artisan vendor:publish --tag=gemini-search-config
```

---

## ⚙️ Configuration

In `.env`, set your Gemini API key:

```env
GEMINI_API_KEY=your_api_key_here
```

You can customize defaults in `config/gemini-search.php`.

---

## 🚀 Usage

### API Endpoint
This package provides an API route:

```http
POST /api/ai-search
Content-Type: application/json

{
  "query": "Show me all users created in the last 7 days"
}
```

### Example Response
```json
{
  "query": "SELECT * FROM users WHERE created_at >= NOW() - INTERVAL 7 DAY;",
  "results": [
    {"id":1, "name":"John Doe", "email":"john@example.com", "created_at":"2025-08-16"}
  ],
  "suggestions": [
    "Users registered last month",
    "Users without email verified",
    "Active users with orders"
  ]
}
```

---

## ✅ Features

- Parses your **database schema** automatically.
- Uses **Gemini AI** to generate **safe SELECT queries**.
- **Validates queries** (no `DELETE`, `UPDATE`, `DROP` allowed).
- Returns results in **JSON format**.
- Suggests **follow-up queries** to refine your search.

---

## 🛡 Security

- Only `SELECT` queries are executed.  
- Query validation prevents destructive SQL.  
- You should still review prompts carefully for edge cases.

---

## 📚 Roadmap

- [ ] Support for vector/semantic search (embeddings).  
- [ ] Artisan CLI command: `php artisan ai:search "latest orders"`.  
- [ ] Query caching for performance.  

---

## 🤝 Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you’d like to add.

---

## 📄 License

MIT
