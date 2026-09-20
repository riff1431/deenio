<?php

namespace App\Http\Controllers\Quran\AI;

use App\Http\Controllers\Controller;
use App\Services\AI\AIProviderService;
use App\Services\Setting\SettingService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    protected AIProviderService $aiProvider;

    public function __construct(SettingService $service, AIProviderService $aiProvider)
    {
        $this->service = $service;
        $this->aiProvider = $aiProvider;
    }

    /**
     * AI Chat endpoint — used by the Flutter app's AI assistant.
     */
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        try {
            $reply = $this->aiProvider->chat([
                [
                    'role'    => 'system',
                    'content' => 'You are Zabi AI, a knowledgeable Islamic scholar assistant. Answer questions about Islam, Quran, Hadith, prayer, fiqh, Islamic history, halal/haram, duas, and Islamic lifestyle. Be respectful, accurate, and cite Quranic verses or hadith references when relevant. Include Arabic text for prayers and duas with English translation. Keep answers helpful, clear, and concise. If asked about unrelated topics, politely redirect to Islamic subjects.',
                ],
                ['role' => 'user', 'content' => $request->message],
            ], 600);

            return response()->json(['success' => true, 'reply' => $reply]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'AI service temporarily unavailable. Please try again. (' . $e->getMessage() . ')'], 503);
        }
    }

    /**
     * Generate Islamic baby names using AI.
     */
    public function generateNames(Request $request)
    {
        $request->validate([
            'gender' => 'required|in:boy,girl',
            'theme'  => 'nullable|string|max:100',
        ]);

        $gender      = $request->gender;
        $theme       = trim($request->theme ?? '');
        $themeClause = $theme ? " with names that relate to the theme '{$theme}'" : '';

        $prompt = "Generate 6 beautiful Islamic names for a {$gender}{$themeClause}. Return ONLY a valid JSON array with no markdown, no explanation. Each object must have exactly: \"arabic\" (Arabic script), \"name\" (English transliteration), \"meaning\" (clear English meaning), \"origin\" (e.g. Arabic, Persian, Urdu). Example: [{\"arabic\":\"عبدالله\",\"name\":\"Abdullah\",\"meaning\":\"Servant of Allah\",\"origin\":\"Arabic\"}]";

        try {
            $content = $this->aiProvider->chat([
                ['role' => 'system', 'content' => 'You are an Islamic names expert. Always respond with a valid JSON array only — no markdown fences, no explanation, no other text.'],
                ['role' => 'user', 'content' => $prompt],
            ], 1000);

            preg_match('/[\s\S]*\]/u', $content, $m);
            $names = json_decode($m[0] ?? '[]', true) ?? [];

            return response()->json(['success' => true, 'names' => $names]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'AI service temporarily unavailable. Please try again.'], 503);
        }
    }

    /**
     * Test connection to the AI provider. Can be called from admin settings
     * with temporary config (before saving) or with saved config.
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'ai_provider' => 'nullable|string|in:groq,openai,openrouter,gemini',
            'ai_model' => 'nullable|string|max:100',
            'ai_api_key' => 'nullable|string',
        ]);

        // If request provides config, use that (for testing before saving)
        $overrideConfig = null;
        if ($request->filled('ai_api_key')) {
            $overrideConfig = [
                'provider' => $request->input('ai_provider', 'groq'),
                'model' => $request->input('ai_model', ''),
                'api_key' => $request->input('ai_api_key'),
            ];

            // Use default model if none specified
            if (empty($overrideConfig['model'])) {
                $providerKey = $overrideConfig['provider'];
                $overrideConfig['model'] = AIProviderService::PROVIDERS[$providerKey]['default_model'] ?? '';
            }
        }

        $result = $this->aiProvider->testConnection($overrideConfig);

        return response()->json($result);
    }

    /**
     * Return the list of available AI providers.
     */
    public function providers()
    {
        return response()->json(AIProviderService::getAvailableProviders());
    }

    protected function safeDecrypt($value)
    {
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }
}
