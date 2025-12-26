<x-layouts.public>
    @php
        $title = __('public.nav.home');
        $stats = [
            ['value' => \App\Models\Beneficiary::active()->count(), 'label' => __('public.home.lives_touched'), 'icon' => '❤️'],
            ['value' => \App\Models\Donation::completed()->count(), 'label' => __('public.home.donations_received'), 'icon' => '🎁'],
            ['value' => \App\Models\Campaign::active()->count(), 'label' => __('public.home.active_campaigns'), 'icon' => '📢'],
            ['value' => '₹' . number_format(\App\Models\Donation::completed()->sum('amount')), 'label' => __('public.home.funds_raised'), 'icon' => '💰'],
        ];
        $campaigns = \App\Models\Campaign::active()->featured()->take(3)->get();
    @endphp

    <!-- Hero Section -->
    <section class="hero-gradient min-h-screen flex items-center relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-accent-400/20 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div class="text-white space-y-8 z-20 relative">
                    <div class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2">
                        <span class="w-2 h-2 bg-accent-400 rounded-full animate-pulse"></span>
                        <span class="text-sm font-medium">{{ __('public.home.hero_badge') }}</span>
                    </div>

                    <h1 class="text-5xl lg:text-7xl font-bold leading-tight drop-shadow-lg">
                        {{ __('public.home.hero_title') }}<br>
                        <span class="text-accent-400">{{ __('public.home.hero_title_highlight') }}</span>
                    </h1>

                    <p class="text-xl text-white/90 max-w-lg leading-relaxed drop-shadow-md">
                        {{ __('public.home.hero_description') }}
                    </p>

                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('donate') }}"
                            class="inline-flex items-center bg-accent-400 hover:bg-accent-500 text-gray-900 font-semibold px-8 py-4 rounded-full transition-all transform hover:scale-105 shadow-xl">
                            <span>{{ __('public.home.donate_now') }}</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                        <a href="{{ route('about') }}"
                            class="inline-flex items-center bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold px-8 py-4 rounded-full transition-all border border-white/20 hover:border-white/40">
                            {{ __('public.home.learn_more') }}
                        </a>
                    </div>
                </div>

                <div class="hidden lg:block relative z-10 translate-x-12">
                    <div class="relative w-full h-[650px] -mr-8">
                        <!-- Main image -->
                        <div
                            class="absolute inset-0 rounded-l-3xl overflow-hidden border-l border-t border-b border-white/10 shadow-2xl">
                            <img src="{{ asset('images/hero-transformation.png') }}"
                                alt="Transformation from addiction to new life"
                                class="w-full h-full object-cover transform scale-105 hover:scale-100 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-primary-900/40 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-900/20 to-transparent"></div>
                        </div>

                        <!-- Floating cards tailored for rehab -->
                        <div
                            class="absolute -bottom-8 -left-12 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-5 transform hover:scale-105 transition-all border border-white/50">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center">
                                    <span class="text-3xl">🧘‍♂️</span>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">100%</p>
                                    <p class="text-sm font-medium text-gray-600">Holistic Care</p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="absolute -top-12 -right-8 bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-5 transform hover:scale-105 transition-all border border-white/50">
                            <div class="flex items-center space-x-4">
                                <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-3xl">🤝</span>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-gray-900">24/7</p>
                                    <p class="text-sm font-medium text-gray-600">Support System</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        </div>
    </section>

    <!-- Path to Recovery Process Section -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-4">{{ __('public.home.process_title') }}</h2>
                <p class="text-xl text-gray-600">{{ __('public.home.process_subtitle') }}</p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 relative">
                <!-- Connecting Line (Desktop) -->
                <div class="hidden md:block absolute top-12 left-0 w-full h-0.5 bg-gray-200 -z-10"></div>

                @foreach(range(1, 4) as $step)
                    <div class="relative bg-white pt-8">
                        <div
                            class="w-24 h-24 mx-auto bg-primary-50 rounded-full border-4 border-white shadow-lg flex items-center justify-center mb-6 relative z-10">
                            <span class="text-4xl">{{ ['📋', '🏥', '🧠', '🌱'][$step - 1] }}</span>
                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-accent-400 rounded-full flex items-center justify-center text-sm font-bold text-gray-900">
                                {{ $step }}
                            </div>
                        </div>
                        <div class="text-center px-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-3">
                                {{ __('public.home.step_' . $step . '_title') }}
                            </h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ __('public.home.step_' . $step . '_desc') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-primary-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @foreach ($stats as $stat)
                    <div
                        class="text-center p-6 bg-white/5 rounded-2xl backdrop-blur-sm border border-white/10 hover:bg-white/10 transition-colors">
                        <span class="text-4xl mb-4 block">{{ $stat['icon'] }}</span>
                        <div class="text-3xl lg:text-4xl font-bold mb-2">{{ $stat['value'] }}</div>
                        <div class="text-primary-200 font-medium">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Programs Header -->
    <div class="pt-24 pb-12 bg-gray-50 text-center">
        <h2 class="text-4xl font-bold text-gray-900">{{ __('public.home.programs_title') }}</h2>
    </div>

    <!-- De-addiction Focus Section -->
    <section class="pb-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden flex flex-col lg:flex-row">
                <div class="lg:w-1/2 bg-primary-600 p-12 lg:p-16 text-white flex flex-col justify-center">
                    <div class="inline-block px-4 py-2 bg-white/20 rounded-full text-sm font-semibold mb-6 w-fit">
                        {{ __('public.home.deaddiction') }}
                    </div>
                    <h3 class="text-3xl lg:text-4xl font-bold mb-6">A Second Chance at Life</h3>
                    <p class="text-lg text-primary-100 mb-8 leading-relaxed">
                        {{ __('public.home.deaddiction_desc') }}
                        {{ __('public.home.hero_description') }}
                    </p>
                    <a href="{{ route('contact') }}"
                        class="inline-flex items-center text-white font-semibold hover:text-accent-300 transition-colors">
                        <span>Get Help Now</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
                <div class="lg:w-1/2 bg-gray-200 min-h-[300px] relative">
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-9xl">🕊️</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Holistic Care (Orphan & Welfare) -->
    <section class="pb-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <span
                    class="text-gray-500 uppercase tracking-wider font-semibold text-sm">{{ __('public.home.holistic_care') }}</span>
                <p class="mt-2 text-xl text-gray-600">{{ __('public.home.holistic_care_desc') }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <!-- Orphan Care -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center text-2xl mb-6">
                        👶
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('public.home.orphan_care') }}</h3>
                    <p class="text-gray-600 mb-6">
                        {{ __('public.home.orphan_care_desc') }}
                    </p>
                    <a href="{{ route('about') }}"
                        class="text-primary-600 font-semibold hover:underline">{{ __('public.home.learn_more') }}
                        &rarr;</a>
                </div>

                <!-- Welfare -->
                <div
                    class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="w-14 h-14 bg-red-100 rounded-xl flex items-center justify-center text-2xl mb-6">
                        🤝
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('public.home.welfare') }}</h3>
                    <p class="text-gray-600 mb-6">
                        {{ __('public.home.welfare_desc') }}
                    </p>
                    <a href="{{ route('about') }}"
                        class="text-primary-600 font-semibold hover:underline">{{ __('public.home.learn_more') }}
                        &rarr;</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Active Campaigns -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ __('public.home.active_campaigns_title') }}
                    </h2>
                    <p class="text-gray-600 max-w-xl">{{ __('public.home.campaigns_description') }}</p>
                </div>
                <a href="{{ route('campaigns.index') }}"
                    class="hidden md:flex items-center text-primary-600 font-semibold hover:text-primary-700">
                    {{ __('public.home.view_all_campaigns') }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            @if($campaigns->count() > 0)
                <div class="grid md:grid-cols-3 gap-8">
                    @foreach($campaigns as $campaign)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-lg overflow-hidden card-hover group">
                            <div class="h-48 bg-gray-200 relative overflow-hidden">
                                <div
                                    class="absolute inset-0 bg-gradient-to-br from-primary-400 to-primary-600 opacity-90 transition-opacity group-hover:opacity-100">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span
                                        class="text-6xl transform group-hover:scale-110 transition-transform duration-300">{{ ['🎓', '🍽️', '🏥'][$loop->index % 3] }}</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $campaign->title }}</h3>
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span
                                            class="font-semibold text-primary-600">₹{{ number_format((float) $campaign->current_amount) }}</span>
                                        <span class="text-gray-500">{{ __('public.common.of') }}
                                            ₹{{ number_format((float) $campaign->target_amount) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="bg-primary-500 h-2 rounded-full"
                                            style="width: {{ min($campaign->progress_percentage, 100) }}%"></div>
                                    </div>
                                </div>
                                <a href="{{ route('campaigns.show', $campaign->slug) }}"
                                    class="block w-full text-center bg-gray-50 hover:bg-primary-50 text-primary-700 font-semibold py-3 rounded-xl transition-colors">
                                    {{ __('public.campaigns.donate') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-2xl">
                    <p class="text-gray-500">{{ __('public.campaigns.no_campaigns') }}</p>
                </div>
            @endif

            <div class="mt-8 text-center md:hidden">
                <a href="{{ route('campaigns.index') }}"
                    class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700">
                    {{ __('public.home.view_all_campaigns') }} &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-primary-900 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10">
            </div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl lg:text-5xl font-bold text-white mb-6">
                {{ __('public.home.ready_to_help') }}
            </h2>
            <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto">
                {{ __('public.home.cta_description') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('donate') }}"
                    class="inline-flex items-center justify-center bg-accent-400 hover:bg-accent-500 text-gray-900 text-lg font-bold px-10 py-5 rounded-full transition-all transform hover:scale-105 shadow-xl">
                    {{ __('public.home.donate_now') }}
                </a>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center justify-center bg-transparent border-2 border-white/20 text-white hover:bg-white/10 text-lg font-semibold px-10 py-5 rounded-full transition-all">
                    {{ __('public.home.get_in_touch') }}
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>