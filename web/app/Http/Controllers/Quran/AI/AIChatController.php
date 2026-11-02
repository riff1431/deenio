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

            // Auto-detect provider if key uniquely identifies one (e.g. sk- key with gemini provider)
            $detected = AIProviderService::detectProviderFromKey($apiKey);
            if ($detected && $detected !== $providerKey) {
                $providerKey = $detected;
                $defaultForDetected = AIProviderService::PROVIDERS[$providerKey]['default_model'] ?? 'gpt-4o-mini';
                $validModels = array_column(AIProviderService::PROVIDERS[$providerKey]['models'] ?? [], 'id');
                if (!in_array($model, $validModels, true)) {
                    $model = $defaultForDetected;
                }
            }

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
     * Dedicated 1-click endpoint to save & persist AI provider settings directly from the AI card.
     */
    public function saveSettings(Request $request)
    {
        $request->validate([
            'ai_provider'      => 'nullable|string|in:groq,openai,openrouter,gemini',
            'ai_model'         => 'nullable|string|max:150',
            'ai_api_key'       => 'nullable|string',
            'is_key_encrypted' => 'nullable|boolean',
            'ai_enabled'       => 'nullable',
            'ai_system_prompt' => 'nullable|string|max:2500',
            'ai_temperature'   => 'nullable|numeric|min:0|max:2',
            'ai_max_tokens'    => 'nullable|integer|min:50|max:4000',
        ]);

        $apiKey = $request->input('ai_api_key');
        if ($apiKey !== null && $request->boolean('is_key_encrypted')) {
            $apiKey = $this->aiProvider->safeDecrypt($apiKey);
        }

        $provider = $request->input('ai_provider');
        // Auto-detect provider if key signature uniquely belongs to one
        if (!empty($apiKey)) {
            $detected = AIProviderService::detectProviderFromKey($apiKey);
            if ($detected) {
                $provider = $detected;
            }
        }
        if (!$provider || !isset(AIProviderService::PROVIDERS[$provider])) {
            $provider = 'groq';
        }

        $defaultModel = AIProviderService::PROVIDERS[$provider]['default_model'] ?? 'llama-3.3-70b-versatile';
        $model = trim($request->input('ai_model', '')) ?: $defaultModel;

        $settingRepo = resolve(\App\Repositories\Setting\SettingRepository::class);

        // 1. Save provider
        $sp = $settingRepo->createSettingInstance('ai_provider', 'app');
        $sp->fill(['name' => 'ai_provider', 'value' => $provider, 'context' => 'app'])->save();

        // 2. Save model
        $sm = $settingRepo->createSettingInstance('ai_model', 'app');
        $sm->fill(['name' => 'ai_model', 'value' => $model, 'context' => 'app'])->save();

        // 3. Save API key (encrypted) if new value provided
        if (!empty($apiKey)) {
            $sk = $settingRepo->createSettingInstance('islamic_name_api_key', 'app');
            $sk->fill(['name' => 'islamic_name_api_key', 'value' => encrypt($apiKey), 'context' => 'app'])->save();
        }

        // 4. Save ai_enabled if provided
        if ($request->has('ai_enabled')) {
            $enabled = in_array($request->input('ai_enabled'), [1, '1', true, 'true'], true) ? '1' : '0';
            $se = $settingRepo->createSettingInstance('ai_enabled', 'app');
            $se->fill(['name' => 'ai_enabled', 'value' => $enabled, 'context' => 'app'])->save();
        }

        // 5. Save prompt / temp / max_tokens if provided
        if ($request->filled('ai_system_prompt')) {
            $ss = $settingRepo->createSettingInstance('ai_system_prompt', 'app');
            $ss->fill(['name' => 'ai_system_prompt', 'value' => $request->input('ai_system_prompt'), 'context' => 'app'])->save();
        }
        if ($request->filled('ai_temperature')) {
            $st = $settingRepo->createSettingInstance('ai_temperature', 'app');
            $st->fill(['name' => 'ai_temperature', 'value' => (string) $request->input('ai_temperature'), 'context' => 'app'])->save();
        }
        if ($request->filled('ai_max_tokens')) {
            $smt = $settingRepo->createSettingInstance('ai_max_tokens', 'app');
            $smt->fill(['name' => 'ai_max_tokens', 'value' => (string) $request->input('ai_max_tokens'), 'context' => 'app'])->save();
        }

        $providerName = AIProviderService::PROVIDERS[$provider]['name'] ?? $provider;

        return response()->json([
            'success'  => true,
            'message'  => "AI Settings saved & activated successfully! Active provider: {$providerName} ({$model}).",
            'provider' => $provider,
            'model'    => $model,
        ]);
    }

    /**
     * Return the list of available AI providers and their curated model catalogs.
     */
    public function providers()
    {
        return response()->json(AIProviderService::getAvailableProviders());
    }
}
