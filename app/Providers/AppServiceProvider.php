<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register event listeners
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\ApplicationStatusChanged::class,
            \App\Listeners\SendApplicationStatusNotification::class,
        );

        // Load SMTP settings from database
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $settings = \App\Models\Settings::pluck('value', 'key');
                
                if ($settings->isNotEmpty()) {
                    $config = [
                        'transport' => 'smtp',
                        'host' => $settings->get('mail_host', config('mail.mailers.smtp.host')),
                        'port' => $settings->get('mail_port', config('mail.mailers.smtp.port')),
                        'encryption' => $settings->get('mail_encryption', config('mail.mailers.smtp.encryption')),
                        'username' => $settings->get('mail_username', config('mail.mailers.smtp.username')),
                        'password' => $settings->get('mail_password', config('mail.mailers.smtp.password')),
                        'timeout' => null,
                    ];
                    
                    config(['mail.mailers.smtp' => $config]);
                    
                    // Update from address if set
                    if ($settings->has('mail_from_address')) {
                        config(['mail.from.address' => $settings->get('mail_from_address')]);
                    }
                    if ($settings->has('mail_from_name')) {
                        config(['mail.from.name' => $settings->get('mail_from_name')]);
                    }
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not ready (e.g., during migration)
            \Illuminate\Support\Facades\Log::warning('Could not load mail settings from database: ' . $e->getMessage());
        }
    }
}
