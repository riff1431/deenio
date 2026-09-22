<?php

namespace App\Http\Controllers\Quran\Settings;

use App\Http\Controllers\Controller;
use App\Services\Setting\SettingService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getFormattedSettings();
        $values = [];
        foreach ($data as $key => $value) {
            $values[$key] = $value;
        }

        // Ensure AI settings have sensible defaults
        $values['ai_enabled'] = $values['ai_enabled'] ?? '1';
        $values['ai_provider'] = $values['ai_provider'] ?? 'groq';
        $values['ai_model'] = $values['ai_model'] ?? 'llama-3.3-70b-versatile';
        $values['ai_system_prompt'] = $values['ai_system_prompt'] ?? \App\Http\Controllers\Quran\AI\AIChatController::DEFAULT_SYSTEM_PROMPT;
        $values['ai_temperature'] = $values['ai_temperature'] ?? '0.7';
        $values['ai_max_tokens'] = $values['ai_max_tokens'] ?? '600';

        return $values;
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => ['required', 'string'],
            'play_store_url' => 'nullable|url:https',
            'app_store_url' => 'nullable|url:https',
            'address' => ['required', 'string', 'max:50'],
            'app_theme' => ['nullable', 'string', Rule::in(array_keys(config('themes.presets')))],
            'theme_use_custom' => ['nullable', Rule::in(['0', '1', 0, 1])],
            'theme_c_mode' => ['nullable', Rule::in(['', 'light', 'dark'])],
            'theme_c_primary' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_secondary' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_accent' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_background' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_surface' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_text_primary' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_text_secondary' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'theme_c_error' => ['nullable', 'string', 'regex:/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/'],
            'ai_enabled' => ['nullable', Rule::in(['0', '1', 0, 1])],
            'ai_provider' => ['nullable', 'string', Rule::in(array_keys(\App\Services\AI\AIProviderService::PROVIDERS))],
            'ai_model' => ['nullable', 'string', 'max:150'],
            'ai_system_prompt' => ['nullable', 'string', 'max:2500'],
            'ai_temperature' => ['nullable', 'numeric', 'min:0', 'max:2'],
            'ai_max_tokens' => ['nullable', 'integer', 'min:50', 'max:4000'],
        ]);


        $this->service->update();

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
        ]);
    }

    public function themePresets()
    {
        return response()->json(config('themes.presets'));
    }

    public function privacySupportUpdate(Request $request)
    {

        $this->service->update();

        return response()->json([
            'success' => true,
            'message' => 'Information updated successfully',
        ]);
    }

    public function homeLayoutUpdate(Request $request)
    {
        $request->validate([
            'home_layout' => ['required', 'string', Rule::in(array_keys(config('home_layouts.presets')))],
        ]);

        $this->service->update();

        return response()->json([
            'success' => true,
            'message' => 'Home screen layout updated successfully',
        ]);
    }

    public function homeLayoutPresets()
    {
        return response()->json(config('home_layouts.presets'));
    }

}
