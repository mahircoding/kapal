<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWhatsAppIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Skip verification for guests
        if (!$user) {
            return $next($request);
        }

        // Skip verification for admin and HRD roles
        if ($user->hasAnyRole(['admin', 'hrd'])) {
            return $next($request);
        }

        // Check if WhatsApp is verified
        if (!$user->hasVerifiedWhatsApp()) {
            // Generate and send OTP
            $otp = rand(100000, 999999);
            $key = 'otp_login_' . $user->id;
            \Illuminate\Support\Facades\Cache::put($key, $otp, now()->addMinutes(5));

            // Send OTP via WhatsApp
            $waService = new \App\Services\WhatsAppService();
            $waService->sendOTP($user->whatsapp_number, $otp);

            // Redirect to OTP verification page
            return redirect()->route('tyro-login.otp.verify', ['user' => encrypt($user->id)])
                ->with('info', 'Please verify your WhatsApp number to continue.');
        }

        return $next($request);
    }
}
