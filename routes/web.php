<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect('/dashboard');
})->middleware('auth')->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // Admin Settings
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [App\Http\Controllers\Admin\AdminSettingsController::class, 'update'])->name('settings.update');
    });

    // Admin & HRD Application Management
    Route::middleware(['role:admin|hrd'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('applications', App\Http\Controllers\Admin\AdminApplicationController::class)->only(['index', 'show', 'update']);
        Route::get('applications/{application}/download-pdf', [App\Http\Controllers\Admin\AdminApplicationController::class, 'downloadPdf'])->name('applications.download-pdf');
    });

    // Job Application Routes
    Route::middleware(['role:user'])->prefix('job-application')->name('job-application.')->group(function () {
        Route::get('create', [App\Http\Controllers\JobApplicationController::class, 'create'])->name('create');
        Route::post('review', [App\Http\Controllers\JobApplicationController::class, 'review'])->name('review');
        Route::post('store', [App\Http\Controllers\JobApplicationController::class, 'store'])->name('store');
        Route::get('my-application', [App\Http\Controllers\JobApplicationController::class, 'show'])->name('show');
        Route::get('edit', [App\Http\Controllers\JobApplicationController::class, 'edit'])->name('edit');
        Route::put('update', [App\Http\Controllers\JobApplicationController::class, 'update'])->name('update');
        Route::get('download-pdf', [App\Http\Controllers\JobApplicationController::class, 'downloadPdf'])->name('download-pdf');
    });
});

// Override Tyro Login Routes
Route::get('login', function () {
    $branding = config('tyro-login.branding');
    $branding['app_name'] = site_name();
    if (site_logo()) {
        $branding['logo'] = site_logo();
    }
    
    return view('vendor.tyro-login.login', [
        'layout' => 'split-left',
        'backgroundImage' => config('tyro-login.background_image'),
        'branding' => $branding,
        'pageContent' => config('tyro-login.pages.login'),
        'loginField' => config('tyro-login.login_field'),
        'features' => config('tyro-login.features'),
        'captchaEnabled' => config('tyro-login.captcha.enabled_login', false),
        'captchaConfig' => config('tyro-login.captcha'),
        'captchaQuestion' => config('tyro-login.captcha.question'),
        'registrationEnabled' => config('tyro-login.registration.enabled', true),
    ]);
})->name('login');

// Register route with dynamic branding
Route::get('register', function () {
    $branding = config('tyro-login.branding');
    $branding['app_name'] = site_name();
    if (site_logo()) {
        $branding['logo'] = site_logo();
    }
    
    return app(\App\Http\Controllers\Auth\CustomRegisterController::class)->showRegistrationForm($branding);
})->name('register');

// Alias for Tyro Login package compatibility
Route::get('tyro-login', function () {
    return redirect()->route('login');
})->name('tyro-login.login');

Route::post('tyro-login/login', [Laravel\Fortify\Http\Controllers\AuthenticatedSessionController::class, 'store'])
    ->middleware(array_filter([
        'guest:'.config('fortify.guard'),
    ]))
    ->name('tyro-login.login.submit');

Route::post('register', [App\Http\Controllers\Auth\CustomRegisterController::class, 'register'])->name('tyro-login.register.submit');

Route::get('otp/verify', [App\Http\Controllers\Auth\CustomOtpController::class, 'showVerifyForm'])->name('tyro-login.otp.verify');
Route::post('otp/verify', [App\Http\Controllers\Auth\CustomOtpController::class, 'verify'])->name('tyro-login.otp.submit');
Route::post('otp/resend', [App\Http\Controllers\Auth\CustomOtpController::class, 'resend'])->name('tyro-login.otp.resend');

