<?php

namespace App\Http\Controllers\Quran\AI;

use App\Http\Controllers\Controller;
use App\Services\AI\AIProviderService;
use App\Services\Setting\SettingService;
use Illuminate\Http\Request;

class AIChatController extends Controller
{
    protected AIProviderService $aiProvider;

    public const DEFAULT_SYSTEM_PROMPT = 'You are Deenio AI, a knowledgeable Islamic scholar assistant. Answer questions about Islam, Quran, Hadith, prayer, fiqh, Islamic history, halal/haram, duas, and Islamic lifestyle. Be respectful, accurate, and cite Quranic verses or hadith references when relevant. Include Arabic text for prayers and duas with English translation. Keep answers helpful, clear, and concise. If asked about unrelated topics, politely redirect to Islamic subjects.';

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
        $request->validate(['message' => 'required|string|max:1000']);

        $config = $this->aiProvider->getProviderConfig();

        if (!$config['enabled']) {
            return response()->json([
                'success' => false,
                'error' => 'AI Assistant is currently disabled by the administrator.',
            ], 503);
        }

        $systemPrompt = !empty($config['system_prompt']) ? $config['system_prompt'] : self::DEFAULT_SYSTEM_PROMPT;
        $maxTokens = $config['max_tokens'] ?? 600;

        try {
            $reply = $this->aiProvider->chat([
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $request->message],
            ], $maxTokens);

            return response()->json([
                'success' => true,
                'reply' => $reply,
                'provider' => $config['provider'],
                'model' => $config['model'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'AI service temporarily unavailable. (' . $e->getMessage() . ')',
            ], 503);
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

            preg_match('/\[[\s\S]*\]/u', $content, $m);
            $names = json_decode($m[0] ?? '[]', true) ?? [];

            return response()->json(['success' => true, 'names' => $names]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'AI service temporarily unavailable. Please try again.'], 503);
        }
    }

    /**
     * Test connection to the AI provider with quick ping or custom test prompt.
     * Can be called from admin settings with temporary config or saved config.
     */
    public function testConnection(Request $request)
    {
        $request->validate([
            'ai_provider'      => 'nullable|string|in:groq,openai,openrouter,gemini',
            'ai_model'         => 'nullable|string|max:100',
            'ai_api_key'       => 'nullable|string',
            'is_key_encrypted' => 'nullable|boolean',
            'test_prompt'      => 'nullable|string|max:500',
        ]);

        $overrideConfig = null;

        if ($request->filled('ai_api_key')) {
            $apiKey = $request->input('ai_api_key');

            if ($request->boolean('is_key_encrypted')) {
                $apiKey = $this->aiProvider->safeDecrypt($apiKey);
            }

            $providerKey = $request->input('ai_provider', 'groq');
            $model       = trim($request->input('ai_model', ''));

            if (empty($model) || ($providerKey === 'gemini' && (str_contains($model, 'gemini-2.0') || $model === 'gemini-2.5-flash' || str_contains($model, 'gemini-1.5')))) {
                $model = AIProviderService::PROVIDERS[$providerKey]['default_model'] ?? 'gemini-3.6-flash';
            }

            $overrideConfig = [
                'provider' => $providerKey,
                'model'    => $model,
                'api_key'  => $apiKey,
            ];
        }

        $testPrompt = $request->input('test_prompt');
        $result = $this->aiProvider->testConnection($overrideConfig, $testPrompt);

        return response()->json($result);
    }

    /**
     * Return the list of available AI providers and their curated model catalogs.
     */
    public function providers()
    {
        return response()->json(AIProviderService::getAvailableProviders());
    }
}
