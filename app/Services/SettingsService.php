<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a setting value by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $settings = $this->getAll();

        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings, cached.
     *
     * @return array
     */
    public function getAll()
    {
        return Cache::rememberForever('app_settings', function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    /**
     * Clear the settings cache.
     *
     * @return void
     */
    public function clearCache()
    {
        Cache::forget('app_settings');
    }

    /**
     * Set a setting value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  string  $group
     * @return void
     */
    public function set(string $key, $value, string $group = 'general')
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
            ]
        );

        $this->clearCache();
    }
}
