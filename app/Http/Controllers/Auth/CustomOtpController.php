<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class CustomOtpController extends Controller
{
    public function showVerifyForm(Request $request)
    {
        if (!$request->has('user')) {
             return redirect()->route('login');
        }
        $otpConfig = config('tyro-login.otp');

        return view('vendor.tyro-login.otp-verify', [
            'layout' => config('tyro-login.layout'),
            'backgroundImage' => config('tyro-login.background_image'),
            'branding' => config('tyro-login.branding'),
            'otpConfig' => $otpConfig,
            'title' => $otpConfig['title'] ?? 'Enter Verification Code',
            'subtitle' => $otpConfig['subtitle'] ?? 'We sent a code to your registered number.',
            'otpLength' => $otpConfig['length'] ?? 6,
            'canResend' => true, // Defaulting to true for now
            'resendCount' => 0,
            'maxResend' => $otpConfig['max_resend'] ?? 3,
            'remainingCooldown' => 0,
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
            'user' => 'required|string',
        ]);

        try {
            $userId = decrypt($request->user);
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
            return back()->withErrors(['otp' => 'Invalid user session.']);
        }

        $key = 'otp_login_' . $user->id;
        $cachedOtp = Cache::get($key);

        if (!$cachedOtp || $cachedOtp != $request->otp) {
            return back()->withErrors(['otp' => 'Invalid or expired verification code.']);
        }

        // Clear OTP
        Cache::forget($key);

        // Mark WhatsApp as verified
        $user->markWhatsAppAsVerified();

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            // Login user if email already verified
            Auth::login($user);
            
            // Redirect applicants to application form if they haven't applied yet
            if ($user->hasRole('user') && !$user->jobApplication()->exists()) {
                return redirect()->route('job-application.create');
            }
            
            return redirect()->intended('/dashboard');
        }

        // Email not verified - send verification email
        $user->sendEmailVerificationNotification();

        // Show success message and redirect to verification notice
        return redirect()->route('verification.notice')
            ->with('success', 'WhatsApp terverifikasi! Silakan cek email Anda untuk menyelesaikan verifikasi.');
    }

    public function resend(Request $request)
    {
        $request->validate(['user' => 'required|string']);

        try {
            $userId = decrypt($request->user);
            $user = User::findOrFail($userId);
        } catch (\Exception $e) {
             return redirect()->route('login')->withErrors(['email' => 'Invalid session. Please login again.']);
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        $key = 'otp_login_' . $user->id;
        Cache::put($key, $otp, now()->addMinutes(5));

        // Use WhatsApp service to send OTP
        $waService = new \App\Services\WhatsAppService();
        $waService->sendOTP($user->whatsapp_number, $otp);

        return back()->with('success', 'A new verification code has been sent.');
    }
}
