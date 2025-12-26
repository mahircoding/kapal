<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Check if table exists to avoid errors during migration
        if (!Schema::hasTable('settings')) {
            return;
        }

        $settings = Settings::pluck('value', 'key');

        if (isset($settings['mail_host'])) {
            $config = [
                'transport' => 'smtp',
                'host' => $settings['mail_host'],
                'port' => $settings['mail_port'] ?? 587,
                'encryption' => $settings['mail_encryption'] ?? 'tls',
                'username' => $settings['mail_username'],
                'password' => $settings['mail_password'],
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ];

            Config::set('mail.mailers.smtp', $config);
        }

        if (isset($settings['mail_from_address'])) {
            Config::set('mail.from.address', $settings['mail_from_address']);
        }

        if (isset($settings['mail_from_name'])) {
            Config::set('mail.from.name', $settings['mail_from_name']);
        }
    }
}
