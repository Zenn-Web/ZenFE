<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as HttpFacade;

class ResearchController extends Controller
{
    /**
     * Fetch latest forex news (XAUUSD) from external API.
     * This method replaces the old ICT/RAG logic.
     */
    public function fetchNews(Request $request)
    {
        // Example API endpoint – replace with your actual news source.
        $apiUrl = env('NEWS_API_ENDPOINT', 'https://example.com/api/news');
        $apiKey = env('NEWS_API_KEY');

        $response = HttpFacade::get($apiUrl, [
            'q' => 'gold XAUUSD forex',
            'apiKey' => $apiKey,
            // limit to recent items
            'pageSize' => 20,
        ]);

        $news = $response->json();
        // Ensure we only return relevant fields
        $filtered = collect($news['articles'] ?? [])->map(function ($item) {
            return [
                'title' => $item['title'] ?? '',
                'url' => $item['url'] ?? '#',
                'source' => $item['source']['name'] ?? '',
                'publishedAt' => $item['publishedAt'] ?? '',
            ];
        });

        return response()->json($filtered);
    }
}
