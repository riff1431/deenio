<?php

namespace App\Http\Controllers\Quran\API\Hadith;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HadithProxyController extends Controller
{
    private function getApiKey(): string
    {
        return env('HADITH_API_KEY', '$2y$10$3OKfClIP6Gc7sRhztDpbeeV8XjM0liVXcOCuboX7bVRu8MXYwfki');
    }

    private string $baseUrl = 'https://www.hadithapi.com/public/api';

    public function books()
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/books", [
                'apiKey' => $this->getApiKey(),
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            Log::error('Hadith books proxy error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to load hadith books: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function hadiths(Request $request)
    {
        try {
            $params = [
                'apiKey' => $this->getApiKey(),
                'paginate' => $request->get('paginate', 500),
            ];

            if ($request->filled('book')) {
                $params['book'] = $request->get('book');
            }
            if ($request->filled('chapter')) {
                $params['chapter'] = $request->get('chapter');
            }
            if ($request->filled('page')) {
                $params['page'] = $request->get('page');
            }

            // Note: no trailing slash to avoid 301 redirect
            $response = Http::timeout(20)->get("{$this->baseUrl}/hadiths", $params);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            Log::error('Hadith hadiths proxy error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to load hadiths: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function chapters($bookSlug)
    {
        try {
            $response = Http::timeout(15)->get("{$this->baseUrl}/{$bookSlug}/chapters", [
                'apiKey' => $this->getApiKey(),
            ]);

            return response()->json($response->json(), $response->status());
        } catch (\Exception $e) {
            Log::error('Hadith chapters proxy error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Failed to load chapters: ' . $e->getMessage(),
            ], 500);
        }
    }
}
