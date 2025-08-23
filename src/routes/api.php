<?php

use Illuminate\Support\Facades\Route;
use Ashok\GeminiSearch\Http\Controllers\SearchController;

Route::post('/ai-search', [SearchController::class, 'search']);
