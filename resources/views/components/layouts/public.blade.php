<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    @inject('seo', 'App\Services\SeoService')
    @inject('settings', 'App\Services\SettingsService')
    <meta charset="utf-8">
    <title>{{ $seo->get('title', 'Leo Foundation - Empowering Lives') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $seo->get('description', __('public.home.hero_description')) }}">

    <!-- Google Analytics -->
    @if($gaId = $settings->get('analytics_ga_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif

    <!-- Google Tag Manager -->
    @if($gtmId = $settings->get('analytics_gtm_id'))
        <script>(function (w, d, s, l, i) {
                w[l] = w[l] || []; w[l].push({
                    'gtm.start':
                        new Date().getTime(), event: 'gtm.js'
                }); var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
                        'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $gtmId }}');</script>
    @endif

    <!-- Custom Head Strings -->
    @if($customHead = $settings->get('analytics_custom_head'))
        {!! $customHead !!}
    @endif

    <!-- Open Graph / Social Media -->
    {!! $seo->generateTags() !!}
    {!! $seo->generateSchema() !!}

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
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
                        },
                        accent: {
                            50: '#fef3c7',
                            100: '#fde68a',
                            200: '#fcd34d',
                            300: '#fbbf24',
                            400: '#f59e0b',
                            500: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        .gradient-text {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #064e3b 0%, #065f46 50%, #047857 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }
    </style>

    @livewireStyles
</head>

<body class="font-sans antialiased text-gray-900 bg-white">
    <!-- Google Tag Manager (noscript) -->
    @if($gtmId = $settings->get('analytics_gtm_id'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}" height="0" width="0"
                style="display:none;visibility:hidden"></iframe></noscript>
    @endif

    <!-- Custom Body Scripts -->
    @if($customBody = $settings->get('analytics_custom_body'))
        {!! $customBody !!}
    @endif

    <!-- Navigation -->
    <nav x-data="{ open: false, scrolled: false, langOpen: false }" @scroll.window="scrolled = window.scrollY > 50"
        :class="scrolled ? 'bg-white shadow-lg' : 'bg-transparent'"
        class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🌟</span>
                        </div>
                        <span :class="scrolled ? 'text-gray-900' : 'text-white'"
                            class="text-xl font-bold transition-colors">
                            Leo Foundation
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-primary-600' : 'text-white/90 hover:text-white'"
                        class="font-medium transition-colors">{{ __('public.nav.home') }}</a>
                    <a href="{{ route('about') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-primary-600' : 'text-white/90 hover:text-white'"
                        class="font-medium transition-colors">{{ __('public.nav.about') }}</a>
                    <a href="{{ route('campaigns.index') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-primary-600' : 'text-white/90 hover:text-white'"
                        class="font-medium transition-colors">{{ __('public.nav.campaigns') }}</a>
                    <a href="{{ route('stories') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-primary-600' : 'text-white/90 hover:text-white'"
                        class="font-medium transition-colors">{{ __('public.nav.stories') }}</a>
                    <a href="{{ route('contact') }}"
                        :class="scrolled ? 'text-gray-700 hover:text-primary-600' : 'text-white/90 hover:text-white'"
                        class="font-medium transition-colors">{{ __('public.nav.contact') }}</a>

                    <!-- Language Switcher -->
                    <div class="relative" @click.away="langOpen = false">
                        <button @click="langOpen = !langOpen"
                            :class="scrolled ? 'text-gray-700 hover:text-primary-600 border-gray-300' : 'text-white/90 hover:text-white border-white/30'"
                            class="flex items-center space-x-1 font-medium transition-colors px-3 py-1.5 rounded-lg border">
                            <span>{{ app()->getLocale() == 'ta' ? 'தமிழ்' : 'EN' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="langOpen" x-cloak x-transition
                            class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-lg py-2 z-50">
                            <a href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ app()->getLocale() == 'en' ? 'bg-primary-50 text-primary-600' : '' }}">
                                🇬🇧 English
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['lang' => 'ta']) }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ app()->getLocale() == 'ta' ? 'bg-primary-50 text-primary-600' : '' }}">
                                🇮🇳 தமிழ்
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('donate') }}"
                        class="bg-accent-400 hover:bg-accent-500 text-gray-900 font-semibold px-6 py-3 rounded-full transition-all transform hover:scale-105 shadow-lg">
                        {{ __('public.nav.donate') }}
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center space-x-2">
                    <!-- Mobile Language Switcher -->
                    <a href="{{ request()->fullUrlWithQuery(['lang' => app()->getLocale() == 'en' ? 'ta' : 'en']) }}"
                        :class="scrolled ? 'text-gray-700 border-gray-300' : 'text-white border-white/30'"
                        class="px-2 py-1 text-sm font-medium border rounded-lg">
                        {{ app()->getLocale() == 'en' ? 'தமிழ்' : 'EN' }}
                    </a>
                    <button @click="open = !open" :class="scrolled ? 'text-gray-900' : 'text-white'" class="p-2">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div x-show="open" x-cloak x-transition class="md:hidden bg-white border-t shadow-lg">
            <div class="px-4 py-6 space-y-4">
                <a href="{{ route('home') }}"
                    class="block text-gray-700 hover:text-primary-600 font-medium">{{ __('public.nav.home') }}</a>
                <a href="{{ route('about') }}"
                    class="block text-gray-700 hover:text-primary-600 font-medium">{{ __('public.nav.about') }}</a>
                <a href="{{ route('campaigns.index') }}"
                    class="block text-gray-700 hover:text-primary-600 font-medium">{{ __('public.nav.campaigns') }}</a>
                <a href="{{ route('stories') }}"
                    class="block text-gray-700 hover:text-primary-600 font-medium">{{ __('public.nav.stories') }}</a>
                <a href="{{ route('contact') }}"
                    class="block text-gray-700 hover:text-primary-600 font-medium">{{ __('public.nav.contact') }}</a>
                <a href="{{ route('donate') }}"
                    class="block bg-accent-400 text-center text-gray-900 font-semibold px-6 py-3 rounded-full">
                    {{ __('public.nav.donate') }}
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    @inject('settings', 'App\Services\SettingsService')
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-primary-600 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🌟</span>
                        </div>
                        <span class="text-xl font-bold">Leo Foundation</span>
                    </div>
                    <p class="text-gray-400 mb-6">
                        {{ $settings->get('site_tagline', __('public.footer.tagline')) }}
                    </p>
                    <div class="flex space-x-4">
                        @if($settings->get('social_facebook'))
                            <a href="{{ $settings->get('social_facebook') }}" target="_blank"
                                class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                        @endif
                        @if($settings->get('social_twitter'))
                            <a href="{{ $settings->get('social_twitter') }}" target="_blank"
                                class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                </svg>
                            </a>
                        @endif
                        @if($settings->get('social_linkedin'))
                            <a href="{{ $settings->get('social_linkedin') }}" target="_blank"
                                class="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-full flex items-center justify-center transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">{{ __('public.footer.quick_links') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.nav.about') }}</a>
                        </li>
                        <li><a href="{{ route('campaigns.index') }}"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.nav.campaigns') }}</a>
                        </li>
                        <li><a href="{{ route('stories') }}"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.nav.stories') }}</a>
                        </li>
                        <li><a href="{{ route('donate') }}"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.nav.donate') }}</a>
                        </li>
                        <li><a href="{{ route('contact') }}"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.nav.contact') }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Programs -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">{{ __('public.footer.our_programs') }}</h3>
                    <ul class="space-y-3">
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.footer.deaddiction') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.footer.orphan_care') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.footer.education') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.footer.food') }}</a>
                        </li>
                        <li><a href="#"
                                class="text-gray-400 hover:text-white transition-colors">{{ __('public.footer.medical') }}</a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-6">{{ __('public.footer.contact_us') }}</h3>
                    <ul class="space-y-4 text-gray-400">
                        <li class="flex items-start space-x-3">
                            <svg class="w-5 h-5 mt-1 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{!! nl2br(e($settings->get('contact_address', '123 Foundation Street, Chennai, Tamil Nadu 600001'))) !!}</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <a href="mailto:{{ $settings->get('contact_email', 'hello@leofoundation.org') }}"
                                class="hover:text-white transition-colors">
                                {{ $settings->get('contact_email', 'hello@leofoundation.org') }}
                            </a>
                        </li>
                        <li class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <a href="tel:{{ $settings->get('contact_phone', '+91 98765 43210') }}"
                                class="hover:text-white transition-colors">
                                {{ $settings->get('contact_phone', '+91 98765 43210') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} Leo Foundation. {{ __('public.footer.rights_reserved') }}
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#"
                        class="text-gray-500 hover:text-white text-sm transition-colors">{{ __('public.footer.privacy') }}</a>
                    <a href="#"
                        class="text-gray-500 hover:text-white text-sm transition-colors">{{ __('public.footer.terms') }}</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>