<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomRegisterController extends Controller
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function showRegistrationForm($branding = null)
    {
        $config = config('tyro-login');
        
        // Use provided branding or fall back to config
        if (!$branding) {
            $branding = $config['branding'] ?? [];
        }

        return view('vendor.tyro-login.register', [
            'layout' => $config['layout'] ?? 'centered',
            'backgroundImage' => $config['background_image'] ?? null,
            'branding' => $branding,
            'pageContent' => $config['pages']['register'] ?? [],
            'requirePasswordConfirmation' => $config['password']['require_confirmation'] ?? true,
            'captchaEnabled' => $config['captcha']['enabled_register'] ?? false,
            'captchaConfig' => $config['captcha'] ?? [],
            'captchaQuestion' => '1 + 1 = ?', // Simple placeholder, ideally implement actual captcha logic
        ]);
    }

    public function register(Request $request)
    {
          $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'whatsapp_number' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Remove + symbol from WhatsApp number if present
        $whatsappNumber = str_replace('+', '', $validated['whatsapp_number']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'whatsapp_number' => $whatsappNumber,
            'password' => Hash::make($validated['password']),
        ]);

        // Assign default role
        $user->assignRole('user');

        // Generate OTP
        // Generate OTP
        $length = config('tyro-login.otp.length', 6);
        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;
        $otp = rand($min, $max);
        $key = 'otp_login_' . $user->id;
        Cache::put($key, $otp, 300); // 5 minutes

        // Send OTP
        $sent = $this->whatsAppService->sendOTP($user->whatsapp_number, $otp);

        if (!$sent) {
            // Optional: Handle error, maybe allow resend or show message
            // For now, proceed but maybe flash a warning or log it
        }

        // Redirect to OTP verify page with user identifier (e.g. encrypt ID)
        return redirect()->route('tyro-login.otp.verify', ['user' => encrypt($user->id)]);
    }
}
