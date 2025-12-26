<x-layouts.public>
    @php $title = $campaign->title; @endphp

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                @if($campaign->is_featured)
                    <span
                        class="inline-flex items-center bg-accent-400 text-gray-900 px-4 py-2 rounded-full text-sm font-bold mb-6">
                        ⭐ {{ __('public.campaigns.featured') }}
                    </span>
                @endif
                <h1 class="text-4xl lg:text-6xl font-bold mb-6">{{ $campaign->title }}</h1>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    {{ $campaign->short_description }}
                </p>
            </div>
        </div>
    </section>

    <!-- Campaign Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div
                            class="h-64 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                            <span class="text-9xl">🎯</span>
                        </div>
                        <div class="p-8">
                            <div class="prose prose-lg max-w-none">
                                {!! $campaign->description !!}
                            </div>
                        </div>
                    </div>

                    <!-- Updates Section -->
                    <div class="mt-8 bg-white rounded-2xl shadow-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('public.campaigns.updates') }}</h2>
                        <div class="text-center py-8 text-gray-500">
                            <span class="text-4xl mb-4 block">📝</span>
                            <p>{{ __('public.campaigns.updates_desc') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Donation Card -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <!-- Progress -->
                        <div class="mb-6">
                            <div class="text-3xl font-bold text-primary-600">
                                ₹{{ number_format($campaign->current_amount) }}
                            </div>
                            <div class="text-gray-500">
                                {{ __('public.campaigns.raised_of') }} ₹{{ number_format($campaign->target_amount) }}
                                {{ __('public.campaigns.goal') }}
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-4 mt-4">
                                <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-4 rounded-full transition-all duration-500"
                                    style="width: {{ min($campaign->progress_percentage, 100) }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-500 mt-2">
                                <span>{{ $campaign->progress_percentage }}% {{ __('public.campaigns.funded') }}</span>
                                <span>{{ $campaign->donor_count }} {{ __('public.campaigns.donors') }}</span>
                            </div>
                        </div>

                        <!-- Quick Donate Buttons -->
                        <div class="grid grid-cols-3 gap-2 mb-4">
                            @foreach([500, 1000, 2500] as $amount)
                                <a href="{{ route('donate', ['campaign' => $campaign->slug, 'amount' => $amount]) }}"
                                    class="text-center py-3 bg-gray-100 hover:bg-primary-100 text-gray-700 hover:text-primary-700 font-semibold rounded-xl transition-colors">
                                    ₹{{ number_format($amount) }}
                                </a>
                            @endforeach
                        </div>

                        <a href="{{ route('donate', ['campaign' => $campaign->slug]) }}"
                            class="block w-full text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-4 rounded-xl transition-colors text-lg">
                            {{ __('public.home.donate_now') }}
                        </a>

                        @if($campaign->allow_recurring)
                            <p class="text-center text-sm text-gray-500 mt-3">
                                💝 {{ __('public.donate.monthly_desc') }}
                            </p>
                        @endif

                        <!-- Campaign Info -->
                        <div class="mt-8 pt-6 border-t border-gray-200 space-y-4">
                            <div class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ __('public.common.since') }} {{ $campaign->start_date->format('M d, Y') }}
                            </div>
                            @if($campaign->end_date)
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $campaign->end_date->diffForHumans() }}
                                </div>
                            @endif
                        </div>

                        <!-- Share -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <p class="text-sm font-semibold text-gray-700 mb-3">
                                {{ __('public.campaigns.share_campaign') }}
                            </p>
                            <div class="flex space-x-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                    target="_blank"
                                    class="w-10 h-10 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($campaign->title) }}"
                                    target="_blank"
                                    class="w-10 h-10 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                    </svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . request()->url()) }}"
                                    target="_blank"
                                    class="w-10 h-10 bg-green-100 hover:bg-green-200 text-green-600 rounded-full flex items-center justify-center transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>