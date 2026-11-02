<?php

namespace App\Services\AI;

use App\Services\Setting\SettingService;
use Illuminate\Support\Facades\Http;

class AIProviderService
{
    /**
     * Supported providers, metadata, endpoints, and curated model catalogs.
     */
    public const PROVIDERS = [
        'groq' => [
            'name' => 'Groq',
            'badge' => '⚡ Fastest & Free Tier',
            'endpoint' => 'https://api.groq.com/openai/v1/chat/completions',
            'default_model' => 'llama-3.3-70b-versatile',
            'key_url' => 'https://console.groq.com/keys',
            'key_label' => 'Groq Console',
            'format' => 'openai',
            'models' => [
                [
                    'id' => 'llama-3.3-70b-versatile',
                    'name' => 'Llama 3.3 70B Versatile',
                    'tag' => 'Recommended',
                    'desc' => 'Flagship intelligence, lightning speed, 128k context',
                ],
                [
                    'id' => 'llama-3.1-8b-instant',
                    'name' => 'Llama 3.1 8B Instant',
                    'tag' => 'Ultra Fast',
                    'desc' => 'Instantaneous responses, lightweight, ideal for quick Q&A',
                ],
                [
                    'id' => 'mixtral-8x7b-32768',
                    'name' => 'Mixtral 8x7B 32k',
                    'tag' => 'MoE High Context',
                    'desc' => 'Mixture-of-Experts architecture, 32k context',
                ],
                [
                    'id' => 'gemma2-9b-it',
                    'name' => 'Gemma 2 9B Instruct',
                    'tag' => 'Google on Groq',
                    'desc' => 'Google open weights model running on ultra-fast Groq LPU',
                ],
            ],
        ],
        'gemini' => [
            'name' => 'Google Gemini',
            'badge' => '🧠 Advanced Multimodal',
            'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent',
            'default_model' => 'gemini-3.6-flash',
            'key_url' => 'https://aistudio.google.com/apikey',
            'key_label' => 'Google AI Studio',
            'format' => 'gemini',
            'models' => [
                [
                    'id' => 'gemini-3.6-flash',
                    'name' => 'Gemini 3.6 Flash',
                    'tag' => 'Recommended',
                    'desc' => 'Google flagship GA model, ultra-fast generation, superior Islamic reasoning',
                ],
                [
                    'id' => 'gemini-2.5-pro',
                    'name' => 'Gemini 2.5 Pro',
                    'tag' => 'Deep Reasoning',
                    'desc' => 'Complex Islamic jurisprudence, fiqh comparisons, and deep tafsir analysis',
                ],
            ],
        ],
        'openai' => [
            'name' => 'OpenAI',
            'badge' => '🌟 Industry Standard',
            'endpoint' => 'https://api.openai.com/v1/chat/completions',
            'default_model' => 'gpt-4o-mini',
            'key_url' => 'https://platform.openai.com/api-keys',
            'key_label' => 'OpenAI Platform',
            'format' => 'openai',
            'models' => [
                [
                    'id' => 'gpt-4o-mini',
                    'name' => 'GPT-4o Mini',
                    'tag' => 'Recommended',
                    'desc' => 'Affordable, fast, highly capable, 128k context',
                ],
                [
                    'id' => 'gpt-4o',
                    'name' => 'GPT-4o (Omni)',
                    'tag' => 'Flagship',
                    'desc' => 'Top-tier OpenAI intelligence across Arabic and English',
                ],
                [
                    'id' => 'gpt-3.5-turbo',
                    'name' => 'GPT-3.5 Turbo',
                    'tag' => 'Legacy',
                    'desc' => 'Standard classic OpenAI chat model',
                ],
            ],
        ],
        'openrouter' => [
            'name' => 'OpenRouter',
            'badge' => '🌐 Multi-Provider Router',
            'endpoint' => 'https://openrouter.ai/api/v1/chat/completions',
            'default_model' => 'meta-llama/llama-3.3-70b-instruct',
            'key_url' => 'https://openrouter.ai/keys',
            'key_label' => 'OpenRouter Console',
            'format' => 'openai',
            'models' => [
                [
                    'id' => 'meta-llama/llama-3.3-70b-instruct',
                    'name' => 'Llama 3.3 70B Instruct',
                    'tag' => 'Recommended',
                    'desc' => 'Meta open model routed via top speed providers',
                ],
                [
                    'id' => 'google/gemini-2.0-flash-exp:free',
                    'name' => 'Gemini 2.0 Flash (Free)',
                    'tag' => '100% Free',
                    'desc' => 'Free tier Gemini 2.0 via OpenRouter free pool',
                ],
                [
                    'id' => 'deepseek/deepseek-chat',
                    'name' => 'DeepSeek V3',
                    'tag' => 'High Intelligence',
                    'desc' => '671B MoE foundation model, excellent multilingual ability',
                ],
                [
                    'id' => 'mistralai/mistral-7b-instruct:free',
                    'name' => 'Mistral 7B (Free)',
                    'tag' => 'Free',
                    'desc' => 'Lightweight free model for simple prompts',
                ],
            ],
        ],
    ];

    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get the current AI provider configuration from settings with robust defaults and dynamic key inference.
     */
    public function getProviderConfig(): array
    {
        $settings = $this->settingService->getFormattedSettings();

        $provider = $settings['ai_provider'] ?? 'groq';
        if (!isset(self::PROVIDERS[$provider])) {
            $provider = 'groq';
        }

        $rawModel = trim($settings['ai_model'] ?? '');
        $defaultModel = self::PROVIDERS[$provider]['default_model'] ?? 'llama-3.3-70b-versatile';
        $model = !empty($rawModel) ? $rawModel : $defaultModel;

        $apiKey = $this->safeDecrypt($settings['islamic_name_api_key'] ?? '');

        // 🧠 Dynamic Auto-Detection:
        // If the key signature uniquely belongs to another provider (e.g. OpenAI key sk-... but setting says gemini),
        // automatically route to the matching provider so requests never fail due to provider-key mismatches!
        $detectedProvider = self::detectProviderFromKey($apiKey);
        if ($detectedProvider && $detectedProvider !== $provider) {
            $provider = $detectedProvider;
            $defaultModel = self::PROVIDERS[$provider]['default_model'] ?? 'gpt-4o-mini';
            $validModelIds = array_column(self::PROVIDERS[$provider]['models'] ?? [], 'id');
            if (!in_array($model, $validModelIds, true)) {
                $model = $defaultModel;
            }
        }

        // Auto-upgrade any retired/deprecated Gemini models
        if ($provider === 'gemini' && (empty($model) || str_contains($model, 'gemini-2.0') || str_contains($model, 'gemini-1.5') || $model === 'gemini-2.5-flash')) {
            $model = 'gemini-3.6-flash';
        }

        $enabled = ($settings['ai_enabled'] ?? '1') !== '0';
        $systemPrompt = $settings['ai_system_prompt'] ?? '';
        $temperature = floatval($settings['ai_temperature'] ?? 0.7);
        $maxTokens = intval($settings['ai_max_tokens'] ?? 600);

        return [
            'enabled' => $enabled,
            'provider' => $provider,
            'model' => $model,
            'api_key' => $apiKey,
            'system_prompt' => $systemPrompt,
            'temperature' => $temperature > 0 ? $temperature : 0.7,
            'max_tokens' => $maxTokens > 0 ? $maxTokens : 600,
        ];
    }

    /**
     * Send a chat completion request to the configured AI provider.
     */
    public function chat(array $messages, int $maxTokens = 600, ?array $overrideConfig = null): string
    {
        $config = $overrideConfig ?? $this->getProviderConfig();

        $provider = $config['provider'] ?? 'groq';
        $model = !empty($config['model']) ? $config['model'] : (self::PROVIDERS[$provider]['default_model'] ?? 'llama-3.3-70b-versatile');
        $apiKey = $config['api_key'] ?? '';

        if (empty($apiKey)) {
            throw new \Exception("AI API key is not configured for provider {$provider}. Please set it in Settings.");
        }

        $providerConfig = self::PROVIDERS[$provider] ?? null;
        if (!$providerConfig) {
            throw new \Exception("Unknown AI provider: {$provider}");
        }

        if ($providerConfig['format'] === 'gemini') {
            return $this->callGemini($messages, $maxTokens, $model, $apiKey);
        }

        return $this->callOpenAICompatible($messages, $maxTokens, $model, $apiKey, $providerConfig, $provider);
    }

    /**
     * Test connection to the AI provider, returning latency and live response.
     */
    public function testConnection(?array $overrideConfig = null, ?string $testPrompt = null): array
    {
        $config = $overrideConfig ?? $this->getProviderConfig();
        $prompt = !empty($testPrompt) ? $testPrompt : 'Say "Connection successful! Deenio AI is ready." in exactly those words.';

        $startTime = microtime(true);
        try {
            $reply = $this->chat(
                [['role' => 'user', 'content' => $prompt]],
                150,
                $config
            );
            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);

            return [
                'success' => true,
                'provider' => self::PROVIDERS[$config['provider']]['name'] ?? $config['provider'],
                'model' => $config['model'],
                'latency_ms' => $latencyMs,
                'reply' => $reply,
                'message' => "Connected successfully! ({$latencyMs}ms) Response: " . mb_substr($reply, 0, 120),
            ];
        } catch (\Exception $e) {
            $latencyMs = (int) round((microtime(true) - $startTime) * 1000);
            return [
                'success' => false,
                'provider' => self::PROVIDERS[$config['provider']]['name'] ?? $config['provider'],
                'model' => $config['model'],
                'latency_ms' => $latencyMs,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Returns the list of available providers with their model catalogs and metadata.
     */
    public static function getAvailableProviders(): array
    {
        $providers = [];
        foreach (self::PROVIDERS as $key => $config) {
            $providers[$key] = [
                'name' => $config['name'],
                'badge' => $config['badge'] ?? '',
                'default_model' => $config['default_model'],
                'key_url' => $config['key_url'],
                'key_label' => $config['key_label'] ?? $config['name'],
                'models' => $config['models'] ?? [],
            ];
        }
        return $providers;
    }

    /**
     * Call an OpenAI-compatible API (Groq, OpenAI, OpenRouter).
     */
    protected function callOpenAICompatible(array $messages, int $maxTokens, string $model, string $apiKey, array $providerConfig, string $provider): string
    {
        $headers = [
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ];

        if ($provider === 'openrouter') {
            $headers['HTTP-Referer'] = config('app.url', 'http://127.0.0.1:8000');
            $headers['X-Title'] = config('app.name', 'Deenio');
        }

        $response = Http::timeout(35)
            ->withHeaders($headers)
            ->post($providerConfig['endpoint'], [
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => $maxTokens,
                'temperature' => 0.7,
            ]);

        if (!$response->successful()) {
            $error = $response->json('error.message') ?? $response->json('message') ?? $response->body();
            throw new \Exception("AI API error ({$response->status()}): {$error}");
        }

        $content = $response->json('choices.0.message.content', '');
        return trim($content);
    }

    /**
     * Call the Google Gemini API (generateContent format).
     */
    protected function callGemini(array $messages, int $maxTokens, string $model, string $apiKey): string
    {
        // Auto-upgrade retired/deprecated Gemini models to active gemini-3.6-flash
        if (
            empty($model) ||
            str_contains($model, 'gemini-2.0') ||
            $model === 'gemini-2.5-flash' ||
            str_contains($model, 'gemini-1.5')
        ) {
            $model = 'gemini-3.6-flash';
        }

        $endpoint = str_replace('{model}', $model, self::PROVIDERS['gemini']['endpoint']);

        $contents = [];
        $systemInstruction = null;

        foreach ($messages as $msg) {
            if ($msg['role'] === 'system') {
                $systemInstruction = $msg['content'];
                continue;
            }
            $contents[] = [
                'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                'parts' => [['text' => $msg['content']]],
            ];
        }

        $body = [
            'contents' => $contents,
            'generationConfig' => [
                'maxOutputTokens' => $maxTokens,
                'temperature' => 0.7,
            ],
            // Relax safety filters so religious terminology and Quranic verses are never falsely blocked
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_ONLY_HIGH'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_ONLY_HIGH'],
            ],
        ];

        if ($systemInstruction) {
            $body['systemInstruction'] = [
                'parts' => [['text' => $systemInstruction]],
            ];
        }

        $response = Http::timeout(35)
            ->withHeaders([
                'x-goog-api-key' => $apiKey,
            ])
            ->post($endpoint . '?key=' . $apiKey, $body);

        if (!$response->successful()) {
            $error = $response->json('error.message', $response->body());
            throw new \Exception("Gemini API error ({$response->status()}): {$error}");
        }

        $candidate = $response->json('candidates.0');
        if (empty($candidate)) {
            throw new \Exception("Gemini returned an empty response. Please check safety thresholds or model name.");
        }

        $finishReason = $candidate['finishReason'] ?? 'STOP';
        if ($finishReason === 'SAFETY') {
            throw new \Exception("Gemini response was blocked by safety filters. Please rephrase your query.");
        }

        $text = $candidate['content']['parts'][0]['text'] ?? '';
        return trim($text);
    }

    /**
     * Safely decrypt a value, returning the original if decryption fails or if already plaintext.
     */
    public function safeDecrypt($value): string
    {
        if (empty($value)) {
            return '';
        }
        try {
            return (string) decrypt($value);
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    /**
     * Intelligently detect the AI provider from the format / prefix of an API key.
     */
    public static function detectProviderFromKey(?string $apiKey): ?string
    {
        if (empty($apiKey)) {
            return null;
        }
        $key = trim($apiKey);

        // Groq: starts with gsk_
        if (str_starts_with($key, 'gsk_')) {
            return 'groq';
        }

        // OpenRouter: starts with sk-or-
        if (str_starts_with($key, 'sk-or-')) {
            return 'openrouter';
        }

        // OpenAI: standard keys start with sk-proj-, sk-admin-, or sk-
        if (str_starts_with($key, 'sk-proj-') || str_starts_with($key, 'sk-admin-') || str_starts_with($key, 'sk-')) {
            return 'openai';
        }

        // Google Gemini: standard AI Studio keys start with AIzaSy or AIza
        if (str_starts_with($key, 'AIzaSy') || str_starts_with($key, 'AIza')) {
            return 'gemini';
        }

        return null;
    }
}
