<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Home page.
     */
    public function home()
    {
        app(\App\Services\SeoService::class)->set(
            __('public.nav.home'),
            __('public.home.hero_description')
        );
        return view('pages.home');
    }

    /**
     * About page.
     */
    public function about()
    {
        app(\App\Services\SeoService::class)->set(
            __('public.nav.about'),
            __('public.about.description')
        );
        return view('pages.about');
    }

    /**
     * Campaigns listing.
     */
    public function campaigns()
    {
        app(\App\Services\SeoService::class)->set(
            __('public.nav.campaigns'),
            __('public.campaigns.description')
        );
        return view('pages.campaigns.index');
    }

    /**
     * Campaign detail.
     */
    public function campaignShow(string $slug)
    {
        $campaign = Campaign::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        app(\App\Services\SeoService::class)->set(
            $campaign->title,
            $campaign->short_description,
            $campaign->featured_image ? asset('storage/' . $campaign->featured_image) : null,
            'article'
        );

        return view('pages.campaigns.show', compact('campaign'));
    }

    /**
     * Success stories.
     */
    public function stories()
    {
        app(\App\Services\SeoService::class)->set(
            __('public.nav.stories'),
            __('public.stories.description')
        );
        return view('pages.stories');
    }

    /**
     * Donate page.
     */
    public function donate()
    {
        app(\App\Services\SeoService::class)->set(
            __('public.nav.donate'),
            __('public.donate.description')
        );
        return view('pages.donate');
    }

    /**
     * Contact page.
     */
    public function contact()
    {
        app(\App\Services\SeoService::class)->set(
            __('public.nav.contact'),
            __('public.contact.description')
        );
        return view('pages.contact');
    }

    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Here you would typically:
        // 1. Send an email notification
        // 2. Store the inquiry in database
        // 3. Send to a CRM, etc.

        return redirect()->route('contact')
            ->with('success', 'Thank you for your message! We\'ll get back to you soon.');
    }

    /**
     * Generate XML Sitemap.
     */
    public function sitemap()
    {
        $campaigns = Campaign::active()->get();
        $stories = \App\Models\SuccessStory::published()->get();

        return response()->view('pages.sitemap', compact('campaigns', 'stories'))
            ->header('Content-Type', 'text/xml');
    }
}
