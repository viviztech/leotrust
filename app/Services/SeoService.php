<?php

namespace App\Services;

use Illuminate\Support\Str;

class SeoService
{
    protected $data = [];

    /**
     * Set SEO data for the current page.
     *
     * @param string $title
     * @param string|null $description
     * @param string|null $image
     * @param string $type
     * @return self
     */
    public function set(string $title, ?string $description = null, ?string $image = null, string $type = 'website'): self
    {
        $settings = app(\App\Services\SettingsService::class);
        $siteName = $settings->get('site_name', 'Leo Foundation');
        $defaultDesc = $settings->get('seo_default_description', __('public.home.hero_description'));

        $this->data = [
            'title' => $title . ' | ' . $siteName,
            'description' => $description ?? $defaultDesc,
            'image' => $image ?? asset('images/hero-transformation.png'),
            'type' => $type,
            'url' => url()->current(),
            'site_name' => $siteName,
        ];

        return $this;
    }

    /**
     * Get a value from the SEO data.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Generate HTML meta tags.
     *
     * @return string
     */
    public function generateTags(): string
    {
        if (empty($this->data)) {
            $this->set('Home');
        }

        $html = [
            // Basic Meta
            '<title>' . e($this->data['title']) . '</title>',
            '<meta name="description" content="' . e($this->data['description']) . '">',
            '<link rel="canonical" href="' . e($this->data['url']) . '">',

            // Open Graph / Facebook
            '<meta property="og:type" content="' . e($this->data['type']) . '">',
            '<meta property="og:title" content="' . e($this->data['title']) . '">',
            '<meta property="og:description" content="' . e($this->data['description']) . '">',
            '<meta property="og:image" content="' . e($this->data['image']) . '">',
            '<meta property="og:url" content="' . e($this->data['url']) . '">',
            '<meta property="og:site_name" content="' . e($this->data['site_name']) . '">',

            // Twitter
            '<meta name="twitter:card" content="summary_large_image">',
            '<meta name="twitter:title" content="' . e($this->data['title']) . '">',
            '<meta name="twitter:description" content="' . e($this->data['description']) . '">',
            '<meta name="twitter:image" content="' . e($this->data['image']) . '">',
        ];

        return implode("\n    ", $html);
    }

    /**
     * Generate JSON-LD Structured Data.
     *
     * @return string
     */
    public function generateSchema(): string
    {
        $settings = app(\App\Services\SettingsService::class);
        $siteName = $settings->get('site_name', 'Leo Foundation');

        $socials = [
            $settings->get('social_facebook', 'https://facebook.com/leofoundation'),
            $settings->get('social_twitter', 'https://twitter.com/leofoundation'),
            $settings->get('social_linkedin', 'https://linkedin.com/company/leofoundation'),
            $settings->get('social_instagram'),
        ];

        $socials = array_values(array_filter($socials));

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'NGO',
            'name' => $siteName,
            'url' => url('/'),
            'logo' => asset('images/logo.png'),
            'description' => $settings->get('seo_default_description', __('public.home.hero_description')),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings->get('contact_address', '123 Foundation Street'),
                'addressLocality' => 'Chennai',
                'addressRegion' => 'Tamil Nadu',
                'postalCode' => '600001',
                'addressCountry' => 'IN',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $settings->get('contact_phone', '+91-98765-43210'),
                'contactType' => 'customer service',
                'areaServed' => 'IN',
                'availableLanguage' => ['en', 'ta'],
            ],
            'sameAs' => $socials,
        ];

        // Specific Cause Schema for De-addiction focus
        $causeSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'MedicalWebPage',
            'name' => 'De-addiction & Rehabilitation Services',
            'description' => 'Comprehensive drug and alcohol de-addiction treatment and rehabilitation services.',
            'provider' => [
                '@type' => 'MedicalOrganization',
                'name' => 'Leo Foundation',
            ],
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>' .
            "\n    " . '<script type="application/ld+json">' . json_encode($causeSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . '</script>';
    }
}
