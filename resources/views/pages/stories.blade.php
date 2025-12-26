<x-layouts.public>
    @php 
        $title = __('public.nav.stories');
        $stories = \App\Models\SuccessStory::published()->latest()->get();
    @endphp

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6">{{ __('public.stories.title') }}</h1>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    {{ __('public.stories.description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Stories Grid -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($stories->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($stories as $story)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                            <div class="h-48 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center relative">
                                @if($story->is_featured)
                                    <div class="absolute top-4 left-4 bg-accent-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full">
                                        {{ __('public.campaigns.featured') }}
                                    </div>
                                @endif
                                <span class="text-6xl">{{ ['🌟', '💪', '🎓', '❤️', '🏆', '🌈'][$loop->index % 6] }}</span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="px-3 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                        {{ $story->category_label }}
                                    </span>
                                    <span class="text-gray-400 text-sm">{{ $story->published_at->format('M d, Y') }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $story->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ $story->short_excerpt ?? Str::limit(strip_tags($story->anonymized_content), 150) }}</p>
                                <a href="#" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-semibold">
                                    {{ __('public.stories.read_full') }}
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-2xl shadow-lg">
                    <span class="text-6xl mb-6 block">📖</span>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('public.stories.coming_soon') }}</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto">
                        {{ __('public.stories.coming_soon_desc') }}
                    </p>
                    <a href="{{ route('donate') }}" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-4 rounded-full transition-all">
                        {{ __('public.stories.help_create') }}
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="text-5xl mb-6 block">💝</span>
            <h2 class="text-4xl font-bold text-gray-900 mb-6">
                {{ __('public.stories.be_part') }}
            </h2>
            <p class="text-xl text-gray-600 mb-10">
                {{ __('public.stories.be_part_desc') }}
            </p>
            <a href="{{ route('donate') }}" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-semibold px-10 py-5 rounded-full text-lg transition-all transform hover:scale-105">
                {{ __('public.nav.donate') }}
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </a>
        </div>
    </section>
</x-layouts.public>
