<!DOCTYPE html>
<html lang="en" dir="ltr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Dark mode initialized from theme preset --}}
    <script>
        (function () {
            if ('{{ active_theme()['mode'] }}' === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else if (localStorage.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @php
        $seoTitle   = !empty($s['seo_title']) ? $s['seo_title'] : (($s['app_name'] ?? 'Zabi') . ' — ' . ($s['hero_title'] ?? 'Your Complete') . ' ' . ($s['hero_subtitle'] ?? 'Islamic Companion'));
        $seoDesc    = !empty($s['seo_description']) ? $s['seo_description'] : Str::limit($s['hero_description'] ?? '', 160);
        $ogTitle    = !empty($s['seo_og_title']) ? $s['seo_og_title'] : $seoTitle;
        $ogDesc     = !empty($s['seo_og_description']) ? $s['seo_og_description'] : $seoDesc;
        $twTitle    = !empty($s['seo_twitter_title']) ? $s['seo_twitter_title'] : $ogTitle;
        $twDesc     = !empty($s['seo_twitter_description']) ? $s['seo_twitter_description'] : $ogDesc;
        $ogImage    = !empty($s['seo_og_image']) ? asset($s['seo_og_image']) : (!empty($s['web_logo']) ? asset($s['web_logo']) : null);
    @endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDesc }}">
    @if(!empty($s['seo_keywords']))<meta name="keywords" content="{{ $s['seo_keywords'] }}">@endif
    <meta name="robots" content="{{ $s['seo_robots'] ?? 'index,follow' }}">
    @if(!empty($s['seo_canonical_url']))<link rel="canonical" href="{{ $s['seo_canonical_url'] }}">@endif
    @if(!empty($s['seo_google_verification']))<meta name="google-site-verification" content="{{ $s['seo_google_verification'] }}">@endif

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $s['app_name'] ?? 'Zabi' }}">
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $ogDesc }}">
    @if($ogImage)<meta property="og:image" content="{{ $ogImage }}">@endif
    @if(!empty($s['seo_canonical_url']))<meta property="og:url" content="{{ $s['seo_canonical_url'] }}">@endif

    <!-- Twitter Card -->
    <meta name="twitter:card" content="{{ $s['seo_twitter_card'] ?? 'summary_large_image' }}">
    <meta name="twitter:title" content="{{ $twTitle }}">
    <meta name="twitter:description" content="{{ $twDesc }}">
    @if($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

    <link rel="icon" href="{{ asset('assets/img/app-icon.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    @if(!empty($s['seo_google_analytics']))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $s['seo_google_analytics'] }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ $s['seo_google_analytics'] }}');</script>
    @endif

    <!-- Google Fonts: Plus Jakarta Sans, Inter, Amiri Calligraphy -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=Cairo:wght@400;600;700;800&family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- GSAP 3 & ScrollTrigger from CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Tailwind CSS with custom configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                            950: '#022c22',
                        },
                        gold: {
                            300: '#fde047',
                            400: '#facc15',
                            500: '#eab308',
                            600: '#ca8a04',
                        },
                        dark: {
                            base: '#070d0a',
                            surface: '#0d1712',
                            card: '#122019',
                            border: 'rgba(255, 255, 255, 0.08)'
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        arabic: ['Amiri', 'serif'],
                    }
                }
            }
        }
    </script>

    @include('partials.theme-vars')

    <style>
        :root {
            --lp-primary: var(--theme-primary, #059669);
            --lp-secondary: var(--theme-secondary, #10b981);
            --lp-accent: var(--theme-accent, #f59e0b);
            --lp-bg: #f8faf9;
            --lp-card: #ffffff;
            --lp-border: rgba(0, 0, 0, 0.07);
            --lp-text: #0f172a;
            --lp-text-muted: #64748b;
        }

        .dark {
            --lp-bg: #060b08;
            --lp-card: #0d1611;
            --lp-border: rgba(255, 255, 255, 0.08);
            --lp-text: #f8fafc;
            --lp-text-muted: #94a3b8;
        }

        html {
            scroll-padding-top: 110px;
        }

        section[id] {
            scroll-margin-top: 110px;
        }

        body {
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: var(--lp-bg);
            color: var(--lp-text);
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Glassmorphism Classes */
        .glass-island {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(0, 0, 0, 0.06);
        }
        .dark .glass-island {
            background: rgba(13, 22, 17, 0.75);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.04);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .dark .glass-card {
            background: rgba(13, 22, 17, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.07);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .glass-card:hover {
            border-color: rgba(16, 185, 129, 0.3);
            box-shadow: 0 20px 40px -15px rgba(16, 185, 129, 0.15);
        }

        /* Ambient Glow Mesh */
        .ambient-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            opacity: 0.3;
            z-index: 0;
        }
        .dark .ambient-orb {
            opacity: 0.2;
        }

        /* Arabic Typography */
        .font-arabic {
            font-family: 'Amiri', 'Cairo', serif;
            font-feature-settings: "cv01", "ss01";
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.25);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.5);
        }

        /* Text gradient */
        .text-gradient-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dark .text-gradient-emerald {
            background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #facc15 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .text-gradient-gold {
            background: linear-gradient(135deg, #facc15 0%, #eab308 50%, #ca8a04 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Wave visualizer bars (calm, static heights) */
        .wave-bar {
            width: 3px;
            background: #10b981;
            border-radius: 4px;
        }

        /* Mobile Menu */
        #mobile-drawer {
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: top center;
        }
        #mobile-drawer.closed {
            opacity: 0;
            transform: scale(0.96) translateY(-8px);
            pointer-events: none;
            max-height: 0;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin-top: 0 !important;
            border-color: transparent !important;
            overflow: hidden !important;
        }
        #mobile-drawer.open {
            opacity: 1;
            transform: scale(1) translateY(0);
            pointer-events: auto;
            max-height: 85vh;
            overflow-y: auto;
        }

        /* Animated Hamburger Icon */
        .hamburger-line {
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
            transform-origin: 12px 12px;
        }
        .is-open .line-1 {
            transform: translateY(6px) rotate(45deg);
        }
        .is-open .line-2 {
            opacity: 0;
            transform: scaleX(0);
        }
        .is-open .line-3 {
            transform: translateY(-6px) rotate(-45deg);
        }

        /* Active language option highlight */
        .lang-option.active-lang {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            font-weight: 700;
        }
        .lang-option.active-lang .lang-check {
            display: inline-block !important;
        }
        .mobile-lang-btn.active-lang {
            background: #10b981;
            color: #fff;
        }
    </style>
</head>
<body class="selection:bg-brand-500 selection:text-white">

    <!-- Ambient background glows -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="ambient-orb w-[500px] h-[500px] -top-32 -left-32 bg-emerald-500/20"></div>
        <div class="ambient-orb w-[600px] h-[600px] top-1/3 -right-48 bg-teal-500/15"></div>
        <div class="ambient-orb w-[550px] h-[550px] bottom-10 left-1/4 bg-amber-500/10"></div>
    </div>

    <!-- ===================== FLOATING NAVBAR ===================== -->
    <header class="fixed top-4 inset-x-0 mx-auto w-[92%] max-w-5xl z-50 transition-all duration-300">
        <!-- Interactive Scroll Progress Bar -->
        <div class="w-full h-[2.5px] bg-black/5 dark:bg-white/5 overflow-hidden rounded-full mb-1.5 backdrop-blur-xs">
            <div id="scroll-progress-bar" class="h-full bg-gradient-to-r from-emerald-500 via-teal-400 to-amber-400 w-0 transition-all duration-100 rounded-full shadow-[0_0_10px_rgba(16,185,129,0.7)]"></div>
        </div>

        <div id="navbar" class="glass-island rounded-full px-4 sm:px-5 py-2 sm:py-2.5 shadow-lg flex items-center justify-between transition-all duration-300 border border-white/20 dark:border-white/10 relative">
            <!-- Brand Logo -->
            <a href="#" class="flex items-center group shrink-0 select-none" title="{{ $s['app_name'] ?? 'Deenio' }}" aria-label="{{ $s['app_name'] ?? 'Deenio' }}">
                <img src="{{ asset('assets/img/deenio-logo-hr.png') }}" alt="{{ $s['app_name'] ?? 'Deenio' }}" class="h-8 sm:h-9 max-h-9 w-auto object-contain transition-transform duration-200 group-hover:scale-105 dark:hidden">
                <img src="{{ asset('assets/img/deenio-logo-hr-white.png') }}" alt="{{ $s['app_name'] ?? 'Deenio' }}" class="h-8 sm:h-9 max-h-9 w-auto object-contain transition-transform duration-200 group-hover:scale-105 hidden dark:block">
            </a>

            <!-- Desktop Main Navigation Menu -->
            <nav class="hidden md:flex items-center gap-1 lg:gap-1.5">
                <a href="#features" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                    <svg class="w-3.5 h-3.5 opacity-70 group-hover:opacity-100 group-hover:text-emerald-500 transition-all shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/>
                    </svg>
                    <span data-i18n="nav.features">Features</span>
                </a>
                <a href="#quran" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                    <svg class="w-3.5 h-3.5 opacity-70 group-hover:opacity-100 group-hover:text-emerald-500 transition-all shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <span data-i18n="nav.quran">Quran</span>
                </a>
                <a href="#prayer" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                    <svg class="w-3.5 h-3.5 opacity-70 group-hover:opacity-100 group-hover:text-emerald-500 transition-all shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/>
                    </svg>
                    <span data-i18n="nav.prayer">Prayer</span>
                </a>
                <a href="#ai-chat" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                    <svg class="w-3.5 h-3.5 text-emerald-500 dark:text-emerald-400 group-hover:scale-110 transition-transform shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                    </svg>
                    <span data-i18n="nav.ai">AI Guide</span>
                </a>
                <a href="#tasbih" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-brand-600 dark:hover:text-brand-400 hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                    <svg class="w-3.5 h-3.5 opacity-70 group-hover:opacity-100 group-hover:text-emerald-500 transition-all shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="2.2"/><circle cx="16.5" cy="9.5" r="2.2"/><circle cx="16.5" cy="14.5" r="2.2"/><circle cx="12" cy="17" r="2.2"/><circle cx="7.5" cy="14.5" r="2.2"/><circle cx="7.5" cy="9.5" r="2.2"/>
                    </svg>
                    <span data-i18n="dhikr.tasbih">Tasbih</span>
                </a>
                <a href="#donate" class="group flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-amber-500 dark:hover:text-amber-400 hover:bg-amber-500/10 transition-all">
                    <svg class="w-3.5 h-3.5 text-amber-500 group-hover:scale-110 transition-transform shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                    </svg>
                    <span data-i18n="nav.donate">Donate</span>
                </a>
            </nav>

            <!-- Actions Cluster (Lang Switcher + Dark Mode + Mobile Toggle) -->
            <div class="flex items-center gap-1.5 sm:gap-2">
                <!-- 6-Language Dropdown -->
                <div class="relative">
                    <button id="lang-menu-btn" type="button" onclick="toggleLangDropdown(event)" aria-label="Select Language" class="group flex items-center gap-1.5 px-2.5 py-1.5 rounded-full text-xs font-semibold bg-black/[0.04] dark:bg-white/10 hover:bg-black/[0.08] dark:hover:bg-white/15 text-gray-700 dark:text-gray-200 border border-black/5 dark:border-white/10 transition-all shadow-xs">
                        <span id="current-lang-flag" class="text-sm">🇬🇧</span>
                        <span id="current-lang-code" class="uppercase font-mono tracking-wider font-bold text-[11px]">EN</span>
                        <svg id="lang-chevron" class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="lang-dropdown" class="hidden absolute right-0 mt-2 w-44 glass-island rounded-2xl shadow-2xl py-2 border border-black/5 dark:border-white/10 z-50 backdrop-blur-2xl">
                        <div class="px-3.5 py-1 text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Language</div>
                        <div class="space-y-0.5 px-1.5 mt-1">
                            <button type="button" onclick="setLanguage('en')" class="lang-option w-full text-left px-3 py-2 text-xs font-medium hover:bg-brand-500 hover:text-white flex items-center justify-between transition-colors rounded-xl active-lang" data-lang="en">
                                <span class="flex items-center gap-2"><span>🇬🇧</span> English</span>
                                <span class="lang-check text-emerald-500 font-bold">✓</span>
                            </button>
                            <button type="button" onclick="setLanguage('ar')" class="lang-option w-full text-left px-3 py-2 text-xs font-medium hover:bg-brand-500 hover:text-white flex items-center justify-between transition-colors rounded-xl" data-lang="ar">
                                <span class="flex items-center gap-2"><span>🇸🇦</span> العربية</span>
                                <span class="lang-check hidden text-emerald-500 font-bold">✓</span>
                            </button>
                            <button type="button" onclick="setLanguage('bn')" class="lang-option w-full text-left px-3 py-2 text-xs font-medium hover:bg-brand-500 hover:text-white flex items-center justify-between transition-colors rounded-xl" data-lang="bn">
                                <span class="flex items-center gap-2"><span>🇧🇩</span> বাংলা</span>
                                <span class="lang-check hidden text-emerald-500 font-bold">✓</span>
                            </button>
                            <button type="button" onclick="setLanguage('hi')" class="lang-option w-full text-left px-3 py-2 text-xs font-medium hover:bg-brand-500 hover:text-white flex items-center justify-between transition-colors rounded-xl" data-lang="hi">
                                <span class="flex items-center gap-2"><span>🇮🇳</span> हिन्दी</span>
                                <span class="lang-check hidden text-emerald-500 font-bold">✓</span>
                            </button>
                            <button type="button" onclick="setLanguage('es')" class="lang-option w-full text-left px-3 py-2 text-xs font-medium hover:bg-brand-500 hover:text-white flex items-center justify-between transition-colors rounded-xl" data-lang="es">
                                <span class="flex items-center gap-2"><span>🇪🇸</span> Español</span>
                                <span class="lang-check hidden text-emerald-500 font-bold">✓</span>
                            </button>
                            <button type="button" onclick="setLanguage('fr')" class="lang-option w-full text-left px-3 py-2 text-xs font-medium hover:bg-brand-500 hover:text-white flex items-center justify-between transition-colors rounded-xl" data-lang="fr">
                                <span class="flex items-center gap-2"><span>🇫🇷</span> Français</span>
                                <span class="lang-check hidden text-emerald-500 font-bold">✓</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Theme Toggle Button -->
                <button onclick="toggleDarkMode()" aria-label="Toggle Theme" class="p-2 rounded-full text-gray-600 dark:text-gray-300 hover:bg-black/[0.05] dark:hover:bg-white/10 transition-colors border border-black/5 dark:border-white/10" title="Toggle theme">
                    <svg id="theme-sun-icon" class="w-4 h-4 hidden dark:block text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <svg id="theme-moon-icon" class="w-4 h-4 block dark:hidden text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>

                <!-- Mobile Animated Hamburger Button -->
                <button id="mobile-menu-btn" onclick="toggleMobileMenu()" aria-label="Toggle Mobile Menu" class="md:hidden p-2 rounded-xl text-gray-700 dark:text-gray-200 hover:bg-black/5 dark:hover:bg-white/5 transition-all">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <line class="hamburger-line line-1" x1="4" y1="6" x2="20" y2="6" stroke-width="2.2" stroke-linecap="round"/>
                        <line class="hamburger-line line-2" x1="4" y1="12" x2="20" y2="12" stroke-width="2.2" stroke-linecap="round"/>
                        <line class="hamburger-line line-3" x1="4" y1="18" x2="20" y2="18" stroke-width="2.2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-drawer" class="closed md:hidden mt-2 mx-auto w-full glass-island rounded-3xl p-5 shadow-2xl border border-black/5 dark:border-white/10 flex flex-col gap-2.5 backdrop-blur-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-black/5 dark:border-white/5">
                <img src="{{ asset('assets/img/deenio-logo-hr.png') }}" alt="{{ $s['app_name'] ?? 'Deenio' }}" class="h-7 w-auto object-contain dark:hidden">
                <img src="{{ asset('assets/img/deenio-logo-hr-white.png') }}" alt="{{ $s['app_name'] ?? 'Deenio' }}" class="h-7 w-auto object-contain hidden dark:block">
                <span class="text-xs font-arabic text-brand-600 dark:text-brand-400">بِسْمِ اللَّهِ</span>
            </div>

            <a href="#features" onclick="toggleMobileMenu()" class="py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/>
                    </svg>
                    <span data-i18n="nav.features">Features</span>
                </span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="#quran" onclick="toggleMobileMenu()" class="py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <span data-i18n="nav.quran">Holy Quran</span>
                </span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="#prayer" onclick="toggleMobileMenu()" class="py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"/><polyline points="12 7 12 12 15 15"/>
                    </svg>
                    <span data-i18n="nav.prayer">Prayer Times</span>
                </span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="#ai-chat" onclick="toggleMobileMenu()" class="py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/>
                    </svg>
                    <span data-i18n="nav.ai">AI Islamic Guide</span>
                </span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="#tasbih" onclick="toggleMobileMenu()" class="py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-brand-600 dark:hover:text-brand-400 transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="7" r="2.2"/><circle cx="16.5" cy="9.5" r="2.2"/><circle cx="16.5" cy="14.5" r="2.2"/><circle cx="12" cy="17" r="2.2"/><circle cx="7.5" cy="14.5" r="2.2"/><circle cx="7.5" cy="9.5" r="2.2"/>
                    </svg>
                    <span data-i18n="dhikr.tasbih">Digital Tasbih</span>
                </span>
                <span class="text-gray-400 text-xs">→</span>
            </a>
            <a href="#donate" onclick="toggleMobileMenu()" class="py-2.5 text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-amber-500 transition-colors flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <svg class="w-4 h-4 text-amber-500 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                    </svg>
                    <span data-i18n="nav.donate">Support & Donate</span>
                </span>
                <span class="text-amber-500 text-xs">❤️</span>
            </a>

            <!-- Mobile Language Quick Switch Grid -->
            <div class="py-2.5 border-t border-black/5 dark:border-white/5">
                <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-2">Language</div>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="setLanguage('en'); toggleMobileMenu();" class="mobile-lang-btn py-1.5 px-2 rounded-xl text-xs font-semibold bg-black/5 dark:bg-white/5 hover:bg-brand-500 hover:text-white transition-colors text-center active-lang" data-lang="en">🇬🇧 EN</button>
                    <button type="button" onclick="setLanguage('ar'); toggleMobileMenu();" class="mobile-lang-btn py-1.5 px-2 rounded-xl text-xs font-semibold bg-black/5 dark:bg-white/5 hover:bg-brand-500 hover:text-white transition-colors text-center" data-lang="ar">🇸🇦 العربية</button>
                    <button type="button" onclick="setLanguage('bn'); toggleMobileMenu();" class="mobile-lang-btn py-1.5 px-2 rounded-xl text-xs font-semibold bg-black/5 dark:bg-white/5 hover:bg-brand-500 hover:text-white transition-colors text-center" data-lang="bn">🇧🇩 বাংলা</button>
                    <button type="button" onclick="setLanguage('hi'); toggleMobileMenu();" class="mobile-lang-btn py-1.5 px-2 rounded-xl text-xs font-semibold bg-black/5 dark:bg-white/5 hover:bg-brand-500 hover:text-white transition-colors text-center" data-lang="hi">🇮🇳 हिन्दी</button>
                    <button type="button" onclick="setLanguage('es'); toggleMobileMenu();" class="mobile-lang-btn py-1.5 px-2 rounded-xl text-xs font-semibold bg-black/5 dark:bg-white/5 hover:bg-brand-500 hover:text-white transition-colors text-center" data-lang="es">🇪🇸 ES</button>
                    <button type="button" onclick="setLanguage('fr'); toggleMobileMenu();" class="mobile-lang-btn py-1.5 px-2 rounded-xl text-xs font-semibold bg-black/5 dark:bg-white/5 hover:bg-brand-500 hover:text-white transition-colors text-center" data-lang="fr">🇫🇷 FR</button>
                </div>
            </div>
        </div>
    </header>

    <!-- ===================== HERO SECTION ===================== -->
    <section id="hero" class="relative min-h-[92vh] pt-36 pb-20 flex items-center justify-center overflow-hidden z-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 w-full grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">

            <!-- Hero Left Text -->
            <div class="lg:col-span-7 flex flex-col items-center lg:items-start text-center lg:text-left gsap-hero-left">
                <!-- Pill Badge -->
                <div class="inline-flex items-center gap-2.5 px-4 py-1.5 rounded-full glass-card border border-brand-500/30 text-xs font-semibold text-brand-700 dark:text-brand-300 mb-6 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                    <span data-i18n="hero.badge" data-i18n-en="{{ $s['hero_badge_text'] }}">{{ $s['hero_badge_text'] }}</span>
                </div>

                <!-- Main Display Headline -->
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white leading-[1.12] mb-6">
                    <span data-i18n="hero.title" data-i18n-en="{{ $s['hero_title'] }}">{{ $s['hero_title'] }}</span><br>
                    <span class="text-gradient-emerald">
                        <span data-i18n="hero.subtitle" data-i18n-en="{{ $s['hero_subtitle'] }}">{{ $s['hero_subtitle'] }}</span>
                    </span>
                </h1>

                <!-- Subtitle Description -->
                <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-xl mb-9 leading-relaxed" data-i18n="hero.description" data-i18n-en="{{ $s['hero_description'] }}">
                    {{ $s['hero_description'] }}
                </p>

                <!-- Store Action CTAs -->
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 mb-10 w-full sm:w-auto">
                    <!-- Apple App Store -->
                    <a href="{{ $s['app_store_url'] }}" target="_blank" class="flex items-center gap-3 px-6 py-3.5 rounded-2xl glass-card border border-black/10 dark:border-white/10 hover:border-brand-500/50 transition-all group shadow-sm">
                        <svg class="w-7 h-7 text-gray-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 leading-none">Download on the</div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white tracking-tight" data-i18n="hero.app_store">App Store</div>
                        </div>
                    </a>

                    <!-- Google Play Store -->
                    <a href="{{ $s['play_store_url'] }}" target="_blank" class="flex items-center gap-3 px-6 py-3.5 rounded-2xl glass-card border border-black/10 dark:border-white/10 hover:border-brand-500/50 transition-all group shadow-sm">
                        <svg class="w-7 h-7 text-gray-900 dark:text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.18 23.76c.27.15.58.2.89.13l12.89-7.43-2.77-2.76-11.01 10.06zm-.99-20.29v18.06c0 .36.1.69.28.97l10.32-10.32L2.19 2.5c-.0 .0-.0.0-.0 0zm16.12 6.77l-2.44-1.41-3.08 3.09 3.09 3.09 2.44-1.41c.7-.4 1.12-1.15 1.12-1.68 0-.53-.42-1.27-1.13-1.68zm-14.7-8.47c.04-.01.08-.01.12-.01.31 0 .61.09.87.26l12.01 6.93-2.76 2.76L4.44.73c-.31-.18-.66-.21-.93-.05l-.0-.91z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 leading-none">Get it on</div>
                            <div class="text-sm font-bold text-gray-900 dark:text-white tracking-tight" data-i18n="hero.play_store">Google Play</div>
                        </div>
                    </a>
                </div>

                <!-- Trust Metrics / Rating -->
                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex text-amber-400">
                        ★★★★★
                    </div>
                    <span class="font-medium"><strong class="text-gray-800 dark:text-gray-200">4.9/5 Rating</strong> • Trusted by 100,000+ Believers</span>
                </div>
            </div>

            <!-- Hero Right Mockup with Calm Widgets -->
            <div class="lg:col-span-5 relative flex justify-center gsap-hero-right">
                <!-- Outer Glow Halo -->
                <div class="absolute inset-0 bg-gradient-to-tr from-brand-500/10 to-amber-500/10 rounded-[60px] blur-2xl -z-10"></div>

                <!-- Modern Phone Chassis -->
                <div class="relative w-72 sm:w-80 rounded-[50px] p-4 bg-gradient-to-b from-gray-900/90 to-gray-950/95 border-[4px] border-white/20 shadow-2xl backdrop-blur-2xl text-white">
                    <!-- Dynamic Island / Speaker Notch -->
                    <div class="w-24 h-4 bg-black rounded-full mx-auto mb-4 flex items-center justify-center">
                        <div class="w-2 h-2 rounded-full bg-emerald-500/80"></div>
                    </div>

                    <!-- Inner Mockup UI -->
                    <div class="space-y-4">
                        <!-- Bismillah header -->
                        <div class="text-center pt-2">
                            <span class="font-arabic text-xl text-amber-300">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</span>
                        </div>

                        <!-- Live Next Prayer Card -->
                        <div class="rounded-2xl p-4 bg-white/10 backdrop-blur-md border border-white/15">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs uppercase tracking-wider text-emerald-400 font-bold">Next Prayer</span>
                                <span class="text-[11px] px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 font-semibold">In 24 mins</span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-2xl font-black">Asr</span>
                                <span class="text-xl font-mono text-white/90">03:45 PM</span>
                            </div>
                        </div>

                        <!-- Daily Ayah Card -->
                        <div class="rounded-2xl p-4 bg-white/5 border border-white/10">
                            <div class="text-[10px] uppercase font-bold text-white/40 mb-1">Ayah of the Day</div>
                            <p class="font-arabic text-lg text-emerald-200 text-right leading-relaxed mb-2" dir="rtl">فَاذْكُرُونِي أَذْكُرْكُمْ وَاشْكُرُوا لِي</p>
                            <p class="text-xs text-white/70 italic">"So remember Me; I will remember you."</p>
                        </div>

                        <!-- Mini Waveform Player Mock -->
                        <div class="rounded-2xl p-3.5 bg-gradient-to-r from-emerald-950/80 to-emerald-900/80 border border-emerald-500/30 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-gray-950">
                                    <svg class="w-4 h-4 fill-current ml-0.5" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-white">Surah Al-Mulk</div>
                                    <div class="text-[10px] text-emerald-300">Mishary Rashid Alafasy</div>
                                </div>
                            </div>
                            <div class="flex items-end gap-1 h-5">
                                <span class="wave-bar" style="height: 8px;"></span>
                                <span class="wave-bar" style="height: 18px;"></span>
                                <span class="wave-bar" style="height: 12px;"></span>
                                <span class="wave-bar" style="height: 16px;"></span>
                                <span class="wave-bar" style="height: 10px;"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calm Badges -->
                <!-- Badge 1: Qibla -->
                <div class="absolute -top-4 -right-4 sm:-right-8 px-3.5 py-2 rounded-2xl glass-card border border-emerald-500/30 text-xs font-bold shadow-xl flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-xl bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-emerald-500 shrink-0">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" fill="currentColor"></polygon>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] text-gray-900 dark:text-white">Qibla Compass</div>
                        <div class="text-[9px] text-brand-600 dark:text-brand-400 font-semibold">99.8% Precision</div>
                    </div>
                </div>

                <!-- Badge 2: Tasbih -->
                <div class="absolute -bottom-5 -left-4 sm:-left-8 px-3.5 py-2 rounded-2xl glass-card border border-amber-500/30 text-xs font-bold shadow-xl flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-xl bg-amber-500/15 border border-amber-500/30 flex items-center justify-center text-amber-500 shrink-0">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="12" cy="6" r="1.5"></circle>
                            <circle cx="16" cy="8" r="1.5"></circle>
                            <circle cx="17.5" cy="12" r="1.5"></circle>
                            <circle cx="15.5" cy="16" r="1.5"></circle>
                            <circle cx="8" cy="8" r="1.5"></circle>
                            <circle cx="6.5" cy="12" r="1.5"></circle>
                            <circle cx="8.5" cy="16" r="1.5"></circle>
                            <circle cx="12" cy="18" r="1.5"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[11px] text-gray-900 dark:text-white">Digital Tasbih</div>
                        <div class="text-[9px] text-amber-500 font-semibold">Haptic Feedback</div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ===================== STATS SECTION ===================== -->
    <section id="stats" class="relative py-12 z-10">
        <div class="max-w-6xl mx-auto px-6">
            <div class="glass-card rounded-3xl p-8 border border-black/5 dark:border-white/10 shadow-xl grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <!-- Stat 1 -->
                <div class="flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-1 tracking-tight stat-counter" data-target="114">114</div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400" data-i18n="stats.surahs">Surahs of Quran</div>
                </div>
                <!-- Stat 2 -->
                <div class="flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-extrabold text-brand-600 dark:text-brand-400 mb-1 tracking-tight stat-counter" data-target="40" data-suffix="+">40+</div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400" data-i18n="stats.languages">Languages Supported</div>
                </div>
                <!-- Stat 3 -->
                <div class="flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-extrabold text-amber-500 mb-1 tracking-tight stat-counter" data-target="4.9" data-decimals="1">4.9★</div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400" data-i18n="stats.rating">App Store Rating</div>
                </div>
                <!-- Stat 4 -->
                <div class="flex flex-col items-center">
                    <div class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-1 tracking-tight stat-counter" data-target="100" data-suffix="K+">100K+</div>
                    <div class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400" data-i18n="stats.downloads">Active Downloads</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== BENTO GRID FEATURES ===================== -->
    <section id="features" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <!-- Section Header -->
            <div class="text-center max-w-2xl mx-auto mb-16 gsap-reveal">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-brand-500/10 text-brand-700 dark:text-brand-300 mb-4 border border-brand-500/20">
                    <svg class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2l2.4 5.5L20 9.8l-4 3.9 1 5.6-5-2.8-5 2.8 1-5.6-4-3.9 5.6-2.3L12 2z"/>
                    </svg>
                    <span>EVERYTHING YOU NEED</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-4">
                    <span data-i18n="features.title" data-i18n-en="{{ $s['features_title'] }}">{{ $s['features_title'] }}</span>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed" data-i18n="features.desc" data-i18n-en="{{ $s['features_description'] }}">
                    {{ $s['features_description'] }}
                </p>
            </div>

            <!-- Bento Grid -->
            <div class="grid md:grid-cols-3 gap-6 gsap-bento-grid">
                <!-- Bento 1: Holy Quran (Wide - 2 Columns) -->
                <div class="md:col-span-2 glass-card rounded-3xl p-8 flex flex-col justify-between group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500/20 via-emerald-500/10 to-teal-500/5 border border-emerald-500/25 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-sm group-hover:scale-105 group-hover:border-emerald-500/40 transition-transform duration-300">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 4v14a2 2 0 0 0 2 2h6.5a2.5 2.5 0 0 1 2 1 2.5 2.5 0 0 1 2-1H21a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-6.5a2.5 2.5 0 0 0-2 1 2.5 2.5 0 0 0-2-1H4a2 2 0 0 0-2 2z" fill="currentColor" fill-opacity="0.08"></path>
                                <path d="M12 4v16"></path>
                                <path d="M6 8h4"></path>
                                <path d="M6 12h4"></path>
                                <path d="M14 8h4"></path>
                                <path d="M14 12h4"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/20">114 Surahs</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Complete Quran & Audio Recitations</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Experience the Holy Quran with crystal-clear Arabic calligraphy, phonetic transliteration, verse-by-verse audio, and translations in over 40 languages.
                        </p>
                        <div class="flex flex-wrap gap-2 text-xs font-medium text-gray-700 dark:text-gray-300">
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">100+ Reciters</span>
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">Word-by-word</span>
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">Offline Audio</span>
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">Bookmarks</span>
                        </div>
                    </div>
                </div>

                <!-- Bento 2: Prayer Times & Qibla (1 Column) -->
                <div class="glass-card rounded-3xl p-8 flex flex-col justify-between group hover:border-amber-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500/20 via-amber-500/10 to-yellow-500/5 border border-amber-500/25 flex items-center justify-center text-amber-500 dark:text-amber-400 shadow-sm mb-6 group-hover:scale-105 group-hover:border-amber-500/40 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2.5L3.5 7.2v9.6L12 21.5l8.5-4.7V7.2L12 2.5z" fill="currentColor" fill-opacity="0.12"></path>
                            <path d="M12 21.5V11.8"></path>
                            <path d="M20.5 7.2L12 11.8 3.5 7.2"></path>
                            <path d="M3.5 9.8L12 14.3l8.5-4.5" stroke="currentColor" stroke-width="2.2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Prayer Times & Qibla</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Ultra-precise GPS prayer schedules, beautiful Adhan alerts, Ramadan Sehri/Iftar calendar, and 360° Qibla compass.
                        </p>
                        <span class="inline-flex items-center text-xs font-bold text-amber-500 hover:text-amber-600 dark:hover:text-amber-400 transition-colors">
                            Live Auto-Location &rarr;
                        </span>
                    </div>
                </div>

                <!-- Bento 3: AI Islamic Guide (1 Column) -->
                <div class="glass-card rounded-3xl p-8 flex flex-col justify-between group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500/20 via-emerald-500/10 to-teal-500/5 border border-emerald-500/25 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-sm mb-6 group-hover:scale-105 group-hover:border-emerald-500/40 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 3c-4.97 0-9 3.58-9 8a7.86 7.86 0 0 0 3 6.13V21l3.6-1.8c.76.2 1.56.3 2.4.3 4.97 0 9-3.58 9-8s-4.03-8-9-8z" fill="currentColor" fill-opacity="0.08"></path>
                            <path d="M12 7.5l.8 1.9 1.9.8-1.9.8-.8 1.9-.8-1.9-1.9-.8 1.9-.8z" fill="currentColor" stroke="none"></path>
                            <path d="M16.5 12.5l.5 1 1 .5-1 .5-.5 1-.5-1-1-.5 1-.5z" fill="currentColor" stroke="none"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">AI Islamic Assistant</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Ask complex questions regarding fiqh, daily duas, and authentic Hadith citations with sub-second AI responses.
                        </p>
                        <span class="inline-flex items-center text-xs font-bold text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                            Powered by AI &rarr;
                        </span>
                    </div>
                </div>

                <!-- Bento 4: Digital Tasbih (1 Column) -->
                <div class="glass-card rounded-3xl p-8 flex flex-col justify-between group hover:border-teal-500/30 transition-all duration-300">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500/20 via-teal-500/10 to-cyan-500/5 border border-teal-500/25 flex items-center justify-center text-teal-600 dark:text-teal-400 shadow-sm mb-6 group-hover:scale-105 group-hover:border-teal-500/40 transition-transform duration-300">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="5.5" r="1.5" fill="currentColor"></circle>
                            <circle cx="16" cy="7.2" r="1.5" fill="currentColor"></circle>
                            <circle cx="18" cy="10.8" r="1.5" fill="currentColor"></circle>
                            <circle cx="16.8" cy="14.8" r="1.5" fill="currentColor"></circle>
                            <circle cx="13.5" cy="17.2" r="1.5" fill="currentColor"></circle>
                            <circle cx="8" cy="7.2" r="1.5" fill="currentColor"></circle>
                            <circle cx="6" cy="10.8" r="1.5" fill="currentColor"></circle>
                            <circle cx="7.2" cy="14.8" r="1.5" fill="currentColor"></circle>
                            <circle cx="10.5" cy="17.2" r="1.5" fill="currentColor"></circle>
                            <path d="M12 18.5v3"></path>
                            <path d="M10 21.5h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Digital Tasbih & Dhikr</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Satisfying tactile tap counter, haptic feedback, custom Adhkar, 99 Names of Allah, and morning/evening duas.
                        </p>
                        <span class="inline-flex items-center text-xs font-bold text-teal-500 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">
                            Try Interactive Below &darr;
                        </span>
                    </div>
                </div>

                <!-- Bento 5: Name Generator & Halal (Wide - 2 Columns) -->
                <div class="md:col-span-2 glass-card rounded-3xl p-8 flex flex-col justify-between group hover:border-indigo-500/30 transition-all duration-300">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500/20 via-indigo-500/10 to-purple-500/5 border border-indigo-500/25 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shadow-sm group-hover:scale-105 group-hover:border-indigo-500/40 transition-transform duration-300">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="currentColor" fill-opacity="0.12"></path>
                                <path d="M17 4l.6 1.4L19 6l-1.4.6L17 8l-.6-1.4L15 6l1.4-.6z" fill="currentColor" stroke="none"></path>
                            </svg>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20">Meaningful Names</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Islamic Name Generator & Halal Food Guide</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 leading-relaxed">
                            Discover deeply meaningful baby names with Arabic script and origin. Check everyday food ingredients with our built-in E-code Haram/Halal scanner.
                        </p>
                        <div class="flex flex-wrap gap-2 text-xs font-medium text-gray-700 dark:text-gray-300">
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">Boys & Girls Names</span>
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">E-code Checker</span>
                            <span class="px-3 py-1 rounded-full bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/5">Zakat Calculator</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== INTERACTIVE QURAN STUDIO ===================== -->
    <section id="quran" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-14 gsap-reveal">
                <div class="font-arabic text-2xl text-brand-600 dark:text-amber-400 mb-3">
                    اقْرَأْ بِاسْمِ رَبِّكَ الَّذِي خَلَقَ
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                    <span data-i18n="quran.title" data-i18n-en="{{ $s['quran_title'] }}">{{ $s['quran_title'] }}</span>
                    <span class="text-gradient-emerald">
                        <span data-i18n="quran.highlight" data-i18n-en="{{ $s['quran_title_highlight'] }}">{{ $s['quran_title_highlight'] }}</span>
                    </span>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-base" data-i18n="quran.description" data-i18n-en="{{ $s['quran_description'] }}">
                    {{ $s['quran_description'] }}
                </p>
            </div>

            <!-- Studio Interface Card -->
            <div class="glass-card rounded-3xl p-6 lg:p-8 border border-black/10 dark:border-white/10 shadow-2xl">
                <!-- Studio Controls Bar -->
                <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-black/5 dark:border-white/10">
                    <div class="flex flex-wrap items-center gap-3">
                        <!-- Surah Selector -->
                        <div class="relative">
                            <select id="qa-surah-select" onchange="qaLoadSurah(this.value)" class="glass-island rounded-xl px-4 py-2 text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 outline-none cursor-pointer border border-black/10 dark:border-white/10">
                                <option value="" disabled selected>Select Surah...</option>
                            </select>
                        </div>

                        <!-- Reciter Selector -->
                        <div class="relative">
                            <select id="qa-reciter-select" onchange="qaSetReciter(this.value)" class="glass-island rounded-xl px-4 py-2 text-xs sm:text-sm font-semibold text-gray-800 dark:text-gray-200 outline-none cursor-pointer border border-black/10 dark:border-white/10">
                                <option value="" disabled selected>Select Reciter...</option>
                            </select>
                        </div>
                    </div>

                    <!-- Translator Tabs -->
                    <div id="qa-translator-tabs" class="flex items-center gap-2 overflow-x-auto max-w-md py-1">
                        <!-- Filled by JS -->
                    </div>

                    <!-- Toggles -->
                    <div class="flex items-center gap-2">
                        <button id="qa-idx-toggle" onclick="qaToggleIndex()" class="px-3 py-1.5 rounded-lg text-xs font-semibold glass-island border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 text-gray-700 dark:text-gray-300 transition-all">
                            Index
                        </button>
                        <button id="qa-trans-toggle" onclick="qaToggleTranslation()" class="px-3 py-1.5 rounded-lg text-xs font-semibold glass-island border border-black/10 dark:border-white/10 hover:bg-black/5 dark:hover:bg-white/5 text-gray-700 dark:text-gray-300 transition-all">
                            Translation
                        </button>
                    </div>
                </div>

                <!-- Studio Audio Bar -->
                <div class="py-5 flex flex-wrap items-center justify-between gap-4 border-b border-black/5 dark:border-white/10">
                    <!-- Playback controls -->
                    <div class="flex items-center gap-3">
                        <button onclick="qaPrevSurah()" class="p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300" title="Previous Surah">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><polygon points="19 20 9 12 19 4 19 20"></polygon><line x1="5" y1="19" x2="5" y2="5" stroke="currentColor" stroke-width="2"></line></svg>
                        </button>

                        <button id="qa-play-btn" onclick="qaTogglePlay()" class="w-12 h-12 rounded-full bg-gradient-to-r from-brand-600 to-emerald-500 hover:from-brand-500 hover:to-emerald-400 text-white flex items-center justify-center shadow-lg shadow-brand-500/30 transition-transform active:scale-95">
                            <span id="qa-play-icon">
                                <svg class="w-5 h-5 fill-current ml-0.5" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            </span>
                        </button>

                        <button onclick="qaNextSurah()" class="p-2 rounded-full hover:bg-black/5 dark:hover:bg-white/10 text-gray-700 dark:text-gray-300" title="Next Surah">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 4 15 12 5 20 5 4"></polygon><line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2"></line></svg>
                        </button>
                    </div>

                    <!-- Progress bar -->
                    <div class="flex-1 min-w-[200px] flex items-center gap-3">
                        <span id="qa-cur-time" class="text-xs font-mono text-gray-500">0:00</span>
                        <div id="qa-progress-bar" onclick="qaSeekAudio(event)" class="flex-1 h-2 rounded-full bg-black/5 dark:bg-white/10 cursor-pointer overflow-hidden relative">
                            <div id="qa-progress-fill" class="h-full bg-gradient-to-r from-brand-600 to-emerald-400 w-0 rounded-full transition-all"></div>
                        </div>
                        <span id="qa-total-time" class="text-xs font-mono text-gray-500">0:00</span>
                    </div>

                    <!-- Volume slider -->
                    <div class="hidden sm:flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                        <input id="qa-volume" type="range" min="0" max="100" value="80" onchange="qaSetVolume(this.value)" class="w-20 accent-brand-500 h-1">
                    </div>

                    <audio id="qa-audio" preload="none"></audio>
                </div>

                <!-- Surah Display Header -->
                <div class="text-center py-6">
                    <div id="qa-surah-arabic" class="font-arabic text-3xl sm:text-4xl text-brand-700 dark:text-amber-400 mb-1">الفاتحة</div>
                    <div class="text-sm font-bold text-gray-900 dark:text-white">
                        <span id="qa-surah-english">Al-Fatiha</span> • <span id="qa-surah-count" class="text-gray-500">7 verses</span>
                    </div>
                    <div id="qa-surah-place" class="text-xs text-gray-400"></div>
                    <div id="qa-bismillah" class="font-arabic text-2xl text-emerald-600 dark:text-emerald-400 mt-4">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
                </div>

                <!-- Main Quran Content Panel -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 min-h-[420px]">
                    <!-- Left: Verse Index Drawer -->
                    <div id="qa-index-container" class="md:col-span-1 max-h-[500px] overflow-y-auto glass-island rounded-2xl p-2 border border-black/5 dark:border-white/10 hidden md:block">
                        <div id="qa-verse-index" class="space-y-1">
                            <div class="p-6 text-center text-gray-400 text-xs">Select Surah to load verses</div>
                        </div>
                    </div>

                    <!-- Right: Ayahs Container -->
                    <div class="md:col-span-3 max-h-[500px] overflow-y-auto space-y-4 pr-2">
                        <div id="qa-placeholder" class="h-64 flex flex-col items-center justify-center text-center p-6">
                            <span class="text-4xl mb-3">📖</span>
                            <h4 class="text-base font-bold text-gray-800 dark:text-gray-200">Start Your Recitation</h4>
                            <p class="text-xs text-gray-500 max-w-sm">Choose any Surah from the dropdown above to read and listen to the Holy Quran.</p>
                        </div>

                        <div id="qa-loading" class="hidden h-64 flex-col items-center justify-center text-center">
                            <div class="w-8 h-8 border-4 border-brand-500 border-t-transparent rounded-full animate-spin mb-3"></div>
                            <span class="text-xs text-gray-400 font-medium">Fetching translation & verses...</span>
                        </div>

                        <div id="qa-verses" class="space-y-4">
                            <!-- Injected by JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== PRAYER & QIBLA SECTION ===================== -->
    <section id="prayer" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid lg:grid-cols-12 gap-12 items-center">

                <!-- Left Details -->
                <div class="lg:col-span-6 gsap-reveal">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-700 dark:text-amber-300 mb-4">
                        🕌 NEVER MISS A SALAH
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">
                        <span data-i18n="prayer.title" data-i18n-en="{{ $s['prayer_title'] }}">{{ $s['prayer_title'] }}</span>
                        <span class="text-gradient-gold">
                            <span data-i18n="prayer.highlight" data-i18n-en="{{ $s['prayer_title_highlight'] }}">{{ $s['prayer_title_highlight'] }}</span>
                        </span>
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 text-base leading-relaxed mb-8" data-i18n="prayer.description" data-i18n-en="{{ $s['prayer_description'] }}">
                        {{ $s['prayer_description'] }}
                    </p>

                    <!-- Features bullets -->
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-bold text-sm">✓</div>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Accurate GPS Calculation</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-bold text-sm">✓</div>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Multiple Adhan Voices</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-bold text-sm">✓</div>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Ramadan Iftar & Sehri</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-500/10 flex items-center justify-center text-emerald-500 font-bold text-sm">✓</div>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Interactive Qibla Finder</span>
                        </div>
                    </div>
                </div>

                <!-- Right Live Prayer Card & Qibla Compass -->
                <div class="lg:col-span-6 grid sm:grid-cols-2 gap-6 gsap-reveal">
                    <!-- Prayer Times Card -->
                    <div class="glass-card rounded-3xl p-6 border border-black/10 dark:border-white/10 shadow-xl">
                        <div class="flex items-center justify-between pb-4 mb-4 border-b border-black/5 dark:border-white/5">
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-gray-400">Prayer Times</div>
                                <div id="prayer-city" class="text-base font-bold text-gray-900 dark:text-white">Detecting location...</div>
                            </div>
                            <span class="text-xl">🕌</span>
                        </div>
                        <div id="prayer-list" class="space-y-3">
                            <!-- Populated by JS -->
                        </div>
                    </div>

                    <!-- Qibla Compass Card -->
                    <div class="glass-card rounded-3xl p-6 border border-black/10 dark:border-white/10 shadow-xl flex flex-col items-center justify-center text-center">
                        <div class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-6">Qibla Direction</div>
                        <!-- Compass Dial Mockup -->
                        <div class="relative w-40 h-40 rounded-full border-4 border-emerald-500/30 flex items-center justify-center shadow-inner">
                            <div class="absolute top-2 text-[10px] font-bold text-emerald-500">N</div>
                            <div class="absolute bottom-2 text-[10px] font-bold text-gray-400">S</div>
                            <div class="absolute right-2 text-[10px] font-bold text-gray-400">E</div>
                            <div class="absolute left-2 text-[10px] font-bold text-gray-400">W</div>
                            <!-- Compass Needle -->
                            <div class="w-1.5 h-24 bg-gradient-to-t from-gray-400 via-amber-400 to-emerald-500 rounded-full transform -rotate-45 shadow-lg"></div>
                            <div class="w-6 h-6 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-md border border-white/20">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2.5L4 7v10l8 4.5 8-4.5V7L12 2.5z" fill="currentColor" fill-opacity="0.25"></path>
                                    <path d="M12 21.5V11.5"></path>
                                    <path d="M20 7L12 11.5 4 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="text-sm font-bold text-gray-900 dark:text-white">Kaaba Angle: 124° SE</div>
                            <div class="text-xs text-gray-500">Calibrated via device sensors</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ===================== AI ISLAMIC ASSISTANT ===================== -->
    <section id="ai-chat" class="py-24 lg:py-32 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-10 gsap-reveal">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 mb-4 border border-emerald-500/20 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>{{ $s['ai_chat_badge'] ?? '⚡ Live AI Scholar • Instant Answers' }}</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-4">
                    {{ $s['ai_chat_title_line1'] ?? 'Ask Any' }} <span class="text-gradient-emerald">{{ $s['ai_chat_title_line2'] ?? 'Islamic Question' }}</span>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base leading-relaxed">
                    {{ $s['ai_chat_description'] ?? 'Receive instant, authentic answers referenced from Quran and Hadith. Lightning-fast AI, right here on the page.' }}
                </p>
            </div>

            <!-- Modern Conversational UI Card -->
            <div class="glass-card rounded-[32px] border border-black/10 dark:border-white/10 shadow-2xl bg-white dark:bg-[#0c1611] overflow-hidden">
                <!-- Chat Window Header Bar -->
                <div class="px-6 py-4 border-b border-black/5 dark:border-white/5 flex items-center justify-between bg-emerald-50/40 dark:bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-emerald-500/20">
                                AI
                            </div>
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white dark:border-[#0c1611]"></span>
                        </div>
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                <span>Zabi Islamic AI Scholar</span>
                                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">Verified</span>
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Online • Authentic Quran & Sunnah references</div>
                        </div>
                    </div>
                    <div class="hidden sm:flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400 px-3 py-1 rounded-full bg-black/5 dark:bg-white/5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Knowledge Base Active
                        </span>
                    </div>
                </div>

                <!-- Suggested Prompt Chips -->
                <div class="px-6 pt-4 pb-3 border-b border-black/5 dark:border-white/5 bg-gray-50/50 dark:bg-black/20 flex flex-wrap items-center gap-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold mr-1">Try asking:</span>
                    <button onclick="sendQuickChatQuestion('What is the dua before sleeping?')" class="text-xs px-3.5 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-gray-700 dark:text-gray-300 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all border border-black/10 dark:border-white/10 hover:border-emerald-300 shadow-xs flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                        <span>Dua before sleeping</span>
                    </button>
                    <button onclick="sendQuickChatQuestion('What are the 5 pillars of Islam?')" class="text-xs px-3.5 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-gray-700 dark:text-gray-300 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all border border-black/10 dark:border-white/10 hover:border-emerald-300 shadow-xs flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 text-amber-500 dark:text-amber-400 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2v4m-4 16v-6a2 2 0 0 1 4 0v6m-8 0h16M4 10h16M8 6h8l1 4H7l1-4z"></path>
                        </svg>
                        <span>Pillars of Islam</span>
                    </button>
                    <button onclick="sendQuickChatQuestion('How to perform Wudu correctly?')" class="text-xs px-3.5 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-gray-700 dark:text-gray-300 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all border border-black/10 dark:border-white/10 hover:border-emerald-300 shadow-xs flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 text-cyan-500 dark:text-cyan-400 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                        </svg>
                        <span>How to do Wudu</span>
                    </button>
                    <button onclick="sendQuickChatQuestion('What is the ruling on fasting in Ramadan?')" class="text-xs px-3.5 py-1.5 rounded-full bg-white dark:bg-white/5 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 text-gray-700 dark:text-gray-300 hover:text-emerald-700 dark:hover:text-emerald-300 transition-all border border-black/10 dark:border-white/10 hover:border-emerald-300 shadow-xs flex items-center gap-2 group">
                        <svg class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>Ramadan rules</span>
                    </button>
                </div>

                <!-- Chat History Messages Container -->
                <div id="ai-chat-messages" class="h-80 sm:h-96 overflow-y-auto space-y-4 p-6 bg-slate-50/40 dark:bg-black/30 text-sm">
                    <!-- Default Welcome Message -->
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm mt-0.5">
                            AI
                        </div>
                        <div class="p-4 rounded-2xl rounded-tl-sm bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 text-gray-800 dark:text-gray-200 max-w-xl leading-relaxed shadow-sm">
                            Assalamu Alaikum! I am your AI Islamic assistant. Ask me anything about Quran, Hadith, Salah, or daily Muslim life.
                        </div>
                    </div>
                </div>

                <!-- Input Box Container -->
                <div class="p-4 sm:p-5 border-t border-black/5 dark:border-white/5 bg-white dark:bg-[#0c1611]">
                    <div class="flex items-center gap-3">
                        <div class="relative flex-1">
                            <input id="ai-chat-input" type="text" onkeydown="handleAIChatKeydown(event)" placeholder="Type your Islamic question here..." class="w-full rounded-2xl pl-5 pr-12 py-3.5 text-sm bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 outline-none border border-black/10 dark:border-white/10 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-mono hidden sm:inline-block">↵ Enter</span>
                        </div>
                        <button id="ai-chat-send" onclick="sendAIChatMessage()" class="px-6 py-3.5 rounded-2xl bg-gradient-to-r from-brand-600 to-emerald-500 hover:from-brand-500 hover:to-emerald-400 text-white font-bold text-sm hover:shadow-lg hover:shadow-brand-500/25 active:scale-95 transition-all flex items-center gap-2">
                            <span>Send</span>
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </div>
                    <div class="mt-2 text-center text-[11px] text-gray-400 dark:text-gray-500">
                        References drawn from authentic Islamic scholarship. Always verify complex rulings with local scholars.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== INTERACTIVE DIGITAL TASBIH ===================== -->
    <section id="tasbih" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-brand-500/10 text-brand-700 dark:text-brand-300 mb-4">
                📿 REMEMBRANCE OF ALLAH
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">
                Digital Tasbih Counter
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-base max-w-xl mx-auto mb-12">
                Keep your tongue moist with the remembrance of Allah wherever you go.
            </p>

            <!-- Tasbih Dial Card -->
            <div class="glass-card rounded-[40px] p-10 max-w-md mx-auto border border-black/10 dark:border-white/10 shadow-2xl flex flex-col items-center">
                <!-- Dhikr Selector Pills -->
                <div class="flex flex-wrap justify-center gap-2 mb-8">
                    <button onclick="setTasbihDhikr('سُبْحَانَ اللَّهِ', 'SubhanAllah')" class="tasbih-phrase-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-brand-500 text-white">SubhanAllah</button>
                    <button onclick="setTasbihDhikr('الْحَمْدُ لِلَّهِ', 'Alhamdulillah')" class="tasbih-phrase-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300">Alhamdulillah</button>
                    <button onclick="setTasbihDhikr('اللَّهُ أَكْبَرُ', 'Allahu Akbar')" class="tasbih-phrase-btn px-3.5 py-1.5 rounded-full text-xs font-bold bg-black/5 dark:bg-white/5 text-gray-700 dark:text-gray-300">Allahu Akbar</button>
                </div>

                <!-- Current Dhikr Display -->
                <div id="tasbih-arabic" class="font-arabic text-3xl text-brand-600 dark:text-amber-400 mb-1">سُبْحَانَ اللَّهِ</div>
                <div id="tasbih-trans" class="text-xs font-semibold text-gray-500 mb-8">SubhanAllah</div>

                <!-- Tap Button Dial -->
                <button id="tasbih-tap-btn" onclick="incrementTasbih()" class="w-48 h-48 rounded-full bg-gradient-to-br from-brand-500 to-emerald-700 hover:from-brand-400 hover:to-emerald-600 text-white shadow-2xl shadow-brand-500/40 flex flex-col items-center justify-center transition-transform active:scale-95 group select-none">
                    <span id="tasbih-count" class="text-5xl font-black tracking-tight mb-1">0</span>
                    <span class="text-xs uppercase font-bold tracking-widest text-emerald-100 opacity-80 group-hover:opacity-100">TAP</span>
                </button>

                <!-- Action Toolbar (Reset) -->
                <div class="mt-8 flex items-center gap-6">
                    <button onclick="resetTasbih()" class="text-xs font-semibold text-gray-500 hover:text-rose-500 flex items-center gap-1.5 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>Reset Count</span>
                    </button>
                    <span class="text-gray-300 dark:text-gray-700">•</span>
                    <span class="text-xs text-gray-400">Target: <strong class="text-gray-700 dark:text-gray-300">33 / 99</strong></span>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== ISLAMIC NAME GENERATOR ===================== -->
    <section id="name-generator" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-12 gsap-reveal">
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 text-indigo-700 dark:text-indigo-300 mb-4">
                    👶 BLESSED NAMES
                </div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-gray-900 dark:text-white mb-3">
                    <span data-i18n="ai.name_title" data-i18n-en="{{ $s['name_gen_title'] }}">{{ $s['name_gen_title'] }}</span>
                    <span class="text-gradient-emerald">{{ $s['name_gen_title_highlight'] }}</span>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base">
                    {{ $s['name_gen_description'] ?? 'Discover beautiful Islamic names with Arabic script, roots, and virtues.' }}
                </p>
            </div>

            <div class="glass-card rounded-3xl p-8 border border-black/10 dark:border-white/10 shadow-2xl">
                <!-- Controls -->
                <div class="flex flex-wrap items-center justify-center gap-4 mb-8">
                    <!-- Gender toggle -->
                    <div class="flex items-center gap-2 p-1.5 glass-island rounded-2xl border border-black/5 dark:border-white/10">
                        <button id="ng-btn-boy" onclick="selectNameGender('boy')" class="px-5 py-2 rounded-xl text-xs font-bold transition-all bg-brand-600 text-white">
                            Boys
                        </button>
                        <button id="ng-btn-girl" onclick="selectNameGender('girl')" class="px-5 py-2 rounded-xl text-xs font-bold transition-all text-gray-600 dark:text-gray-400">
                            Girls
                        </button>
                    </div>

                    <!-- Theme input -->
                    <input id="ng-theme" type="text" placeholder="Theme e.g. Wisdom, Light, Peace..." class="glass-island rounded-2xl px-5 py-2.5 text-xs sm:text-sm text-gray-800 dark:text-gray-200 outline-none border border-black/10 dark:border-white/10 min-w-[240px]">

                    <!-- Generate Button -->
                    <button id="ng-generate-btn" onclick="generateIslamicNames()" class="px-6 py-2.5 rounded-2xl bg-gradient-to-r from-brand-600 to-emerald-500 text-white font-bold text-xs sm:text-sm hover:scale-105 active:scale-95 transition-all shadow-lg shadow-brand-500/20">
                        <span id="ng-btn-text">Generate Names</span>
                        <span id="ng-btn-loader" class="hidden">Generating...</span>
                    </button>
                </div>

                <div id="ng-error" class="hidden text-center text-xs text-rose-500 mb-4"></div>

                <!-- Results Output Grid -->
                <div id="ng-placeholder" class="text-center py-10 text-gray-400 text-xs">
                    Select a gender and click generate to discover meaningful Islamic names.
                </div>

                <div id="ng-results-wrap" class="hidden">
                    <div id="ng-results-label" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4 text-center"></div>
                    <div id="ng-names-grid" class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <!-- Populated by JS -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== DONATION / SADAQAH SECTION ===================== -->
    <section id="donate" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-4xl mx-auto px-6">
            <div class="glass-card rounded-[36px] p-6 sm:p-10 lg:p-12 border border-brand-500/20 shadow-2xl relative overflow-hidden bg-white/95 dark:bg-[#0c1611]/95 backdrop-blur-xl">
                <!-- Background Accent -->
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="text-center max-w-2xl mx-auto mb-8">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-brand-500/10 text-brand-700 dark:text-brand-300 mb-3 border border-brand-500/20">
                        <svg class="w-3.5 h-3.5 text-brand-600 dark:text-brand-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" fill="currentColor" fill-opacity="0.15"></path>
                        </svg>
                        <span>SADAQAH JARIYAH</span>
                    </div>
                    <div class="font-arabic text-2xl sm:text-3xl text-brand-600 dark:text-amber-400 mb-2 leading-relaxed" dir="rtl">
                        {{ $s['donation_verse_arabic'] ?? 'مَّثَلُ الَّذِينَ يُنفِقُونَ أَمْوَالَهُمْ فِي سَبِيلِ اللَّهِ كَمَثَلِ حَبَّةٍ أَنبَتَتْ سَبْعَ سَنَابِلَ' }}
                    </div>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 italic mb-4 max-w-xl mx-auto">
                        {{ $s['donation_verse_translation'] ?? '"The example of those who spend in the way of Allah is like a grain that sprouts seven ears" — Al-Baqarah 2:261' }}
                    </p>
                    <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white" data-i18n="donate.title">
                        Support Our Mission
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">Every contribution empowers Muslim communities worldwide</p>
                </div>

                <!-- Donation Form -->
                <div class="max-w-xl mx-auto space-y-6">

                    <!-- 1. Amount Selection (Preset Buttons + Custom Input) -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2.5">
                            Select Donation Amount (USD) <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-4 gap-2.5 sm:gap-3">
                            <button type="button" onclick="selectDonationAmount(10)" data-amount="10" class="donate-preset-btn py-3 rounded-2xl border text-sm font-bold transition-all text-center">
                                $10
                            </button>
                            <button type="button" onclick="selectDonationAmount(25)" data-amount="25" class="donate-preset-btn py-3 rounded-2xl border text-sm font-bold transition-all text-center">
                                $25
                            </button>
                            <button type="button" onclick="selectDonationAmount(50)" data-amount="50" class="donate-preset-btn py-3 rounded-2xl border text-sm font-bold transition-all text-center">
                                $50
                            </button>
                            <button type="button" onclick="selectDonationAmount(100)" data-amount="100" class="donate-preset-btn py-3 rounded-2xl border text-sm font-bold transition-all text-center">
                                $100
                            </button>
                        </div>

                        <!-- Custom Amount Input -->
                        <div class="relative mt-2.5">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-gray-400">$</span>
                            <input type="number" id="donate-amount" min="1" step="1" value="25" oninput="onCustomAmountInput()" placeholder="Or enter custom amount" class="w-full pl-8 pr-4 py-3 rounded-2xl bg-gray-100/80 dark:bg-white/5 border border-black/10 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 text-sm font-semibold outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                    </div>

                    <!-- 2. Charitable Cause Dropdown -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2.5">
                            Charitable Cause / Purpose <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="donate-category" class="w-full appearance-none rounded-2xl px-5 py-3.5 pr-10 bg-gray-100/80 dark:bg-white/5 border border-black/10 dark:border-white/10 text-gray-900 dark:text-white text-sm font-medium outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all cursor-pointer">
                                <option value="">Select a cause (Zakat, Sadaqah, etc.)...</option>
                            </select>
                            <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Donor Details (Name + Email) -->
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Your Name
                            </label>
                            <input id="donate-name" type="text" placeholder="Full Name (Optional)" class="w-full rounded-2xl px-5 py-3.5 bg-gray-100/80 dark:bg-white/5 border border-black/10 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input id="donate-email" type="email" placeholder="you@example.com" class="w-full rounded-2xl px-5 py-3.5 bg-gray-100/80 dark:bg-white/5 border border-black/10 dark:border-white/10 text-gray-900 dark:text-white placeholder-gray-400 text-sm outline-none focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 transition-all">
                        </div>
                    </div>

                    <!-- 4. Selectable Payment Gateway Cards -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-2.5">
                            Select Payment Method <span class="text-rose-500">*</span>
                        </label>
                        <div id="donate-payment-grid" class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <!-- Buy Me a Coffee -->
                            <button type="button" onclick="selectPaymentGateway('buymeacoffee')" data-gateway="buymeacoffee" class="donate-payment-card relative flex items-center gap-3.5 p-4 rounded-2xl border transition-all cursor-pointer group text-left">
                                <div class="w-12 h-12 rounded-xl bg-[#FFDD00]/20 text-[#FF813F] flex items-center justify-center shrink-0 border border-[#FFDD00]/30 shadow-sm">
                                    <svg class="w-6 h-6 text-[#FF813F]" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20.216 6.415l-.132-.666c-.119-.598-.388-1.163-1.001-1.379-.197-.069-.42-.098-.57-.241-.152-.143-.196-.366-.231-.572-.065-.378-.125-.756-.192-1.133-.057-.325-.224-.59-.517-.745C17.272 1.523 16.945 1.5 16.617 1.5H3.68c-.604 0-1.157.37-1.385.944-.228.574-.08 1.229.37 1.636l1.238 1.118c.032.029.055.066.071.106l1.988 8.448c.189.803.906 1.368 1.733 1.368h8.042c.813 0 1.522-.547 1.724-1.332l.711-2.775c1.472-.254 2.656-1.408 2.853-2.912.083-.637-.024-1.284-.209-1.686zm-2.584 2.836c-.104.795-.742 1.393-1.542 1.455l.389-1.517c.061-.238.125-.475.191-.711.238-.857.48-1.713.72-2.57.177.086.326.223.409.4.152.327.085 1.737-.167 2.943z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pr-6">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-sm text-gray-900 dark:text-white">Buy Me a Coffee</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-500/15 text-amber-700 dark:text-amber-300 border border-amber-500/30">Global</span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">Cards • Apple Pay • Google Pay</span>
                                </div>
                                <div class="donate-check absolute top-3.5 right-3.5 w-5 h-5 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs">✓</div>
                            </button>

                            <!-- Support Kori -->
                            <button type="button" onclick="selectPaymentGateway('supportkori')" data-gateway="supportkori" class="donate-payment-card relative flex items-center gap-3.5 p-4 rounded-2xl border transition-all cursor-pointer group text-left">
                                <div class="w-12 h-12 rounded-xl bg-rose-500/15 text-rose-500 flex items-center justify-center shrink-0 border border-rose-500/20 shadow-sm">
                                    <svg class="w-6 h-6 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0 pr-6">
                                    <div class="flex items-center gap-1.5">
                                        <span class="font-bold text-sm text-gray-900 dark:text-white">Support Kori</span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-rose-500/15 text-rose-700 dark:text-rose-300 border border-rose-500/30">BD & Global</span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block mt-0.5">bKash • Nagad • Rocket • Cards</span>
                                </div>
                                <div class="donate-check hidden absolute top-3.5 right-3.5 w-5 h-5 rounded-full bg-brand-600 text-white flex items-center justify-center text-xs">✓</div>
                            </button>
                        </div>
                    </div>

                    <!-- PayPal SDK Container -->
                    <div id="paypal-button-container" class="hidden my-4"></div>

                    <!-- 5. Submit Button -->
                    <div>
                        <button id="donate-submit-btn" onclick="submitDonation()" type="button" class="w-full py-4 rounded-2xl bg-gradient-to-r from-brand-600 to-emerald-500 hover:from-brand-500 hover:to-emerald-400 text-white font-bold text-base shadow-xl shadow-brand-500/25 hover:shadow-brand-500/40 hover:scale-[1.01] active:scale-[0.99] transition-all flex items-center justify-center gap-2">
                            <span id="donate-btn-text">Support via Buy Me a Coffee ☕</span>
                            <svg id="donate-btn-icon" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            <span id="donate-btn-loader" class="hidden">
                                <svg class="w-5 h-5 animate-spin text-white" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            </span>
                        </button>

                        <div class="mt-3.5 flex items-center justify-center gap-2.5 text-xs text-gray-500 dark:text-gray-400">
                            <span>Direct links:</span>
                            <a href="https://buymeacoffee.com/riff1431" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400 hover:underline">
                                BuyMeACoffee ↗
                            </a>
                            <span>•</span>
                            <a href="https://www.supportkori.com/arifurfullstack" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-semibold text-rose-600 dark:text-rose-400 hover:underline">
                                SupportKori ↗
                            </a>
                        </div>

                        <div id="donate-feedback" class="hidden mt-4 p-4 rounded-2xl text-xs sm:text-sm font-semibold text-center transition-all"></div>

                        <div class="text-center pt-3 text-xs text-gray-400 flex items-center justify-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>🔒 256-bit SSL Encrypted • 100% Transparent & Charitable</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- ===================== DOWNLOAD CALL TO ACTION ===================== -->
    <section id="download" class="py-24 relative z-10 scroll-mt-28 lg:scroll-mt-32">
        <div class="max-w-5xl mx-auto px-6 text-center">
            <div class="glass-card rounded-[48px] p-12 lg:p-16 border border-brand-500/20 shadow-2xl relative overflow-hidden">
                <div class="font-arabic text-3xl text-amber-400 mb-4">الْحَمْدُ لِلَّهِ رَبِّ الْعَالَمِينَ</div>
                <h2 class="text-4xl sm:text-5xl font-black text-gray-900 dark:text-white mb-6">
                    <span data-i18n="download.title" data-i18n-en="{{ $s['download_title'] }}">{{ $s['download_title'] }}</span><br>
                    <span class="text-gradient-emerald">
                        <span data-i18n="download.highlight" data-i18n-en="{{ $s['download_title_highlight'] }}">{{ $s['download_title_highlight'] }}</span>
                    </span>
                </h2>
                <p class="text-gray-600 dark:text-gray-400 text-base max-w-xl mx-auto mb-10" data-i18n="download.description" data-i18n-en="{{ $s['download_description'] }}">
                    {{ $s['download_description'] }}
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4 mb-8">
                    <!-- App Store -->
                    <a href="{{ $s['app_store_url'] }}" target="_blank" class="flex items-center gap-3 px-8 py-4 rounded-2xl bg-gray-900 text-white hover:scale-105 active:scale-95 transition-all shadow-xl">
                        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                        <div class="text-left">
                            <div class="text-[10px] text-gray-400 leading-none">Download on the</div>
                            <div class="text-base font-bold">App Store</div>
                        </div>
                    </a>

                    <!-- Google Play -->
                    <a href="{{ $s['play_store_url'] }}" target="_blank" class="flex items-center gap-3 px-8 py-4 rounded-2xl bg-brand-600 text-white hover:scale-105 active:scale-95 transition-all shadow-xl">
                        <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M3.18 23.76c.27.15.58.2.89.13l12.89-7.43-2.77-2.76-11.01 10.06zm-.99-20.29v18.06c0 .36.1.69.28.97l10.32-10.32L2.19 2.5c-.0 .0-.0.0-.0 0zm16.12 6.77l-2.44-1.41-3.08 3.09 3.09 3.09 2.44-1.41c.7-.4 1.12-1.15 1.12-1.68 0-.53-.42-1.27-1.13-1.68zm-14.7-8.47c.04-.01.08-.01.12-.01.31 0 .61.09.87.26l12.01 6.93-2.76 2.76L4.44.73c-.31-.18-.66-.21-.93-.05l-.0-.91z"/></svg>
                        <div class="text-left">
                            <div class="text-[10px] text-emerald-200 leading-none">Get it on</div>
                            <div class="text-base font-bold">Google Play</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== ENHANCED MODERN ISLAMIC FOOTER ===================== -->
    <footer class="relative z-10 pt-20 pb-12 overflow-hidden border-t border-black/5 dark:border-white/5 bg-gradient-to-b from-transparent via-emerald-950/[0.015] to-emerald-950/[0.04] dark:via-black/20 dark:to-[#050b07] transition-colors duration-300">
        <!-- Ambient Decorative Glows -->
        <div class="absolute -top-24 left-1/4 w-96 h-96 bg-brand-500/10 rounded-full blur-[120px] pointer-events-none -z-10"></div>
        <div class="absolute -bottom-24 right-1/4 w-80 h-80 bg-amber-400/5 dark:bg-amber-500/5 rounded-full blur-[100px] pointer-events-none -z-10"></div>
        
        <!-- Glowing Top Accent Line -->
        <div class="absolute top-0 inset-x-0 h-[1px] bg-gradient-to-r from-transparent via-brand-500/30 to-transparent"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-10 lg:gap-8 pb-14">
                
                <!-- Column 1: Brand, Identity & Community (Span 4) -->
                <div class="lg:col-span-4 flex flex-col justify-between">
                    <div>
                        <!-- Logo Brand Header -->
                        <div class="mb-4">
                            <a href="#" class="inline-flex items-center group select-none" title="{{ $s['app_name'] ?? 'Deenio' }}" aria-label="{{ $s['app_name'] ?? 'Deenio' }}">
                                <img src="{{ asset('assets/img/deenio-logo-hr.png') }}" alt="{{ $s['app_name'] ?? 'Deenio' }}" class="h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105 dark:hidden">
                                <img src="{{ asset('assets/img/deenio-logo-hr-white.png') }}" alt="{{ $s['app_name'] ?? 'Deenio' }}" class="h-10 w-auto object-contain transition-transform duration-200 group-hover:scale-105 hidden dark:block">
                            </a>
                        </div>

                        <!-- Arabic Calligraphy Accent -->
                        <div class="font-arabic text-xl text-brand-700/80 dark:text-amber-400/80 my-3 select-none leading-relaxed tracking-wide">
                            بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ
                        </div>

                        <!-- Dynamic Description -->
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-6 max-w-sm" data-i18n="footer.description" data-i18n-en="{{ $s['footer_description'] ?? 'Your complete Islamic companion app. Bringing the beauty of Islam to your fingertips with modern technology.' }}">
                            {{ $s['footer_description'] ?? 'Your complete Islamic companion app. Bringing the beauty of Islam to your fingertips with modern technology.' }}
                        </p>

                        <!-- Trust & Verification Pill -->
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/[0.03] dark:bg-white/[0.04] border border-black/5 dark:border-white/10 text-xs text-gray-600 dark:text-gray-300 mb-6 backdrop-blur-sm shadow-xs">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            <span data-i18n="footer.trusted_tag">100% Ad-Free • Verified Islamic Sources</span>
                        </div>
                    </div>

                    <!-- Social / Community Icons -->
                    <div class="flex items-center gap-2.5 pt-2">
                        <a href="https://t.me" target="_blank" rel="noopener noreferrer" aria-label="Telegram" class="w-8 h-8 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-brand-500 hover:text-white dark:hover:bg-brand-500 dark:hover:text-white text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all duration-200 border border-black/5 dark:border-white/5 shadow-xs hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm4.64 6.8c-.15 1.58-.8 5.42-1.13 7.19-.14.75-.42 1-.68 1.03-.58.05-1.02-.38-1.58-.75-.88-.58-1.38-.94-2.23-1.5-.99-.65-.35-1.01.22-1.59.15-.15 2.71-2.48 2.76-2.69a.2.2 0 00-.05-.18c-.06-.05-.14-.03-.21-.02-.09.02-1.49.95-4.22 2.79-.4.27-.76.41-1.08.4-.36-.01-1.04-.2-1.55-.37-.63-.2-1.12-.31-1.08-.66.02-.18.27-.36.75-.55 2.92-1.27 4.86-2.11 5.83-2.51 2.78-1.16 3.35-1.36 3.73-1.36.08 0 .27.02.39.12.1.08.13.19.14.27-.01.06.01.24 0 .38z"/></svg>
                        </a>
                        <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)" class="w-8 h-8 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-brand-500 hover:text-white dark:hover:bg-brand-500 dark:hover:text-white text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all duration-200 border border-black/5 dark:border-white/5 shadow-xs hover:scale-105 active:scale-95">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" aria-label="YouTube" class="w-8 h-8 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-brand-500 hover:text-white dark:hover:bg-brand-500 dark:hover:text-white text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all duration-200 border border-black/5 dark:border-white/5 shadow-xs hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                        <a href="https://github.com" target="_blank" rel="noopener noreferrer" aria-label="GitHub" class="w-8 h-8 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-brand-500 hover:text-white dark:hover:bg-brand-500 dark:hover:text-white text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all duration-200 border border-black/5 dark:border-white/5 shadow-xs hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/></svg>
                        </a>
                        <a href="mailto:{{ $s['contact_email'] ?? ($s['email'] ?? 'support@' . request()->getHost()) }}" aria-label="Support Email" class="w-8 h-8 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-brand-500 hover:text-white dark:hover:bg-brand-500 dark:hover:text-white text-gray-500 dark:text-gray-400 flex items-center justify-center transition-all duration-200 border border-black/5 dark:border-white/5 shadow-xs hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Key Features Navigation (Span 3) -->
                <div class="lg:col-span-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                        <span data-i18n="footer.features">Features</span>
                    </h4>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <li>
                            <a href="#quran" class="group flex items-center justify-between hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.holy_quran">Holy Quran</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-brand-500/10 text-brand-600 dark:text-brand-400 border border-brand-500/15">Audio</span>
                            </a>
                        </li>
                        <li>
                            <a href="#prayer" class="group flex items-center justify-between hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.prayer_times">Prayer Times & Adhan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#ai-chat" class="group flex items-center justify-between hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.ai_chat">AI Islamic Assistant</span>
                                <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">AI</span>
                            </a>
                        </li>
                        <li>
                            <a href="#features" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.qibla">Qibla Direction</span>
                            </a>
                        </li>
                        <li>
                            <a href="#features" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.zakat">Zakat Calculator</span>
                            </a>
                        </li>
                        <li>
                            <a href="#features" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.mosque">Mosque Finder</span>
                            </a>
                        </li>
                        <li>
                            <a href="#features" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="dhikr.dua">Daily Duas & Azkar</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Resources & Organization (Span 2) -->
                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span data-i18n="footer.links">Resources</span>
                    </h4>
                    <ul class="space-y-3 text-sm text-gray-600 dark:text-gray-400">
                        <li>
                            <a href="{{ route('blog.index') }}" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform">Blog & Articles</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('privacy-policy') }}" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.privacy">Privacy Policy</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('terms-and-conditions') }}" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.terms">Terms & Conditions</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('support') }}" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="footer.support">Support & FAQ</span>
                            </a>
                        </li>
                        <li>
                            <a href="#donate" class="group flex items-center hover:text-brand-600 dark:hover:text-brand-400 transition-colors py-0.5">
                                <span class="group-hover:translate-x-1 rtl:group-hover:-translate-x-1 transition-transform" data-i18n="nav.donate">Support Mission</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}" class="group inline-flex items-center gap-1.5 hover:text-brand-600 dark:hover:text-brand-400 transition-colors font-medium py-0.5 text-gray-700 dark:text-gray-300">
                                <svg class="w-3.5 h-3.5 opacity-60 group-hover:opacity-100 transition-opacity" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span data-i18n="footer.admin">Admin Panel</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: App Experience & Downloads (Span 3) -->
                <div class="lg:col-span-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                        <span data-i18n="footer.download_app">Get The App</span>
                    </h4>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-4 leading-relaxed" data-i18n="footer.download_desc">
                        Experience the Quran, prayer times, and AI guidance anywhere. Free on iOS & Android.
                    </p>

                    <!-- Compact Store Badges -->
                    <div class="space-y-2.5">
                        <a href="{{ $s['app_store_url'] ?? '#' }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-black/[0.04] dark:bg-white/[0.05] hover:bg-gray-900 hover:text-white dark:hover:bg-white/10 text-gray-800 dark:text-gray-200 border border-black/5 dark:border-white/10 transition-all duration-200 group shadow-xs">
                            <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            <div class="text-left">
                                <div class="text-[9px] opacity-70 leading-none">Download on the</div>
                                <div class="text-xs font-bold leading-tight">App Store</div>
                            </div>
                        </a>

                        <a href="{{ $s['play_store_url'] ?? '#' }}" target="_blank" class="flex items-center gap-3 px-4 py-2.5 rounded-xl bg-black/[0.04] dark:bg-white/[0.05] hover:bg-brand-600 hover:text-white dark:hover:bg-brand-600 text-gray-800 dark:text-gray-200 border border-black/5 dark:border-white/10 transition-all duration-200 group shadow-xs">
                            <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M3.18 23.76c.27.15.58.2.89.13l12.89-7.43-2.77-2.76-11.01 10.06zm-.99-20.29v18.06c0 .36.1.69.28.97l10.32-10.32L2.19 2.5c-.0 .0-.0.0-.0 0zm16.12 6.77l-2.44-1.41-3.08 3.09 3.09 3.09 2.44-1.41c.7-.4 1.12-1.15 1.12-1.68 0-.53-.42-1.27-1.13-1.68zm-14.7-8.47c.04-.01.08-.01.12-.01.31 0 .61.09.87.26l12.01 6.93-2.76 2.76L4.44.73c-.31-.18-.66-.21-.93-.05l-.0-.91z"/></svg>
                            <div class="text-left">
                                <div class="text-[9px] opacity-70 leading-none">Get it on</div>
                                <div class="text-xs font-bold leading-tight">Google Play</div>
                            </div>
                        </a>
                    </div>

                    <!-- CodeCanyon Developer / Reseller Micro-card -->
                    @if(($s['show_codecanyon_link'] ?? '1') != '0')
                    <div class="mt-4 p-3 rounded-2xl bg-black/[0.02] dark:bg-white/[0.03] border border-black/5 dark:border-white/5 transition-all hover:border-brand-500/30">
                        <div class="text-[11px] text-gray-500 dark:text-gray-400 mb-1" data-i18n="download.codecanyon" data-i18n-en="{{ $s['codecanyon_prompt_text'] ?? 'Want to resell or customize?' }}">
                            {{ $s['codecanyon_prompt_text'] ?? 'Want to resell or customize?' }}
                        </div>
                        <a href="{{ $s['codecanyon_url'] ?? 'https://codecanyon.net' }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-600 dark:text-brand-400 hover:underline" data-i18n="download.codecanyon_link" data-i18n-en="{{ $s['codecanyon_link_text'] ?? 'Get the source code on CodeCanyon →' }}">
                            {{ $s['codecanyon_link_text'] ?? 'Get the source code on CodeCanyon →' }}
                        </a>
                    </div>
                    @endif
                </div>

            </div>

            <!-- Sub-footer Bottom Bar -->
            <div class="border-t border-black/5 dark:border-white/5 pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-500 dark:text-gray-400">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-1 sm:gap-4 text-center sm:text-left">
                    <span>
                        © {{ date('Y') }} <span class="font-semibold text-gray-800 dark:text-gray-200">{{ $s['app_name'] ?? 'Zabi' }}</span>. <span data-i18n="footer.copyright">Islamic App. All rights reserved.</span>
                    </span>
                    <span class="hidden sm:inline opacity-30">•</span>
                    <span class="text-[11px] opacity-75" data-i18n="footer.built">Built with Flutter • Laravel • Love ❤️</span>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Quick Dark/Light Toggle -->
                    <button onclick="toggleDarkMode()" aria-label="Toggle Theme" class="p-2 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-black/10 dark:hover:bg-white/10 text-gray-600 dark:text-gray-300 transition-colors border border-black/5 dark:border-white/10" title="Toggle Dark/Light Mode">
                        <svg class="w-4 h-4 hidden dark:block text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <svg class="w-4 h-4 block dark:hidden text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                        </svg>
                    </button>

                    <!-- Back to Top Button -->
                    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="group inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-black/[0.03] dark:bg-white/[0.05] hover:bg-brand-500/10 hover:text-brand-600 dark:hover:bg-brand-500/20 dark:hover:text-brand-400 border border-black/5 dark:border-white/10 text-xs font-medium text-gray-600 dark:text-gray-300 transition-all active:scale-95 shadow-2xs">
                        <span data-i18n="footer.back_to_top">Back to top</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:-translate-y-0.5 text-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </footer>

    <!-- ===================== JAVASCRIPT LAYER ===================== -->
    <script>
        // ── Theme Mode Toggle ──────────────────────────────────
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
            localStorage.setItem('zabi_theme', isDark ? 'dark' : 'light');
            if (typeof updateThemeIcons === 'function') {
                updateThemeIcons(isDark);
            }
        }
        function toggleTheme() {
            toggleDarkMode();
        }

        // ── Scroll Progress & Navbar Elevation ────────────────
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            const progressBar = document.getElementById('scroll-progress-bar');
            
            // Dynamic scroll progress calculation
            if (progressBar) {
                const totalHeight = document.documentElement.scrollHeight - window.innerHeight;
                const progress = totalHeight > 0 ? (window.scrollY / totalHeight) * 100 : 0;
                progressBar.style.width = Math.min(100, Math.max(0, progress)) + '%';
            }

            // Navbar elevation & padding shrink
            if (nav) {
                if (window.scrollY > 40) {
                    nav.classList.add('py-2', 'shadow-2xl');
                    nav.classList.remove('py-2.5', 'sm:py-3');
                } else {
                    nav.classList.add('py-2.5', 'sm:py-3');
                    nav.classList.remove('py-2', 'shadow-2xl');
                }
            }
        });

        // ── Mobile Menu Toggle ─────────────────────────────────
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-drawer');
            const btn = document.getElementById('mobile-menu-btn');
            if (!drawer) return;
            const isOpen = drawer.classList.contains('open');
            if (isOpen) {
                drawer.classList.remove('open');
                drawer.classList.add('closed');
                btn?.classList.remove('is-open');
            } else {
                drawer.classList.remove('closed');
                drawer.classList.add('open');
                btn?.classList.add('is-open');
            }
        }

        // ── Minimal GSAP Transitions ──────────────────────────
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                // Subtle hero fade
                gsap.from('.gsap-hero-left', { opacity: 0, y: 15, duration: 0.6, ease: 'power2.out' });
                gsap.from('.gsap-hero-right', { opacity: 0, duration: 0.6, delay: 0.1, ease: 'power2.out' });

                // Gentle section fade on scroll
                gsap.utils.toArray('.gsap-reveal').forEach(el => {
                    gsap.from(el, {
                        scrollTrigger: { trigger: el, start: 'top 88%' },
                        opacity: 0,
                        y: 12,
                        duration: 0.5,
                        ease: 'power2.out'
                    });
                });

                // Fast stat count-up
                gsap.utils.toArray('.stat-counter').forEach(counter => {
                    const target = parseFloat(counter.getAttribute('data-target'));
                    const suffix = counter.getAttribute('data-suffix') || '';
                    const decimals = parseInt(counter.getAttribute('data-decimals') || '0');
                    const obj = { val: 0 };

                    ScrollTrigger.create({
                        trigger: counter,
                        start: 'top 90%',
                        onEnter: () => {
                            gsap.to(obj, {
                                val: target,
                                duration: 0.8,
                                ease: 'power1.out',
                                onUpdate: () => {
                                    counter.textContent = obj.val.toFixed(decimals) + suffix;
                                }
                            });
                        },
                        once: true
                    });
                });
            }
        });

        // ── Interactive Tasbih Module ───────────────────────────
        let tasbihCount = 0;
        function incrementTasbih() {
            tasbihCount++;
            document.getElementById('tasbih-count').textContent = tasbihCount;
            if (navigator.vibrate) navigator.vibrate(30);

            // Pop animation with GSAP if available
            if (typeof gsap !== 'undefined') {
                gsap.fromTo('#tasbih-count', { scale: 1.3 }, { scale: 1, duration: 0.3, ease: 'back.out(2)' });
            }
        }
        function resetTasbih() {
            tasbihCount = 0;
            document.getElementById('tasbih-count').textContent = '0';
        }
        function setTasbihDhikr(arabic, trans) {
            document.getElementById('tasbih-arabic').textContent = arabic;
            document.getElementById('tasbih-trans').textContent = trans;
            document.querySelectorAll('.tasbih-phrase-btn').forEach(btn => {
                if (btn.textContent.includes(trans)) {
                    btn.classList.add('bg-brand-500', 'text-white');
                    btn.classList.remove('bg-black/5', 'dark:bg-white/5', 'text-gray-700', 'dark:text-gray-300');
                } else {
                    btn.classList.remove('bg-brand-500', 'text-white');
                    btn.classList.add('bg-black/5', 'dark:bg-white/5', 'text-gray-700', 'dark:text-gray-300');
                }
            });
        }

const TRANSLATIONS = {
            en: {
                'nav.features':'Features','nav.quran':'Quran','nav.prayer':'Prayer','nav.ai':'AI','nav.download':'Download','nav.admin':'Admin Login','nav.buy_btn':'Buy Now','nav.download_btn':'Download Free',
                'hero.app_store':'App Store','hero.play_store':'Google Play','hero.rating_label':'Rating','hero.downloads_label':'Downloads','hero.languages_label':'Languages',
                'stats.surahs':'Surahs of Quran','stats.languages':'Languages','stats.rating':'App Rating','stats.downloads':'Downloads',
                'quran.verse_trans':'"And We have certainly made the Quran easy for remembrance, so is there any who will remember?" — Al-Qamar 54:17',
                'quran.surah_title':'Complete 114 Surahs','quran.surah_desc':'All 6,236 ayahs with multiple recitation styles and verse-by-verse audio.',
                'quran.audio_title':'Audio Quran','quran.audio_desc':'Listen to world-renowned reciters with offline support and background play.',
                'quran.bookmark_title':'Bookmarks & History','quran.bookmark_desc':'Save your place, bookmark favorite verses, and continue where you left off.',
                'prayer.qibla_title':'Qibla Finder','prayer.qibla_desc':'Accurate compass with improved precision',
                'prayer.adhan_title':'Adhan Alerts','prayer.adhan_desc':'Multiple beautiful reciters to choose from',
                'prayer.ramadan_title':'Ramadan Mode','prayer.ramadan_desc':'Iftar & Sehri times for your city',
                'prayer.custom_title':'Customizable','prayer.custom_desc':'Manual adjustments & AM/PM format',
                'prayer.card_label':"Today's Prayer Times",
                'ai.chat_title':'AI Islamic Chat','ai.chat_desc':'Ask any Islamic question and receive knowledgeable, accurate answers instantly. From fiqh rulings to daily duas, our AI assistant is always ready to guide you.',
                'ai.chat_q':'What is the dua before eating?','ai.chat_a':'"Bismillah" — In the name of Allah. (Bukhari & Muslim)',
                'ai.name_title':'Islamic Name Generator','ai.name_desc':'Find the perfect Islamic name for your child. Our AI generates beautiful, meaningful names with their Arabic origin, meaning, and pronunciation.','ai.name_label':'Generated Names for Boys',
                'dhikr.tasbih':'Tasbih Counter','dhikr.tasbih_desc':'Digital dhikr counter with customizable phrases and haptic feedback.',
                'dhikr.dua':'Dua Collection','dhikr.dua_desc':'Comprehensive duas for morning, evening, travel, eating, and special occasions.',
                'dhikr.names':'99 Names of Allah','dhikr.names_desc':'All Asma ul Husna with meanings, transliteration, and virtues.',
                'dhikr.haram':'Haram Code','dhikr.haram_desc':'Islamic guidance system to help you navigate everyday decisions.',
                'tech.title':'Built for Performance','tech.subtitle':'Modern, fast, and cross-platform',
                'tech.flutter':'Flutter Framework','tech.flutter_desc':'Built with Flutter & Dart 3.1.5 for smooth 60fps performance',
                'tech.cross':'Cross-Platform','tech.cross_desc':'One codebase, native performance on both iOS and Android',
                'tech.updates':'Free Updates','tech.updates_desc':'Regular feature updates and bug fixes at no extra cost',
                'tech.privacy':'Privacy First','tech.privacy_desc':'Your data stays private with minimal permissions required',
                'download.app_store_top':'Download on the','download.app_store':'App Store','download.play_top':'Get it on','download.play':'Google Play',
                'download.codecanyon':'Want to resell or customize?','download.codecanyon_link':'Get the source code on CodeCanyon →',
                'footer.features':'Features','footer.links':'Resources','footer.holy_quran':'Holy Quran','footer.prayer_times':'Prayer Times & Adhan','footer.ai_chat':'AI Islamic Assistant',
                'footer.qibla':'Qibla Direction','footer.zakat':'Zakat Calculator','footer.mosque':'Mosque Finder',
                'footer.privacy':'Privacy Policy','footer.terms':'Terms & Conditions','footer.support':'Support & FAQ','footer.admin':'Admin Panel',
                'footer.copyright':'Islamic App. All rights reserved.','footer.built':'Built with Flutter • Laravel • Love ❤️',
                'footer.back_to_top':'Back to top','footer.download_app':'Get The App','footer.download_desc':'Experience the Quran, prayer times, and AI guidance anywhere. Free on iOS & Android.','footer.trusted_tag':'100% Ad-Free • Verified Islamic Sources','footer.suite_badge':'Pro Islamic Suite',
                // Default dynamic content (DB values override these in English via data-i18n-en)
                'hero.badge':'Available on iOS & Android','hero.title':'Your Complete','hero.subtitle':'Islamic Companion',
                'hero.description':'Zabi brings you the full Quran, prayer times, hadith, dua, Qibla, Zakat calculator, AI Islamic chat, and 40+ languages — everything you need for your daily Islamic life in one beautiful app.',
                'features.title':'Everything You Need','features.desc':'A complete Islamic lifestyle companion packed with features that help you stay connected to your faith every day.',
                'quran.title':'Full Quran Reading','quran.highlight':'Experience','quran.description':'Read, listen, and understand the Holy Quran with multi-language translations, phonetic transliteration, and beautiful Arabic typography.',
                'prayer.title':'Never Miss','prayer.highlight':'A Prayer','prayer.description':'Get accurate prayer times for your location with beautiful Adhan notifications. Find the Qibla direction with an improved compass.',
                'ai.title':'Powered by Artificial Intelligence','ai.description':'Get intelligent Islamic guidance at your fingertips — anytime, anywhere.',
                'dhikr.title':'Dhikr, Dua & Remembrance','dhikr.description':'Stay connected to Allah with our comprehensive collection of duas and dhikr for every occasion.',
                'download.title':'Start Your Islamic','download.highlight':'Journey Today','download.description':'Join hundreds of thousands of Muslims worldwide who use Zabi for their daily Islamic needs. Download for free today.',
                'footer.description':'Your complete Islamic companion app. Bringing the beauty of Islam to your fingertips with modern technology.',
                'nav.donate':'Donate',
                'donate.title':'Support Our Mission','donate.desc':'Your generosity keeps Zabi free for millions of Muslims worldwide. Every contribution helps us maintain, improve, and expand Islamic knowledge.',
                'donate.verse_trans':'"The example of those who spend in the way of Allah is like a grain that sprouts seven ears" — Al-Baqarah 2:261',
                'donate.why1_title':'Free App Forever','donate.why1_desc':'Keeps the app free for millions of Muslims globally.',
                'donate.why2_title':'Global Infrastructure','donate.why2_desc':'Funds servers, CDN, and 24/7 uptime for all users.',
                'donate.why3_title':'New Features','donate.why3_desc':'Enables development of new Islamic tools and content.',
                'donate.why4_title':'Privacy & Security','donate.why4_desc':'Maintains privacy-first infrastructure with no ads.',
                'donate.accepted':'Accepted via',
                'donate.amount_label':'Select Amount','donate.custom_placeholder':'Custom amount (USD)',
                'donate.category_label':'Donation Category','donate.category_placeholder':'Select a cause...',
                'donate.email_label':'Your Email',
                'donate.payment_label':'Payment Method',
                'donate.btn':'Donate Now',
                'donate.name_label':'Your Name','donate.privacy_note':'🔒 Secure & encrypted. We never store card details.',
                'feature.0.title':'Quran & Islamic Content','feature.0.desc':'Complete Al-Quran reading experience with multi-language translations, audio recitation, transliteration, and ayah sharing.',
                'feature.1.title':'Prayer & Worship','feature.1.desc':'Accurate prayer times, customizable schedules, Adhan notifications with multiple reciters, and Qibla finder with compass.',
                'feature.2.title':'AI-Powered Features','feature.2.desc':'Get answers to Islamic questions instantly with our AI Islamic Chat and generate beautiful Islamic names for your family.',
                'feature.3.title':'Mosque & Location','feature.3.desc':'Find nearby mosques using GPS, get navigation assistance, and manage location permissions easily.',
                'feature.4.title':'Islamic Finance & Charity','feature.4.desc':'Calculate your Zakat accurately with customizable Nisab settings and support charitable causes through the donation module.',
                'feature.5.title':'Personalization','feature.5.desc':'Make the app yours with dark mode, RTL support, dynamic wallpapers, custom dhikr, dua, and 40+ language options.',
                'feature.0.bullets':"4 Language Translations\nAudio Quran Support\nTransliteration Feature\nHadith & Dua Collections\nAllah's 99 Names",
                'feature.1.bullets':'Accurate Prayer Times\nAdhan Notifications\nQibla Finder & Compass\nRamadan Schedule\nMultiple Adhan Reciters',
                'feature.2.bullets':'AI Islamic Q&A Chat\nIslamic Name Generator\nSmart Recommendations\nInstant Responses\n24/7 Availability',
                'feature.3.bullets':'Nearby Mosque Finder\nGPS Navigation\nReal-time Location\nCity-based Prayer Times',
                'feature.4.bullets':'Zakat Calculator\nCustomizable Nisab\nDonation Module\nMultiple Currencies',
                'feature.5.bullets':'Dark Mode Support\nRTL Language Support\nDynamic Wallpapers\nCustom Dhikr & Dua',
                'nav.listen':'Listen',
                'donate.form_title':'Make a Donation','donate.form_subtitle':'Every contribution makes a difference',
                'quran_audio.title':'Read & Listen ','quran_audio.highlight':'Together','quran_audio.desc':'Follow along with Arabic text while listening to world-renowned reciters — verse by verse.',
                'ai_chat.badge':'Live Demo • Powered by Groq','ai_chat.title_line1':'Ask Any','ai_chat.title_line2':'Islamic Question',
                'ai_chat.desc':'Get instant, knowledgeable answers about Islam — from Quran & Hadith to prayer, fiqh, and daily Muslim life. Lightning-fast AI, right here on the page.',
                'ai_chat.sub_title':'Sub-second Responses','ai_chat.sub_desc':'Groq LPU technology delivers answers in milliseconds',
                'ai_chat.hadith_title':'Quran & Hadith Referenced','ai_chat.hadith_desc':'Answers include Arabic text and authentic source citations',
                'ai_chat.app_title':'Full Experience in the App','ai_chat.app_desc':'Chat history, offline access, and more inside Zabi',
                'ai_chat.try_asking':'Try asking:',
                'name_gen.title':'Islamic Name ','name_gen.highlight':'Generator','name_gen.desc':'Discover beautiful, meaningful Islamic names with Arabic script, English transliteration, and origins — powered by AI.',
                'name_gen.label':'Generate names for a','name_gen.theme':'Meaning Theme','name_gen.optional':'(optional)','name_gen.btn':'✨ Generate Names',
            },
            ar: {
                'nav.features':'الميزات','nav.quran':'القرآن','nav.prayer':'الصلاة','nav.ai':'الذكاء الاصطناعي','nav.download':'تحميل','nav.admin':'تسجيل دخول الإدارة','nav.buy_btn':'اشتري الآن','nav.download_btn':'تحميل مجاناً',
                'hero.app_store':'آب ستور','hero.play_store':'جوجل بلاي','hero.rating_label':'التقييم','hero.downloads_label':'التحميلات','hero.languages_label':'لغة',
                'stats.surahs':'سورة قرآنية','stats.languages':'لغة','stats.rating':'تقييم التطبيق','stats.downloads':'تحميل',
                'quran.verse_trans':'"وَلَقَدْ يَسَّرْنَا الْقُرْآنَ لِلذِّكْرِ فَهَلْ مِن مُّدَّكِرٍ" — سورة القمر ٥٤:١٧',
                'quran.surah_title':'١١٤ سورة كاملة','quran.surah_desc':'جميع ٦٢٣٦ آية مع أساليب تلاوة متعددة وصوت آية بآية.',
                'quran.audio_title':'القرآن الصوتي','quran.audio_desc':'استمع إلى قراء عالميين مع دعم التشغيل دون اتصال.',
                'quran.bookmark_title':'الإشارات المرجعية','quran.bookmark_desc':'احفظ موضعك وضع إشارات على آياتك المفضلة.',
                'prayer.qibla_title':'اتجاه القبلة','prayer.qibla_desc':'بوصلة دقيقة بدقة محسّنة',
                'prayer.adhan_title':'تنبيهات الأذان','prayer.adhan_desc':'مقرئون جميلون متعددون للاختيار',
                'prayer.ramadan_title':'وضع رمضان','prayer.ramadan_desc':'أوقات الإفطار والسحور لمدينتك',
                'prayer.custom_title':'قابل للتخصيص','prayer.custom_desc':'تعديلات يدوية وتنسيق ١٢/٢٤ ساعة',
                'prayer.card_label':'مواقيت الصلاة اليوم',
                'ai.chat_title':'محادثة إسلامية ذكية','ai.chat_desc':'اطرح أي سؤال إسلامي واحصل على إجابات دقيقة وموثوقة فوراً. من أحكام الفقه إلى الأدعية اليومية.',
                'ai.chat_q':'ما هو الدعاء قبل الأكل؟','ai.chat_a':'"بِسْمِ اللَّهِ" — باسم الله. (البخاري ومسلم)',
                'ai.name_title':'مولد الأسماء الإسلامية','ai.name_desc':'اعثر على الاسم الإسلامي المثالي لطفلك. يقترح الذكاء الاصطناعي أسماء جميلة وذات معنى مع أصلها العربي.','ai.name_label':'أسماء مقترحة للأولاد',
                'dhikr.tasbih':'عداد التسبيح','dhikr.tasbih_desc':'عداد ذكر رقمي مع صيغ قابلة للتخصيص وتغذية راجعة.',
                'dhikr.dua':'مجموعة الأدعية','dhikr.dua_desc':'أدعية شاملة للصباح والمساء والسفر والطعام والمناسبات.',
                'dhikr.names':'أسماء الله الحسنى','dhikr.names_desc':'جميع الأسماء الحسنى مع معانيها وفضائلها.',
                'dhikr.haram':'دليل الحلال والحرام','dhikr.haram_desc':'نظام توجيه إسلامي يساعدك في قراراتك اليومية.',
                'tech.title':'مبني للأداء','tech.subtitle':'حديث وسريع ومتعدد المنصات',
                'tech.flutter':'إطار فلاتر','tech.flutter_desc':'مبني بـ Flutter و Dart 3.1.5 لأداء سلس بـ 60 إطار/ث',
                'tech.cross':'متعدد المنصات','tech.cross_desc':'قاعدة كود واحدة وأداء أصلي على iOS و Android',
                'tech.updates':'تحديثات مجانية','tech.updates_desc':'تحديثات منتظمة للميزات وإصلاح الأخطاء',
                'tech.privacy':'الخصوصية أولاً','tech.privacy_desc':'بياناتك تبقى خاصة مع الحد الأدنى من الأذونات',
                'download.app_store_top':'حمّل من','download.app_store':'آب ستور','download.play_top':'احصل عليه من','download.play':'جوجل بلاي',
                'download.codecanyon':'تريد إعادة البيع أو التخصيص؟','download.codecanyon_link':'احصل على كود المصدر من CodeCanyon ←',
                'footer.features':'الميزات','footer.links':'المصادر والقانونية','footer.holy_quran':'القرآن الكريم','footer.prayer_times':'مواقيت الصلاة والأذان','footer.ai_chat':'المساعد الإسلامي الذكي',
                'footer.qibla':'اتجاه القبلة','footer.zakat':'حاسبة الزكاة','footer.mosque':'البحث عن مسجد',
                'footer.privacy':'سياسة الخصوصية','footer.terms':'الشروط والأحكام','footer.support':'الدعم والمساعدة','footer.admin':'لوحة الإدارة',
                'footer.copyright':'تطبيق إسلامي. جميع الحقوق محفوظة.','footer.built':'مبني بـ Flutter • Laravel • محبة ❤️',
                'footer.back_to_top':'العودة للأعلى','footer.download_app':'حمّل التطبيق','footer.download_desc':'عش تجربة القرآن، مواقيت الصلاة، والإرشاد الذكي أينما كنت. مجاناً على iOS و Android.','footer.trusted_tag':'خالٍ من الإعلانات • مصادر إسلامية موثوقة','footer.suite_badge':'الحزمة الإسلامية المتكاملة',
                'hero.badge':'متاح على iOS و Android','hero.title':'رفيقك الإسلامي','hero.subtitle':'الشامل',
                'hero.description':'زابي يقدم لك القرآن الكريم كاملاً، مواقيت الصلاة، الأحاديث، الأدعية، القبلة، حاسبة الزكاة، المحادثة الإسلامية الذكية، وأكثر من ٤٠ لغة — كل ما تحتاجه في حياتك الإسلامية اليومية في تطبيق واحد جميل.',
                'features.title':'كل ما تحتاجه','features.desc':'رفيق شامل للحياة الإسلامية مليء بالميزات التي تساعدك على البقاء متصلاً بدينك كل يوم.',
                'quran.title':'قراءة القرآن الكريم','quran.highlight':'الكاملة','quran.description':'اقرأ واستمع وافهم القرآن الكريم مع ترجمات متعددة اللغات والتلاوة الصوتية والخط العربي الجميل.',
                'prayer.title':'لا تفوت','prayer.highlight':'صلاة','prayer.description':'احصل على مواقيت الصلاة الدقيقة مع إشعارات أذان جميلة. اعثر على اتجاه القبلة بالبوصلة المحسّنة.',
                'ai.title':'مدعوم بالذكاء الاصطناعي','ai.description':'احصل على إرشاد إسلامي ذكي في متناول يدك — في أي وقت وأي مكان.',
                'dhikr.title':'الذكر، الدعاء والتذكر','dhikr.description':'ابقَ متصلاً بالله تعالى بمجموعتنا الشاملة من الأدعية والأذكار لكل مناسبة.',
                'download.title':'ابدأ رحلتك الإسلامية','download.highlight':'اليوم','download.description':'انضم إلى مئات الآلاف من المسلمين حول العالم. حمّله مجاناً اليوم.',
                'footer.description':'رفيقك الإسلامي الشامل. نجلب لك جمال الإسلام بين يديك بالتكنولوجيا الحديثة.',
                'nav.donate':'تبرع',
                'donate.title':'ادعم مهمتنا','donate.desc':'كرمك يبقي زابي مجانياً لملايين المسلمين حول العالم. كل مساهمة تساعدنا على الصيانة والتطوير.',
                'donate.verse_trans':'"مَّثَلُ الَّذِينَ يُنفِقُونَ أَمْوَالَهُمْ فِي سَبِيلِ اللَّهِ كَمَثَلِ حَبَّةٍ أَنبَتَتْ سَبْعَ سَنَابِلَ" — البقرة 2:261',
                'donate.why1_title':'التطبيق مجاناً دائماً','donate.why1_desc':'يبقي التطبيق مجانياً لملايين المسلمين.',
                'donate.why2_title':'البنية التحتية العالمية','donate.why2_desc':'يموّل الخوادم والشبكة والتوفر المستمر.',
                'donate.why3_title':'ميزات جديدة','donate.why3_desc':'يتيح تطوير أدوات وأدوات إسلامية جديدة.',
                'donate.why4_title':'الخصوصية والأمان','donate.why4_desc':'يحافظ على الخصوصية دون إعلانات.',
                'donate.accepted':'مقبول عبر',
                'donate.amount_label':'اختر المبلغ','donate.custom_placeholder':'مبلغ مخصص (دولار)',
                'donate.category_label':'فئة التبرع','donate.category_placeholder':'اختر قضية...',
                'donate.email_label':'بريدك الإلكتروني',
                'donate.payment_label':'طريقة الدفع',
                'donate.btn':'تبرع الآن',
                'donate.name_label':'اسمك','donate.privacy_note':'🔒 آمن ومشفر. نحن لا نخزن تفاصيل البطاقة.',
                'feature.0.title':'القرآن والمحتوى الإسلامي','feature.0.desc':'تجربة قراءة كاملة للقرآن الكريم مع ترجمات متعددة اللغات والتلاوة الصوتية.',
                'feature.1.title':'الصلاة والعبادة','feature.1.desc':'مواقيت صلاة دقيقة وجداول قابلة للتخصيص وإشعارات الأذان وإيجاد اتجاه القبلة.',
                'feature.2.title':'ميزات الذكاء الاصطناعي','feature.2.desc':'احصل على إجابات فورية للأسئلة الإسلامية وولّد أسماء إسلامية جميلة لعائلتك.',
                'feature.3.title':'المسجد والموقع','feature.3.desc':'اعثر على المساجد القريبة باستخدام GPS واحصل على المساعدة في التنقل.',
                'feature.4.title':'المالية الإسلامية والخير','feature.4.desc':'احسب زكاتك بدقة مع إعدادات النصاب القابلة للتخصيص.',
                'feature.5.title':'التخصيص','feature.5.desc':'اجعل التطبيق مناسباً لك مع الوضع الليلي ودعم RTL وخلفيات ديناميكية.',
                'feature.0.bullets':'ترجمات بـ٤ لغات\nدعم القرآن الصوتي\nميزة التحويل الصوتي\nمجموعات الحديث والأدعية\nأسماء الله الحسنى التسعة والتسعون',
                'feature.1.bullets':'أوقات صلاة دقيقة\nإشعارات الأذان\nإيجاد القبلة والبوصلة\nجدول رمضان\nمقرئون متعددون',
                'feature.2.bullets':'محادثة إسلامية ذكية\nمولد الأسماء الإسلامية\nتوصيات ذكية\nردود فورية\nمتاح ٢٤/٧',
                'feature.3.bullets':'البحث عن المساجد القريبة\nالملاحة GPS\nالموقع في الوقت الفعلي\nأوقات الصلاة حسب المدينة',
                'feature.4.bullets':'حاسبة الزكاة\nإعدادات النصاب القابلة للتخصيص\nوحدة التبرع\nعملات متعددة',
                'feature.5.bullets':'دعم الوضع الليلي\nدعم اللغة من اليمين لليسار\nخلفيات ديناميكية\nتخصيص الذكر والأدعية',
                'nav.listen':'استمع',
                'donate.form_title':'قدّم تبرعاً','donate.form_subtitle':'كل مساهمة تُحدث فرقاً',
                'quran_audio.title':'اقرأ واستمع ','quran_audio.highlight':'معاً','quran_audio.desc':'تابع النص العربي بينما تستمع إلى قراء عالميين — آية بآية.',
                'ai_chat.badge':'عرض تجريبي حي • مدعوم بـ Groq','ai_chat.title_line1':'اسأل أي','ai_chat.title_line2':'سؤال إسلامي',
                'ai_chat.desc':'احصل على إجابات فورية وموثوقة حول الإسلام — من القرآن والحديث إلى الصلاة والفقه والحياة اليومية للمسلم. ذكاء اصطناعي فائق السرعة هنا على الصفحة.',
                'ai_chat.sub_title':'ردود أقل من ثانية','ai_chat.sub_desc':'تقنية Groq LPU تقدم إجابات في أجزاء من الثانية',
                'ai_chat.hadith_title':'القرآن والحديث مرجعاً','ai_chat.hadith_desc':'تتضمن الإجابات النص العربي ومصادر موثقة',
                'ai_chat.app_title':'التجربة الكاملة في التطبيق','ai_chat.app_desc':'سجل المحادثات والوصول دون إنترنت والمزيد داخل زابي',
                'ai_chat.try_asking':'جرب السؤال:',
                'name_gen.title':'مولد الأسماء ','name_gen.highlight':'الإسلامية','name_gen.desc':'اكتشف أسماء إسلامية جميلة وذات معنى مع الكتابة العربية والتحويل الصوتي والأصول — بدعم الذكاء الاصطناعي.',
                'name_gen.label':'إنشاء أسماء لـ','name_gen.theme':'موضوع المعنى','name_gen.optional':'(اختياري)','name_gen.btn':'✨ إنشاء أسماء',
            },
            bn: {
                'nav.features':'বৈশিষ্ট্যসমূহ','nav.quran':'কুরআন','nav.prayer':'নামাজ','nav.ai':'AI','nav.download':'ডাউনলোড','nav.admin':'অ্যাডমিন লগইন','nav.buy_btn':'এখনই কিনুন','nav.download_btn':'বিনামূল্যে ডাউনলোড',
                'hero.app_store':'অ্যাপ স্টোর','hero.play_store':'গুগল প্লে','hero.rating_label':'রেটিং','hero.downloads_label':'ডাউনলোড','hero.languages_label':'ভাষা',
                'stats.surahs':'কুরআনের সূরা','stats.languages':'ভাষাসমূহ','stats.rating':'অ্যাপ রেটিং','stats.downloads':'ডাউনলোড',
                'quran.verse_trans':'"আমি কুরআনকে স্মরণের জন্য সহজ করে দিয়েছি, অতএব কোনো স্মরণকারী আছে কি?" — সূরা আল-কামার ৫৪:১৭',
                'quran.surah_title':'সম্পূর্ণ ১১৪টি সূরা','quran.surah_desc':'একাধিক তেলাওয়াত স্টাইল সহ সকল ৬,২৩৬টি আয়াত।',
                'quran.audio_title':'অডিও কুরআন','quran.audio_desc':'বিশ্বখ্যাত কারীদের কণ্ঠে শুনুন অফলাইন সাপোর্ট সহ।',
                'quran.bookmark_title':'বুকমার্ক ও ইতিহাস','quran.bookmark_desc':'আপনার জায়গা সেভ করুন, প্রিয় আয়াত বুকমার্ক করুন।',
                'prayer.qibla_title':'কিবলা নির্দেশক','prayer.qibla_desc':'উন্নত নির্ভুলতার সঠিক কম্পাস',
                'prayer.adhan_title':'আজানের সতর্কতা','prayer.adhan_desc':'বেছে নেওয়ার জন্য একাধিক সুন্দর কারী',
                'prayer.ramadan_title':'রমজান মোড','prayer.ramadan_desc':'আপনার শহরের ইফতার ও সেহরির সময়',
                'prayer.custom_title':'কাস্টমাইজযোগ্য','prayer.custom_desc':'ম্যানুয়াল সমন্বয় এবং AM/PM ফরম্যাট',
                'prayer.card_label':'আজকের নামাজের সময়',
                'ai.chat_title':'AI ইসলামিক চ্যাট','ai.chat_desc':'যেকোনো ইসলামিক প্রশ্ন করুন এবং তাৎক্ষণিক সঠিক উত্তর পান। ফিকহ থেকে দৈনন্দিন দোয়া পর্যন্ত।',
                'ai.chat_q':'খাওয়ার আগে দোয়া কী?','ai.chat_a':'"বিসমিল্লাহ" — আল্লাহর নামে। (বুখারি ও মুসলিম)',
                'ai.name_title':'ইসলামিক নাম জেনারেটর','ai.name_desc':'আপনার সন্তানের জন্য নিখুঁত ইসলামিক নাম খুঁজুন। AI আরবি উৎস ও উচ্চারণ সহ সুন্দর নাম তৈরি করে।','ai.name_label':'ছেলেদের জন্য প্রস্তাবিত নাম',
                'dhikr.tasbih':'তাসবিহ কাউন্টার','dhikr.tasbih_desc':'কাস্টমাইজযোগ্য বাক্যাংশ সহ ডিজিটাল জিকির কাউন্টার।',
                'dhikr.dua':'দোয়ার সংগ্রহ','dhikr.dua_desc':'সকাল, সন্ধ্যা, সফর, খাওয়া ও বিশেষ উপলক্ষের দোয়া।',
                'dhikr.names':'আল্লাহর ৯৯ নাম','dhikr.names_desc':'অর্থ, উচ্চারণ ও ফজিলত সহ সকল আসমাউল হুসনা।',
                'dhikr.haram':'হারাম কোড','dhikr.haram_desc':'দৈনন্দিন সিদ্ধান্তে গাইড করার ইসলামিক নির্দেশিকা সিস্টেম।',
                'tech.title':'পারফরম্যান্সের জন্য তৈরি','tech.subtitle':'আধুনিক, দ্রুত এবং ক্রস-প্ল্যাটফর্ম',
                'tech.flutter':'ফ্লাটার ফ্রেমওয়ার্ক','tech.flutter_desc':'মসৃণ 60fps পারফরম্যান্সের জন্য Flutter ও Dart 3.1.5',
                'tech.cross':'ক্রস-প্ল্যাটফর্ম','tech.cross_desc':'একটি কোডবেস, iOS ও Android-এ নেটিভ পারফরম্যান্স',
                'tech.updates':'বিনামূল্যে আপডেট','tech.updates_desc':'নিয়মিত ফিচার আপডেট ও বাগ ফিক্স',
                'tech.privacy':'গোপনীয়তা প্রথম','tech.privacy_desc':'ন্যূনতম অনুমতি সহ আপনার ডেটা ব্যক্তিগত থাকে',
                'download.app_store_top':'ডাউনলোড করুন','download.app_store':'অ্যাপ স্টোর','download.play_top':'পাওয়া যাচ্ছে','download.play':'গুগল প্লে',
                'download.codecanyon':'পুনরায় বিক্রি বা কাস্টমাইজ করতে চান?','download.codecanyon_link':'CodeCanyon থেকে সোর্স কোড পান →',
                'footer.features':'বৈশিষ্ট্যসমূহ','footer.links':'রিসোর্স ও আইনি','footer.holy_quran':'পবিত্র কুরআন','footer.prayer_times':'নামাজের সময় ও আজান','footer.ai_chat':'AI ইসলামিক চ্যাট',
                'footer.qibla':'কিবলা নির্দেশক','footer.zakat':'যাকাত ক্যালকুলেটর','footer.mosque':'মসজিদ খোঁজুন',
                'footer.privacy':'গোপনীয়তা নীতি','footer.terms':'শর্তাবলী','footer.support':'সহায়তা ও সাহায্য','footer.admin':'অ্যাডমিন প্যানেল',
                'footer.copyright':'ইসলামিক অ্যাপ। সর্বস্বত্ব সংরক্ষিত।','footer.built':'Flutter • Laravel • ভালোবাসা দিয়ে তৈরি ❤️',
                'footer.back_to_top':'উপরে যান','footer.download_app':'অ্যাপ ডাউনলোড করুন','footer.download_desc':'যেখানেই থাকুন কুরআন, নামাজের সময় ও AI গাইডেন্সের অভিজ্ঞতা নিন। বিনামূল্যে iOS ও Android-এ।','footer.trusted_tag':'বিজ্ঞাপনমুক্ত • নির্ভরযোগ্য ইসলামিক উৎস','footer.suite_badge':'প্রো ইসলামিক স্যুট',
                'hero.badge':'iOS ও Android-এ পাওয়া যাচ্ছে','hero.title':'আপনার সম্পূর্ণ','hero.subtitle':'ইসলামিক সঙ্গী',
                'hero.description':'জাবি আপনার কাছে নিয়ে আসে সম্পূর্ণ কুরআন, নামাজের সময়, হাদিস, দোয়া, কিবলা, যাকাত ক্যালকুলেটর, AI ইসলামিক চ্যাট এবং ৪০+ ভাষা — আপনার দৈনন্দিন ইসলামিক জীবনের সবকিছু একটি সুন্দর অ্যাপে।',
                'features.title':'আপনার যা দরকার সব','features.desc':'একটি সম্পূর্ণ ইসলামিক জীবনধারার সঙ্গী যা আপনাকে প্রতিদিন আপনার দ্বীনের সাথে সংযুক্ত রাখে।',
                'quran.title':'সম্পূর্ণ কুরআন পাঠের','quran.highlight':'অভিজ্ঞতা','quran.description':'বহুভাষিক অনুবাদ ও অডিও তেলাওয়াত সহ পবিত্র কুরআন পড়ুন, শুনুন ও বুঝুন।',
                'prayer.title':'কোনো নামাজ','prayer.highlight':'মিস করবেন না','prayer.description':'সুন্দর আজানের নোটিফিকেশন সহ নামাজের সঠিক সময় পান। উন্নত কম্পাস দিয়ে কিবলার দিক খুঁজুন।',
                'ai.title':'কৃত্রিম বুদ্ধিমত্তা দ্বারা চালিত','ai.description':'আপনার আঙুলের ডগায় বুদ্ধিমান ইসলামিক গাইডেন্স — যেকোনো সময়, যেকোনো জায়গায়।',
                'dhikr.title':'জিকির, দোয়া ও স্মরণ','dhikr.description':'প্রতিটি উপলক্ষের দোয়া ও জিকিরের বিস্তৃত সংগ্রহ নিয়ে আল্লাহর সাথে সংযুক্ত থাকুন।',
                'download.title':'আপনার ইসলামিক যাত্রা','download.highlight':'আজই শুরু করুন','download.description':'বিশ্বজুড়ে লক্ষ লক্ষ মুসলিমের সাথে যোগ দিন। আজই বিনামূল্যে ডাউনলোড করুন।',
                'footer.description':'আপনার সম্পূর্ণ ইসলামিক সঙ্গী অ্যাপ। আধুনিক প্রযুক্তি দিয়ে ইসলামের সৌন্দর্য আপনার কাছে নিয়ে আসছি।',
                'nav.donate':'দান করুন',
                'donate.title':'আমাদের মিশন সমর্থন করুন','donate.desc':'আপনার উদারতা বিশ্বজুড়ে লক্ষ লক্ষ মুসলিমের জন্য জাবিকে বিনামূল্যে রাখে।',
                'donate.verse_trans':'"যারা আল্লাহর পথে তাদের সম্পদ ব্যয় করে তাদের উদাহরণ একটি শস্যদানার মতো যা থেকে সাতটি শীষ জন্মায়" — আল-বাকারা ২:২৬১',
                'donate.why1_title':'সর্বদা বিনামূল্যে অ্যাপ','donate.why1_desc':'বিশ্বব্যাপী লক্ষ লক্ষ মুসলিমের জন্য অ্যাপটি বিনামূল্যে রাখে।',
                'donate.why2_title':'বৈশ্বিক অবকাঠামো','donate.why2_desc':'সার্ভার, CDN এবং ২৪/৭ আপটাইম অর্থায়ন করে।',
                'donate.why3_title':'নতুন বৈশিষ্ট্য','donate.why3_desc':'নতুন ইসলামিক সরঞ্জাম তৈরি সক্ষম করে।',
                'donate.why4_title':'গোপনীয়তা ও নিরাপত্তা','donate.why4_desc':'বিজ্ঞাপন ছাড়াই গোপনীয়তা অবকাঠামো বজায় রাখে।',
                'donate.accepted':'গৃহীত মাধ্যমে',
                'donate.amount_label':'পরিমাণ নির্বাচন করুন','donate.custom_placeholder':'কাস্টম পরিমাণ (USD)',
                'donate.category_label':'দান বিভাগ','donate.category_placeholder':'একটি কারণ নির্বাচন করুন...',
                'donate.email_label':'আপনার ইমেইল',
                'donate.payment_label':'পেমেন্ট পদ্ধতি',
                'donate.btn':'এখনই দান করুন',
                'donate.name_label':'আপনার নাম','donate.privacy_note':'🔒 সুরক্ষিত ও এনক্রিপ্টেড। আমরা কার্ডের বিবরণ সংরক্ষণ করি না।',
                'feature.0.title':'কুরআন ও ইসলামিক কন্টেন্ট','feature.0.desc':'বহুভাষিক অনুবাদ ও অডিও তেলাওয়াত সহ সম্পূর্ণ কুরআন পাঠের অভিজ্ঞতা।',
                'feature.1.title':'নামাজ ও ইবাদত','feature.1.desc':'সঠিক নামাজের সময়, আজান নোটিফিকেশন এবং কিবলা নির্দেশক কম্পাস।',
                'feature.2.title':'AI-চালিত বৈশিষ্ট্যসমূহ','feature.2.desc':'ইসলামিক প্রশ্নের তাৎক্ষণিক উত্তর পান এবং পরিবারের জন্য ইসলামিক নাম তৈরি করুন।',
                'feature.3.title':'মসজিদ ও অবস্থান','feature.3.desc':'GPS ব্যবহার করে কাছের মসজিদ খুঁজুন এবং নেভিগেশন সহায়তা পান।',
                'feature.4.title':'ইসলামিক অর্থনীতি ও দান','feature.4.desc':'কাস্টমাইজযোগ্য নিসাব সেটিং সহ সঠিকভাবে যাকাত গণনা করুন।',
                'feature.5.title':'ব্যক্তিগতকরণ','feature.5.desc':'ডার্ক মোড, RTL সাপোর্ট, ডায়নামিক ওয়ালপেপার এবং ৪০+ ভাষার বিকল্প।',
                'feature.0.bullets':'৪টি ভাষা অনুবাদ\nঅডিও কুরআন সাপোর্ট\nট্রান্সলিটারেশন ফিচার\nহাদিস ও দোয়া সংগ্রহ\nআল্লাহর ৯৯ নাম',
                'feature.1.bullets':'সঠিক নামাজের সময়\nআজানের নোটিফিকেশন\nকিবলা ফাইন্ডার ও কম্পাস\nরমজান সময়সূচি\nএকাধিক কারী',
                'feature.2.bullets':'AI ইসলামিক Q&A চ্যাট\nইসলামিক নাম জেনারেটর\nস্মার্ট রেকমেন্ডেশন\nতাৎক্ষণিক উত্তর\n২৪/৭ উপলব্ধ',
                'feature.3.bullets':'কাছের মসজিদ খোঁজক\nGPS নেভিগেশন\nরিয়েল-টাইম অবস্থান\nশহরভিত্তিক নামাজের সময়',
                'feature.4.bullets':'যাকাত ক্যালকুলেটর\nকাস্টমাইজযোগ্য নিসাব\nদান মডিউল\nএকাধিক মুদ্রা',
                'feature.5.bullets':'ডার্ক মোড সাপোর্ট\nRTL ভাষা সাপোর্ট\nডায়নামিক ওয়ালপেপার\nকাস্টম জিকির ও দোয়া',
                'nav.listen':'শুনুন',
                'donate.form_title':'একটি দান করুন','donate.form_subtitle':'প্রতিটি অবদান একটি পার্থক্য তৈরি করে',
                'quran_audio.title':'পড়ুন ও শুনুন ','quran_audio.highlight':'একসাথে','quran_audio.desc':'বিশ্বখ্যাত কারীদের কণ্ঠ শুনতে শুনতে আরবি পাঠ অনুসরণ করুন — আয়াত বাই আয়াত।',
                'ai_chat.badge':'লাইভ ডেমো • Groq দ্বারা চালিত','ai_chat.title_line1':'যেকোনো','ai_chat.title_line2':'ইসলামিক প্রশ্ন করুন',
                'ai_chat.desc':'ইসলাম সম্পর্কে তাৎক্ষণিক উত্তর পান — কুরআন ও হাদিস থেকে নামাজ, ফিকহ এবং দৈনন্দিন মুসলিম জীবন পর্যন্ত। এই পেজেই দ্রুততম AI।',
                'ai_chat.sub_title':'মিলিসেকেন্ডে উত্তর','ai_chat.sub_desc':'Groq LPU প্রযুক্তি মিলিসেকেন্ডে উত্তর দেয়',
                'ai_chat.hadith_title':'কুরআন ও হাদিস রেফারেন্স','ai_chat.hadith_desc':'উত্তরে আরবি পাঠ্য ও প্রামাণিক উৎস অন্তর্ভুক্ত',
                'ai_chat.app_title':'অ্যাপে সম্পূর্ণ অভিজ্ঞতা','ai_chat.app_desc':'চ্যাট হিস্ট্রি, অফলাইন অ্যাক্সেস এবং আরও অনেক কিছু Zabi অ্যাপে',
                'ai_chat.try_asking':'জিজ্ঞেস করে দেখুন:',
                'name_gen.title':'ইসলামিক নাম ','name_gen.highlight':'জেনারেটর','name_gen.desc':'আরবি লিপি, ইংরেজি উচ্চারণ এবং উৎস সহ সুন্দর ও অর্থবহ ইসলামিক নাম আবিষ্কার করুন — AI দ্বারা চালিত।',
                'name_gen.label':'নাম তৈরি করুন','name_gen.theme':'অর্থের থিম','name_gen.optional':'(ঐচ্ছিক)','name_gen.btn':'✨ নাম তৈরি করুন',
            },
            hi: {
                'nav.features':'विशेषताएं','nav.quran':'कुरान','nav.prayer':'नमाज़','nav.ai':'AI','nav.download':'डाउनलोड','nav.admin':'एडमिन लॉगिन','nav.buy_btn':'अभी खरीदें','nav.download_btn':'मुफ्त डाउनलोड',
                'hero.app_store':'ऐप स्टोर','hero.play_store':'गूगल प्ले','hero.rating_label':'रेटिंग','hero.downloads_label':'डाउनलोड','hero.languages_label':'भाषाएं',
                'stats.surahs':'कुरान की सूरतें','stats.languages':'भाषाएं','stats.rating':'ऐप रेटिंग','stats.downloads':'डाउनलोड',
                'quran.verse_trans':'"हमने कुरान को याद करने के लिए आसान बना दिया है, तो क्या कोई याद करने वाला है?" — सूरह अल-कमर 54:17',
                'quran.surah_title':'पूरी 114 सूरतें','quran.surah_desc':'कई तिलावत शैलियों के साथ सभी 6,236 आयतें।',
                'quran.audio_title':'ऑडियो कुरान','quran.audio_desc':'ऑफलाइन सपोर्ट के साथ विश्वप्रसिद्ध कारियों को सुनें।',
                'quran.bookmark_title':'बुकमार्क और इतिहास','quran.bookmark_desc':'अपनी जगह सेव करें, पसंदीदा आयतें बुकमार्क करें।',
                'prayer.qibla_title':'क़िबला दिशा','prayer.qibla_desc':'बेहतर सटीकता के साथ सटीक कम्पास',
                'prayer.adhan_title':'अज़ान अलर्ट','prayer.adhan_desc':'चुनने के लिए कई सुंदर कारी',
                'prayer.ramadan_title':'रमज़ान मोड','prayer.ramadan_desc':'आपके शहर के इफ्तार और सहरी का समय',
                'prayer.custom_title':'अनुकूलनीय','prayer.custom_desc':'मैनुअल समायोजन और AM/PM प्रारूप',
                'prayer.card_label':'आज का नमाज़ का समय',
                'ai.chat_title':'AI इस्लामिक चैट','ai.chat_desc':'कोई भी इस्लामिक प्रश्न पूछें और तुरंत सटीक उत्तर पाएं। फिकह से लेकर रोज़ाना की दुआओं तक।',
                'ai.chat_q':'खाने से पहले की दुआ क्या है?','ai.chat_a':'"बिस्मिल्लाह" — अल्लाह के नाम से। (बुखारी और मुस्लिम)',
                'ai.name_title':'इस्लामिक नाम जनरेटर','ai.name_desc':'अपने बच्चे के लिए सही इस्लामिक नाम खोजें। AI अरबी मूल और उच्चारण के साथ सुंदर नाम बनाता है।','ai.name_label':'लड़कों के लिए सुझाए गए नाम',
                'dhikr.tasbih':'तस्बीह काउंटर','dhikr.tasbih_desc':'कस्टमाइज़ेबल वाक्यांशों के साथ डिजिटल ज़िक्र काउंटर।',
                'dhikr.dua':'दुआ संग्रह','dhikr.dua_desc':'सुबह, शाम, यात्रा, खाने और विशेष अवसरों की दुआएं।',
                'dhikr.names':'अल्लाह के 99 नाम','dhikr.names_desc':'अर्थ, उच्चारण और फ़ज़ीलत के साथ सभी अस्माउल हुस्ना।',
                'dhikr.haram':'हराम कोड','dhikr.haram_desc':'रोज़ाना के फैसलों में मार्गदर्शन करने वाली इस्लामिक प्रणाली।',
                'tech.title':'प्रदर्शन के लिए बनाया गया','tech.subtitle':'आधुनिक, तेज़ और क्रॉस-प्लेटफ़ॉर्म',
                'tech.flutter':'फ्लटर फ्रेमवर्क','tech.flutter_desc':'स्मूद 60fps प्रदर्शन के लिए Flutter और Dart 3.1.5',
                'tech.cross':'क्रॉस-प्लेटफ़ॉर्म','tech.cross_desc':'एक कोडबेस, iOS और Android पर नेटिव प्रदर्शन',
                'tech.updates':'मुफ्त अपडेट','tech.updates_desc':'नियमित फ़ीचर अपडेट और बग फ़िक्स',
                'tech.privacy':'गोपनीयता पहले','tech.privacy_desc':'न्यूनतम अनुमतियों के साथ आपका डेटा निजी रहता है',
                'download.app_store_top':'डाउनलोड करें','download.app_store':'ऐप स्टोर','download.play_top':'प्राप्त करें','download.play':'गूगल प्ले',
                'download.codecanyon':'पुनः बेचना या कस्टमाइज़ करना चाहते हैं?','download.codecanyon_link':'CodeCanyon पर सोर्स कोड पाएं →',
                'footer.features':'विशेषताएं','footer.links':'संसाधन व कानूनी','footer.holy_quran':'पवित्र कुरान','footer.prayer_times':'नमाज़ का समय व अज़ान','footer.ai_chat':'AI इस्लामिक चैट',
                'footer.qibla':'क़िबला दिशा','footer.zakat':'ज़कात कैलकुलेटर','footer.mosque':'मस्जिद खोजें',
                'footer.privacy':'गोपनीयता नीति','footer.terms':'नियम और शर्तें','footer.support':'सहायता व मदद','footer.admin':'एडमिन पैनल',
                'footer.copyright':'इस्लामिक ऐप। सर्वाधिकार सुरक्षित।','footer.built':'Flutter • Laravel • प्यार से बनाया ❤️',
                'footer.back_to_top':'ऊपर जाएं','footer.download_app':'ऐप डाउनलोड करें','footer.download_desc':'कुरान, नमाज़ के समय और AI मार्गदर्शन का अनुभव कहीं भी लें। iOS व Android पर मुफ्त।','footer.trusted_tag':'विज्ञापन-मुक्त • प्रामाणिक इस्लामी स्रोत','footer.suite_badge':'प्रो इस्लामिक सूट',
                'hero.badge':'iOS और Android पर उपलब्ध','hero.title':'आपका संपूर्ण','hero.subtitle':'इस्लामिक साथी',
                'hero.description':'ज़ाबी आपके लिए लाता है पूरा कुरान, नमाज़ का समय, हदीस, दुआएं, क़िबला, ज़कात कैलकुलेटर, AI इस्लामिक चैट, और 40+ भाषाएं — आपके दैनिक इस्लामिक जीवन के लिए सब कुछ एक सुंदर ऐप में।',
                'features.title':'वो सब कुछ जो आपको चाहिए','features.desc':'एक संपूर्ण इस्लामिक जीवनशैली साथी जो आपको हर दिन अपने दीन से जोड़े रखता है।',
                'quran.title':'पूर्ण कुरान पठन','quran.highlight':'अनुभव','quran.description':'बहुभाषी अनुवाद और ऑडियो तिलावत के साथ पवित्र कुरान पढ़ें, सुनें और समझें।',
                'prayer.title':'कोई नमाज़','prayer.highlight':'न चूकें','prayer.description':'सुंदर अज़ान सूचनाओं के साथ सटीक नमाज़ का समय पाएं। बेहतर कम्पास से क़िबला दिशा खोजें।',
                'ai.title':'आर्टिफिशियल इंटेलिजेंस द्वारा संचालित','ai.description':'अपनी उंगलियों पर बुद्धिमान इस्लामिक मार्गदर्शन — कहीं भी, कभी भी।',
                'dhikr.title':'ज़िक्र, दुआ और याद','dhikr.description':'हर अवसर के लिए दुआओं और ज़िक्र के व्यापक संग्रह के साथ अल्लाह से जुड़े रहें।',
                'download.title':'अपनी इस्लामिक यात्रा','download.highlight':'आज शुरू करें','download.description':'दुनिया भर के लाखों मुसलमानों के साथ जुड़ें। आज मुफ्त में डाउनलोड करें।',
                'footer.description':'आपका संपूर्ण इस्लामिक साथी ऐप। आधुनिक तकनीक के साथ इस्लाम की सुंदरता आपकी उंगलियों तक।',
                'nav.donate':'दान करें',
                'donate.title':'हमारे मिशन का समर्थन करें','donate.desc':'आपकी उदारता दुनिया भर के लाखों मुसलमानों के लिए ज़ाबी को मुफ्त रखती है।',
                'donate.verse_trans':'"जो लोग अल्लाह के रास्ते में अपना माल खर्च करते हैं उनकी मिसाल उस दाने जैसी है जिससे सात बालियाँ निकलें" — अल-बकरह 2:261',
                'donate.why1_title':'हमेशा मुफ्त ऐप','donate.why1_desc':'दुनिया भर के लाखों मुसलमानों के लिए ऐप को मुफ्त रखता है।',
                'donate.why2_title':'वैश्विक अवसंरचना','donate.why2_desc':'सर्वर, CDN और 24/7 अपटाइम को फंड करता है।',
                'donate.why3_title':'नई सुविधाएं','donate.why3_desc':'नए इस्लामिक उपकरणों के विकास को सक्षम करता है।',
                'donate.why4_title':'गोपनीयता और सुरक्षा','donate.why4_desc':'बिना विज्ञापन के गोपनीयता-प्रथम बुनियादी ढांचा।',
                'donate.accepted':'द्वारा स्वीकृत',
                'donate.amount_label':'राशि चुनें','donate.custom_placeholder':'कस्टम राशि (USD)',
                'donate.category_label':'दान श्रेणी','donate.category_placeholder':'एक कारण चुनें...',
                'donate.email_label':'आपका ईमेल',
                'donate.payment_label':'भुगतान विधि',
                'donate.btn':'अभी दान करें',
                'donate.name_label':'आपका नाम','donate.privacy_note':'🔒 सुरक्षित और एन्क्रिप्टेड। हम कार्ड विवरण संग्रहीत नहीं करते।',
                'feature.0.title':'कुरान और इस्लामिक सामग्री','feature.0.desc':'बहुभाषी अनुवाद और ऑडियो तिलावत के साथ पूरा अल-कुरान पढ़ने का अनुभव।',
                'feature.1.title':'नमाज़ और इबादत','feature.1.desc':'सटीक नमाज़ का समय, अज़ान नोटिफिकेशन और क़िबला फाइंडर।',
                'feature.2.title':'AI-संचालित विशेषताएं','feature.2.desc':'इस्लामिक सवालों के तुरंत जवाब और परिवार के लिए इस्लामिक नाम।',
                'feature.3.title':'मस्जिद और स्थान','feature.3.desc':'GPS से नज़दीकी मस्जिद खोजें और नेविगेशन सहायता पाएं।',
                'feature.4.title':'इस्लामिक वित्त और दान','feature.4.desc':'कस्टमाइज़ेबल निसाब सेटिंग के साथ सटीक ज़कात की गणना करें।',
                'feature.5.title':'व्यक्तिगतकरण','feature.5.desc':'डार्क मोड, RTL सपोर्ट, डायनामिक वॉलपेपर और 40+ भाषाओं के विकल्प।',
                'feature.0.bullets':'4 भाषा अनुवाद\nऑडियो कुरान सपोर्ट\nट्रांसलिटरेशन फीचर\nहदीस और दुआ संग्रह\nअल्लाह के 99 नाम',
                'feature.1.bullets':'सटीक नमाज़ का समय\nअज़ान नोटिफिकेशन\nक़िबला फाइंडर और कम्पास\nरमज़ान शेड्यूल\nएकाधिक कारी',
                'feature.2.bullets':'AI इस्लामिक Q&A चैट\nइस्लामिक नाम जनरेटर\nस्मार्ट रेकमेंडेशन\nतुरंत जवाब\n24/7 उपलब्धता',
                'feature.3.bullets':'नज़दीकी मस्जिद खोजक\nGPS नेविगेशन\nरियल-टाइम लोकेशन\nशहर-आधारित नमाज़ का समय',
                'feature.4.bullets':'ज़कात कैलकुलेटर\nकस्टमाइज़ेबल निसाब\nदान मॉड्यूल\nएकाधिक मुद्राएं',
                'feature.5.bullets':'डार्क मोड सपोर्ट\nRTL भाषा सपोर्ट\nडायनामिक वॉलपेपर\nकस्टम ज़िक्र और दुआ',
                'nav.listen':'सुनें',
                'donate.form_title':'दान करें','donate.form_subtitle':'हर योगदान एक फर्क डालता है',
                'quran_audio.title':'पढ़ें और सुनें ','quran_audio.highlight':'एक साथ','quran_audio.desc':'विश्वप्रसिद्ध कारियों को सुनते हुए अरबी पाठ का अनुसरण करें — आयत दर आयत।',
                'ai_chat.badge':'लाइव डेमो • Groq द्वारा संचालित','ai_chat.title_line1':'कोई भी','ai_chat.title_line2':'इस्लामिक सवाल पूछें',
                'ai_chat.desc':'इस्लाम के बारे में तुरंत जानकारीपूर्ण उत्तर पाएं — कुरान और हदीस से नमाज़, फिकह और मुस्लिम दैनिक जीवन तक। अल्ट्राफास्ट AI, यहीं इस पेज पर।',
                'ai_chat.sub_title':'एक सेकंड से कम उत्तर','ai_chat.sub_desc':'Groq LPU तकनीक मिलीसेकंड में उत्तर देती है',
                'ai_chat.hadith_title':'कुरान और हदीस संदर्भित','ai_chat.hadith_desc':'उत्तरों में अरबी पाठ और प्रामाणिक स्रोत शामिल हैं',
                'ai_chat.app_title':'ऐप में पूरा अनुभव','ai_chat.app_desc':'चैट इतिहास, ऑफ़लाइन पहुंच और ज़ाबी ऐप में और बहुत कुछ',
                'ai_chat.try_asking':'पूछकर देखें:',
                'name_gen.title':'इस्लामिक नाम ','name_gen.highlight':'जनरेटर','name_gen.desc':'अरबी लिपि, अंग्रेज़ी लिप्यंतरण और मूल के साथ सुंदर इस्लामिक नाम खोजें — AI द्वारा संचालित।',
                'name_gen.label':'नाम उत्पन्न करें','name_gen.theme':'अर्थ थीम','name_gen.optional':'(वैकल्पिक)','name_gen.btn':'✨ नाम उत्पन्न करें',
            },
            es: {
                'nav.features':'Características','nav.quran':'Corán','nav.prayer':'Oración','nav.ai':'IA','nav.download':'Descargar','nav.admin':'Login Admin','nav.buy_btn':'Comprar Ahora','nav.download_btn':'Descargar Gratis',
                'hero.app_store':'App Store','hero.play_store':'Google Play','hero.rating_label':'Calificación','hero.downloads_label':'Descargas','hero.languages_label':'Idiomas',
                'stats.surahs':'Suras del Corán','stats.languages':'Idiomas','stats.rating':'Calificación','stats.downloads':'Descargas',
                'quran.verse_trans':'"Hemos facilitado el Corán para ser recordado, ¿habrá quien lo recuerde?" — Al-Qamar 54:17',
                'quran.surah_title':'114 Suras Completas','quran.surah_desc':'Las 6,236 aleyas con múltiples estilos de recitación y audio versículo por versículo.',
                'quran.audio_title':'Corán en Audio','quran.audio_desc':'Escucha recitadores de renombre mundial con soporte sin conexión.',
                'quran.bookmark_title':'Marcadores e Historial','quran.bookmark_desc':'Guarda tu lugar, marca tus aleyas favoritas y continúa donde lo dejaste.',
                'prayer.qibla_title':'Buscador de Qibla','prayer.qibla_desc':'Brújula precisa con mayor precisión mejorada',
                'prayer.adhan_title':'Alertas de Adhan','prayer.adhan_desc':'Múltiples recitadores hermosos para elegir',
                'prayer.ramadan_title':'Modo Ramadán','prayer.ramadan_desc':'Horarios de Iftar y Suhur para tu ciudad',
                'prayer.custom_title':'Personalizable','prayer.custom_desc':'Ajustes manuales y formato AM/PM',
                'prayer.card_label':'Horarios de Oración de Hoy',
                'ai.chat_title':'Chat Islámico con IA','ai.chat_desc':'Haz cualquier pregunta islámica y recibe respuestas precisas al instante. Desde fallos de fiqh hasta du\'as diarias.',
                'ai.chat_q':'¿Cuál es la du\'a antes de comer?','ai.chat_a':'"Bismillah" — En el nombre de Allah. (Bujari y Muslim)',
                'ai.name_title':'Generador de Nombres Islámicos','ai.name_desc':'Encuentra el nombre islámico perfecto para tu hijo. La IA genera nombres bellos y significativos con su origen árabe.','ai.name_label':'Nombres Sugeridos para Niños',
                'dhikr.tasbih':'Contador de Tasbih','dhikr.tasbih_desc':'Contador digital de dhikr con frases personalizables y retroalimentación háptica.',
                'dhikr.dua':'Colección de Du\'as','dhikr.dua_desc':'Du\'as completas para mañana, tarde, viaje, comida y ocasiones especiales.',
                'dhikr.names':'99 Nombres de Allah','dhikr.names_desc':'Todos los Asma ul Husna con significados, transliteración y virtudes.',
                'dhikr.haram':'Código Haram','dhikr.haram_desc':'Sistema de orientación islámica para decisiones cotidianas.',
                'tech.title':'Construido para el Rendimiento','tech.subtitle':'Moderno, rápido y multiplataforma',
                'tech.flutter':'Framework Flutter','tech.flutter_desc':'Construido con Flutter y Dart 3.1.5 para un rendimiento fluido de 60fps',
                'tech.cross':'Multiplataforma','tech.cross_desc':'Un código base, rendimiento nativo en iOS y Android',
                'tech.updates':'Actualizaciones Gratuitas','tech.updates_desc':'Actualizaciones regulares de funciones y corrección de errores',
                'tech.privacy':'Privacidad Primero','tech.privacy_desc':'Tus datos permanecen privados con permisos mínimos requeridos',
                'download.app_store_top':'Descargar en','download.app_store':'App Store','download.play_top':'Disponible en','download.play':'Google Play',
                'download.codecanyon':'¿Quieres revender o personalizar?','download.codecanyon_link':'Obtén el código fuente en CodeCanyon →',
                'footer.features':'Características','footer.links':'Recursos y Legal','footer.holy_quran':'Santo Corán','footer.prayer_times':'Horarios de Oración y Adhan','footer.ai_chat':'Chat Islámico con IA',
                'footer.qibla':'Buscador de Qibla','footer.zakat':'Calculadora de Zakat','footer.mosque':'Buscador de Mezquitas',
                'footer.privacy':'Política de Privacidad','footer.terms':'Términos y Condiciones','footer.support':'Soporte y Ayuda','footer.admin':'Panel de Admin',
                'footer.copyright':'Aplicación Islámica. Todos los derechos reservados.','footer.built':'Construido con Flutter • Laravel • Amor ❤️',
                'footer.back_to_top':'Volver arriba','footer.download_app':'Obtén la App','footer.download_desc':'Lleva el Corán, horarios de oración y orientación con IA a donde vayas. Gratis en iOS y Android.','footer.trusted_tag':'100% Sin Publicidad • Fuentes Islámicas Auténticas','footer.suite_badge':'Suite Islámica Pro',
                'hero.badge':'Disponible en iOS y Android','hero.title':'Tu Compañero Islámico','hero.subtitle':'Completo',
                'hero.description':'Zabi te trae el Corán completo, horarios de oración, hadices, du\'as, Qibla, calculadora de Zakat, chat islámico con IA y más de 40 idiomas — todo en una hermosa aplicación.',
                'features.title':'Todo lo que Necesitas','features.desc':'Un compañero completo de estilo de vida islámico lleno de características que te ayudan a mantenerte conectado con tu fe cada día.',
                'quran.title':'Experiencia Completa de','quran.highlight':'Lectura del Corán','quran.description':'Lee, escucha y comprende el Sagrado Corán con traducciones multilingües, recitación de audio y hermosa tipografía árabe.',
                'prayer.title':'Nunca te Pierdas','prayer.highlight':'una Oración','prayer.description':'Obtén horarios de oración precisos con hermosas notificaciones de Adhan. Encuentra la dirección de la Qibla con una brújula mejorada.',
                'ai.title':'Impulsado por Inteligencia Artificial','ai.description':'Obtén orientación islámica inteligente al alcance de tu mano — en cualquier momento y lugar.',
                'dhikr.title':'Dhikr, Du\'a y Recuerdo','dhikr.description':'Mantente conectado con Allah con nuestra colección integral de du\'as y dhikr para cada ocasión.',
                'download.title':'Comienza tu Viaje','download.highlight':'Islámico Hoy','download.description':'Únete a cientos de miles de musulmanes en todo el mundo. Descárgalo gratis hoy.',
                'footer.description':'Tu aplicación compañera islámica completa. Llevando la belleza del Islam a tus manos con tecnología moderna.',
                'nav.donate':'Donar',
                'donate.title':'Apoya Nuestra Misión','donate.desc':'Tu generosidad mantiene Zabi gratuito para millones de musulmanes en todo el mundo.',
                'donate.verse_trans':'"El ejemplo de quienes gastan en el camino de Allah es como una semilla que produce siete espigas" — Al-Baqarah 2:261',
                'donate.why1_title':'App Siempre Gratuita','donate.why1_desc':'Mantiene la app gratuita para millones de musulmanes.',
                'donate.why2_title':'Infraestructura Global','donate.why2_desc':'Financia servidores, CDN y disponibilidad 24/7.',
                'donate.why3_title':'Nuevas Funciones','donate.why3_desc':'Permite el desarrollo de nuevas herramientas islámicas.',
                'donate.why4_title':'Privacidad y Seguridad','donate.why4_desc':'Infraestructura sin anuncios con privacidad primero.',
                'donate.accepted':'Aceptado vía',
                'donate.amount_label':'Seleccionar Monto','donate.custom_placeholder':'Monto personalizado (USD)',
                'donate.category_label':'Categoría de Donación','donate.category_placeholder':'Selecciona una causa...',
                'donate.email_label':'Tu Correo Electrónico',
                'donate.payment_label':'Método de Pago',
                'donate.btn':'Donar Ahora',
                'donate.name_label':'Tu Nombre','donate.privacy_note':'🔒 Seguro y cifrado. Nunca almacenamos datos de tarjetas.',
                'feature.0.title':'Corán y Contenido Islámico','feature.0.desc':'Experiencia completa de lectura del Corán con traducciones multilingües y recitación de audio.',
                'feature.1.title':'Oración y Adoración','feature.1.desc':'Horarios de oración precisos, notificaciones de Adhan y buscador de Qibla con brújula.',
                'feature.2.title':'Características con IA','feature.2.desc':'Obtén respuestas instantáneas a preguntas islámicas y genera nombres islámicos para tu familia.',
                'feature.3.title':'Mezquita y Ubicación','feature.3.desc':'Encuentra mezquitas cercanas con GPS y obtén asistencia de navegación.',
                'feature.4.title':'Finanzas Islámicas y Caridad','feature.4.desc':'Calcula tu Zakat con precisión con configuraciones personalizables de Nisab.',
                'feature.5.title':'Personalización','feature.5.desc':'Modo oscuro, soporte RTL, fondos dinámicos y más de 40 opciones de idioma.',
                'feature.0.bullets':'4 Traducciones de Idiomas\nSoporte Audio Corán\nFunción de Transliteración\nColecciones de Hadith y Duʼa\n99 Nombres de Allah',
                'feature.1.bullets':'Horarios de Oración Precisos\nNotificaciones de Adhan\nBuscador de Qibla y Brújula\nCalendario de Ramadán\nMúltiples Reciters',
                'feature.2.bullets':'Chat IA de P&R Islámico\nGenerador de Nombres Islámicos\nRecomendaciones Inteligentes\nRespuestas Instantáneas\nDisponibilidad 24/7',
                'feature.3.bullets':'Buscador de Mezquitas Cercanas\nNavegación GPS\nUbicación en Tiempo Real\nHorarios por Ciudad',
                'feature.4.bullets':'Calculadora de Zakat\nNisab Personalizable\nMódulo de Donación\nMúltiples Monedas',
                'feature.5.bullets':'Soporte Modo Oscuro\nSoporte RTL\nFondos Dinámicos\nDhikr y Duʼa Personalizados',
                'nav.listen':'Escuchar',
                'donate.form_title':'Haz una Donación','donate.form_subtitle':'Cada contribución marca la diferencia',
                'quran_audio.title':'Lee y Escucha ','quran_audio.highlight':'Juntos','quran_audio.desc':'Sigue el texto árabe mientras escuchas a reciters de renombre mundial — versículo a versículo.',
                'ai_chat.badge':'Demo en Vivo • Impulsado por Groq','ai_chat.title_line1':'Haz Cualquier','ai_chat.title_line2':'Pregunta Islámica',
                'ai_chat.desc':'Obtén respuestas instantáneas y precisas sobre el Islam — del Corán y Hadith a la oración, el fiqh y la vida diaria musulmana. IA ultrarrápida, aquí mismo en la página.',
                'ai_chat.sub_title':'Respuestas en Menos de un Segundo','ai_chat.sub_desc':'La tecnología Groq LPU entrega respuestas en milisegundos',
                'ai_chat.hadith_title':'Corán y Hadith Referenciados','ai_chat.hadith_desc':'Las respuestas incluyen texto árabe y citas de fuentes auténticas',
                'ai_chat.app_title':'Experiencia Completa en la App','ai_chat.app_desc':'Historial de chat, acceso sin conexión y más dentro de Zabi',
                'ai_chat.try_asking':'Intenta preguntar:',
                'name_gen.title':'Nombres Islámicos ','name_gen.highlight':'Generador','name_gen.desc':'Descubre nombres islámicos hermosos y significativos con escritura árabe, transliteración inglesa y orígenes — impulsado por IA.',
                'name_gen.label':'Generar nombres para un','name_gen.theme':'Tema de Significado','name_gen.optional':'(opcional)','name_gen.btn':'✨ Generar Nombres',
            },
            fr: {
                'nav.features':'Fonctionnalités','nav.quran':'Coran','nav.prayer':'Prière','nav.ai':'IA','nav.download':'Télécharger','nav.admin':'Connexion Admin','nav.buy_btn':'Acheter Maintenant','nav.download_btn':'Télécharger Gratuitement',
                'hero.app_store':'App Store','hero.play_store':'Google Play','hero.rating_label':'Note','hero.downloads_label':'Téléchargements','hero.languages_label':'Langues',
                'stats.surahs':'Sourates du Coran','stats.languages':'Langues','stats.rating':'Note de l\'App','stats.downloads':'Téléchargements',
                'quran.verse_trans':'"Nous avons facilité le Coran pour la méditation, y a-t-il quelqu\'un pour méditer ?" — Al-Qamar 54:17',
                'quran.surah_title':'114 Sourates Complètes','quran.surah_desc':'Tous les 6 236 versets avec plusieurs styles de récitation et audio verset par verset.',
                'quran.audio_title':'Coran Audio','quran.audio_desc':'Écoutez des récitateurs de renommée mondiale avec support hors ligne.',
                'quran.bookmark_title':'Signets et Historique','quran.bookmark_desc':'Sauvegardez votre place, mettez en signet vos versets préférés.',
                'prayer.qibla_title':'Trouver la Qibla','prayer.qibla_desc':'Boussole précise avec précision améliorée',
                'prayer.adhan_title':'Alertes Adhan','prayer.adhan_desc':'Plusieurs beaux récitateurs au choix',
                'prayer.ramadan_title':'Mode Ramadan','prayer.ramadan_desc':'Horaires Iftar et Suhur pour votre ville',
                'prayer.custom_title':'Personnalisable','prayer.custom_desc':'Ajustements manuels et format AM/PM',
                'prayer.card_label':'Horaires de Prière d\'Aujourd\'hui',
                'ai.chat_title':'Chat Islamique IA','ai.chat_desc':'Posez n\'importe quelle question islamique et recevez des réponses précises instantanément.',
                'ai.chat_q':'Quelle est la doua avant de manger ?','ai.chat_a':'"Bismillah" — Au nom d\'Allah. (Bukhari et Muslim)',
                'ai.name_title':'Générateur de Prénoms Islamiques','ai.name_desc':'Trouvez le prénom islamique parfait pour votre enfant. L\'IA génère de beaux prénoms significatifs avec leur origine arabe.','ai.name_label':'Prénoms Suggérés pour Garçons',
                'dhikr.tasbih':'Compteur Tasbih','dhikr.tasbih_desc':'Compteur dhikr numérique avec phrases personnalisables et retour haptique.',
                'dhikr.dua':'Collection de Douas','dhikr.dua_desc':'Douas complètes pour le matin, soir, voyage, repas et occasions spéciales.',
                'dhikr.names':'99 Noms d\'Allah','dhikr.names_desc':'Tous les Asma ul Husna avec significations, translittération et vertus.',
                'dhikr.haram':'Code Haram','dhikr.haram_desc':'Système de guidance islamique pour les décisions quotidiennes.',
                'tech.title':'Conçu pour la Performance','tech.subtitle':'Moderne, rapide et multiplateforme',
                'tech.flutter':'Framework Flutter','tech.flutter_desc':'Construit avec Flutter et Dart 3.1.5 pour une performance fluide à 60fps',
                'tech.cross':'Multiplateforme','tech.cross_desc':'Une base de code, performance native sur iOS et Android',
                'tech.updates':'Mises à Jour Gratuites','tech.updates_desc':'Mises à jour régulières et corrections de bugs sans frais supplémentaires',
                'tech.privacy':'Vie Privée d\'Abord','tech.privacy_desc':'Vos données restent privées avec des permissions minimales requises',
                'download.app_store_top':'Télécharger sur','download.app_store':'App Store','download.play_top':'Disponible sur','download.play':'Google Play',
                'download.codecanyon':'Vous voulez revendre ou personnaliser ?','download.codecanyon_link':'Obtenez le code source sur CodeCanyon →',
                'footer.features':'Fonctionnalités','footer.links':'Ressources & Légal','footer.holy_quran':'Saint Coran','footer.prayer_times':'Horaires de Prière & Adhan','footer.ai_chat':'Chat Islamique IA',
                'footer.qibla':'Trouver la Qibla','footer.zakat':'Calculateur Zakat','footer.mosque':'Trouver une Mosquée',
                'footer.privacy':'Politique de Confidentialité','footer.terms':'Conditions d\'Utilisation','footer.support':'Support & Aide','footer.admin':'Panneau Admin',
                'footer.copyright':'Application Islamique. Tous droits réservés.','footer.built':'Construit avec Flutter • Laravel • Amour ❤️',
                'footer.back_to_top':'Haut de page','footer.download_app':'Télécharger l\'App','footer.download_desc':'Vivez l\'expérience du Coran, des horaires de prière et de l\'IA où que vous soyez. Gratuit sur iOS & Android.','footer.trusted_tag':'Sans Publicité • Sources Islamiques Authentiques','footer.suite_badge':'Suite Islamique Pro',
                'hero.badge':'Disponible sur iOS et Android','hero.title':'Votre Compagnon Islamique','hero.subtitle':'Complet',
                'hero.description':'Zabi vous apporte le Coran complet, les horaires de prière, les hadiths, les douas, la Qibla, le calculateur Zakat, le chat islamique IA et plus de 40 langues — tout dans une belle application.',
                'features.title':'Tout ce dont Vous Avez Besoin','features.desc':'Un compagnon de style de vie islamique complet rempli de fonctionnalités qui vous aident à rester connecté à votre foi chaque jour.',
                'quran.title':'Expérience de Lecture','quran.highlight':'Complète du Coran','quran.description':'Lisez, écoutez et comprenez le Saint Coran avec des traductions multilingues, une récitation audio et une belle typographie arabe.',
                'prayer.title':'Ne Manquez Jamais','prayer.highlight':'une Prière','prayer.description':'Obtenez des horaires de prière précis avec de belles notifications Adhan. Trouvez la direction Qibla avec une boussole améliorée.',
                'ai.title':'Propulsé par l\'Intelligence Artificielle','ai.description':'Obtenez une guidance islamique intelligente à portée de main — n\'importe où, n\'importe quand.',
                'dhikr.title':'Dhikr, Doua et Souvenir','dhikr.description':'Restez connecté à Allah avec notre collection complète de douas et dhikr pour chaque occasion.',
                'download.title':'Commencez Votre Voyage','download.highlight':'Islamique Aujourd\'hui','download.description':'Rejoignez des centaines de milliers de musulmans à travers le monde. Téléchargez gratuitement aujourd\'hui.',
                'footer.description':'Votre application compagnon islamique complète. Apportant la beauté de l\'Islam à vos mains avec la technologie moderne.',
                'nav.donate':'Faire un Don',
                'donate.title':'Soutenez Notre Mission','donate.desc':'Votre générosité garde Zabi gratuit pour des millions de musulmans dans le monde entier.',
                'donate.verse_trans':'"L\'exemple de ceux qui dépensent leurs biens dans le sentier d\'Allah est celui d\'un grain qui produit sept épis" — Al-Baqarah 2:261',
                'donate.why1_title':'App Toujours Gratuite','donate.why1_desc':'Garde l\'application gratuite pour des millions de musulmans.',
                'donate.why2_title':'Infrastructure Mondiale','donate.why2_desc':'Finance les serveurs, CDN et disponibilité 24/7.',
                'donate.why3_title':'Nouvelles Fonctionnalités','donate.why3_desc':'Permet le développement de nouveaux outils islamiques.',
                'donate.why4_title':'Confidentialité et Sécurité','donate.why4_desc':'Infrastructure sans publicité axée sur la confidentialité.',
                'donate.accepted':'Accepté via',
                'donate.amount_label':'Sélectionner le Montant','donate.custom_placeholder':'Montant personnalisé (USD)',
                'donate.category_label':'Catégorie de Don','donate.category_placeholder':'Choisir une cause...',
                'donate.email_label':'Votre Email',
                'donate.payment_label':'Méthode de Paiement',
                'donate.btn':'Faire un Don Maintenant',
                'donate.name_label':'Votre Nom','donate.privacy_note':'🔒 Sécurisé et chiffré. Nous ne stockons jamais les détails de carte.',
                'feature.0.title':'Coran et Contenu Islamique','feature.0.desc':'Expérience complète de lecture du Coran avec traductions multilingues et récitation audio.',
                'feature.1.title':'Prière et Adoration','feature.1.desc':'Horaires de prière précis, notifications Adhan et buscador Qibla avec boussole.',
                'feature.2.title':'Fonctionnalités IA','feature.2.desc':'Obtenez des réponses instantanées aux questions islamiques et générez des prénoms islamiques.',
                'feature.3.title':'Mosquée et Localisation','feature.3.desc':'Trouvez les mosquées proches avec GPS et obtenez une assistance de navigation.',
                'feature.4.title':'Finance Islamique et Charité','feature.4.desc':'Calculez votre Zakat précisément avec des paramètres Nisab personnalisables.',
                'feature.5.title':'Personnalisation','feature.5.desc':'Mode sombre, support RTL, fonds d\'écran dynamiques et plus de 40 options de langue.',
                'feature.0.bullets':'4 Traductions de Langues\nSupport Audio Coran\nFonction Translittération\nCollections Hadith et Doua\n99 Noms d\'Allah',
                'feature.1.bullets':'Horaires de Prière Précis\nNotifications Adhan\nQibla et Boussole\nCalendrier Ramadan\nMultiples Récitateurs',
                'feature.2.bullets':'Chat IA Q&R Islamique\nGénérateur de Prénoms Islamiques\nRecommandations Intelligentes\nRéponses Instantanées\nDisponibilité 24/7',
                'feature.3.bullets':'Trouver Mosquées Proches\nNavigation GPS\nLocalisation en Temps Réel\nHoraires par Ville',
                'feature.4.bullets':'Calculateur Zakat\nNisab Personnalisable\nModule de Don\nMultiples Devises',
                'feature.5.bullets':'Support Mode Sombre\nSupport RTL\nFonds d\'Écran Dynamiques\nDhikr et Doua Personnalisés',
                'nav.listen':'Écouter',
                'donate.form_title':'Faire un Don','donate.form_subtitle':'Chaque contribution fait une différence',
                'quran_audio.title':'Lisez et Écoutez ','quran_audio.highlight':'Ensemble','quran_audio.desc':'Suivez le texte arabe en écoutant des récitants de renommée mondiale — verset par verset.',
                'ai_chat.badge':'Démo en Direct • Propulsé par Groq','ai_chat.title_line1':'Posez N\'importe Quelle','ai_chat.title_line2':'Question Islamique',
                'ai_chat.desc':'Obtenez des réponses instantanées sur l\'Islam — du Coran et Hadith à la prière, le fiqh et la vie quotidienne musulmane. IA ultra-rapide, ici sur cette page.',
                'ai_chat.sub_title':'Réponses en Moins d\'une Seconde','ai_chat.sub_desc':'La technologie Groq LPU fournit des réponses en millisecondes',
                'ai_chat.hadith_title':'Coran et Hadith Référencés','ai_chat.hadith_desc':'Les réponses incluent le texte arabe et des citations de sources authentiques',
                'ai_chat.app_title':'Expérience Complète dans l\'App','ai_chat.app_desc':'Historique de chat, accès hors ligne et plus encore dans Zabi',
                'ai_chat.try_asking':'Essayez de demander :',
                'name_gen.title':'Prénom Islamique ','name_gen.highlight':'Générateur','name_gen.desc':'Découvrez de beaux prénoms islamiques significatifs avec écriture arabe, translittération anglaise et origines — propulsé par l\'IA.',
                'name_gen.label':'Générer des prénoms pour un','name_gen.theme':'Thème de Signification','name_gen.optional':'(optionnel)','name_gen.btn':'✨ Générer des Prénoms',
            },
        };

        // ── Prayer names per language ────────────────────────────
        const PRAYER_NAMES = {
            en: { Fajr:'Fajr', Sunrise:'Sunrise', Dhuhr:'Dhuhr', Asr:'Asr', Maghrib:'Maghrib', Isha:'Isha', Next:'Next', detecting:'Detecting location…', sample:'Sample Times' },
            ar: { Fajr:'الفجر', Sunrise:'الشروق', Dhuhr:'الظهر', Asr:'العصر', Maghrib:'المغرب', Isha:'العشاء', Next:'التالي', detecting:'جارٍ تحديد الموقع…', sample:'أوقات نموذجية' },
            bn: { Fajr:'ফজর', Sunrise:'সূর্যোদয়', Dhuhr:'যোহর', Asr:'আসর', Maghrib:'মাগরিব', Isha:'ইশা', Next:'পরবর্তী', detecting:'অবস্থান শনাক্ত হচ্ছে…', sample:'নমুনা সময়' },
            hi: { Fajr:'फ़ज्र', Sunrise:'सूर्योदय', Dhuhr:'ज़ुहर', Asr:'अस्र', Maghrib:'मग़रिब', Isha:'इशा', Next:'अगली', detecting:'स्थान पता किया जा रहा है…', sample:'नमूना समय' },
            es: { Fajr:'Fajr', Sunrise:'Amanecer', Dhuhr:'Dhuhr', Asr:'Asr', Maghrib:'Maghrib', Isha:'Isha', Next:'Próxima', detecting:'Detectando ubicación…', sample:'Horarios de Muestra' },
            fr: { Fajr:'Fajr', Sunrise:'Lever du Soleil', Dhuhr:'Dhuhr', Asr:'Asr', Maghrib:'Maghrib', Isha:'Isha', Next:'Suivante', detecting:'Détection localisation…', sample:'Horaires Exemples' },
        };

        // ── Language meta ────────────────────────────────────────
        const LANG_META = {
            en: { dir:'ltr', font:"'Plus Jakarta Sans', 'Inter', sans-serif", label:'EN', flag:'🇬🇧', name:'English' },
            ar: { dir:'rtl', font:"'Cairo', 'Amiri', sans-serif",             label:'AR', flag:'🇸🇦', name:'العربية' },
            bn: { dir:'ltr', font:"'Noto Sans Bengali', sans-serif",          label:'BN', flag:'🇧🇩', name:'বাংলা' },
            hi: { dir:'ltr', font:"'Noto Sans Devanagari', sans-serif",       label:'HI', flag:'🇮🇳', name:'हिन्दी' },
            es: { dir:'ltr', font:"'Plus Jakarta Sans', 'Inter', sans-serif", label:'ES', flag:'🇪🇸', name:'Español' },
            fr: { dir:'ltr', font:"'Plus Jakarta Sans', 'Inter', sans-serif", label:'FR', flag:'🇫🇷', name:'Français' },
        };

        let currentLang = 'en';
        let cachedPrayers = null;

        // ── Language dropdown ────────────────────────────────────
        function toggleLangDropdown(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('lang-dropdown');
            const chevron = document.getElementById('lang-chevron');
            if (dropdown) {
                const isHidden = dropdown.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-180', !isHidden);
            }
        }
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('lang-dropdown');
            const chevron = document.getElementById('lang-chevron');
            const btn = document.getElementById('lang-menu-btn');
            if (dropdown && !dropdown.classList.contains('hidden')) {
                if (btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        });

        // ── Apply translations ───────────────────────────────────
        function setLanguage(lang) {
            if (!TRANSLATIONS[lang]) return;
            currentLang = lang;
            const meta = LANG_META[lang];
            const t    = TRANSLATIONS[lang];

            // Direction + font
            document.documentElement.setAttribute('lang', lang);
            document.documentElement.setAttribute('dir', meta.dir);
            document.body.style.fontFamily = meta.font;

            // Update desktop label & flag
            const codeEl = document.getElementById('current-lang-code');
            if (codeEl) codeEl.textContent = meta.label;
            const flagEl = document.getElementById('current-lang-flag');
            if (flagEl) flagEl.textContent = meta.flag;
            document.querySelectorAll('#current-lang-label').forEach(el => el.textContent = meta.label);

            // Close dropdown & reset chevron
            const dropdown = document.getElementById('lang-dropdown');
            if (dropdown) dropdown.classList.add('hidden');
            const chevron = document.getElementById('lang-chevron');
            if (chevron) chevron.classList.remove('rotate-180');

            // Highlight active in dropdown menu & show/hide checkmark
            document.querySelectorAll('.lang-option').forEach(btn => {
                const isActive = btn.dataset.lang === lang;
                btn.classList.toggle('active-lang', isActive);
                const check = btn.querySelector('.lang-check');
                if (check) check.classList.toggle('hidden', !isActive);
            });

            // Highlight active in mobile drawer quick switch
            document.querySelectorAll('.mobile-lang-btn').forEach(btn => {
                btn.classList.toggle('active-lang', btn.dataset.lang === lang);
            });

            // Translate all data-i18n elements
            document.querySelectorAll('[data-i18n]').forEach(el => {
                const key = el.dataset.i18n;
                if (lang === 'en') {
                    // Prefer PHP-rendered value stored in data-i18n-en
                    const en = el.dataset.i18nEn;
                    el.textContent = (en !== undefined && en !== '') ? en : (t[key] || el.textContent);
                } else {
                    if (t[key]) el.textContent = t[key];
                }
            });

            // Translate feature card bullet points
            document.querySelectorAll('[data-feature-bullets]').forEach(ul => {
                const idx = parseInt(ul.dataset.featureBullets);
                const key = 'feature.' + idx + '.bullets';
                let bullets;
                if (lang === 'en') {
                    const enStr = ul.dataset.enBullets;
                    if (enStr) {
                        bullets = enStr.split('\n').map(b => b.trim()).filter(Boolean);
                    } else if (t[key]) {
                        bullets = t[key].split('\n').map(b => b.trim()).filter(Boolean);
                    }
                } else if (t[key]) {
                    bullets = t[key].split('\n').map(b => b.trim()).filter(Boolean);
                }
                if (bullets && bullets.length) {
                    const checkColor = ul.className.indexOf('text-white') !== -1 ? '#d4a843' : '#1a5c38';
                    ul.innerHTML = bullets.map(function(b) {
                        return '<li class="flex items-center gap-2"><span style="color: ' + checkColor + ';">✓</span> ' + escapeHtml(b) + '</li>';
                    }).join('');
                }
            });

            // Re-render prayer rows with translated names
            if (cachedPrayers) renderPrayerRows(cachedPrayers);
            else {
                const pn = PRAYER_NAMES[lang] || PRAYER_NAMES.en;
                const cityEl = document.getElementById('prayer-city');
                if (cityEl && cityEl.textContent === (PRAYER_NAMES[currentLang === lang ? 'en' : 'en'].detecting || 'Detecting location…')) {
                    cityEl.textContent = pn.detecting;
                }
            }

            localStorage.setItem('zabi_lang', lang);
        }

        // ── Prayer times ─────────────────────────────────────────
        const FALLBACK_PRAYERS = [
            { name:'Fajr',    time:'05:12 AM', is_next:false },
            { name:'Sunrise', time:'06:34 AM', is_next:false },
            { name:'Dhuhr',   time:'12:30 PM', is_next:false },
            { name:'Asr',     time:'04:30 PM', is_next:true  },
            { name:'Maghrib', time:'06:42 PM', is_next:false },
            { name:'Isha',    time:'08:10 PM', is_next:false },
        ];

        function renderPrayerRows(prayers) {
            const pn = PRAYER_NAMES[currentLang] || PRAYER_NAMES.en;
            const container = document.getElementById('prayer-rows');
            if (!container) return;
            container.innerHTML = prayers.map((p, i) => `
                <div class="flex items-center justify-between py-3 ${i < prayers.length - 1 ? 'border-b border-gray-50' : ''}">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full ${p.is_next ? 'bg-green-500 animate-pulse' : 'bg-gray-200'}"></div>
                        <span class="font-medium text-gray-900 text-sm">${pn[p.name] || p.name}</span>
                        ${p.is_next ? `<span class="text-xs px-2 py-0.5 rounded-full text-white" style="background:var(--green-mid);">${pn.Next || 'Next'}</span>` : ''}
                    </div>
                    <span class="font-semibold text-sm ${p.is_next ? 'text-emerald-700' : 'text-gray-500'}">${p.time}</span>
                </div>
            `).join('');
        }

        function loadPrayerTimes(lat, lng) {
            const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            fetch(`/api/prayer-by-location?lat=${lat}&lng=${lng}&timezone=${encodeURIComponent(timezone)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        document.getElementById('prayer-city').textContent = data.city;
                        cachedPrayers = data.prayers;
                        renderPrayerRows(data.prayers);
                    } else { showFallback(); }
                })
                .catch(() => showFallback());
        }

        function showFallback() {
            const pn = PRAYER_NAMES[currentLang] || PRAYER_NAMES.en;
            document.getElementById('prayer-city').textContent = pn.sample;
            cachedPrayers = FALLBACK_PRAYERS;
            renderPrayerRows(FALLBACK_PRAYERS);
        }

        if (navigator.geolocation) {
            const pn = PRAYER_NAMES[currentLang] || PRAYER_NAMES.en;
            document.getElementById('prayer-city').textContent = pn.detecting;
            renderPrayerRows(FALLBACK_PRAYERS);
            navigator.geolocation.getCurrentPosition(
                pos => loadPrayerTimes(pos.coords.latitude, pos.coords.longitude),
                ()  => showFallback(),
                { timeout: 8000 }
            );
        } else { showFallback(); }

        // ── Init language from localStorage ─────────────────────
        (function () {
            const saved = localStorage.getItem('zabi_lang') || 'en';
            setLanguage(saved);
        })();

        // ── Theme toggle ─────────────────────────────────────────
        function updateThemeIcons(isDark) {
            document.querySelectorAll('.theme-icon-sun, #theme-sun-icon').forEach(el => el.classList.toggle('hidden', !isDark));
            document.querySelectorAll('.theme-icon-moon, #theme-moon-icon').forEach(el => el.classList.toggle('hidden', isDark));
        }

        updateThemeIcons(document.documentElement.classList.contains('dark'));

        // ════════════════════════════════════════════════════════
        // DONATION MODULE
        // ════════════════════════════════════════════════════════
        let selectedGateway = 'buymeacoffee';
        let paypalSdkLoaded = false;

        function selectDonationAmount(amount) {
            const input = document.getElementById('donate-amount');
            if (input) input.value = amount;

            document.querySelectorAll('.donate-preset-btn').forEach(btn => {
                const active = parseInt(btn.dataset.amount) === amount;
                if (active) {
                    btn.className = 'donate-preset-btn py-3 rounded-2xl border-2 border-brand-600 bg-brand-500/10 text-brand-600 dark:text-brand-400 font-extrabold text-sm shadow-sm transition-all text-center';
                } else {
                    btn.className = 'donate-preset-btn py-3 rounded-2xl border border-black/10 dark:border-white/10 bg-black/5 dark:bg-white/5 hover:border-brand-500 text-gray-700 dark:text-gray-300 font-bold text-sm transition-all text-center';
                }
            });
        }

        function onCustomAmountInput() {
            const input = document.getElementById('donate-amount');
            const val = parseFloat(input?.value);
            document.querySelectorAll('.donate-preset-btn').forEach(btn => {
                const active = parseInt(btn.dataset.amount) === val;
                if (active) {
                    btn.className = 'donate-preset-btn py-3 rounded-2xl border-2 border-brand-600 bg-brand-500/10 text-brand-600 dark:text-brand-400 font-extrabold text-sm shadow-sm transition-all text-center';
                } else {
                    btn.className = 'donate-preset-btn py-3 rounded-2xl border border-black/10 dark:border-white/10 bg-black/5 dark:bg-white/5 hover:border-brand-500 text-gray-700 dark:text-gray-300 font-bold text-sm transition-all text-center';
                }
            });
        }

        function selectPaymentGateway(gateway) {
            selectedGateway = gateway;
            document.querySelectorAll('.donate-payment-card').forEach(card => {
                const active = card.dataset.gateway === gateway;
                const check = card.querySelector('.donate-check');
                if (active) {
                    card.className = 'donate-payment-card relative flex items-center gap-3.5 p-4 rounded-2xl border-2 border-brand-500 bg-brand-500/10 dark:bg-brand-500/15 shadow-md shadow-brand-500/10 transition-all cursor-pointer text-left';
                    if (check) check.classList.remove('hidden');
                } else {
                    card.className = 'donate-payment-card relative flex items-center gap-3.5 p-4 rounded-2xl border border-black/10 dark:border-white/10 bg-black/5 dark:bg-white/5 hover:border-brand-400 transition-all cursor-pointer text-left';
                    if (check) check.classList.add('hidden');
                }
            });

            const paypalContainer = document.getElementById('paypal-button-container');
            const submitBtn       = document.getElementById('donate-submit-btn');
            const btnTxt          = document.getElementById('donate-btn-text');

            if (submitBtn) submitBtn.classList.remove('hidden');
            if (paypalContainer) paypalContainer.classList.add('hidden');

            if (btnTxt) {
                btnTxt.textContent = gateway === 'supportkori'
                    ? 'Support via Support Kori ❤️'
                    : 'Support via Buy Me a Coffee ☕';
            }
        }

        function loadDonationCategories() {
            fetch('/api/donation-categories')
                .then(r => r.json())
                .then(res => {
                    const sel = document.getElementById('donate-category');
                    if (!sel) return;
                    sel.innerHTML = '<option value="">Select a cause (Zakat, Sadaqah, etc.)...</option>';
                    (res.data || []).forEach(cat => {
                        const opt = document.createElement('option');
                        opt.value = cat.id;
                        opt.textContent = cat.name;
                        sel.appendChild(opt);
                    });
                })
                .catch(() => {});
        }

        function showDonationFeedback(message, success) {
            const el = document.getElementById('donate-feedback');
            if (!el) return;
            el.textContent = message;
            el.className = success
                ? 'mt-4 p-4 rounded-2xl text-xs sm:text-sm font-semibold text-center transition-all bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                : 'mt-4 p-4 rounded-2xl text-xs sm:text-sm font-semibold text-center transition-all bg-rose-50 dark:bg-rose-950/40 text-rose-800 dark:text-rose-300 border border-rose-200 dark:border-rose-800';
            el.classList.remove('hidden');
            if (success) {
                setTimeout(() => el.classList.add('hidden'), 9000);
            }
        }

        function getDonationFormValues() {
            return {
                amount:   parseFloat(document.getElementById('donate-amount')?.value || '0'),
                category: document.getElementById('donate-category')?.value || '',
                email:    document.getElementById('donate-email')?.value.trim() || '',
                name:     document.getElementById('donate-name')?.value.trim() || null,
            };
        }

        function validateDonationForm() {
            const { amount, category, email } = getDonationFormValues();
            if (!amount || amount < 1) {
                showDonationFeedback('Please enter a valid donation amount (minimum $1).', false);
                document.getElementById('donate-amount')?.focus();
                return false;
            }
            if (!category) {
                showDonationFeedback('Please choose a charitable cause from the list.', false);
                document.getElementById('donate-category')?.focus();
                return false;
            }
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showDonationFeedback('Please enter a valid email address for receipt confirmation.', false);
                document.getElementById('donate-email')?.focus();
                return false;
            }
            if (!selectedGateway) {
                showDonationFeedback('Please select a payment method.', false);
                return false;
            }
            return true;
        }

        function resetDonationForm() {
            const amountInput = document.getElementById('donate-amount');
            const catSelect = document.getElementById('donate-category');
            const emailInput = document.getElementById('donate-email');
            const nameInput = document.getElementById('donate-name');

            if (amountInput) amountInput.value = '25';
            if (catSelect) catSelect.value = '';
            if (emailInput) emailInput.value = '';
            if (nameInput) nameInput.value = '';

            selectDonationAmount(25);
            selectPaymentGateway('stripe');
            document.getElementById('paypal-button-container')?.classList.add('hidden');
            document.getElementById('donate-submit-btn')?.classList.remove('hidden');
        }

        // ── Fallback Direct Store ────────────────────────────────
        function fallbackStoreDonation(formValues, gateway, note) {
            return fetch('/api/donation-store', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    amount: formValues.amount,
                    category_id: parseInt(formValues.category),
                    email: formValues.email,
                    name: formValues.name,
                    payment_gateway: gateway,
                    currency: 'USD'
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.status) {
                    const catSelect = document.getElementById('donate-category');
                    const catName = catSelect.options[catSelect.selectedIndex]?.text || 'Islamic Mission';
                    showDonationFeedback(`JazakAllah Khair! Your donation of $${formValues.amount} for "${catName}" has been recorded successfully. May Allah reward your generosity abundantly! 🤲`, true);
                    resetDonationForm();
                } else {
                    showDonationFeedback(res.message || note || 'Payment processing failed. Please try again.', false);
                }
            })
            .catch(() => {
                showDonationFeedback('Network error. Please check your connection.', false);
            });
        }

        // ── Stripe Checkout ──────────────────────────────────────
        function submitStripe(formValues) {
            return fetch('/api/donation/stripe/checkout', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify({ ...formValues, category_id: parseInt(formValues.category), currency: 'USD' }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.status && res.checkout_url) {
                    window.location.href = res.checkout_url;
                } else {
                    return fallbackStoreDonation(formValues, 'stripe', res.message);
                }
            });
        }

        // ── PayPal ───────────────────────────────────────────────
        function initPaypalButtons() {
            const container = document.getElementById('paypal-button-container');
            if (!container) return;
            container.innerHTML = '<div class="text-xs text-center py-2 text-gray-400">Connecting to PayPal...</div>';

            fetch('/api/donation/paypal/client-id')
                .then(r => r.json())
                .then(res => {
                    if (!res.status || !res.client_id) {
                        container.innerHTML = '<div class="text-xs text-emerald-700 dark:text-emerald-300 text-center py-2.5 px-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800">PayPal gateway ready. Click "Donate Now" below to confirm your contribution.</div>';
                        document.getElementById('donate-submit-btn')?.classList.remove('hidden');
                        return;
                    }
                    if (window.paypal) {
                        renderPaypalButtons(container);
                        return;
                    }
                    const script = document.createElement('script');
                    script.src = `https://www.paypal.com/sdk/js?client-id=${res.client_id}&currency=USD`;
                    script.onload = () => { paypalSdkLoaded = true; renderPaypalButtons(container); };
                    script.onerror = () => {
                        container.innerHTML = '<div class="text-xs text-rose-500 text-center py-2">Could not load PayPal SDK.</div>';
                        document.getElementById('donate-submit-btn')?.classList.remove('hidden');
                    };
                    document.head.appendChild(script);
                })
                .catch(() => {
                    container.innerHTML = '<div class="text-xs text-emerald-700 dark:text-emerald-300 text-center py-2.5 px-3 bg-emerald-50 dark:bg-emerald-950/30 rounded-xl border border-emerald-200 dark:border-emerald-800">PayPal ready. Click "Donate Now" below to complete.</div>';
                    document.getElementById('donate-submit-btn')?.classList.remove('hidden');
                });
        }

        function renderPaypalButtons(container) {
            container.innerHTML = '';
            window.paypal.Buttons({
                createOrder: function(data, actions) {
                    if (!validateDonationForm()) return actions.reject();
                    const { amount, category, email, name } = getDonationFormValues();
                    return fetch('/api/donation/paypal/create-order', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body:    JSON.stringify({ amount, category_id: parseInt(category), email, name, currency: 'USD' }),
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (!res.status) throw new Error(res.message);
                        container.dataset.donationId = res.donation_id;
                        return res.order_id;
                    });
                },
                onApprove: function(data, actions) {
                    const donationId = container.dataset.donationId;
                    return fetch('/api/donation/paypal/capture-order', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body:    JSON.stringify({ order_id: data.orderID, donation_id: donationId }),
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status) {
                            showDonationFeedback('JazakAllah Khair! PayPal payment successful. 🤲', true);
                            resetDonationForm();
                        } else {
                            showDonationFeedback(res.message || 'Payment verification failed.', false);
                        }
                    });
                },
                onError: function(err) {
                    showDonationFeedback('PayPal error. Please try again.', false);
                },
                style: { layout: 'horizontal', color: 'gold', shape: 'rect', label: 'donate', height: 42 },
            }).render(container);
        }

        // ── Razorpay ─────────────────────────────────────────────
        function submitRazorpay(formValues) {
            return fetch('/api/donation/razorpay/create-order', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify({ ...formValues, category_id: parseInt(formValues.category), currency: 'INR' }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.status && res.order_id && res.key_id) {
                    return new Promise((resolve) => {
                        const open = () => {
                            const rzp = new window.Razorpay({
                                key:         res.key_id,
                                amount:      res.amount,
                                currency:    res.currency,
                                name:        'Zabi — Islamic App',
                                description: 'Islamic Mission Donation',
                                order_id:    res.order_id,
                                prefill:     { name: res.name, email: res.email },
                                theme:       { color: '#059669' },
                                handler: function(response) {
                                    fetch('/api/donation/razorpay/verify', {
                                        method:  'POST',
                                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                        body:    JSON.stringify({
                                            razorpay_payment_id: response.razorpay_payment_id,
                                            razorpay_order_id:   response.razorpay_order_id,
                                            razorpay_signature:  response.razorpay_signature,
                                            donation_id:         res.donation_id,
                                        }),
                                    })
                                    .then(r => r.json())
                                    .then(v => {
                                        if (v.status) { showDonationFeedback('JazakAllah Khair! Razorpay payment successful. 🤲', true); resetDonationForm(); }
                                        else          { showDonationFeedback(v.message || 'Payment verification failed.', false); }
                                    })
                                    .catch(() => showDonationFeedback('Verification error. Please contact support.', false));
                                },
                            });
                            rzp.open();
                            resolve();
                        };
                        if (window.Razorpay) { open(); return; }
                        const s = document.createElement('script');
                        s.src = 'https://checkout.razorpay.com/v1/checkout.js';
                        s.onload  = open;
                        s.onerror = () => { showDonationFeedback('Could not load Razorpay SDK.', false); resolve(); };
                        document.head.appendChild(s);
                    });
                } else {
                    return fallbackStoreDonation(formValues, 'razorpay', res.message);
                }
            });
        }

        // ── Paystack ─────────────────────────────────────────────
        function submitPaystack(formValues) {
            return fetch('/api/donation/paystack/initialize', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify({ ...formValues, category_id: parseInt(formValues.category), currency: 'NGN' }),
            })
            .then(r => r.json())
            .then(res => {
                if (res.status && res.public_key) {
                    return new Promise((resolve) => {
                        const open = () => {
                            const handler = window.PaystackPop.setup({
                                key:      res.public_key,
                                email:    res.email,
                                amount:   res.amount,
                                currency: res.currency,
                                ref:      res.reference,
                                callback: function(response) {
                                    fetch('/api/donation/paystack/verify', {
                                        method:  'POST',
                                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                        body:    JSON.stringify({ reference: response.reference, donation_id: res.donation_id }),
                                    })
                                    .then(r => r.json())
                                    .then(v => {
                                        if (v.status) { showDonationFeedback('JazakAllah Khair! Paystack payment successful. 🤲', true); resetDonationForm(); }
                                        else          { showDonationFeedback(v.message || 'Payment verification failed.', false); }
                                    })
                                    .catch(() => showDonationFeedback('Verification error. Please contact support.', false));
                                },
                                onClose: function() {},
                            });
                            handler.openIframe();
                            resolve();
                        };
                        if (window.PaystackPop) { open(); return; }
                        const s = document.createElement('script');
                        s.src = 'https://js.paystack.co/v1/inline.js';
                        s.onload  = open;
                        s.onerror = () => { showDonationFeedback('Could not load Paystack.', false); resolve(); };
                        document.head.appendChild(s);
                    });
                } else {
                    return fallbackStoreDonation(formValues, 'paystack', res.message);
                }
            });
        }

        // ── Main submit handler ──────────────────────────────────
        function submitDonation() {
            if (!validateDonationForm()) return;

            const formValues = getDonationFormValues();
            const btn        = document.getElementById('donate-submit-btn');
            const btnTxt     = document.getElementById('donate-btn-text');
            const loader     = document.getElementById('donate-btn-loader');

            const targetUrl = selectedGateway === 'supportkori'
                ? 'https://www.supportkori.com/arifurfullstack'
                : 'https://buymeacoffee.com/riff1431';

            const gatewayName = selectedGateway === 'supportkori' ? 'Support Kori' : 'Buy Me a Coffee';

            if (btn) btn.disabled = true;
            if (btnTxt) btnTxt.textContent = 'Redirecting…';
            if (loader) loader.classList.remove('hidden');

            const done = () => {
                if (btn) btn.disabled = false;
                if (btnTxt) {
                    btnTxt.textContent = selectedGateway === 'supportkori'
                        ? 'Support via Support Kori ❤️'
                        : 'Support via Buy Me a Coffee ☕';
                }
                if (loader) loader.classList.add('hidden');
            };

            // Record donor intention asynchronously in backend
            try {
                fetch('/api/donation/manual', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        amount: formValues.amount,
                        donation_category_id: formValues.category || null,
                        email: formValues.email || null,
                        name: formValues.name || null,
                        payment_gateway: selectedGateway,
                    })
                }).catch(() => {});
            } catch (_) {}

            showDonationFeedback(`JazakAllah Khair! Opening ${gatewayName} to complete your donation... 🤲`, true);

            setTimeout(() => {
                const win = window.open(targetUrl, '_blank');
                if (!win || win.closed || typeof win.closed === 'undefined') {
                    window.location.href = targetUrl;
                }
                done();
            }, 600);
        }

        // Initialize donation components on load
        selectDonationAmount(25);
        selectPaymentGateway('buymeacoffee');
        loadDonationCategories();

        // ── On-load: check payment return params ─────────────────
        (function checkPaymentReturn() {
            const params   = new URLSearchParams(window.location.search);
            const donation = params.get('donation');

            if (donation === 'success') {
                const sessionId  = params.get('session_id');
                const donationId = params.get('donation_id');
                if (sessionId && donationId) {
                    fetch('/api/donation/stripe/verify', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body:    JSON.stringify({ session_id: sessionId, donation_id: donationId }),
                    }).catch(() => {});
                }
                setTimeout(() => {
                    const el = document.getElementById('donate-feedback');
                    if (el) { el.textContent = 'JazakAllah Khair! Your Stripe payment was successful. 🤲'; el.style.background='#d1fae5'; el.style.color='#065f46'; el.classList.remove('hidden'); }
                    document.getElementById('donate')?.scrollIntoView({ behavior: 'smooth' });
                }, 500);
                history.replaceState({}, '', window.location.pathname);
            } else if (donation === 'ssl_success') {
                setTimeout(() => {
                    const el = document.getElementById('donate-feedback');
                    if (el) { el.textContent = 'JazakAllah Khair! Your SSLCommerz payment was successful. 🤲'; el.style.background='#d1fae5'; el.style.color='#065f46'; el.classList.remove('hidden'); }
                    document.getElementById('donate')?.scrollIntoView({ behavior: 'smooth' });
                }, 500);
                history.replaceState({}, '', window.location.pathname);
            } else if (donation === 'ssl_fail') {
                setTimeout(() => {
                    const el = document.getElementById('donate-feedback');
                    if (el) { el.textContent = 'Payment failed. Please try again.'; el.style.background='#fee2e2'; el.style.color='#991b1b'; el.classList.remove('hidden'); }
                    document.getElementById('donate')?.scrollIntoView({ behavior: 'smooth' });
                }, 500);
                history.replaceState({}, '', window.location.pathname);
            } else if (donation === 'cancelled') {
                setTimeout(() => {
                    const el = document.getElementById('donate-feedback');
                    if (el) { el.textContent = 'Payment was cancelled.'; el.style.background='#fee2e2'; el.style.color='#991b1b'; el.classList.remove('hidden'); }
                    document.getElementById('donate')?.scrollIntoView({ behavior: 'smooth' });
                }, 500);
                history.replaceState({}, '', window.location.pathname);
            }
        })();

        // Load categories on page load
        loadDonationCategories();

        // ════════════════════════════════════════════════════════
        // AI CHAT MODULE
        // ════════════════════════════════════════════════════════
        let aiChatBusy = false;

        function escapeHtml(str) {
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
        }

        function formatAIReply(text) {
            // Bold **text**
            text = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            // Line breaks
            text = text.replace(/\n/g, '<br>');
            return text;
        }

        function appendChatBubble(role, html, isHtml) {
            const container = document.getElementById('ai-chat-messages');
            const div = document.createElement('div');

            if (role === 'user') {
                div.className = 'flex gap-3 justify-end items-start';
                div.innerHTML = `
                    <div class="rounded-2xl rounded-tr-sm px-4 py-3 text-sm max-w-sm sm:max-w-md leading-relaxed bg-brand-600 text-white shadow-sm font-normal">
                        ${isHtml ? html : escapeHtml(html)}
                    </div>
                    <div class="w-8 h-8 rounded-xl bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-gray-200 flex-shrink-0 flex items-center justify-center text-xs font-bold mt-0.5 shadow-sm">👤</div>
                `;
            } else {
                div.className = 'flex gap-3 items-start';
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex-shrink-0 flex items-center justify-center text-xs font-bold mt-0.5 shadow-sm">AI</div>
                    <div class="rounded-2xl rounded-tl-sm px-4 py-3 text-sm max-w-sm sm:max-w-lg leading-relaxed bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 text-gray-800 dark:text-gray-200 shadow-sm">
                        ${isHtml ? html : escapeHtml(html)}
                    </div>
                `;
            }
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
            return div;
        }

        function appendTypingIndicator() {
            const container = document.getElementById('ai-chat-messages');
            const div = document.createElement('div');
            div.id = 'ai-typing';
            div.className = 'flex gap-3 items-start';
            div.innerHTML = `
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex-shrink-0 flex items-center justify-center text-xs font-bold mt-0.5 shadow-sm">AI</div>
                <div class="rounded-2xl rounded-tl-sm px-4 py-3 bg-white dark:bg-white/5 border border-black/5 dark:border-white/10 shadow-sm flex items-center gap-1.5 h-11">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-bounce" style="animation-delay:0ms"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-bounce" style="animation-delay:150ms"></span>
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-bounce" style="animation-delay:300ms"></span>
                </div>
            `;
            container.appendChild(div);
            container.scrollTop = container.scrollHeight;
        }

        function removeTypingIndicator() {
            document.getElementById('ai-typing')?.remove();
        }

        function sendAIChatMessage() {
            if (aiChatBusy) return;
            const input = document.getElementById('ai-chat-input');
            const message = input.value.trim();
            if (!message) return;

            aiChatBusy = true;
            input.value = '';
            input.style.height = 'auto';
            document.getElementById('ai-chat-send').style.opacity = '0.5';

            appendChatBubble('user', message, false);
            appendTypingIndicator();

            fetch('/api/ai/chat', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify({ message }),
            })
            .then(r => r.json())
            .then(data => {
                removeTypingIndicator();
                if (data.success) {
                    appendChatBubble('bot', formatAIReply(escapeHtml(data.reply)), true);
                } else {
                    appendChatBubble('bot', data.error || 'Sorry, an error occurred. Please try again.', false);
                }
            })
            .catch(() => {
                removeTypingIndicator();
                appendChatBubble('bot', 'Network error. Please check your connection and try again.', false);
            })
            .finally(() => {
                aiChatBusy = false;
                document.getElementById('ai-chat-send').style.opacity = '1';
            });
        }

        function handleAIChatKeydown(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendAIChatMessage();
            }
        }

        function sendQuickChatQuestion(q) {
            document.getElementById('ai-chat-input').value = q;
            document.getElementById('ai-chat').scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(sendAIChatMessage, 400);
        }

        // ════════════════════════════════════════════════════════
        // ISLAMIC NAME GENERATOR MODULE
        // ════════════════════════════════════════════════════════
        let ngGender = 'boy';

        function selectNameGender(gender) {
            ngGender = gender;
            const boyBtn  = document.getElementById('ng-btn-boy');
            const girlBtn = document.getElementById('ng-btn-girl');
            if (gender === 'boy') {
                boyBtn.style.borderColor  = '#1a5c38';
                boyBtn.style.background   = '#1a5c38';
                boyBtn.style.color        = 'white';
                girlBtn.style.borderColor = 'var(--lp-border)';
                girlBtn.style.background  = 'var(--lp-surface)';
                girlBtn.style.color       = 'var(--lp-text-soft)';
            } else {
                girlBtn.style.borderColor = '#be185d';
                girlBtn.style.background  = '#be185d';
                girlBtn.style.color       = 'white';
                boyBtn.style.borderColor  = 'var(--lp-border)';
                boyBtn.style.background   = 'var(--lp-surface)';
                boyBtn.style.color        = 'var(--lp-text-soft)';
            }
        }

        function generateIslamicNames() {
            const btn    = document.getElementById('ng-generate-btn');
            const btnTxt = document.getElementById('ng-btn-text');
            const loader = document.getElementById('ng-btn-loader');
            const errEl  = document.getElementById('ng-error');
            const theme  = document.getElementById('ng-theme').value.trim();

            btn.disabled  = true;
            btnTxt.classList.add('hidden');
            loader.classList.remove('hidden');
            errEl.classList.add('hidden');

            fetch('/api/ai/generate-names', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body:    JSON.stringify({ gender: ngGender, theme }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && Array.isArray(data.names) && data.names.length) {
                    renderNameCards(data.names);
                } else {
                    showNgError(data.error || 'Could not generate names. Please try again.');
                }
            })
            .catch(() => showNgError('Network error. Please check your connection.'))
            .finally(() => {
                btn.disabled = false;
                btnTxt.classList.remove('hidden');
                loader.classList.add('hidden');
            });
        }

        function renderNameCards(names) {
            const grid    = document.getElementById('ng-names-grid');
            const wrap    = document.getElementById('ng-results-wrap');
            const label   = document.getElementById('ng-results-label');
            const placeholder = document.getElementById('ng-placeholder');

            const genderLabel = ngGender === 'boy' ? 'Boys' : 'Girls';
            label.textContent = `AI-generated Islamic names for ${genderLabel}`;

            grid.innerHTML = names.map(n => `
                <div class="bg-white rounded-2xl p-5 card-hover" style="border:1px solid var(--lp-border); animation: fadeInUp 0.4s ease both;">
                    <div class="flex items-start justify-between mb-3">
                        <span class="font-arabic text-2xl leading-none" style="color:var(--lp-accent);">${escapeHtml(n.arabic || '')}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full font-medium" style="background:${ngGender==='boy'?'#dbeafe':'#fce7f3'}; color:${ngGender==='boy'?'#1d4ed8':'#be185d'};">${ngGender==='boy'?'Boy':'Girl'}</span>
                    </div>
                    <p class="font-bold text-gray-900 text-base mb-1">${escapeHtml(n.name || '')}</p>
                    <p class="text-gray-500 text-sm leading-relaxed mb-2">${escapeHtml(n.meaning || '')}</p>
                    <p class="text-gray-400 text-xs">Origin: ${escapeHtml(n.origin || 'Arabic')}</p>
                </div>
            `).join('');

            placeholder.style.display = 'none';
            wrap.classList.remove('hidden');
            observer.observe(wrap);

            document.getElementById('name-generator').scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        function showNgError(msg) {
            const el = document.getElementById('ng-error');
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        // ════════════════════════════════════════════════════════
        // QURAN AUDIO PLAYER MODULE
        // ════════════════════════════════════════════════════════

        // All 114 surahs hardcoded — avoids /api/chapters which requires translator_id
        const QA_SURAHS = [
            {id:1,  ar:'الفاتحة',   en:'Al-Fatiha',       v:7},
            {id:2,  ar:'البقرة',    en:'Al-Baqarah',      v:286},
            {id:3,  ar:'آل عمران',  en:"Ali 'Imran",      v:200},
            {id:4,  ar:'النساء',    en:"An-Nisa",         v:176},
            {id:5,  ar:'المائدة',   en:'Al-Maidah',       v:120},
            {id:6,  ar:'الأنعام',   en:"Al-An'am",        v:165},
            {id:7,  ar:'الأعراف',   en:"Al-A'raf",        v:206},
            {id:8,  ar:'الأنفال',   en:'Al-Anfal',        v:75},
            {id:9,  ar:'التوبة',    en:'At-Tawbah',       v:129},
            {id:10, ar:'يونس',      en:'Yunus',           v:109},
            {id:11, ar:'هود',       en:'Hud',             v:123},
            {id:12, ar:'يوسف',      en:'Yusuf',           v:111},
            {id:13, ar:'الرعد',     en:"Ar-Ra'd",         v:43},
            {id:14, ar:'إبراهيم',   en:'Ibrahim',         v:52},
            {id:15, ar:'الحجر',     en:'Al-Hijr',         v:99},
            {id:16, ar:'النحل',     en:'An-Nahl',         v:128},
            {id:17, ar:'الإسراء',   en:"Al-Isra",         v:111},
            {id:18, ar:'الكهف',     en:'Al-Kahf',         v:110},
            {id:19, ar:'مريم',      en:'Maryam',          v:98},
            {id:20, ar:'طه',        en:'Ta-Ha',           v:135},
            {id:21, ar:'الأنبياء',  en:'Al-Anbiya',       v:112},
            {id:22, ar:'الحج',      en:'Al-Hajj',         v:78},
            {id:23, ar:'المؤمنون',  en:"Al-Mu'minun",     v:118},
            {id:24, ar:'النور',     en:'An-Nur',          v:64},
            {id:25, ar:'الفرقان',   en:'Al-Furqan',       v:77},
            {id:26, ar:'الشعراء',   en:"Ash-Shu'ara",     v:227},
            {id:27, ar:'النمل',     en:'An-Naml',         v:93},
            {id:28, ar:'القصص',     en:'Al-Qasas',        v:88},
            {id:29, ar:'العنكبوت',  en:'Al-Ankabut',      v:69},
            {id:30, ar:'الروم',     en:'Ar-Rum',          v:60},
            {id:31, ar:'لقمان',     en:'Luqman',          v:34},
            {id:32, ar:'السجدة',    en:'As-Sajdah',       v:30},
            {id:33, ar:'الأحزاب',   en:'Al-Ahzab',        v:73},
            {id:34, ar:'سبأ',       en:'Saba',            v:54},
            {id:35, ar:'فاطر',      en:'Fatir',           v:45},
            {id:36, ar:'يس',        en:'Ya-Sin',          v:83},
            {id:37, ar:'الصافات',   en:'As-Saffat',       v:182},
            {id:38, ar:'ص',         en:'Sad',             v:88},
            {id:39, ar:'الزمر',     en:'Az-Zumar',        v:75},
            {id:40, ar:'غافر',      en:'Ghafir',          v:85},
            {id:41, ar:'فصلت',      en:'Fussilat',        v:54},
            {id:42, ar:'الشورى',    en:'Ash-Shura',       v:53},
            {id:43, ar:'الزخرف',    en:'Az-Zukhruf',      v:89},
            {id:44, ar:'الدخان',    en:'Ad-Dukhan',       v:59},
            {id:45, ar:'الجاثية',   en:'Al-Jathiyah',     v:37},
            {id:46, ar:'الأحقاف',   en:'Al-Ahqaf',        v:35},
            {id:47, ar:'محمد',      en:'Muhammad',        v:38},
            {id:48, ar:'الفتح',     en:'Al-Fath',         v:29},
            {id:49, ar:'الحجرات',   en:'Al-Hujurat',      v:18},
            {id:50, ar:'ق',         en:'Qaf',             v:45},
            {id:51, ar:'الذاريات',  en:'Adh-Dhariyat',    v:60},
            {id:52, ar:'الطور',     en:'At-Tur',          v:49},
            {id:53, ar:'النجم',     en:'An-Najm',         v:62},
            {id:54, ar:'القمر',     en:'Al-Qamar',        v:55},
            {id:55, ar:'الرحمن',    en:'Ar-Rahman',       v:78},
            {id:56, ar:'الواقعة',   en:"Al-Waqi'ah",      v:96},
            {id:57, ar:'الحديد',    en:'Al-Hadid',        v:29},
            {id:58, ar:'المجادلة',  en:'Al-Mujadila',     v:22},
            {id:59, ar:'الحشر',     en:'Al-Hashr',        v:24},
            {id:60, ar:'الممتحنة',  en:'Al-Mumtahanah',   v:13},
            {id:61, ar:'الصف',      en:'As-Saf',          v:14},
            {id:62, ar:'الجمعة',    en:"Al-Jumu'ah",      v:11},
            {id:63, ar:'المنافقون', en:'Al-Munafiqun',    v:11},
            {id:64, ar:'التغابن',   en:'At-Taghabun',     v:18},
            {id:65, ar:'الطلاق',    en:'At-Talaq',        v:12},
            {id:66, ar:'التحريم',   en:'At-Tahrim',       v:12},
            {id:67, ar:'الملك',     en:'Al-Mulk',         v:30},
            {id:68, ar:'القلم',     en:'Al-Qalam',        v:52},
            {id:69, ar:'الحاقة',    en:'Al-Haqqah',       v:52},
            {id:70, ar:'المعارج',   en:"Al-Ma'arij",      v:44},
            {id:71, ar:'نوح',       en:'Nuh',             v:28},
            {id:72, ar:'الجن',      en:'Al-Jinn',         v:28},
            {id:73, ar:'المزمل',    en:'Al-Muzzammil',    v:20},
            {id:74, ar:'المدثر',    en:'Al-Muddaththir',  v:56},
            {id:75, ar:'القيامة',   en:'Al-Qiyamah',      v:40},
            {id:76, ar:'الإنسان',   en:'Al-Insan',        v:31},
            {id:77, ar:'المرسلات',  en:'Al-Mursalat',     v:50},
            {id:78, ar:'النبأ',     en:"An-Naba",         v:40},
            {id:79, ar:'النازعات',  en:"An-Nazi'at",      v:46},
            {id:80, ar:'عبس',       en:'Abasa',           v:42},
            {id:81, ar:'التكوير',   en:'At-Takwir',       v:29},
            {id:82, ar:'الانفطار',  en:'Al-Infitar',      v:19},
            {id:83, ar:'المطففين',  en:'Al-Mutaffifin',   v:36},
            {id:84, ar:'الانشقاق',  en:'Al-Inshiqaq',     v:25},
            {id:85, ar:'البروج',    en:'Al-Buruj',        v:22},
            {id:86, ar:'الطارق',    en:'At-Tariq',        v:17},
            {id:87, ar:'الأعلى',    en:"Al-A'la",         v:19},
            {id:88, ar:'الغاشية',   en:'Al-Ghashiyah',    v:26},
            {id:89, ar:'الفجر',     en:'Al-Fajr',         v:30},
            {id:90, ar:'البلد',     en:'Al-Balad',        v:20},
            {id:91, ar:'الشمس',     en:'Ash-Shams',       v:15},
            {id:92, ar:'الليل',     en:'Al-Layl',         v:21},
            {id:93, ar:'الضحى',     en:'Ad-Duhaa',        v:11},
            {id:94, ar:'الشرح',     en:'Ash-Sharh',       v:8},
            {id:95, ar:'التين',     en:'At-Tin',          v:8},
            {id:96, ar:'العلق',     en:"Al-'Alaq",        v:19},
            {id:97, ar:'القدر',     en:'Al-Qadr',         v:5},
            {id:98, ar:'البينة',    en:'Al-Bayyinah',     v:8},
            {id:99, ar:'الزلزلة',   en:'Az-Zalzalah',     v:8},
            {id:100,ar:'العاديات',  en:"Al-'Adiyat",      v:11},
            {id:101,ar:'القارعة',   en:"Al-Qari'ah",      v:11},
            {id:102,ar:'التكاثر',   en:'At-Takathur',     v:8},
            {id:103,ar:'العصر',     en:"Al-'Asr",         v:3},
            {id:104,ar:'الهمزة',    en:'Al-Humazah',      v:9},
            {id:105,ar:'الفيل',     en:'Al-Fil',          v:5},
            {id:106,ar:'قريش',      en:'Quraysh',         v:4},
            {id:107,ar:'الماعون',   en:"Al-Ma'un",        v:7},
            {id:108,ar:'الكوثر',    en:'Al-Kawthar',      v:3},
            {id:109,ar:'الكافرون',  en:'Al-Kafirun',      v:6},
            {id:110,ar:'النصر',     en:'An-Nasr',         v:3},
            {id:111,ar:'المسد',     en:'Al-Masad',        v:5},
            {id:112,ar:'الإخلاص',   en:'Al-Ikhlas',       v:4},
            {id:113,ar:'الفلق',     en:'Al-Falaq',        v:5},
            {id:114,ar:'الناس',     en:'An-Nas',          v:6},
        ];

        const qa = {
            reciterSuras: {},
            currentSurah: null,
            translatorId: null,
            showTranslation: true,
            audioLoaded: false,
            autoPlay: false,
        };

        // Expand index container height on desktop
        (function qaResponsiveIndex() {
            function applyHeight() {
                const el = document.getElementById('qa-index-container');
                if (!el) return;
                el.style.maxHeight = window.innerWidth >= 1024 ? '540px' : '260px';
            }
            applyHeight();
            window.addEventListener('resize', applyHeight);
        })();

        // Populate surah dropdown immediately (no API needed)
        (function qaInit() {
            // Surah dropdown
            const sel = document.getElementById('qa-surah-select');
            QA_SURAHS.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.id + '. ' + s.ar + '  –  ' + s.en;
                opt.style.background = '#0a2e1e';
                sel.appendChild(opt);
            });

            // Translators → tabs
            fetch('/api/translators')
                .then(r => r.json())
                .then(data => {
                    const list = data.data || [];
                    const tabWrap = document.getElementById('qa-translator-tabs');
                    tabWrap.innerHTML = '';
                    list.forEach((t, i) => {
                        const btn = document.createElement('button');
                        btn.className = 'qa-translator-tab flex-shrink-0 px-3 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap';
                        btn.dataset.tid = t.id;
                        // Show language + short name, e.g. "EN – Sahih"
                        btn.textContent = (t.language_code ? t.language_code.toUpperCase() : t.language) + ' – ' + (t.short_name || t.full_name);
                        btn.title = t.full_name;
                        btn.onclick = () => qaSetTranslator(t.id);
                        qaStyleTab(btn, false);
                        if (i === 0) {
                            qa.translatorId = t.id;
                            qaStyleTab(btn, true);
                        }
                        tabWrap.appendChild(btn);
                    });
                })
                .catch(() => {
                    document.getElementById('qa-translator-tabs').innerHTML =
                        '<span class="text-white/30 text-xs py-1.5">No translators found</span>';
                });

            // Reciters
            fetch('/api/reciters')
                .then(r => r.json())
                .then(data => {
                    const list = data.data || [];
                    const rSel = document.getElementById('qa-reciter-select');
                    list.forEach(rec => {
                        const opt = document.createElement('option');
                        opt.value = rec.id;
                        opt.textContent = rec.name;
                        opt.style.background = '#0a2e1e';
                        rSel.appendChild(opt);
                    });
                    if (list.length > 0) rSel.value = list[0].id;

                    // Automatically load Surah 1 on initial page load
                    if (!qa.currentSurah) {
                        const sSel = document.getElementById('qa-surah-select');
                        if (sSel) sSel.value = 1;
                        qaLoadSurah(1);
                    }
                })
                .catch(() => {});
        })();

        function qaStyleTab(btn, active) {
            btn.style.cssText = active
                ? 'background:rgba(212,168,67,0.25);color:#d4a843;border:1px solid rgba(212,168,67,0.5);'
                : 'background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.55);border:1px solid rgba(255,255,255,0.12);';
        }

        function qaSetTranslator(id) {
            qa.translatorId = id;
            document.querySelectorAll('.qa-translator-tab').forEach(btn => {
                qaStyleTab(btn, btn.dataset.tid == id);
            });
            if (qa.currentSurah) qaFetchVerses(qa.currentSurah.id);
        }

        function qaLoadSurah(surahId) {
            if (!surahId) return;
            const ch = QA_SURAHS.find(s => s.id == surahId);
            if (!ch) return;

            qa.currentSurah = { id: ch.id, arabicName: ch.ar, englishName: ch.en, versesCount: ch.v };

            document.getElementById('qa-surah-arabic').textContent = ch.ar;
            document.getElementById('qa-surah-english').textContent = ch.en;
            document.getElementById('qa-surah-count').textContent = ch.v + ' verses';
            document.getElementById('qa-surah-place').textContent = '';
            document.getElementById('qa-bismillah').classList.toggle('hidden', ch.id === 9);

            qaFetchVerses(ch.id);
            qaLoadAudio();
        }

        function qaFetchVerses(surahId) {
            if (!qa.translatorId) {
                // translators still loading — retry briefly
                setTimeout(() => { if (qa.translatorId) qaFetchVerses(surahId); }, 600);
                return;
            }

            // Show loading state
            document.getElementById('qa-placeholder').style.display = 'none';
            const loading = document.getElementById('qa-loading');
            loading.classList.remove('hidden');
            loading.style.display = 'flex';
            const versesContainer = document.getElementById('qa-verses');
            versesContainer.querySelectorAll('.qa-verse-card').forEach(el => el.remove());
            document.getElementById('qa-verse-index').innerHTML =
                '<div class="p-6 text-center text-gray-300 text-sm">Loading…</div>';

            fetch('/api/verses/' + surahId + '?translator_id=' + qa.translatorId)
                .then(r => r.json())
                .then(data => {
                    loading.style.display = 'none';

                    if (!data.data?.chapter_info?.length) {
                        const msg = document.createElement('div');
                        msg.className = 'qa-verse-card py-10 text-center text-gray-400 text-sm';
                        msg.textContent = 'No translation data available for this surah.';
                        versesContainer.appendChild(msg);
                        return;
                    }

                    // Flatten pages → verses
                    const verses = data.data.chapter_info.flatMap(p => p.page_verses);

                    // Verse index (left panel)
                    const idx = document.getElementById('qa-verse-index');
                    idx.innerHTML = '';
                    verses.forEach((v, i) => {
                        const div = document.createElement('div');
                        div.className = 'qa-idx-item flex items-center gap-3 px-4 py-2.5 cursor-pointer transition-colors hover:bg-black/5 dark:hover:bg-white/5 border-b border-black/5 dark:border-white/5';
                        div.dataset.verse = i;
                        const preview = v.arabic_name.length > 38 ? v.arabic_name.substring(0, 38) + '…' : v.arabic_name;
                        div.innerHTML = `
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs flex-shrink-0 font-semibold bg-black/5 dark:bg-white/10 text-gray-700 dark:text-gray-300">${v.verses_number}</div>
                            <span class="font-arabic text-right flex-1 text-sm leading-relaxed text-gray-800 dark:text-gray-200" dir="rtl">${preview}</span>
                        `;
                        div.addEventListener('click', () => qaScrollToVerse(i));
                        idx.appendChild(div);
                    });

                    // Verse reading cards (right panel)
                    verses.forEach((v, i) => {
                        const card = document.createElement('div');
                        card.className = 'qa-verse-card rounded-2xl p-4 lg:p-5 transition-all bg-black/5 dark:bg-white/5 border border-black/5 dark:border-white/10';
                        card.dataset.verse = i;
                        card.innerHTML = `
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 bg-gradient-to-tr from-brand-600 to-emerald-500 text-white">${v.verses_number}</div>
                                <div class="h-px flex-1 bg-black/5 dark:bg-white/10"></div>
                            </div>
                            <p class="font-arabic text-xl lg:text-2xl text-gray-900 dark:text-white text-right mb-3"
                                dir="rtl" style="line-height:2.2;">${v.arabic_name}</p>
                            ${v.english_transliteration
                                ? `<p class="text-gray-500 dark:text-gray-400 text-xs italic mb-2 leading-relaxed">${escapeHtml(v.english_transliteration)}</p>`
                                : ''}
                            <p class="qa-translation text-gray-700 dark:text-gray-300 text-sm leading-relaxed${qa.showTranslation ? '' : ' hidden'}">${escapeHtml(v.translated_name || '')}</p>
                        `;
                        versesContainer.appendChild(card);
                    });

                    qaLoadAudio();
                })
                .catch(() => {
                    loading.style.display = 'none';
                    const msg = document.createElement('div');
                    msg.className = 'qa-verse-card py-10 text-center text-gray-400 text-sm';
                    msg.textContent = 'Could not load verses. Please check your connection.';
                    versesContainer.appendChild(msg);
                });
        }

        function qaScrollToVerse(idx) {
            // Scroll right panel (reading area) to the verse card
            const versesContainer = document.getElementById('qa-verses');
            const card = versesContainer.querySelector(`.qa-verse-card[data-verse="${idx}"]`);
            if (card) {
                const offset = card.offsetTop - versesContainer.offsetTop - 16;
                versesContainer.scrollTo({ top: offset, behavior: 'smooth' });
                // Brief highlight
                card.style.background = 'var(--lp-flash-bg)';
                card.style.borderColor = 'var(--lp-accent)';
                setTimeout(() => {
                    card.style.background = 'var(--lp-input-bg)';
                    card.style.borderColor = 'var(--lp-border)';
                }, 1200);
            }

            // Scroll left panel (index) to the matching index item
            const indexContainer = document.getElementById('qa-index-container');
            const idxItem = document.querySelector(`.qa-idx-item[data-verse="${idx}"]`);
            if (idxItem && indexContainer) {
                const header = document.getElementById('qa-surah-header');
                const headerH = header ? header.offsetHeight : 0;
                const offset = idxItem.offsetTop - indexContainer.offsetTop - headerH;
                indexContainer.scrollTo({ top: offset, behavior: 'smooth' });
            }
        }

        const QA_PLAY_SVG = '<svg class="w-5 h-5 fill-current ml-0.5" viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>';
        const QA_PAUSE_SVG = '<svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16" fill="currentColor"></rect><rect x="14" y="4" width="4" height="16" fill="currentColor"></rect></svg>';
        const QA_LOAD_SVG = '<svg class="w-5 h-5 animate-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        function qaSetPlayIcon(state) {
            const el = document.getElementById('qa-play-icon');
            if (!el) return;
            if (state === 'pause') el.innerHTML = QA_PAUSE_SVG;
            else if (state === 'loading') el.innerHTML = QA_LOAD_SVG;
            else el.innerHTML = QA_PLAY_SVG;
        }

        function qaSetReciter(reciterId) {
            qaLoadAudio(reciterId);
        }

        function qaLoadAudio(reciterIdOverride) {
            const reciterSel = document.getElementById('qa-reciter-select');
            const reciterId = reciterIdOverride || (reciterSel ? reciterSel.value : null);
            if (!reciterId || !qa.currentSurah) return;

            if (qa.reciterSuras[reciterId]) {
                qaSetAudio(reciterId);
                return;
            }

            qaSetPlayIcon('loading');
            fetch('/api/reciter-sura/' + reciterId)
                .then(r => r.json())
                .then(data => {
                    qaSetPlayIcon('play');
                    if (data && data.data) {
                        qa.reciterSuras[reciterId] = data.data;
                    }
                    qaSetAudio(reciterId);
                })
                .catch(() => {
                    qaSetPlayIcon('play');
                });
        }

        function qaSetAudio(reciterId) {
            if (!qa.currentSurah) return;
            const suras = qa.reciterSuras[reciterId] || [];
            const match = suras.find(s => parseInt(s.number) === parseInt(qa.currentSurah.id));
            const audio = document.getElementById('qa-audio');
            const playBtn = document.getElementById('qa-play-btn');

            if (!audio) return;

            audio.pause();
            qaSetPlayIcon('play');
            qaResetProgressUI();

            if (match && match.path) {
                audio.src = match.path;
                audio.volume = (document.getElementById('qa-volume')?.value || 80) / 100;
                audio.load();
                qa.audioLoaded = true;
                if (playBtn) playBtn.disabled = false;

                if (match.duration) {
                    const totalEl = document.getElementById('qa-total-time');
                    if (totalEl) totalEl.textContent = qaFmtTime(match.duration / 1000);
                }

                if (qa.autoPlay) {
                    qa.autoPlay = false;
                    qaSetPlayIcon('loading');
                    audio.play().catch(e => {
                        console.warn('Audio auto-play prevented:', e);
                        qaSetPlayIcon('play');
                    });
                }
            } else {
                audio.src = '';
                qa.audioLoaded = false;
                qa.autoPlay = false;
                if (playBtn) playBtn.disabled = true;
                const totalEl = document.getElementById('qa-total-time');
                if (totalEl) totalEl.textContent = '0:00';
            }
        }

        function qaGetCdnFallback(reciterId, surahNumber) {
            const padded = String(surahNumber).padStart(3, '0');
            return parseInt(reciterId) === 2
                ? 'https://cdn.mp3quran.net/audio/mishary-alafasy/r1/' + padded + '.mp3'
                : 'https://cdn.mp3quran.net/audio/abdulrahman-sudais/r1/' + padded + '.mp3';
        }

        function qaTogglePlay() {
            const audio = document.getElementById('qa-audio');
            if (!audio) return;
            if (!qa.audioLoaded || !audio.src) {
                qa.autoPlay = true;
                qaLoadAudio();
                return;
            }
            if (audio.paused) {
                qaSetPlayIcon('loading');
                audio.play()
                    .then(() => {
                        qaSetPlayIcon('pause');
                    })
                    .catch(e => {
                        console.warn('Playback error, switching to CDN fallback...', e);
                        const reciterSel = document.getElementById('qa-reciter-select');
                        const reciterId = reciterSel ? reciterSel.value : 1;
                        const surahNum = qa.currentSurah ? qa.currentSurah.id : 1;
                        const cdnUrl = qaGetCdnFallback(reciterId, surahNum);
                        if (audio.src !== cdnUrl) {
                            audio.src = cdnUrl;
                            audio.load();
                            audio.play()
                                .then(() => qaSetPlayIcon('pause'))
                                .catch(() => qaSetPlayIcon('play'));
                        } else {
                            qaSetPlayIcon('play');
                        }
                    });
            } else {
                audio.pause();
                qaSetPlayIcon('play');
            }
        }

        function qaOnAudioEnded() {
            qaSetPlayIcon('play');
            qaResetProgressUI();
            const currentId = qa.currentSurah?.id;
            if (currentId && currentId < 114) {
                qa.autoPlay = true;
                const nextId = parseInt(currentId) + 1;
                const sSel = document.getElementById('qa-surah-select');
                if (sSel) sSel.value = nextId;
                qaLoadSurah(nextId);
            }
        }

        function qaResetProgressUI() {
            const fill = document.getElementById('qa-progress-fill');
            const curTime = document.getElementById('qa-cur-time');
            if (fill) fill.style.width = '0%';
            if (curTime) curTime.textContent = '0:00';
        }

        function qaSeekAudio(event) {
            const audio = document.getElementById('qa-audio');
            if (!audio || !audio.duration || isNaN(audio.duration)) return;
            const bar = document.getElementById('qa-progress-bar');
            if (!bar) return;
            const rect = bar.getBoundingClientRect();
            const pct = Math.max(0, Math.min(1, (event.clientX - rect.left) / rect.width));
            audio.currentTime = pct * audio.duration;
            const fill = document.getElementById('qa-progress-fill');
            const curTime = document.getElementById('qa-cur-time');
            if (fill) fill.style.width = (pct * 100) + '%';
            if (curTime) curTime.textContent = qaFmtTime(audio.currentTime);
        }

        function qaSkipBackward() {
            const audio = document.getElementById('qa-audio');
            if (!audio) return;
            audio.currentTime = Math.max(0, (audio.currentTime || 0) - 10);
        }

        function qaSkipForward() {
            const audio = document.getElementById('qa-audio');
            if (!audio) return;
            audio.currentTime = Math.min(audio.duration || 0, (audio.currentTime || 0) + 10);
        }

        function qaPrevSurah() {
            const currentId = qa.currentSurah?.id;
            if (!currentId || currentId <= 1) return;
            const prevId = parseInt(currentId) - 1;
            const sSel = document.getElementById('qa-surah-select');
            if (sSel) sSel.value = prevId;
            qaLoadSurah(prevId);
        }

        function qaNextSurah() {
            const currentId = qa.currentSurah?.id;
            if (!currentId || currentId >= 114) return;
            const nextId = parseInt(currentId) + 1;
            const sSel = document.getElementById('qa-surah-select');
            if (sSel) sSel.value = nextId;
            qaLoadSurah(nextId);
        }

        function qaSetVolume(val) {
            const audio = document.getElementById('qa-audio');
            if (audio) audio.volume = val / 100;
        }

        function qaToggleIndex() {
            const container = document.getElementById('qa-index-container');
            const btn = document.getElementById('qa-idx-toggle');
            if (!container) return;
            const isHidden = container.classList.contains('hidden');
            container.classList.toggle('hidden', !isHidden);
            if (btn) {
                btn.style.background = isHidden ? 'rgba(212,168,67,0.2)' : 'rgba(255,255,255,0.1)';
                btn.style.color      = isHidden ? '#d4a843' : 'rgba(255,255,255,0.8)';
                btn.style.borderColor= isHidden ? 'rgba(212,168,67,0.3)' : 'rgba(255,255,255,0.2)';
            }
        }

        function qaToggleTranslation() {
            qa.showTranslation = !qa.showTranslation;
            document.querySelectorAll('.qa-translation').forEach(el => {
                el.classList.toggle('hidden', !qa.showTranslation);
            });
            const btn = document.getElementById('qa-trans-toggle');
            if (btn) {
                btn.style.background = qa.showTranslation ? 'rgba(212,168,67,0.2)' : 'rgba(255,255,255,0.08)';
                btn.style.color = qa.showTranslation ? '#d4a843' : 'rgba(255,255,255,0.5)';
                btn.style.borderColor = qa.showTranslation ? 'rgba(212,168,67,0.3)' : 'rgba(255,255,255,0.15)';
            }
        }

        function qaFmtTime(seconds) {
            if (!seconds || isNaN(seconds) || !isFinite(seconds)) return '0:00';
            const m = Math.floor(seconds / 60);
            const s = Math.floor(seconds % 60);
            return m + ':' + String(s).padStart(2, '0');
        }

        // Attach native audio events
        (function qaBindAudioListeners() {
            const audio = document.getElementById('qa-audio');
            if (!audio) return;

            audio.addEventListener('play', () => qaSetPlayIcon('pause'));
            audio.addEventListener('pause', () => qaSetPlayIcon('play'));
            audio.addEventListener('playing', () => qaSetPlayIcon('pause'));
            audio.addEventListener('waiting', () => qaSetPlayIcon('loading'));
            audio.addEventListener('error', () => {
                const reciterSel = document.getElementById('qa-reciter-select');
                const reciterId = reciterSel ? reciterSel.value : 1;
                const surahNum = qa.currentSurah ? qa.currentSurah.id : 1;
                const cdnUrl = qaGetCdnFallback(reciterId, surahNum);
                if (audio.src !== cdnUrl) {
                    console.warn('Audio failed, falling back to CDN:', cdnUrl);
                    audio.src = cdnUrl;
                    audio.load();
                } else {
                    qaSetPlayIcon('play');
                }
            });
            audio.addEventListener('ended', qaOnAudioEnded);
            audio.addEventListener('timeupdate', () => {
                if (!audio.duration || isNaN(audio.duration) || !isFinite(audio.duration)) return;
                const pct = Math.min(100, (audio.currentTime / audio.duration) * 100);
                const fill = document.getElementById('qa-progress-fill');
                const cur = document.getElementById('qa-cur-time');
                if (fill) fill.style.width = pct + '%';
                if (cur) cur.textContent = qaFmtTime(audio.currentTime);
            });
            audio.addEventListener('loadedmetadata', () => {
                if (audio.duration && isFinite(audio.duration)) {
                    const total = document.getElementById('qa-total-time');
                    if (total) total.textContent = qaFmtTime(audio.duration);
                }
            });
        })();
    
</script>
</body>
</html>
