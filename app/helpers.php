<?php

if (!function_exists('get_setting')) {
    /**
     * Get a setting value from database
     */
    function get_setting($key, $default = null)
    {
        $setting = \App\Models\Settings::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}

if (!function_exists('site_name')) {
    /**
     * Get site name
     */
    function site_name()
    {
        return get_setting('site_name', config('app.name', 'Laravel'));
    }
}

if (!function_exists('site_logo')) {
    /**
     * Get site logo URL
     */
    function site_logo()
    {
        $logo = get_setting('site_logo');
        return $logo ? Storage::url($logo) : null;
    }
}

if (!function_exists('site_favicon')) {
    /**
     * Get site favicon URL
     */
    function site_favicon()
    {
        $favicon = get_setting('site_favicon');
        return $favicon ? Storage::url($favicon) : null;
    }
}

if (!function_exists('site_tagline')) {
    /**
     * Get site tagline
     */
    function site_tagline()
    {
        return get_setting('site_tagline', '');
    }
}
