@extends('tyro-login::layouts.auth')

@section('content')
<div class="auth-container {{ $layout }}" @if($layout==='fullscreen' ) style="background-image: url('{{ $backgroundImage }}');" @endif>
    @if(in_array($layout, ['split-left', 'split-right']))
    <div class="background-panel" style="background-image: url('{{ $backgroundImage }}');">
        <div class="background-panel-content">
            <h1>{{ $pageContent['background_title'] ?? 'Selamat Datang Kembali!' }}</h1>
            <p>{{ $pageContent['background_description'] ?? 'Masuk untuk mengakses akun Anda dan melanjutkan dari terakhir kali. Kami senang melihat Anda lagi.' }}</p>
        </div>
    </div>
    @endif

    <div class="form-panel">
        <div class="form-card">
            <!-- Logo -->
            <div class="logo-container">
                @if($branding['logo'] ?? false)
                <img src="{{ $branding['logo'] }}" alt="{{ $branding['app_name'] ?? config('app.name') }}">
                @else
                <div class="app-logo">
                    <svg viewBox="0 0 50 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M49.626 11.564a.809.809 0 0 1 .028.209v10.972a.8.8 0 0 1-.402.694l-9.209 5.302V39.25c0 .286-.152.55-.4.694L20.42 51.01c-.044.025-.092.041-.14.058-.018.006-.035.017-.054.022a.805.805 0 0 1-.41 0c-.022-.006-.042-.018-.063-.026-.044-.016-.09-.03-.132-.054L.402 39.944A.801.801 0 0 1 0 39.25V6.334c0-.072.01-.142.028-.21.006-.023.02-.044.028-.067.015-.042.029-.085.051-.124.015-.026.037-.047.055-.071.023-.032.044-.065.071-.093.023-.023.053-.04.079-.06.029-.024.055-.05.088-.069h.001l9.61-5.533a.802.802 0 0 1 .8 0l9.61 5.533h.002c.032.02.059.045.088.068.026.02.055.038.078.06.028.029.048.062.072.094.017.024.04.045.054.071.023.04.036.082.052.124.008.023.022.044.028.068a.809.809 0 0 1 .028.209v20.559l8.008-4.611v-10.51c0-.07.01-.141.028-.208.007-.024.02-.045.028-.068.016-.042.03-.085.052-.124.015-.026.037-.047.054-.071.024-.032.044-.065.072-.093.023-.023.052-.04.078-.06.03-.024.056-.05.088-.069h.001l9.611-5.533a.801.801 0 0 1 .8 0l9.61 5.533c.034.02.06.045.09.068.025.02.054.038.077.06.028.029.048.062.072.094.018.024.04.045.054.071.023.039.036.082.052.124.009.023.022.044.028.068zm-1.574 10.718v-9.124l-3.363 1.936-4.646 2.675v9.124l8.01-4.611zm-9.61 16.505v-9.13l-4.57 2.61-13.05 7.448v9.216l17.62-10.144zM1.602 7.719v31.068L19.22 48.93v-9.214l-9.204-5.209-.003-.002-.004-.002c-.031-.018-.057-.044-.086-.066-.025-.02-.054-.036-.076-.058l-.002-.003c-.026-.025-.044-.056-.066-.084-.02-.027-.044-.05-.06-.078l-.001-.003c-.018-.03-.029-.066-.042-.1-.013-.03-.03-.058-.038-.09v-.001c-.01-.038-.012-.078-.016-.117-.004-.03-.012-.06-.012-.09v-.002-21.481L4.965 9.654 1.602 7.72zm8.81-5.994L2.405 6.334l8.005 4.609 8.006-4.61-8.006-4.608zm4.164 28.764l4.645-2.674V7.719l-3.363 1.936-4.646 2.675v20.096l3.364-1.937zM39.243 7.164l-8.006 4.609 8.006 4.609 8.005-4.61-8.005-4.608zm-.801 10.605l-4.646-2.675-3.363-1.936v9.124l4.645 2.674 3.364 1.937v-9.124zM20.02 38.33l11.743-6.704 5.87-3.35-8-4.606-9.211 5.303-8.395 4.833 7.993 4.524z" fill="currentColor" />
                    </svg>
                </div>
                @endif
            </div>

            <!-- Header -->
            <div class="form-header">
                <h2>Masuk ke Akun Anda</h2>
                @if(($loginField ?? 'email') === 'both')
                <p>Masukkan email atau username dan password Anda di bawah ini</p>
                @elseif(($loginField ?? 'email') === 'username')
                <p>Masukkan username dan password Anda di bawah ini</p>
                @else
                <p>Masukkan email dan password Anda di bawah ini</p>
                @endif
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('tyro-login.login.submit') }}">
                @csrf

                <!-- Login Field (Email, Username, or Both) -->
                @if(($loginField ?? 'email') === 'both')
                <div class="form-group">
                    <label for="login" class="form-label">Email atau Username</label>
                    <input type="text" id="login" name="login" class="form-input @error('login') is-invalid @enderror" value="{{ old('login') }}" required autocomplete="username" autofocus placeholder="Email atau username">
                    @error('login')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                @elseif(($loginField ?? 'email') === 'username')
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-input @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Username">
                    @error('username')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                @else
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="email@example.com">
                    @error('email')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="password-toggle-wrapper" style="position: relative;">
                        <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Kata Sandi" style="padding-right: 2.5rem;">
                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('password')" style="position: absolute; right: 0.75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--foreground); opacity: 0.7;">
                            <!-- Eye Icon (Show) -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="eye-icon-show" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 1.25rem; height: 1.25rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye Slash Icon (Hide) - Hidden by default -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="eye-icon-hide" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 1.25rem; height: 1.25rem; display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    @if($features['remember_me'] ?? true)
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember" class="checkbox-input" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="checkbox-label">Ingat Saya</label>
                    </div>
                    @else
                    <div></div>
                    @endif

                    @if($features['forgot_password'] ?? true)
                    <a href="{{ route('password.request') }}" class="form-link">Lupa Kata Sandi?</a>
                    @endif
                </div>

                <!-- Captcha -->
                @if($captchaEnabled ?? false)
                <div class="form-group captcha-group">
                    <label for="captcha_answer" class="form-label">{{ $captchaConfig['label'] ?? 'Security Check' }}</label>
                    <div class="captcha-container">
                        <span class="captcha-question">{{ $captchaQuestion }}</span>
                        <input type="number" id="captcha_answer" name="captcha_answer" class="form-input captcha-input @error('captcha_answer') is-invalid @enderror" required autocomplete="off" placeholder="{{ $captchaConfig['placeholder'] ?? 'Enter the answer' }}">
                    </div>
                    @error('captcha_answer')
                    <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">
                    Masuk
                </button>
            </form>

            <!-- Register Link -->
            @if($registrationEnabled ?? true)
            <div class="form-footer">
                <p>
                    Sudah punya akun?
                    <a href="{{ route('register') }}" class="form-link">Daftar</a>
                </p>
            </div>
            @endif

            <!-- Social Login -->
            @include('tyro-login::partials.social-login', ['action' => 'login'])
        </div>
    </div>
</div>

<style>
    .captcha-group {
        margin-bottom: 1.25rem;
    }

    .captcha-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .captcha-question {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1rem;
        background-color: var(--muted);
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 1rem;
        color: var(--foreground);
        white-space: nowrap;
        min-width: 100px;
        text-align: center;
    }

    .captcha-input {
        flex: 1;
        text-align: center;
        font-weight: 500;
    }

    /* Hide number input spinners */
    .captcha-input::-webkit-outer-spin-button,
    .captcha-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .captcha-input[type=number] {
        -moz-appearance: textfield;
    }
</style>
@endsection

@push('scripts')
<script>
    function togglePasswordVisibility(id) {
        const input = document.getElementById(id);
        const btn = input.nextElementSibling;
        const showIcon = btn.querySelector('.eye-icon-show');
        const hideIcon = btn.querySelector('.eye-icon-hide');

        if (input.type === 'password') {
            input.type = 'text';
            showIcon.style.display = 'none';
            hideIcon.style.display = 'block';
        } else {
            input.type = 'password';
            showIcon.style.display = 'block';
            hideIcon.style.display = 'none';
        }
    }
</script>
@endpush