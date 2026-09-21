<template>
    <app-loader v-if="pageLoader" />
    <div v-else class="lp-wrapper">
        <!-- Header -->
        <div class="lp-header">
            <div class="lp-header-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                    <line x1="4" y1="17" x2="20" y2="17"/>
                </svg>
            </div>
            <div>
                <h2 class="lp-title">{{ $t('landing_footer.title') }}</h2>
                <p class="lp-subtitle">{{ $t('landing_footer.subtitle') }}</p>
            </div>
            <a href="/" target="_blank" class="preview-btn">
                <span>{{ $t('landing_footer.preview_page') }}</span>
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="7" y1="17" x2="17" y2="7"/>
                    <polyline points="7 7 17 7 17 17"/>
                </svg>
            </a>
        </div>

        <form @submit.prevent="submit">
            <!-- Brand & Content Inputs -->
            <div class="lp-card">
                <div class="card-label">
                    <span class="dot"></span>
                    <span>{{ $t('landing_footer.section.brand') }}</span>
                </div>
                <div class="fields-grid">
                    <div class="field-group full">
                        <label class="field-label">
                            {{ $t('landing_footer.form.app_name_label') }}
                            <span class="hint">({{ $t('landing_footer.form.app_name_hint') }})</span>
                        </label>
                        <input
                            class="field-input"
                            type="text"
                            :placeholder="$t('landing_footer.form.app_name_placeholder')"
                            v-model="form.app_name"
                        />
                        <small class="field-error" v-if="errors.app_name">{{ errors.app_name[0] }}</small>
                    </div>

                    <div class="field-group full">
                        <div class="label-with-meta">
                            <label class="field-label">{{ $t('landing_footer.form.footer_description_label') }}</label>
                            <span class="char-count">{{ (form.footer_description || '').length }} chars</span>
                        </div>
                        <textarea
                            class="field-input field-textarea"
                            rows="4"
                            :placeholder="$t('landing_footer.form.footer_description_placeholder')"
                            v-model="form.footer_description"
                        ></textarea>
                        <small class="field-error" v-if="errors.footer_description">{{ errors.footer_description[0] }}</small>
                    </div>
                </div>
            </div>

            <!-- Live Footer Preview Card -->
            <div class="lp-card">
                <div class="card-label-row">
                    <div class="card-label">
                        <span class="dot pulse"></span>
                        <span>{{ $t('landing_footer.section.footer_preview') }}</span>
                    </div>
                    <!-- Dark/Light Preview Toggle -->
                    <div class="preview-mode-toggle">
                        <button
                            type="button"
                            :class="['mode-btn', { active: previewMode === 'dark' }]"
                            @click="previewMode = 'dark'"
                        >
                            🌙 {{ $t('landing_footer.preview.mode_dark') }}
                        </button>
                        <button
                            type="button"
                            :class="['mode-btn', { active: previewMode === 'light' }]"
                            @click="previewMode = 'light'"
                        >
                            ☀️ {{ $t('landing_footer.preview.mode_light') }}
                        </button>
                    </div>
                </div>

                <!-- Preview Canvas -->
                <div :class="['footer-preview-canvas', previewMode]">
                    <div class="fp-glow top-glow"></div>
                    <div class="fp-accent-line"></div>

                    <!-- Main Columns -->
                    <div class="fp-grid">
                        <!-- Column 1: Brand & Bio -->
                        <div class="fp-col-brand">
                            <div class="fp-brand-row">
                                <div class="fp-icon-wrap">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2L3 9v11a2 2 0 002 2h14a2 2 0 002-2V9l-9-7z"/>
                                        <path d="M9 22V12h6v10"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="fp-bismillah">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>

                            <p class="fp-desc">
                                {{ form.footer_description || $t('landing_footer.preview.default_description') }}
                            </p>

                            <div class="fp-trust-pill">
                                <span class="trust-dot"></span>
                                <span>100% Ad-Free • Verified Islamic Sources</span>
                            </div>

                            <div class="fp-socials">
                                <span class="soc-icon">TG</span>
                                <span class="soc-icon">X</span>
                                <span class="soc-icon">YT</span>
                                <span class="soc-icon">GH</span>
                            </div>
                        </div>

                        <!-- Column 2: Mini Features -->
                        <div class="fp-col-nav">
                            <div class="fp-col-title">
                                <span class="mini-dot green"></span>
                                {{ $t('landing_footer.preview.features_title') }}
                            </div>
                            <div class="fp-nav-list">
                                <span class="fp-nav-item highlight">Holy Quran <small class="badge green">Audio</small></span>
                                <span class="fp-nav-item">Prayer Times & Adhan</span>
                                <span class="fp-nav-item highlight">AI Islamic Chat <small class="badge gold">AI</small></span>
                                <span class="fp-nav-item">Qibla Direction</span>
                                <span class="fp-nav-item">Zakat Calculator</span>
                            </div>
                        </div>

                        <!-- Column 3: Mini Resources -->
                        <div class="fp-col-nav">
                            <div class="fp-col-title">
                                <span class="mini-dot gold"></span>
                                {{ $t('landing_footer.preview.resources_title') }}
                            </div>
                            <div class="fp-nav-list">
                                <span class="fp-nav-item">Blog & Articles</span>
                                <span class="fp-nav-item">Privacy Policy</span>
                                <span class="fp-nav-item">Terms & Conditions</span>
                                <span class="fp-nav-item">Support & FAQ</span>
                                <span class="fp-nav-item">Admin Panel 🔒</span>
                            </div>
                        </div>

                        <!-- Column 4: Mini App Store -->
                        <div class="fp-col-stores">
                            <div class="fp-col-title">
                                <span class="mini-dot teal"></span>
                                {{ $t('landing_footer.preview.get_app_title') }}
                            </div>
                            <div class="fp-store-buttons">
                                <div class="fp-store-btn">
                                    <span class="store-ic"></span>
                                    <div>
                                        <div class="store-sub">App Store</div>
                                    </div>
                                </div>
                                <div class="fp-store-btn play">
                                    <span class="store-ic">▶</span>
                                    <div>
                                        <div class="store-sub">Google Play</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-footer Bar -->
                    <div class="fp-subfooter">
                        <div class="fp-copy-row">
                            <span>© {{ new Date().getFullYear() }} <strong>{{ form.app_name || 'Zabi' }}</strong> {{ $t('landing_footer.preview.copyright_suffix') }}</span>
                            <span class="fp-built">Built with Flutter • Laravel • Love ❤️</span>
                        </div>
                        <div class="fp-back-top">
                            <span>Back to top ↑</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="save-btn" :disabled="saving">
                    <app-button-loader v-if="saving" />
                    <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ $t('common.save') }}</span>
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Axios from '@/services/axios/index.js';
import { toast } from 'vue3-toastify';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const pageLoader = ref(false);
const saving = ref(false);
const errors = ref({});
const previewMode = ref('dark');
const form = ref({ app_name: '', footer_description: '' });

const load = () => {
    pageLoader.value = true;
    Axios.get('landing-settings')
        .then(({ data }) => {
            if (data) {
                form.value.app_name = data.app_name || '';
                form.value.footer_description = data.footer_description || '';
            }
        })
        .finally(() => {
            pageLoader.value = false;
        });
};

const footerFields = ['app_name', 'footer_description'];

const submit = () => {
    saving.value = true;
    errors.value = {};
    const payload = Object.fromEntries(footerFields.map(k => [k, form.value[k]]));
    Axios.post('landing-settings', payload)
        .then(({ data }) => {
            toast.success(data.message);
        })
        .catch(({ response }) => {
            if (response?.status === 422) {
                errors.value = response.data.errors;
            } else {
                toast.error(response?.data?.message ?? t('landing_footer.error_saving'));
            }
        })
        .finally(() => {
            saving.value = false;
        });
};

onMounted(load);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

.lp-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    max-width: 960px;
    padding-bottom: 48px;
    color: var(--theme-text);
}

.lp-header {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 24px;
}

.lp-header-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(var(--theme-primary-rgb), 0.25);
}

.lp-title {
    font-size: 1.35rem;
    font-weight: 700;
    margin: 0;
    color: var(--theme-text);
}

.lp-subtitle {
    font-size: 0.84rem;
    color: var(--theme-text-muted);
    margin: 2px 0 0;
}

.preview-btn {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 9px;
    border: 1px solid var(--theme-border);
    background: var(--theme-card);
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--theme-primary);
    text-decoration: none;
    transition: all 0.2s ease;
}

.preview-btn:hover {
    background: var(--theme-muted-bg);
    border-color: var(--theme-primary);
    transform: translateY(-1px);
}

.lp-card {
    background: var(--theme-card);
    border-radius: 16px;
    border: 1px solid var(--theme-border);
    padding: 24px 26px;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.card-label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
}

.card-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--theme-text-muted);
}

.dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: var(--theme-primary);
    flex-shrink: 0;
}

.dot.pulse {
    box-shadow: 0 0 0 0 rgba(var(--theme-primary-rgb), 0.4);
    animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
    0% { box-shadow: 0 0 0 0 rgba(var(--theme-primary-rgb), 0.5); }
    70% { box-shadow: 0 0 0 6px rgba(var(--theme-primary-rgb), 0); }
    100% { box-shadow: 0 0 0 0 rgba(var(--theme-primary-rgb), 0); }
}

.preview-mode-toggle {
    display: flex;
    background: var(--theme-muted-bg);
    border-radius: 8px;
    padding: 3px;
    gap: 4px;
}

.mode-btn {
    border: none;
    background: transparent;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--theme-text-muted);
    cursor: pointer;
    transition: all 0.18s ease;
}

.mode-btn.active {
    background: var(--theme-card);
    color: var(--theme-text);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.fields-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.label-with-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.field-label {
    font-size: 0.84rem;
    font-weight: 600;
    color: var(--theme-text);
}

.hint {
    font-size: 0.76rem;
    font-weight: 400;
    color: var(--theme-text-faint);
}

.char-count {
    font-size: 0.72rem;
    color: var(--theme-text-faint);
}

.field-input {
    font-family: inherit;
    font-size: 0.9rem;
    color: var(--theme-text);
    background: var(--theme-muted-bg);
    border: 1.5px solid var(--theme-border);
    border-radius: 10px;
    padding: 10px 14px;
    outline: none;
    width: 100%;
    box-sizing: border-box;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
}

.field-input:focus {
    border-color: var(--theme-primary);
    background: var(--theme-card);
    box-shadow: 0 0 0 3px rgba(var(--theme-primary-rgb), 0.12);
}

.field-textarea {
    resize: vertical;
    min-height: 96px;
    line-height: 1.55;
}

.field-error {
    font-size: 0.78rem;
    color: var(--theme-error);
    margin-top: 2px;
}

/* ================= Footer Preview Canvas ================= */
.footer-preview-canvas {
    position: relative;
    border-radius: 14px;
    padding: 24px 26px;
    overflow: hidden;
    transition: background 0.25s, color 0.25s;
    border: 1px solid rgba(255, 255, 255, 0.08);
}

.footer-preview-canvas.dark {
    background: #060b08;
    color: #e2e8f0;
    border-color: rgba(255, 255, 255, 0.08);
}

.footer-preview-canvas.light {
    background: #f8faf9;
    color: #0f172a;
    border-color: rgba(0, 0, 0, 0.08);
}

.fp-accent-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1.5px;
    background: linear-gradient(90deg, transparent, #10b981, transparent);
}

.fp-grid {
    display: grid;
    grid-template-columns: 2.2fr 1.3fr 1.3fr 1.5fr;
    gap: 20px;
    margin-bottom: 24px;
}

.fp-brand-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}

.fp-icon-wrap {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(16, 185, 129, 0.3);
}

.fp-name {
    font-weight: 700;
    font-size: 1.05rem;
}

.fp-pill {
    font-size: 0.62rem;
    font-weight: 700;
    padding: 1px 7px;
    border-radius: 9999px;
    background: rgba(16, 185, 129, 0.15);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.25);
}

.fp-bismillah {
    font-family: 'Amiri', serif;
    font-size: 1rem;
    color: #10b981;
    margin-bottom: 6px;
    opacity: 0.9;
}

.footer-preview-canvas.dark .fp-bismillah {
    color: #fbbf24;
}

.fp-desc {
    font-size: 0.78rem;
    line-height: 1.5;
    margin: 0 0 10px;
    opacity: 0.72;
    max-width: 260px;
}

.fp-trust-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.65rem;
    padding: 3px 8px;
    border-radius: 9999px;
    background: rgba(16, 185, 129, 0.08);
    border: 1px solid rgba(16, 185, 129, 0.18);
    color: #10b981;
    margin-bottom: 12px;
}

.trust-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #10b981;
}

.fp-socials {
    display: flex;
    gap: 6px;
}

.soc-icon {
    font-size: 0.62rem;
    font-weight: 700;
    width: 22px;
    height: 22px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(125, 125, 125, 0.1);
    opacity: 0.75;
}

.fp-col-title {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 10px;
    opacity: 0.85;
}

.mini-dot {
    width: 5px;
    height: 5px;
    border-radius: 50%;
}

.mini-dot.green { background: #10b981; }
.mini-dot.gold { background: #fbbf24; }
.mini-dot.teal { background: #14b8a6; }

.fp-nav-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.fp-nav-item {
    font-size: 0.74rem;
    opacity: 0.65;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.fp-nav-item.highlight {
    opacity: 0.85;
}

.badge {
    font-size: 0.58rem;
    padding: 1px 5px;
    border-radius: 4px;
    font-weight: 700;
}

.badge.green {
    background: rgba(16, 185, 129, 0.18);
    color: #10b981;
}

.badge.gold {
    background: rgba(251, 191, 36, 0.18);
    color: #f59e0b;
}

.fp-store-buttons {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.fp-store-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 6px 10px;
    border-radius: 8px;
    background: rgba(125, 125, 125, 0.08);
    border: 1px solid rgba(125, 125, 125, 0.15);
    font-size: 0.72rem;
    font-weight: 600;
}

.store-ic {
    font-size: 0.9rem;
}

.store-sub {
    font-size: 0.7rem;
    font-weight: 700;
}

.fp-subfooter {
    border-top: 1px solid rgba(125, 125, 125, 0.12);
    padding-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.7rem;
    opacity: 0.65;
}

.fp-copy-row {
    display: flex;
    gap: 10px;
    align-items: center;
}

.fp-built {
    opacity: 0.75;
}

.fp-back-top {
    padding: 3px 8px;
    border-radius: 6px;
    background: rgba(125, 125, 125, 0.1);
    font-weight: 600;
    font-size: 0.68rem;
}

/* Actions */
.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 10px;
}

.save-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 28px;
    background: linear-gradient(135deg, var(--theme-primary), var(--theme-secondary));
    color: #fff;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 600;
    border: none;
    border-radius: 11px;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(var(--theme-primary-rgb), 0.3);
    transition: all 0.2s ease;
}

.save-btn:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(var(--theme-primary-rgb), 0.4);
}

.save-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .fp-grid {
        grid-template-columns: 1fr 1fr;
    }
    .fp-col-brand {
        grid-column: 1 / -1;
    }
}

@media (max-width: 480px) {
    .fp-grid {
        grid-template-columns: 1fr;
    }
}
</style>
