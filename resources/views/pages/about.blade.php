<x-layouts.public>
    @php $title = __('public.nav.about'); @endphp

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6">{{ __('public.about.title') }}</h1>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    {{ __('public.about.description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-primary-600 to-primary-800 rounded-3xl transform -rotate-3">
                    </div>
                    <div class="relative bg-gray-100 rounded-3xl p-8 flex items-center justify-center min-h-[400px]">
                        <div class="text-center">
                            <span class="text-8xl">🏛️</span>
                            <p class="mt-4 text-xl font-semibold text-gray-700">{{ __('public.common.since') }} 2010</p>
                        </div>
                    </div>
                </div>

                <div>
                    <span
                        class="text-primary-600 font-semibold text-sm uppercase tracking-wider">{{ __('public.about.our_story') }}</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-4 mb-6">
                        {{ __('public.about.journey') }}
                    </h2>
                    <div class="prose prose-lg text-gray-600">
                        <p>
                            {{ __('public.about.story_p1') }}
                        </p>
                        <p>
                            {{ __('public.about.story_p2') }}
                        </p>
                        <p>
                            {{ __('public.about.story_p3') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="text-primary-600 font-semibold text-sm uppercase tracking-wider">{{ __('public.about.what_drives_us') }}</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-4">{{ __('public.about.core_values') }}</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                @php
                    $values = [
                        ['icon' => '💖', 'title' => __('public.about.compassion'), 'desc' => __('public.about.compassion_desc')],
                        ['icon' => '🤝', 'title' => __('public.about.integrity'), 'desc' => __('public.about.integrity_desc')],
                        ['icon' => '🎯', 'title' => __('public.about.impact'), 'desc' => __('public.about.impact_desc')],
                        ['icon' => '🌱', 'title' => __('public.about.sustainability'), 'desc' => __('public.about.sustainability_desc')],
                    ];
                @endphp

                @foreach($values as $value)
                    <div class="bg-white rounded-2xl p-8 shadow-lg text-center card-hover">
                        <div class="text-5xl mb-4">{{ $value['icon'] }}</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $value['title'] }}</h3>
                        <p class="text-gray-600">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span
                    class="text-primary-600 font-semibold text-sm uppercase tracking-wider">{{ __('public.about.what_we_do') }}</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-4">{{ __('public.about.our_programs') }}</h2>
            </div>

            <div class="space-y-16">
                <!-- Orphan Care -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div
                        class="bg-gradient-to-br from-primary-100 to-primary-200 rounded-3xl p-12 flex items-center justify-center">
                        <span class="text-9xl">👨‍👩‍👧‍👦</span>
                    </div>
                    <div>
                        <div
                            class="inline-flex items-center bg-primary-100 text-primary-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                            {{ __('public.home.orphan_care') }}
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('public.home.orphan_care') }}</h3>
                        <p class="text-gray-600 text-lg mb-6">
                            {{ __('public.home.orphan_care_desc') }}
                        </p>
                    </div>
                </div>

                <!-- De-addiction -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="order-2 lg:order-1">
                        <div
                            class="inline-flex items-center bg-accent-100 text-accent-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                            {{ __('public.home.deaddiction') }}
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('public.home.deaddiction') }}</h3>
                        <p class="text-gray-600 text-lg mb-6">
                            {{ __('public.home.deaddiction_desc') }}
                        </p>
                    </div>
                    <div
                        class="order-1 lg:order-2 bg-gradient-to-br from-accent-100 to-accent-200 rounded-3xl p-12 flex items-center justify-center">
                        <span class="text-9xl">💪</span>
                    </div>
                </div>

                <!-- Welfare -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div
                        class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl p-12 flex items-center justify-center">
                        <span class="text-9xl">🤲</span>
                    </div>
                    <div>
                        <div
                            class="inline-flex items-center bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium mb-4">
                            {{ __('public.home.welfare') }}
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ __('public.home.welfare') }}</h3>
                        <p class="text-gray-600 text-lg mb-6">
                            {{ __('public.home.welfare_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="py-24 hero-gradient">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">
                {{ __('public.about.join_mission') }}
            </h2>
            <p class="text-xl text-white/80 mb-10">
                {{ __('public.about.join_description') }}
            </p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('donate') }}"
                    class="inline-flex items-center bg-accent-400 hover:bg-accent-500 text-gray-900 font-semibold px-10 py-5 rounded-full text-lg transition-all transform hover:scale-105">
                    {{ __('public.nav.donate') }}
                </a>
                <a href="{{ route('contact') }}"
                    class="inline-flex items-center bg-white/10 hover:bg-white/20 text-white font-semibold px-10 py-5 rounded-full text-lg transition-all border border-white/20">
                    {{ __('public.nav.contact') }}
                </a>
            </div>
        </div>
    </section>
</x-layouts.public>