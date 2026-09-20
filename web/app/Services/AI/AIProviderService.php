<?php

namespace App\Services\AI;

use App\Services\Setting\SettingService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Http;

class AIProviderService
{
    /**
     * Supported providers and their configurations.
     */
    public const PROVIDERS = [
        'groq' => [
            'name' => 'Groq',
            'endpoint' => 'https://api.groq.com/openai/v1/chat/completions',
            'default_model' => 'llama-3.3-70b-versatile',
            'key_url' => 'https://console.groq.com/keys',
            'format' => 'openai', // OpenAI-compatible
        ],
        'openai' => [
            'name' => 'OpenAI',
            'endpoint' => 'https://api.openai.com/v1/chat/completions',
            'default_model' => 'gpt-4o-mini',
            'key_url' => 'https://platform.openai.com/api-keys',
            'format' => 'openai',
        ],
        'openrouter' => [
            'name' => 'OpenRouter',
            'endpoint' => 'https://openrouter.ai/api/v1/chat/completions',
            'default_model' => 'meta-llama/llama-3.3-70b-instruct',
            'key_url' => 'https://openrouter.ai/keys',
            'format' => 'openai',
        ],
        'gemini' => [
            'name' => 'Google Gemini',
            'endpoint' => 'https://generativelanguage.googleapis.com/v1beta/models/{model}:generateContent',
            'default_model' => 'gemini-2.0-flash',
            'key_url' => 'https://aistudio.google.com/apikey',
            'format' => 'gemini',
        ],
    ];

    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get the current AI provider configuration from settings.
     */
    public function getProviderConfig(): array
    {
        $settings = $this->settingService->getFormattedSettings();

        $provider = $settings['ai_provider'] ?? 'groq';
        $model = $settings['ai_model'] ?? (self::PROVIDERS[$provider]['default_model'] ?? 'llama-3.3-70b-versatile');
        $apiKey = $this->safeDecrypt($settings['islamic_name_api_key'] ?? '');

        return [
            'provider' => $provider,
            'model' => $model,
            'api_key' => $apiKey,
        ];
    }

    /**
     * Send a chat completion request to the configured AI provider.
     */
    public function chat(array $messages, int $maxTokens = 600, ?array $overrideConfig = null): string
    {
        $config = $overrideConfig ?? $this->getProviderConfig();

        $provider = $config['provider'];
        $model = $config['model'];
        $apiKey = $config['api_key'];

        if (empty($apiKey)) {
            throw new \Exception('AI API key is not configured. Please set it in Settings.');
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
     * Test connection to the configured AI provider.
     */
    public function testConnection(?array $overrideConfig = null): array
    {
        $config = $overrideConfig ?? $this->getProviderConfig();

        try {
            $reply = $this->chat(
                [['role' => 'user', 'content' => 'Say "Connection successful!" in exactly those words.']],
                50,
                $config
            );

            return [
                'success' => true,
                'provider' => self::PROVIDERS[$config['provider']]['name'] ?? $config['provider'],
                'model' => $config['model'],
                'message' => 'Connection successful! AI responded: ' . mb_substr($reply, 0, 100),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'provider' => self::PROVIDERS[$config['provider']]['name'] ?? $config['provider'],
                'model' => $config['model'],
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Returns the list of available providers with their metadata.
     */
    public static function getAvailableProviders(): array
    {
        $providers = [];
        foreach (self::PROVIDERS as $key => $config) {
            $providers[$key] = [
                'name' => $config['name'],
                'default_model' => $config['default_model'],
                'key_url' => $config['key_url'],
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

        // OpenRouter requires HTTP-Referer header
        if ($provider === 'openrouter') {
            $headers['HTTP-Referer'] = config('app.url', 'http://localhost');
            $headers['X-Title'] = config('app.name', 'Zabi');
        }

        $response = Http::timeout(30)
            ->withHeaders($headers)
            ->post($providerConfig['endpoint'], [
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => $maxTokens,
                'temperature' => 0.7,
            ]);

        if (!$response->successful()) {
            $error = $response->json('error.message', $response->body());
            throw new \Exception("AI API error ({$response->status()}): {$error}");
        }

        return $response->json('choices.0.message.content', '');
    }

    /**
     * Call the Google Gemini API (non-OpenAI format).
     */
    protected function callGemini(array $messages, int $maxTokens, string $model, string $apiKey): string
    {
        $endpoint = str_replace('{model}', $model, self::PROVIDERS['gemini']['endpoint']);

        // Convert OpenAI-style messages to Gemini format
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
        ];

        if ($systemInstruction) {
            $body['systemInstruction'] = [
                'parts' => [['text' => $systemInstruction]],
            ];
        }

        $response = Http::timeout(30)
            ->post($endpoint . '?key=' . $apiKey, $body);

        if (!$response->successful()) {
            $error = $response->json('error.message', $response->body());
            throw new \Exception("Gemini API error ({$response->status()}): {$error}");
        }

        return $response->json('candidates.0.content.parts.0.text', '');
    }

    /**
     * Safely decrypt a value, returning the original if decryption fails.
     */
    protected function safeDecrypt($value)
    {
        if (empty($value)) {
            return '';
        }
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return $value;
        }
    }
}
