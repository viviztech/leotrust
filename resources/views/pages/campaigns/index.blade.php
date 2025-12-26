<x-layouts.public>
    @php $title = __('public.nav.campaigns'); @endphp

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6">{{ __('public.campaigns.title') }}</h1>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    {{ __('public.campaigns.description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Campaigns Grid -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $campaigns = \App\Models\Campaign::active()->latest()->get();
            @endphp

            @if($campaigns->count() > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($campaigns as $campaign)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                            <div class="h-52 bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center relative">
                                @if($campaign->is_featured)
                                    <div class="absolute top-4 left-4 bg-accent-400 text-gray-900 text-xs font-bold px-3 py-1 rounded-full">
                                        {{ __('public.campaigns.featured') }}
                                    </div>
                                @endif
                                <span class="text-7xl">{{ ['🎓', '🍽️', '🏥', '🏠', '👶', '💊'][$loop->index % 6] }}</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $campaign->title }}</h3>
                                <p class="text-gray-600 mb-4 line-clamp-2">{{ $campaign->short_description ?? Str::limit(strip_tags($campaign->description), 100) }}</p>
                                
                                <!-- Progress bar -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-semibold text-primary-600">₹{{ number_format($campaign->current_amount) }}</span>
                                        <span class="text-gray-500">{{ __('public.common.of') }} ₹{{ number_format($campaign->target_amount) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-primary-500 to-primary-600 h-3 rounded-full transition-all duration-500" style="width: {{ min($campaign->progress_percentage, 100) }}%"></div>
                                    </div>
                                    <div class="flex justify-between text-xs text-gray-500 mt-2">
                                        <span>{{ $campaign->progress_percentage }}% {{ __('public.campaigns.funded') }}</span>
                                        @if($campaign->end_date)
                                            <span>{{ $campaign->end_date->diffForHumans() }}</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('campaigns.show', $campaign->slug) }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition-colors">
                                        {{ __('public.campaigns.learn_more') }}
                                    </a>
                                    <a href="{{ route('donate', ['campaign' => $campaign->slug]) }}" class="flex-1 text-center bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3 rounded-xl transition-colors">
                                        {{ __('public.campaigns.donate') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20">
                    <span class="text-6xl mb-6 block">📢</span>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('public.campaigns.no_campaigns') }}</h3>
                    <p class="text-gray-600 mb-8">{{ __('public.campaigns.check_back') }}</p>
                    <a href="{{ route('donate') }}" class="inline-flex items-center bg-primary-600 hover:bg-primary-700 text-white font-semibold px-8 py-4 rounded-full transition-all">
                        {{ __('public.campaigns.general_donation') }}
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary-600 font-semibold text-sm uppercase tracking-wider">{{ __('public.campaigns.how_it_works') }}</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-4">{{ __('public.campaigns.donation_journey') }}</h2>
            </div>

            <div class="grid md:grid-cols-4 gap-8">
                @php
                    $steps = [
                        ['step' => '01', 'title' => __('public.campaigns.step1_title'), 'desc' => __('public.campaigns.step1_desc')],
                        ['step' => '02', 'title' => __('public.campaigns.step2_title'), 'desc' => __('public.campaigns.step2_desc')],
                        ['step' => '03', 'title' => __('public.campaigns.step3_title'), 'desc' => __('public.campaigns.step3_desc')],
                        ['step' => '04', 'title' => __('public.campaigns.step4_title'), 'desc' => __('public.campaigns.step4_desc')],
                    ];
                @endphp

                @foreach($steps as $step)
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-4">
                            {{ $step['step'] }}
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                        <p class="text-gray-600">{{ $step['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
