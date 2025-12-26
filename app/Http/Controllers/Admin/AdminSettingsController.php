<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::pluck('value', 'key');
        
        $apiKey = $settings['whatsapp_api_key'] ?? '';
        $mailHost = $settings['mail_host'] ?? '';
        $mailPort = $settings['mail_port'] ?? '';
        $mailUsername = $settings['mail_username'] ?? '';
        $mailPassword = $settings['mail_password'] ?? '';
        $mailEncryption = $settings['mail_encryption'] ?? '';
        $mailFromAddress = $settings['mail_from_address'] ?? '';
        $mailFromName = $settings['mail_from_name'] ?? '';
        
        // Appearance settings
        $siteName = $settings['site_name'] ?? config('app.name');
        $siteTagline = $settings['site_tagline'] ?? '';
        $siteLogo = $settings['site_logo'] ?? '';
        $siteFavicon = $settings['site_favicon'] ?? '';

        return view('admin.settings.index', compact(
            'apiKey', 
            'mailHost', 
            'mailPort', 
            'mailUsername', 
            'mailPassword', 
            'mailEncryption', 
            'mailFromAddress', 
            'mailFromName',
            'siteName',
            'siteTagline',
            'siteLogo',
            'siteFavicon'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'whatsapp_api_key' => 'nullable|string',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|numeric',
            'mail_username' => 'nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string',
            'site_name' => 'nullable|string|max:255',
            'site_tagline' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico|max:1024',
        ]);

        $settings = $request->only([
            'whatsapp_api_key',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'site_name',
            'site_tagline',
        ]);
        
        // Handle logo upload
        if ($request->hasFile('site_logo')) {
            // Delete old logo
            $oldLogo = Settings::where('key', 'site_logo')->first();
            if ($oldLogo && $oldLogo->value) {
                \Storage::disk('public')->delete($oldLogo->value);
            }
            
            $settings['site_logo'] = $request->file('site_logo')->store('branding', 'public');
        }
        
        // Handle favicon upload
        if ($request->hasFile('site_favicon')) {
            // Delete old favicon
            $oldFavicon = Settings::where('key', 'site_favicon')->first();
            if ($oldFavicon && $oldFavicon->value) {
                \Storage::disk('public')->delete($oldFavicon->value);
            }
            
            $settings['site_favicon'] = $request->file('site_favicon')->store('branding', 'public');
        }

        foreach ($settings as $key => $value) {
            Settings::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
