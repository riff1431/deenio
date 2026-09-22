<template>
    <app-loader v-if="pageLoader" />
    <div v-else class="settings-wrapper">

        <form @submit.prevent="submit" class="settings-form">

            <!-- Section: Organization -->
            <div class="settings-card">
                <div class="card-label">
                    <span class="label-dot"></span>
                    {{ $t('setting.index.organization.title') }}
                </div>

                <div class="fields-grid">
                    <div class="field-group">
                        <label class="field-label" for="company_name">{{ $t('setting.index.organization.company_name_label') }} <span class="required">*</span></label>
                        <input type="text" class="field-input" id="company_name" :placeholder="$t('setting.index.organization.company_name_placeholder')" v-model="formData.company_name" />
                        <small class="field-error" v-if="errors.company_name">{{ errors.company_name[0] }}</small>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="address">{{ $t('setting.index.organization.address_label') }} <span class="required">*</span></label>
                        <input type="text" class="field-input" id="address" :placeholder="$t('setting.index.organization.address_placeholder')" v-model="formData.address" />
                        <small class="field-error" v-if="errors.address">{{ errors.address[0] }}</small>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="zakat_nisab">{{ $t('setting.index.organization.zakat_nisab_label') }} <span class="required">*</span></label>
                        <input type="number" class="field-input" id="zakat_nisab" :placeholder="$t('setting.index.organization.zakat_nisab_placeholder')" v-model="formData.zakat_nisab" />
                        <small class="field-error" v-if="errors.zakat_nisab">{{ errors.zakat_nisab[0] }}</small>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="currency_symbol">{{ $t('setting.index.organization.currency_symbol_label') }}</label>
                        <input type="text" class="field-input" id="currency_symbol" placeholder="e.g. $, €, ৳" v-model="formData.currency_symbol" />
                        <small class="field-error" v-if="errors.currency_symbol">{{ errors.currency_symbol[0] }}</small>
                    </div>
                </div>

                <div class="toggles-row">
                    <div class="toggle-item">
                        <div class="toggle-info">
                            <span class="toggle-label">{{ $t('setting.index.organization.ramadan_schedule_label') }}</span>
                            <span class="toggle-desc">{{ $t('setting.index.organization.ramadan_schedule_desc') }}</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="ramadan_schedule" v-model="formData.ramadan_schedule" />
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-info">
                            <span class="toggle-label">{{ $t('setting.index.organization.show_donation_banner_label') }}</span>
                            <span class="toggle-desc">{{ $t('setting.index.organization.show_donation_banner_desc') }}</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="show_donation_banner" v-model="formData.show_donation_banner" />
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </label>
                    </div>

                    <div class="toggle-item">
                        <div class="toggle-info">
                            <span class="toggle-label">{{ $t('setting.index.organization.show_donation_icon_label') }}</span>
                            <span class="toggle-desc">{{ $t('setting.index.organization.show_donation_icon_desc') }}</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" id="show_donation_icon" v-model="formData.show_donation_icon" />
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Section: Media Assets -->
            <div class="settings-card">
                <div class="card-label">
                    <span class="label-dot"></span>
                    {{ $t('setting.index.media.title') }}
                </div>

                <div class="media-grid">
                    <div class="media-item" v-for="media in mediaItems" :key="media.key">
                        <div class="media-preview">
                            <img :id="media.imgId" :src="formData[media.key]" :alt="media.label" class="preview-img" />
                            <div class="preview-overlay">
                                <label :for="media.inputId" class="overlay-btn">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                        <polyline points="17 8 12 3 7 8"/>
                                        <line x1="12" y1="3" x2="12" y2="15"/>
                                    </svg>
                                    {{ $t('setting.index.media.upload') }}
                                </label>
                                <input :id="media.inputId" :name="media.inputId" type="file" class="hidden-input" @change="media.handler($event)" />
                            </div>
                        </div>
                        <span class="media-label">{{ media.label }}</span>
                    </div>
                </div>
            </div>

            <!-- Section: Appearance -->
            <div class="settings-card">
                <div class="card-label">
                    <span class="label-dot"></span>
                    {{ $t('setting.index.appearance.title') }}
                </div>
                <p class="section-desc">{{ $t('setting.index.appearance.description') }}</p>

                <div class="theme-grid">
                    <button
                        type="button"
                        v-for="(preset, key) in themePresets"
                        :key="key"
                        class="theme-swatch"
                        :class="{ 'theme-swatch--active': formData.app_theme === key }"
                        @click="formData.app_theme = key"
                    >
                        <span class="theme-swatch-colors">
                            <span class="swatch-dot" :style="{ background: preset.colors.primary }"></span>
                            <span class="swatch-dot" :style="{ background: preset.colors.secondary }"></span>
                            <span class="swatch-dot" :style="{ background: preset.colors.accent }"></span>
                        </span>
                        <span class="theme-swatch-preview" :style="{ background: preset.colors.background, borderColor: preset.colors.surface }">
                            <span class="theme-swatch-bar" :style="{ background: preset.colors.primary }"></span>
                        </span>
                        <span class="theme-swatch-name">{{ preset.name }}</span>
                        <span class="theme-swatch-mode">{{ preset.mode }}</span>
                        <span v-if="formData.app_theme === key" class="theme-swatch-check">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Custom colors -->
                <div class="custom-theme">
                    <div class="toggle-item toggle-item--flush">
                        <div class="toggle-info">
                            <span class="toggle-label">{{ $t('setting.index.appearance.customize_label') }}</span>
                            <span class="toggle-desc">{{ $t('setting.index.appearance.customize_desc') }}</span>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" v-model="useCustom" />
                            <span class="toggle-track"><span class="toggle-thumb"></span></span>
                        </label>
                    </div>

                    <div v-if="useCustom" class="custom-colors">
                        <div class="custom-colors-grid">
                            <div class="color-field" v-for="tok in colorTokens" :key="tok.key">
                                <label class="color-field-label">{{ tok.label }}</label>
                                <div class="color-input-wrap">
                                    <input type="color" class="color-picker" v-model="customColors[tok.key]" />
                                    <input type="text" class="field-input hex-input" v-model="customColors[tok.key]" maxlength="7" spellcheck="false" />
                                </div>
                            </div>
                            <div class="color-field">
                                <label class="color-field-label">{{ $t('setting.index.appearance.mode_label') }}</label>
                                <select class="field-input" v-model="customMode">
                                    <option value="light">{{ $t('setting.index.appearance.mode_light') }}</option>
                                    <option value="dark">{{ $t('setting.index.appearance.mode_dark') }}</option>
                                </select>
                            </div>
                        </div>
                        <button type="button" class="reset-colors-btn" @click="resetToPreset">
                            {{ $t('setting.index.appearance.reset_to_preset') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section: AI & Intelligence Engine -->
            <div class="settings-card ai-settings-card">
                <div class="card-header-flex">
                    <div class="card-label">
                        <span class="label-dot ai-dot"></span>
                        AI & Intelligence Engine (Deenio AI)
                    </div>
                    <div class="ai-status-pill" :class="formData.ai_enabled ? 'status-active' : 'status-inactive'">
                        <span class="status-indicator-dot"></span>
                        {{ formData.ai_enabled ? 'AI Enabled' : 'AI Disabled' }}
                    </div>
                </div>
                <p class="section-desc">
                    Configure the AI provider and model that powers the Deenio Islamic Scholar Q&A, Tafsir guidance, and smart Islamic services.
                </p>

                <!-- Master Enable/Disable Toggle -->
                <div class="toggle-item toggle-item--ai mb-4">
                    <div class="toggle-info">
                        <span class="toggle-label">Enable AI Assistant in Mobile App</span>
                        <span class="toggle-desc">When disabled, the mobile app hides or gracefully pauses AI queries.</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" id="ai_enabled" v-model="formData.ai_enabled" />
                        <span class="toggle-track"><span class="toggle-thumb"></span></span>
                    </label>
                </div>

                <!-- Provider Selection Cards -->
                <div class="field-label mb-2">Select AI Provider</div>
                <div class="ai-provider-grid mb-4">
                    <button
                        type="button"
                        v-for="(prov, pKey) in AI_PROVIDERS"
                        :key="pKey"
                        class="ai-provider-card"
                        :class="{ 'ai-provider-card--active': formData.ai_provider === pKey }"
                        @click="setAiProvider(pKey)"
                    >
                        <div class="provider-header">
                            <span class="provider-badge">{{ prov.badge }}</span>
                            <span v-if="formData.ai_provider === pKey" class="provider-check">✓</span>
                        </div>
                        <div class="provider-name">{{ prov.name }}</div>
                        <div class="provider-key-source">{{ prov.keyLabel }}</div>
                    </button>
                </div>

                <div class="fields-grid mb-3">
                    <!-- Curated Model Selector -->
                    <div class="field-group">
                        <label class="field-label" for="ai_model_select">
                            AI Model
                            <span class="badge-subtle" v-if="currentModelTag">{{ currentModelTag }}</span>
                        </label>
                        <select class="field-input" id="ai_model_select" v-model="selectedModelChoice" @change="onModelSelectChange">
                            <option v-for="m in currentProviderModels" :key="m.id" :value="m.id">
                                {{ m.name }} — {{ m.tag }}
                            </option>
                            <option value="__custom__">✏️ Custom Model ID...</option>
                        </select>
                        <small class="field-helper" v-if="currentModelDesc">{{ currentModelDesc }}</small>
                    </div>

                    <!-- Custom Model Input (if custom selected) -->
                    <div class="field-group" v-if="isCustomModel">
                        <label class="field-label" for="ai_custom_model">Custom Model Identifier <span class="required">*</span></label>
                        <input type="text" class="field-input" id="ai_custom_model" placeholder="e.g. meta-llama/llama-3.2-3b-instruct" v-model="formData.ai_model" />
                        <small class="field-helper">Enter the exact model ID required by the endpoint.</small>
                    </div>

                    <!-- API Key Input -->
                    <div class="field-group">
                        <label class="field-label" for="islamic_name_api_key">
                            {{ aiProviderName }} API Key
                            <a :href="aiKeyUrl" target="_blank" class="ai-key-link">
                                Get key from {{ aiKeyLabel }} ↗
                            </a>
                        </label>
                        <div class="ai-key-wrapper">
                            <input :type="showAiKey ? 'text' : 'password'" class="field-input ai-key-input" id="islamic_name_api_key"
                                   :placeholder="formData.ai_provider === 'gemini' ? 'Paste Google AI Studio key (AQ... or AIzaSy...)' : 'Enter API key'" v-model="formData.islamic_name_api_key" />
                            <button type="button" @click="showAiKey = !showAiKey" class="ai-key-toggle-btn" :title="showAiKey ? 'Hide' : 'Show'">
                                {{ showAiKey ? '🙈' : '👁️' }}
                            </button>
                        </div>
                        <small v-if="formData.ai_provider === 'gemini'" class="field-helper">
                            Supports official Google AI Studio keys (<code>AQ...</code> or <code>AIzaSy...</code>). Generate for free at <a :href="aiKeyUrl" target="_blank" class="text-emerald-600 underline">Google AI Studio</a>.
                        </small>
                        <small class="field-error" v-if="errors.islamic_name_api_key">{{ errors.islamic_name_api_key[0] }}</small>
                    </div>
                </div>

                <!-- Advanced Configuration (Collapsible) -->
                <div class="ai-advanced-toggle mb-3" @click="showAdvancedAi = !showAdvancedAi">
                    <span class="advanced-toggle-title">⚙️ Advanced Persona & Generation Parameters</span>
                    <span class="advanced-toggle-arrow">{{ showAdvancedAi ? '▲ Hide' : '▼ Expand' }}</span>
                </div>

                <div v-if="showAdvancedAi" class="ai-advanced-panel mb-4">
                    <div class="field-group mb-3">
                        <div class="flex-between mb-1">
                            <label class="field-label" for="ai_system_prompt">Deenio AI Persona & System Instructions</label>
                            <button type="button" class="btn-link-sm" @click="resetSystemPrompt">Reset to Default</button>
                        </div>
                        <textarea class="field-input field-textarea" id="ai_system_prompt" rows="3" v-model="formData.ai_system_prompt" placeholder="Enter system instructions for the AI scholar..."></textarea>
                        <small class="field-helper">Shapes scholar tone, Quran/Hadith citation rules, and boundaries.</small>
                    </div>

                    <div class="fields-grid two-col">
                        <div class="field-group">
                            <label class="field-label" for="ai_temperature">Creativity (Temperature: {{ formData.ai_temperature }})</label>
                            <input type="range" class="field-range" id="ai_temperature" min="0.1" max="1.2" step="0.05" v-model.number="formData.ai_temperature" />
                            <div class="range-labels">
                                <span>Strict (0.1)</span>
                                <span>Balanced (0.7)</span>
                                <span>Creative (1.2)</span>
                            </div>
                        </div>
                        <div class="field-group">
                            <label class="field-label" for="ai_max_tokens">Max Output Tokens</label>
                            <input type="number" class="field-input" id="ai_max_tokens" min="100" max="3000" step="50" v-model.number="formData.ai_max_tokens" />
                            <small class="field-helper">Controls maximum reply length (default: 600).</small>
                        </div>
                    </div>
                </div>

                <!-- AI Testing & Diagnostics Section -->
                <div class="ai-test-section">
                    <div class="ai-test-header">
                        <div>
                            <div class="test-section-title">⚡ AI Connection & Diagnostics</div>
                            <div class="test-section-subtitle">Verify your API credentials and model latency.</div>
                        </div>
                        <button type="button" class="test-connection-btn" @click="testAiConnection" :disabled="aiTestLoading">
                            <span v-if="aiTestLoading" class="spinner-sm"></span>
                            {{ aiTestLoading ? 'Testing...' : '⚡ Test Connection' }}
                        </button>
                    </div>

                    <div v-if="aiTestResult" :class="['test-result-banner mt-3', aiTestResult.success ? 'banner-success' : 'banner-error']">
                        <div class="banner-title-row">
                            <span class="status-icon">{{ aiTestResult.success ? '✅' : '❌' }}</span>
                            <strong>{{ aiTestResult.provider }} — {{ aiTestResult.model }}</strong>
                            <span v-if="aiTestResult.latency_ms" class="latency-badge">{{ aiTestResult.latency_ms }}ms</span>
                        </div>
                        <div class="banner-msg">{{ aiTestResult.message }}</div>
                    </div>

                    <!-- Live Chat Sandbox -->
                    <div class="ai-playground mt-3">
                        <div class="playground-header">
                            <span class="playground-title">💬 Live Test Sandbox</span>
                            <span class="playground-sub">Ask a sample question to inspect real model generation.</span>
                        </div>
                        <div class="sample-chips">
                            <span class="chip-caption">Presets:</span>
                            <button type="button" class="sample-chip" @click="runPlaygroundPrompt('What are the five pillars of Islam?')">Five Pillars</button>
                            <button type="button" class="sample-chip" @click="runPlaygroundPrompt('Explain the significance of Ayat al-Kursi with English translation.')">Ayat al-Kursi</button>
                            <button type="button" class="sample-chip" @click="runPlaygroundPrompt('What is the dua when leaving home?')">Dua for Leaving Home</button>
                        </div>
                        <div class="playground-input-row">
                            <input type="text" class="field-input playground-input" placeholder="Type a test question for Deenio AI..." v-model="playgroundQuery" @keydown.enter.prevent="runPlaygroundPrompt(playgroundQuery)" />
                            <button type="button" class="playground-send-btn" @click="runPlaygroundPrompt(playgroundQuery)" :disabled="playgroundLoading || !playgroundQuery">
                                <span v-if="playgroundLoading" class="spinner-sm"></span>
                                {{ playgroundLoading ? 'Generating...' : 'Ask AI' }}
                            </button>
                        </div>
                        <div v-if="playgroundResponse" class="playground-bubble mt-3">
                            <div class="bubble-header">
                                <span class="bubble-tag">Deenio AI Response ({{ playgroundLatency }}ms)</span>
                                <button type="button" class="btn-copy" @click="copyResponse">📋 Copy</button>
                            </div>
                            <div class="bubble-content">{{ playgroundResponse }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section: Integrations -->
            <div class="settings-card">
                <div class="card-label">
                    <span class="label-dot"></span>
                    {{ $t('setting.index.integrations.title') }}
                </div>

                <div class="fields-grid">
                    <div class="field-group">
                        <label class="field-label" for="google_map_key">{{ $t('setting.index.integrations.google_map_key_label') }}</label>
                        <input type="text" class="field-input" id="google_map_key" :placeholder="$t('setting.index.integrations.google_map_key_placeholder')" v-model="formData.google_map_key" />
                        <small class="field-error" v-if="errors.google_map_key">{{ errors.google_map_key[0] }}</small>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="play_store_url">{{ $t('setting.index.integrations.play_store_url_label') }}</label>
                        <input type="url" class="field-input" id="play_store_url" placeholder="https://play.google.com/..." v-model="formData.play_store_url" />
                        <small class="field-error" v-if="errors.play_store_url">{{ errors.play_store_url[0] }}</small>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="app_store_url">{{ $t('setting.index.integrations.app_store_url_label') }}</label>
                        <input type="url" class="field-input" id="app_store_url" placeholder="https://apps.apple.com/..." v-model="formData.app_store_url" />
                        <small class="field-error" v-if="errors.app_store_url">{{ errors.app_store_url[0] }}</small>
                    </div>
                </div>
            </div>

            <!-- Section: Descriptions -->
            <div class="settings-card">
                <div class="card-label">
                    <span class="label-dot"></span>
                    {{ $t('setting.index.descriptions.title') }}
                </div>

                <div class="fields-grid two-col">
                    <div class="field-group">
                        <label class="field-label" for="zakat_description">{{ $t('setting.index.descriptions.zakat_description_label') }}</label>
                        <textarea class="field-input field-textarea" id="zakat_description" rows="6" v-model="formData.zakat_description" :placeholder="$t('setting.index.descriptions.zakat_description_placeholder')"></textarea>
                        <small class="field-error" v-if="errors.zakat_description">{{ errors.zakat_description[0] }}</small>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="haram_description">{{ $t('setting.index.descriptions.haram_description_label') }}</label>
                        <textarea class="field-input field-textarea" id="haram_description" rows="6" v-model="formData.haram_description" :placeholder="$t('setting.index.descriptions.haram_description_placeholder')"></textarea>
                        <small class="field-error" v-if="errors.haram_description">{{ errors.haram_description[0] }}</small>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="form-actions" v-if="$canAccess('update_setting')">
                <button type="submit" class="submit-btn" :disabled="preloader">
                    <app-button-loader v-if="preloader" />
                    <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ $t('setting.index.save_changes') }}
                </button>
            </div>

        </form>
    </div>
</template>

<script setup>
import { onMounted, ref, computed, watch } from "vue";
import Axios from "@/services/axios/index.js";
import _ from "lodash";
import { urlGenerator } from "@/utilities/urlGenerator.js";
import { toast } from "vue3-toastify";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const preloader = ref(false);
const formData = ref({});
const errors = ref({});
const themePresets = ref({});

/* ── Custom theme colors ────────────────────────────────── */
const useCustom = ref(false);
const customMode = ref('light');
const customColors = ref({
    primary: '#1A6B4A', secondary: '#2D8A63', accent: '#E8B84B',
    background: '#FFFFFF', surface: '#F5F7FB', text_primary: '#1A1F2E',
    text_secondary: '#5A6478', error: '#E05C5C',
});
const colorTokens = computed(() => [
    { key: 'primary',        label: t('setting.index.appearance.color_primary') },
    { key: 'secondary',      label: t('setting.index.appearance.color_secondary') },
    { key: 'accent',         label: t('setting.index.appearance.color_accent') },
    { key: 'background',     label: t('setting.index.appearance.color_background') },
    { key: 'surface',        label: t('setting.index.appearance.color_surface') },
    { key: 'text_primary',   label: t('setting.index.appearance.color_text_primary') },
    { key: 'text_secondary', label: t('setting.index.appearance.color_text_secondary') },
    { key: 'error',          label: t('setting.index.appearance.color_error') },
]);

function hexToRgb(hex) {
    hex = String(hex || '').replace('#', '');
    if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
    const n = parseInt(hex, 16);
    if (hex.length !== 6 || isNaN(n)) return '0, 0, 0';
    return [(n >> 16) & 255, (n >> 8) & 255, n & 255].join(', ');
}
function readableOn(hex) {
    const [r, g, b] = hexToRgb(hex).split(', ').map(Number);
    return (0.299 * r + 0.587 * g + 0.114 * b) / 255 > 0.6 ? '#1A1F2E' : '#FFFFFF';
}
// Mirror of resources/views/partials/theme-vars.blade.php for live preview.
function applyThemeVars(c, mode) {
    const el = document.documentElement, s = el.style, dark = mode === 'dark';
    s.setProperty('--theme-primary', c.primary);
    s.setProperty('--theme-secondary', c.secondary);
    s.setProperty('--theme-accent', c.accent);
    s.setProperty('--theme-error', c.error);
    s.setProperty('--theme-primary-rgb', hexToRgb(c.primary));
    s.setProperty('--theme-secondary-rgb', hexToRgb(c.secondary));
    s.setProperty('--theme-accent-rgb', hexToRgb(c.accent));
    s.setProperty('--theme-error-rgb', hexToRgb(c.error));
    s.setProperty('--theme-on-primary', readableOn(c.primary));
    s.setProperty('--theme-on-accent', readableOn(c.accent));
    s.setProperty('--theme-text', c.text_primary);
    s.setProperty('--theme-text-muted', c.text_secondary);
    s.setProperty('--theme-text-faint', dark ? c.text_secondary : '#9AA3B5');
    s.setProperty('--theme-card', dark ? c.surface : '#FFFFFF');
    s.setProperty('--theme-muted-bg', dark ? c.background : c.surface);
    s.setProperty('--theme-page', dark ? c.background : c.surface);
    s.setProperty('--theme-border', dark ? 'rgba(255, 255, 255, 0.10)' : '#E4E8F0');
    s.setProperty('--green-dark', c.primary);
    s.setProperty('--green-mid', c.secondary);
    s.setProperty('--gold', c.accent);
    el.setAttribute('data-theme-mode', dark ? 'dark' : 'light');
    el.classList.toggle('dark', dark);
}
function currentPreset() {
    return themePresets.value?.[formData.value.app_theme] || null;
}
// Copy the active preset's palette into the editable custom pickers.
function seedFromPreset() {
    const p = currentPreset();
    if (!p) return;
    customColors.value = { ...customColors.value, ...p.colors };
    customMode.value = p.mode || 'light';
}
function resetToPreset() {
    seedFromPreset();
}
// Push the effective palette (custom or preset) onto the live page.
function applyPreview() {
    const p = currentPreset();
    if (useCustom.value) {
        applyThemeVars(customColors.value, customMode.value);
    } else if (p) {
        applyThemeVars(p.colors, p.mode);
    }
}

const handleFileChange = (fileRef, formDataKey) => (event) => {
    const file = event.target.files[0];
    fileRef.value = file;
    formData.value[formDataKey] = URL.createObjectURL(file);
};

const webLogo = ref(null);
const changeWebLogo = handleFileChange(webLogo, 'web_logo');
const webIcon = ref(null);
const changeWebIcon = handleFileChange(webIcon, 'web_icon');
const appBanner = ref(null);
const changeAppBanner = handleFileChange(appBanner, 'app_logo');
const donationBanner = ref(null);
const changeDonationBanner = handleFileChange(donationBanner, 'donation_banner');

const mediaItems = computed(() => [
    { key: 'web_logo',        label: t('setting.index.media.company_logo'),    imgId: 'web_logo_img',     inputId: 'web_logo',            handler: changeWebLogo },
    { key: 'web_icon',        label: t('setting.index.media.company_icon'),    imgId: 'web_icon_img',     inputId: 'web_icon',            handler: changeWebIcon },
    { key: 'app_logo',        label: t('setting.index.media.app_banner'),      imgId: 'app_logo_img',     inputId: 'app_logo',            handler: changeAppBanner },
    { key: 'donation_banner', label: t('setting.index.media.donation_banner'), imgId: 'app_donation_img', inputId: 'app_donation_banner', handler: changeDonationBanner },
]);

// ── AI Provider & Model Config ──────────────────────────
const DEFAULT_SYSTEM_PROMPT = 'You are Deenio AI, a knowledgeable Islamic scholar assistant. Answer questions about Islam, Quran, Hadith, prayer, fiqh, Islamic history, halal/haram, duas, and Islamic lifestyle. Be respectful, accurate, and cite Quranic verses or hadith references when relevant. Include Arabic text for prayers and duas with English translation. Keep answers helpful, clear, and concise. If asked about unrelated topics, politely redirect to Islamic subjects.';

const AI_PROVIDERS = {
    groq: {
        name: 'Groq',
        badge: '⚡ Fastest & Free Tier',
        keyLabel: 'Groq Console',
        keyUrl: 'https://console.groq.com/keys',
        defaultModel: 'llama-3.3-70b-versatile',
        models: [
            { id: 'llama-3.3-70b-versatile', name: 'Llama 3.3 70B Versatile', tag: 'Recommended', desc: 'Flagship speed & intelligence, 128k context window' },
            { id: 'llama-3.1-8b-instant', name: 'Llama 3.1 8B Instant', tag: 'Ultra Fast', desc: 'Lightning quick response times for high-volume questions' },
            { id: 'mixtral-8x7b-32768', name: 'Mixtral 8x7B 32k', tag: 'MoE Context', desc: 'Mixture-of-Experts architecture with 32k context' },
            { id: 'gemma2-9b-it', name: 'Gemma 2 9B Instruct', tag: 'Google LPU', desc: 'Google open weights model running on Groq LPU speed' },
        ],
    },
    gemini: {
        name: 'Google Gemini',
        badge: '🧠 Advanced Multimodal',
        keyLabel: 'Google AI Studio',
        keyUrl: 'https://aistudio.google.com/apikey',
        defaultModel: 'gemini-3.6-flash',
        models: [
            { id: 'gemini-3.6-flash', name: 'Gemini 3.6 Flash', tag: 'Recommended', desc: 'Google flagship GA model, ultra-fast generation, superior Islamic reasoning' },
            { id: 'gemini-2.5-pro', name: 'Gemini 2.5 Pro', tag: 'Deep Reasoning', desc: 'Complex Islamic jurisprudence, fiqh comparisons, and deep tafsir analysis' },
        ],
    },
    openai: {
        name: 'OpenAI',
        badge: '🌟 Industry Standard',
        keyLabel: 'OpenAI Platform',
        keyUrl: 'https://platform.openai.com/api-keys',
        defaultModel: 'gpt-4o-mini',
        models: [
            { id: 'gpt-4o-mini', name: 'GPT-4o Mini', tag: 'Recommended', desc: 'Affordable, fast, highly capable across English & Arabic' },
            { id: 'gpt-4o', name: 'GPT-4o (Omni)', tag: 'Flagship', desc: 'Top-tier OpenAI intelligence across multilingual tasks' },
            { id: 'gpt-3.5-turbo', name: 'GPT-3.5 Turbo', tag: 'Legacy', desc: 'Classic lightweight OpenAI model' },
        ],
    },
    openrouter: {
        name: 'OpenRouter',
        badge: '🌐 Multi-Provider Router',
        keyLabel: 'OpenRouter Console',
        keyUrl: 'https://openrouter.ai/keys',
        defaultModel: 'meta-llama/llama-3.3-70b-instruct',
        models: [
            { id: 'meta-llama/llama-3.3-70b-instruct', name: 'Llama 3.3 70B Instruct', tag: 'Recommended', desc: 'Meta open weights model routed via lowest-latency host' },
            { id: 'google/gemini-2.0-flash-exp:free', name: 'Gemini 2.0 Flash (Free)', tag: '100% Free', desc: 'Free-tier Gemini routed via OpenRouter free cluster' },
            { id: 'deepseek/deepseek-chat', name: 'DeepSeek V3', tag: 'High Intelligence', desc: '671B MoE model with strong multilingual and reasoning capability' },
            { id: 'mistralai/mistral-7b-instruct:free', name: 'Mistral 7B (Free)', tag: 'Free Tier', desc: 'Fast, lightweight free option for simple queries' },
        ],
    },
};

const showAiKey = ref(false);
const showAdvancedAi = ref(false);
const aiTestLoading = ref(false);
const aiTestResult = ref(null);

const playgroundQuery = ref('What are the five pillars of Islam?');
const playgroundResponse = ref('');
const playgroundLatency = ref(0);
const playgroundLoading = ref(false);

const aiProviderName = computed(() => AI_PROVIDERS[formData.value.ai_provider]?.name || 'Groq');
const aiKeyLabel    = computed(() => AI_PROVIDERS[formData.value.ai_provider]?.keyLabel || aiProviderName.value);
const aiKeyUrl      = computed(() => AI_PROVIDERS[formData.value.ai_provider]?.keyUrl || AI_PROVIDERS.groq.keyUrl);

const currentProviderModels = computed(() => AI_PROVIDERS[formData.value.ai_provider]?.models || []);

const isCustomModel = ref(false);
const selectedModelChoice = computed({
    get() {
        let cur = formData.value.ai_model || '';
        // Auto-upgrade deprecated Gemini models to 3.6 Flash
        if (cur === 'gemini-2.0-flash' || cur === 'gemini-2.5-flash' || cur.includes('gemini-1.5')) {
            cur = 'gemini-3.6-flash';
            formData.value.ai_model = 'gemini-3.6-flash';
        }
        const exists = currentProviderModels.value.some(m => m.id === cur);
        if (!exists && cur !== '') {
            return '__custom__';
        }
        return cur || (AI_PROVIDERS[formData.value.ai_provider]?.defaultModel || '');
    },
    set(val) {
        if (val === '__custom__') {
            isCustomModel.value = true;
        } else {
            isCustomModel.value = false;
            formData.value.ai_model = val;
        }
    }
});

const currentModelObj = computed(() => {
    return currentProviderModels.value.find(m => m.id === formData.value.ai_model) || null;
});

const currentModelTag = computed(() => currentModelObj.value?.tag || (isCustomModel.value ? 'Custom' : ''));
const currentModelDesc = computed(() => currentModelObj.value?.desc || '');

const setAiProvider = (pKey) => {
    formData.value.ai_provider = pKey;
    const defaultModel = AI_PROVIDERS[pKey]?.defaultModel || '';
    formData.value.ai_model = defaultModel;
    isCustomModel.value = false;
    aiTestResult.value = null;
};

const onModelSelectChange = () => {
    aiTestResult.value = null;
};

const resetSystemPrompt = () => {
    formData.value.ai_system_prompt = DEFAULT_SYSTEM_PROMPT;
};

const copyResponse = () => {
    if (playgroundResponse.value) {
        navigator.clipboard.writeText(playgroundResponse.value);
        toast.success('Copied to clipboard');
    }
};

const testAiConnection = () => {
    aiTestLoading.value = true;
    aiTestResult.value = null;

    const payload = {
        ai_provider: formData.value.ai_provider || 'groq',
        ai_model: formData.value.ai_model || '',
        ai_api_key: formData.value.islamic_name_api_key || '',
        is_key_encrypted: !formData.value.is_typed_islamic_name,
    };

    Axios.post('ai/test-connection', payload)
        .then(({ data }) => { aiTestResult.value = data; })
        .catch(({ response }) => {
            aiTestResult.value = {
                success: false,
                message: response?.data?.message || 'Connection test failed. Check your API key and settings.',
            };
        })
        .finally(() => { aiTestLoading.value = false; });
};

const runPlaygroundPrompt = (query) => {
    if (!query || !query.trim()) return;
    playgroundLoading.value = true;
    playgroundResponse.value = '';
    playgroundQuery.value = query;

    const payload = {
        ai_provider: formData.value.ai_provider || 'groq',
        ai_model: formData.value.ai_model || '',
        ai_api_key: formData.value.islamic_name_api_key || '',
        is_key_encrypted: !formData.value.is_typed_islamic_name,
        test_prompt: query,
    };

    Axios.post('ai/test-connection', payload)
        .then(({ data }) => {
            if (data.success) {
                playgroundResponse.value = data.reply || data.message;
                playgroundLatency.value = data.latency_ms || 0;
            } else {
                playgroundResponse.value = '❌ ' + (data.message || 'Generation failed.');
            }
        })
        .catch(({ response }) => {
            playgroundResponse.value = '❌ Error: ' + (response?.data?.message || 'Request failed.');
        })
        .finally(() => {
            playgroundLoading.value = false;
        });
};

const submit = () => {
    preloader.value = true;
    errors.value = {};
    setOrDeleteProperty(formData.value, 'web_logo', webLogo.value);
    setOrDeleteProperty(formData.value, 'web_icon', webIcon.value);
    setOrDeleteProperty(formData.value, 'app_logo', appBanner.value);
    setOrDeleteProperty(formData.value, 'donation_banner', donationBanner.value);
    formData.value.show_donation_banner = formData.value.show_donation_banner === true ? 1 : 0;
    formData.value.show_donation_icon   = formData.value.show_donation_icon   === true ? 1 : 0;
    formData.value.ramadan_schedule     = formData.value.ramadan_schedule     === true ? 1 : 0;
    formData.value.ai_enabled           = formData.value.ai_enabled           === true ? 1 : 0;

    // Persist custom theme colors (or clear them when custom mode is off).
    formData.value.theme_use_custom = useCustom.value ? '1' : '0';
    formData.value.theme_c_mode = useCustom.value ? customMode.value : '';
    colorTokens.value.forEach(({ key }) => {
        formData.value['theme_c_' + key] = useCustom.value ? customColors.value[key] : '';
    });

    Axios.post('settings', formData.value, { headers: { 'Content-Type': 'multipart/form-data' } })
        .then(({ data }) => { toast.success(data.message); location.reload(); })
        .catch(({ response }) => {
            if (response.status === 422) errors.value = response.data.errors;
            else toast.error(response.data.message);
        })
        .finally(() => { preloader.value = false; });
};

function setOrDeleteProperty(obj, key, value) {
    if (value) obj[key] = value;
    else delete obj[key];
}

const pageLoader = ref(false);
const originalGoogleMapKey = ref(null);
const originalIslamicNameApiKey = ref(null);
const isInitialLoadComplete = ref(false);

const getServerData = () => {
    pageLoader.value = true;
    Axios.get('settings').then(({ data }) => {
        if (!_.isEmpty(data)) {
            formData.value = data;
            formData.value.web_logo          = data.web_logo        ? urlGenerator(data.web_logo)        : null;
            formData.value.web_icon          = data.web_icon        ? urlGenerator(data.web_icon)        : null;
            formData.value.app_logo          = data.app_logo        ? urlGenerator(data.app_logo)        : null;
            formData.value.donation_banner   = data.donation_banner ? urlGenerator(data.donation_banner) : null;
            formData.value.show_donation_banner = Number(data.show_donation_banner) === 1;
            formData.value.show_donation_icon   = Number(data.show_donation_icon)   === 1;
            formData.value.ramadan_schedule     = Number(data.ramadan_schedule)     === 1;

            formData.value.islamic_name_api_key = data.islamic_name_api_key || '';
            originalIslamicNameApiKey.value = data.islamic_name_api_key || '';

            formData.value.google_map_key       = data.google_map_key || '';
            originalGoogleMapKey.value          = data.google_map_key || '';

            formData.value.ai_enabled           = data.ai_enabled === undefined || data.ai_enabled === null ? true : Number(data.ai_enabled) === 1;
            formData.value.ai_provider          = data.ai_provider || 'groq';
            formData.value.ai_model             = data.ai_model || AI_PROVIDERS[formData.value.ai_provider]?.defaultModel || 'llama-3.3-70b-versatile';
            if (formData.value.ai_model === 'gemini-2.0-flash' || formData.value.ai_model === 'gemini-2.5-flash' || (formData.value.ai_model && formData.value.ai_model.includes('gemini-1.5'))) {
                formData.value.ai_model = 'gemini-3.6-flash';
            }
            formData.value.ai_system_prompt     = data.ai_system_prompt || DEFAULT_SYSTEM_PROMPT;
            formData.value.ai_temperature       = Number(data.ai_temperature || 0.7);
            formData.value.ai_max_tokens        = Number(data.ai_max_tokens || 600);

            isInitialLoadComplete.value         = true;
        }
    }).finally(() => { pageLoader.value = false; });
};

watch(() => formData.value.google_map_key, (newVal) => {
    if (!isInitialLoadComplete.value) return;
    if (newVal !== originalGoogleMapKey.value) formData.value.is_typed_g_map = true;
    else delete formData.value.is_typed_g_map;
});

watch(() => formData.value.islamic_name_api_key, (newVal) => {
    if (!isInitialLoadComplete.value) return;
    if (newVal !== originalIslamicNameApiKey.value) formData.value.is_typed_islamic_name = true;
    else delete formData.value.is_typed_islamic_name;
});

const getThemePresets = () => {
    return Axios.get('theme-presets').then(({ data }) => { themePresets.value = data; });
};

const HEX_RE = /^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/;
// Initialise custom-color state from saved settings once both loads finish.
function initTheme() {
    const d = formData.value || {};
    useCustom.value = String(d.theme_use_custom) === '1';
    seedFromPreset(); // start from the active preset...
    // ...then layer any saved overrides on top.
    colorTokens.value.forEach(({ key }) => {
        const saved = d['theme_c_' + key];
        if (typeof saved === 'string' && HEX_RE.test(saved)) customColors.value[key] = saved;
    });
    if (d.theme_c_mode === 'light' || d.theme_c_mode === 'dark') customMode.value = d.theme_c_mode;
    applyPreview();
}

// Live preview as the admin edits.
watch(useCustom, applyPreview);
watch(customMode, applyPreview);
watch(customColors, applyPreview, { deep: true });
watch(() => formData.value.app_theme, () => {
    if (!useCustom.value) seedFromPreset(); // follow the newly picked preset
    applyPreview();
});

onMounted(() => {
    pageLoader.value = true;
    Promise.all([
        Axios.get('settings').then(({ data }) => {
            if (!_.isEmpty(data)) {
                formData.value = data;
                formData.value.web_logo        = data.web_logo        ? urlGenerator(data.web_logo)        : null;
                formData.value.web_icon        = data.web_icon        ? urlGenerator(data.web_icon)        : null;
                formData.value.app_logo        = data.app_logo        ? urlGenerator(data.app_logo)        : null;
                formData.value.donation_banner = data.donation_banner ? urlGenerator(data.donation_banner) : null;
                formData.value.show_donation_banner = Number(data.show_donation_banner) === 1;
                formData.value.show_donation_icon   = Number(data.show_donation_icon)   === 1;
                formData.value.ramadan_schedule     = Number(data.ramadan_schedule)     === 1;
                formData.value.islamic_name_api_key = data.islamic_name_api_key || '';
                originalIslamicNameApiKey.value     = data.islamic_name_api_key || '';
                formData.value.google_map_key       = data.google_map_key || '';
                originalGoogleMapKey.value          = data.google_map_key || '';

                formData.value.ai_enabled           = data.ai_enabled === undefined || data.ai_enabled === null ? true : Number(data.ai_enabled) === 1;
                formData.value.ai_provider          = data.ai_provider || 'groq';
                formData.value.ai_model             = data.ai_model || AI_PROVIDERS[formData.value.ai_provider]?.defaultModel || 'llama-3.3-70b-versatile';
                if (formData.value.ai_model === 'gemini-2.0-flash' || formData.value.ai_model === 'gemini-2.5-flash' || (formData.value.ai_model && formData.value.ai_model.includes('gemini-1.5'))) {
                    formData.value.ai_model = 'gemini-3.6-flash';
                }
                formData.value.ai_system_prompt     = data.ai_system_prompt || DEFAULT_SYSTEM_PROMPT;
                formData.value.ai_temperature       = Number(data.ai_temperature || 0.7);
                formData.value.ai_max_tokens        = Number(data.ai_max_tokens || 600);

                isInitialLoadComplete.value         = true;
            }
        }),
        getThemePresets(),
    ]).then(() => { initTheme(); }).finally(() => { pageLoader.value = false; });
});
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&family=Playfair+Display:wght@500;600&display=swap');

.settings-wrapper {
    font-family: 'DM Sans', sans-serif;
    max-width: 900px;
    padding: 0 0 48px;
    color: var(--theme-text);
}

/* ── Header ── */
.settings-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
}
.header-icon {
    width: 46px; height: 46px;
    border-radius: 12px;
    background: var(--theme-primary);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(var(--theme-primary-rgb),.28);
}
.settings-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 600;
    color: var(--theme-text); margin: 0; line-height: 1.2;
}
.settings-subtitle {
    font-size: 0.84rem; color: var(--theme-text-faint); margin: 3px 0 0;
}

/* ── Card ── */
.settings-card {
    background: var(--theme-card);
    border-radius: 14px;
    border: 1px solid var(--theme-border);
    padding: 26px 28px;
    margin-bottom: 18px;
    box-shadow: 0 1px 3px rgba(0,0,0,.05);
    transition: box-shadow .2s ease;
}
.settings-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
}
.card-label {
    display: flex; align-items: center; gap: 8px;
    font-size: .7rem; font-weight: 600;
    letter-spacing: .1em; text-transform: uppercase;
    color: var(--theme-text-faint); margin-bottom: 20px;
}
.label-dot {
    width: 6px; height: 6px;
    border-radius: 50%; background: var(--theme-primary);
}

/* ── Fields ── */
.fields-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}
.two-col { grid-template-columns: 1fr 1fr; }

.field-group { display: flex; flex-direction: column; gap: 6px; }
.field-label {
    font-size: .82rem; font-weight: 500; color: var(--theme-text-muted);
}
.required { color: var(--theme-error); margin-inline-start: 2px; }

.field-input {
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem; color: var(--theme-text);
    background: var(--theme-muted-bg);
    border: 1.5px solid var(--theme-border);
    border-radius: 9px;
    padding: 10px 13px;
    outline: none; width: 100%; box-sizing: border-box;
    transition: border-color .18s, box-shadow .18s, background .18s;
}
.field-input::placeholder { color: #b0b9cc; }
.field-input:focus {
    border-color: var(--theme-secondary);
    background: var(--theme-card);
    box-shadow: 0 0 0 3px rgba(var(--theme-secondary-rgb),.11);
}
.field-textarea { resize: vertical; min-height: 120px; line-height: 1.6; }
.field-error { font-size: .77rem; color: var(--theme-error); }

/* ── Toggles ── */
.toggles-row {
    margin-top: 10px;
    border-top: 1px solid var(--theme-border);
    padding-top: 18px;
}
.toggle-item {
    display: flex; align-items: center; justify-content: space-between;
    padding: 11px 0;
    border-bottom: 1px solid var(--theme-border);
}
.toggle-item:last-child { border-bottom: none; }
.toggle-info { display: flex; flex-direction: column; gap: 2px; }
.toggle-label { font-size: .88rem; font-weight: 500; color: var(--theme-text); }
.toggle-desc { font-size: .77rem; color: var(--theme-text-faint); }

.toggle-switch { position: relative; display: inline-flex; cursor: pointer; }
.toggle-switch input { position: absolute; opacity: 0; width: 0; height: 0; }
.toggle-track {
    width: 44px; height: 24px;
    background: var(--theme-border);
    border-radius: 50px; position: relative;
    transition: background .2s ease; display: block;
}
.toggle-thumb {
    position: absolute; top: 3px; left: 3px;
    width: 18px; height: 18px;
    border-radius: 50%; background: var(--theme-card);
    box-shadow: 0 1px 4px rgba(0,0,0,.18);
    transition: transform .2s ease;
}
.toggle-switch input:checked + .toggle-track { background: var(--theme-primary); }
.toggle-switch input:checked + .toggle-track .toggle-thumb { transform: translateX(20px); }

/* ── Media ── */
.media-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
.media-item { display: flex; flex-direction: column; gap: 8px; align-items: center; }
.media-preview {
    position: relative; width: 100%; aspect-ratio: 1;
    border-radius: 10px; border: 1.5px solid var(--theme-border);
    background: var(--theme-muted-bg); overflow: hidden;
    display: flex; align-items: center; justify-content: center;
}
.preview-img { max-width: 80%; max-height: 80%; object-fit: contain; }
.preview-overlay {
    position: absolute; inset: 0;
    background: rgba(var(--theme-primary-rgb),.87);
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity .2s ease; border-radius: 10px;
}
.media-preview:hover .preview-overlay { opacity: 1; }
.overlay-btn {
    display: flex; align-items: center; gap: 5px;
    color: #fff; font-size: .78rem; font-weight: 500; cursor: pointer;
    padding: 6px 11px; border-radius: 6px;
    border: 1px solid rgba(255,255,255,.35);
    background: rgba(255,255,255,.1);
    transition: background .15s;
}
.overlay-btn:hover { background: rgba(255,255,255,.2); }
.hidden-input { display: none; }
.media-label { font-size: .77rem; font-weight: 500; color: var(--theme-text-muted); text-align: center; }

/* ── Appearance ── */
.section-desc { font-size: .82rem; color: var(--theme-text-faint); margin: -12px 0 18px; }
.theme-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 14px;
}
.theme-swatch {
    position: relative;
    display: flex; flex-direction: column; align-items: flex-start; gap: 8px;
    padding: 14px;
    background: var(--theme-muted-bg);
    border: 1.5px solid var(--theme-border);
    border-radius: 12px;
    cursor: pointer;
    font-family: 'DM Sans', sans-serif;
    text-align: start;
    transition: border-color .18s, box-shadow .18s, background .18s;
}
.theme-swatch:hover { border-color: #b7c3d6; }
.theme-swatch--active {
    border-color: var(--theme-secondary);
    background: var(--theme-card);
    box-shadow: 0 0 0 3px rgba(var(--theme-secondary-rgb),.11);
}
.theme-swatch-colors { display: flex; gap: 5px; }
.swatch-dot { width: 14px; height: 14px; border-radius: 50%; box-shadow: inset 0 0 0 1px rgba(0,0,0,.08); }
.theme-swatch-preview {
    width: 100%; height: 34px;
    border-radius: 8px;
    border: 1.5px solid;
    display: flex; align-items: flex-end; overflow: hidden;
}
.theme-swatch-bar { width: 100%; height: 8px; }
.theme-swatch-name { font-size: .85rem; font-weight: 600; color: var(--theme-text); }
.theme-swatch-mode { font-size: .72rem; color: var(--theme-text-faint); text-transform: capitalize; }
.theme-swatch-check {
    position: absolute; top: 10px; right: 10px;
    width: 20px; height: 20px; border-radius: 50%;
    background: var(--theme-secondary); color: #fff;
    display: flex; align-items: center; justify-content: center;
}

/* ── Custom colors ── */
.custom-theme {
    margin-top: 20px;
    border-top: 1px solid var(--theme-border);
    padding-top: 8px;
}
.toggle-item--flush { padding: 12px 0; border-bottom: none; }
.custom-colors {
    margin-top: 6px;
    padding: 18px;
    background: var(--theme-muted-bg);
    border: 1px solid var(--theme-border);
    border-radius: 12px;
}
.custom-colors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 14px;
}
.color-field { display: flex; flex-direction: column; gap: 6px; }
.color-field-label { font-size: .78rem; font-weight: 500; color: var(--theme-text-muted); }
.color-input-wrap {
    display: flex; align-items: center; gap: 8px;
    background: var(--theme-card);
    border: 1.5px solid var(--theme-border);
    border-radius: 9px;
    padding: 5px 8px;
}
.color-picker {
    width: 30px; height: 30px; padding: 0;
    border: none; background: none; cursor: pointer;
    border-radius: 6px; flex-shrink: 0;
}
.color-picker::-webkit-color-swatch-wrapper { padding: 0; }
.color-picker::-webkit-color-swatch { border: 1px solid rgba(0,0,0,.12); border-radius: 6px; }
.hex-input {
    border: none !important; background: transparent !important;
    padding: 0 !important; font-size: .82rem; text-transform: uppercase;
    box-shadow: none !important;
}
.reset-colors-btn {
    margin-top: 16px;
    background: transparent;
    border: 1.5px solid var(--theme-border);
    color: var(--theme-text-muted);
    font-family: 'DM Sans', sans-serif;
    font-size: .82rem; font-weight: 500;
    padding: 8px 16px; border-radius: 8px; cursor: pointer;
    transition: border-color .18s, color .18s;
}
.reset-colors-btn:hover { border-color: var(--theme-primary); color: var(--theme-primary); }

/* ── Submit ── */
.form-actions { display: flex; justify-content: flex-end; margin-top: 6px; }
.submit-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 26px;
    background: var(--theme-primary); color: #fff;
    font-family: 'DM Sans', sans-serif;
    font-size: .9rem; font-weight: 600;
    border: none; border-radius: 10px; cursor: pointer;
    transition: background .18s, transform .18s, box-shadow .18s;
    box-shadow: 0 4px 14px rgba(var(--theme-primary-rgb),.28);
}
.submit-btn:hover:not(:disabled) {
    background: var(--theme-secondary);
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(var(--theme-primary-rgb),.36);
}
.submit-btn:active:not(:disabled) { transform: translateY(0); }
.submit-btn:disabled { opacity: .6; cursor: not-allowed; }

@media (max-width: 680px) {
    .fields-grid, .two-col { grid-template-columns: 1fr; }
    .media-grid { grid-template-columns: repeat(2, 1fr); }
}

/* ── AI & Intelligence Engine Section ── */
.ai-settings-card {
    border-top: 3px solid var(--theme-secondary, #2E7D32);
}
.card-header-flex {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}
.ai-dot {
    background: linear-gradient(135deg, #10B981, #6366F1) !important;
}
.ai-status-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 20px;
}
.status-active {
    background: rgba(16, 185, 129, 0.12);
    color: #059669;
    border: 1px solid rgba(16, 185, 129, 0.28);
}
.status-inactive {
    background: rgba(100, 116, 139, 0.12);
    color: #64748B;
    border: 1px solid rgba(100, 116, 139, 0.25);
}
.status-indicator-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}
.toggle-item--ai {
    background: var(--theme-muted-bg);
    border: 1px solid var(--theme-border);
    padding: 12px 14px;
    border-radius: 10px;
}

/* ── AI Provider Grid ── */
.ai-provider-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 10px;
}
.ai-provider-card {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 12px;
    background: var(--theme-muted-bg);
    border: 1.5px solid var(--theme-border);
    border-radius: 12px;
    cursor: pointer;
    font-family: inherit;
    text-align: left;
    transition: all .18s ease;
}
.ai-provider-card:hover {
    border-color: #94A3B8;
    transform: translateY(-1px);
}
.ai-provider-card--active {
    border-color: var(--theme-secondary, #2E7D32);
    background: var(--theme-card);
    box-shadow: 0 0 0 3px rgba(var(--theme-secondary-rgb, 46, 125, 50), 0.12);
}
.provider-header {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.provider-badge {
    font-size: 10px;
    font-weight: 600;
    color: var(--theme-text-muted);
}
.provider-check {
    font-size: 11px;
    font-weight: 700;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--theme-secondary, #2E7D32);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}
.provider-name {
    font-size: 14px;
    font-weight: 600;
    color: var(--theme-text);
}
.provider-key-source {
    font-size: 11px;
    color: var(--theme-text-faint);
    margin-top: 2px;
}

/* ── Model & Key Styling ── */
.badge-subtle {
    font-size: 10px;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 12px;
    background: rgba(var(--theme-primary-rgb, 26, 107, 74), 0.1);
    color: var(--theme-primary, #1A6B4A);
    margin-left: 6px;
}
.field-helper {
    display: block;
    font-size: 11px;
    color: var(--theme-text-faint);
    margin-top: 4px;
    line-height: 1.35;
}
.ai-key-link {
    font-size: 11px;
    color: var(--theme-primary, #1A6B4A);
    text-decoration: underline;
    margin-left: 8px;
}
.ai-key-wrapper {
    position: relative;
}
.ai-key-input {
    padding-right: 42px;
}
.ai-key-toggle-btn {
    position: absolute;
    right: 8px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    font-size: 14px;
    color: var(--theme-text-muted);
    padding: 4px;
}

/* ── Advanced AI Settings ── */
.ai-advanced-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    background: var(--theme-muted-bg);
    border: 1px solid var(--theme-border);
    border-radius: 8px;
    font-size: 12px;
    font-weight: 500;
    color: var(--theme-text);
    cursor: pointer;
    user-select: none;
    transition: background .15s;
}
.ai-advanced-toggle:hover {
    background: var(--theme-card);
}
.ai-advanced-panel {
    padding: 14px;
    background: var(--theme-muted-bg);
    border: 1px solid var(--theme-border);
    border-radius: 10px;
}
.flex-between {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.btn-link-sm {
    font-size: 11px;
    color: var(--theme-primary, #1A6B4A);
    background: none;
    border: none;
    cursor: pointer;
    text-decoration: underline;
}
.field-range {
    width: 100%;
    margin-top: 6px;
    accent-color: var(--theme-primary, #1A6B4A);
}
.range-labels {
    display: flex;
    justify-content: space-between;
    font-size: 10px;
    color: var(--theme-text-faint);
    margin-top: 2px;
}

/* ── Testing & Diagnostics ── */
.ai-test-section {
    background: var(--theme-muted-bg);
    border: 1px solid var(--theme-border);
    border-radius: 12px;
    padding: 14px;
}
.ai-test-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.test-section-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--theme-text);
}
.test-section-subtitle {
    font-size: 11px;
    color: var(--theme-text-faint);
}
.test-connection-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: var(--theme-primary, #1A6B4A);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all .18s;
}
.test-connection-btn:hover:not(:disabled) {
    opacity: .9;
    transform: translateY(-1px);
}
.test-connection-btn:disabled {
    opacity: .6;
    cursor: not-allowed;
}

.test-result-banner {
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 12px;
}
.banner-success {
    background: #ECFDF5;
    border: 1px solid #A7F3D0;
    color: #065F46;
}
.banner-error {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #991B1B;
}
.banner-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
}
.latency-badge {
    background: rgba(0,0,0,0.08);
    font-size: 10px;
    font-weight: 700;
    padding: 1px 6px;
    border-radius: 10px;
}
.banner-msg {
    margin-top: 4px;
    font-size: 12px;
    word-break: break-word;
}

/* ── Interactive Playground ── */
.ai-playground {
    border-top: 1px solid var(--theme-border);
    padding-top: 12px;
}
.playground-header {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin-bottom: 8px;
}
.playground-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--theme-text);
}
.playground-sub {
    font-size: 11px;
    color: var(--theme-text-faint);
}
.sample-chips {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
}
.chip-caption {
    font-size: 11px;
    color: var(--theme-text-faint);
    font-weight: 500;
}
.sample-chip {
    background: var(--theme-card);
    border: 1px solid var(--theme-border);
    border-radius: 14px;
    padding: 3px 9px;
    font-size: 11px;
    color: var(--theme-text);
    cursor: pointer;
    transition: all .15s;
}
.sample-chip:hover {
    border-color: var(--theme-secondary, #2E7D32);
    color: var(--theme-secondary, #2E7D32);
}
.playground-input-row {
    display: flex;
    gap: 8px;
}
.playground-input {
    flex: 1;
    font-size: 13px;
}
.playground-send-btn {
    padding: 8px 16px;
    background: var(--theme-secondary, #2E7D32);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: opacity .18s;
}
.playground-send-btn:hover:not(:disabled) {
    opacity: .9;
}
.playground-send-btn:disabled {
    opacity: .5;
    cursor: not-allowed;
}

.playground-bubble {
    background: var(--theme-card);
    border: 1px solid var(--theme-border);
    border-radius: 10px;
    padding: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.bubble-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--theme-border);
    padding-bottom: 6px;
    margin-bottom: 8px;
}
.bubble-tag {
    font-size: 11px;
    font-weight: 600;
    color: var(--theme-secondary, #2E7D32);
}
.btn-copy {
    background: none;
    border: none;
    font-size: 11px;
    color: var(--theme-text-muted);
    cursor: pointer;
}
.btn-copy:hover {
    color: var(--theme-text);
}
.bubble-content {
    font-size: 13px;
    line-height: 1.55;
    color: var(--theme-text);
    white-space: pre-wrap;
    word-break: break-word;
}

.spinner-sm {
    display: inline-block;
    width: 13px;
    height: 13px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
