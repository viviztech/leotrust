<x-layouts.public>
    @php 
        $title = __('public.nav.donate');
        $campaigns = \App\Models\Campaign::active()->get();
        $selectedCampaign = request('campaign') ? \App\Models\Campaign::where('slug', request('campaign'))->first() : null;
        $presetAmount = request('amount');
    @endphp

    <!-- Hero Section -->
    <section class="hero-gradient pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-5xl lg:text-6xl font-bold mb-6">{{ __('public.donate.title') }}</h1>
                <p class="text-xl text-white/80 max-w-3xl mx-auto">
                    {{ __('public.donate.description') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Donation Form -->
    <section class="py-24 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="p-8 lg:p-12">
                    <form action="#" method="POST" x-data="{ 
                        amount: '{{ $presetAmount ?? 1000 }}',
                        customAmount: false,
                        recurring: false,
                        anonymous: false
                    }">
                        @csrf
                        
                        <!-- Campaign Selection -->
                        @if($campaigns->count() > 0)
                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">{{ __('public.donate.select_campaign') }}</label>
                                <select name="campaign_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">{{ __('public.donate.general_donation') }}</option>
                                    @foreach($campaigns as $campaign)
                                        <option value="{{ $campaign->id }}" {{ $selectedCampaign && $selectedCampaign->id == $campaign->id ? 'selected' : '' }}>
                                            {{ $campaign->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- Amount Selection -->
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">{{ __('public.donate.amount') }} (₹)</label>
                            <div class="grid grid-cols-4 gap-3 mb-4">
                                @foreach([500, 1000, 2500, 5000] as $amt)
                                    <button type="button" 
                                            @click="amount = '{{ $amt }}'; customAmount = false"
                                            :class="amount == '{{ $amt }}' && !customAmount ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-700 border-gray-300 hover:border-primary-500'"
                                            class="py-4 text-lg font-semibold border-2 rounded-xl transition-all">
                                        ₹{{ number_format($amt) }}
                                    </button>
                                @endforeach
                            </div>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg">₹</span>
                                <input type="number" 
                                       name="amount" 
                                       x-model="amount"
                                       @focus="customAmount = true"
                                       min="100"
                                       placeholder="{{ __('public.donate.custom_amount') }}"
                                       class="w-full pl-10 pr-4 py-4 text-lg border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>

                        <!-- Donation Type -->
                        <div class="mb-8">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" x-model="recurring" name="recurring" class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-gray-700">{{ __('public.donate.monthly_donation') }}</span>
                            </label>
                            <p class="text-sm text-gray-500 mt-2 ml-8">{{ __('public.donate.monthly_desc') }}</p>
                        </div>

                        <!-- Donor Information -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('public.donate.your_info') }}</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.donate.full_name') }} *</label>
                                    <input type="text" name="donor_name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.donate.email') }} *</label>
                                    <input type="email" name="donor_email" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.donate.phone') }}</label>
                                    <input type="tel" name="donor_phone" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.donate.pan') }}</label>
                                    <input type="text" name="pan" maxlength="10" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 uppercase">
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-8">
                            <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('public.donate.message') }}</label>
                            <textarea name="message" rows="3" placeholder="{{ __('public.donate.message_placeholder') }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                        </div>

                        <!-- Anonymous -->
                        <div class="mb-8">
                            <label class="flex items-center space-x-3 cursor-pointer">
                                <input type="checkbox" x-model="anonymous" name="anonymous" class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-gray-700">{{ __('public.donate.anonymous') }}</span>
                            </label>
                        </div>

                        <!-- Payment Methods -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('public.donate.payment_method') }}</h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <button type="button" class="flex items-center justify-center space-x-3 p-4 border-2 border-primary-600 bg-primary-50 rounded-xl">
                                    <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none">
                                        <rect width="40" height="40" rx="8" fill="#635BFF"/>
                                        <path d="M19.2 16.48c0-.88.72-1.2 1.92-1.2 1.72 0 3.88.52 5.6 1.44V12.2c-1.88-.74-3.72-1.04-5.6-1.04-4.6 0-7.64 2.4-7.64 6.4 0 6.24 8.6 5.24 8.6 7.92 0 1.04-.92 1.36-2.2 1.36-1.92 0-4.36-.8-6.28-1.84v4.6c2.12.92 4.28 1.32 6.28 1.32 4.72 0 7.96-2.32 7.96-6.36 0-6.76-8.64-5.56-8.64-8.08z" fill="white"/>
                                    </svg>
                                    <span class="font-semibold text-primary-700">Stripe</span>
                                </button>
                                <button type="button" class="flex items-center justify-center space-x-3 p-4 border-2 border-gray-300 hover:border-primary-500 rounded-xl transition-colors">
                                    <svg class="w-8 h-8" viewBox="0 0 40 40" fill="none">
                                        <rect width="40" height="40" rx="8" fill="#072654"/>
                                        <path d="M20 12L11 28h6l1-2h4l1 2h6L20 12zm0 8l1.5 3h-3L20 20z" fill="#3395FF"/>
                                    </svg>
                                    <span class="font-semibold text-gray-700">Razorpay</span>
                                </button>
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white text-xl font-semibold py-5 rounded-xl transition-all transform hover:scale-[1.02] flex items-center justify-center space-x-3">
                            <span>{{ __('public.donate.donate_button') }} ₹<span x-text="Number(amount).toLocaleString('en-IN')"></span></span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>

                        <p class="text-center text-sm text-gray-500 mt-4">
                            🔒 {{ __('public.donate.secure_payment') }}
                        </p>
                    </form>
                </div>
            </div>

            <!-- Trust Badges -->
            <div class="mt-12 grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach([
                    ['icon' => '🔒', 'text' => __('public.donate.secure')],
                    ['icon' => '📜', 'text' => __('public.donate.tax_benefit')],
                    ['icon' => '📧', 'text' => __('public.donate.instant_receipt')],
                    ['icon' => '💯', 'text' => __('public.donate.transparent')],
                ] as $badge)
                    <div class="bg-white rounded-xl p-4 text-center shadow">
                        <span class="text-2xl">{{ $badge['icon'] }}</span>
                        <p class="text-sm font-medium text-gray-700 mt-1">{{ $badge['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
