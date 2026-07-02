<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - ArabicQuest | Lanjutkan Petualanganmu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('css/login.css') }}">
</head>
<body>

<div class="login-page">

    {{-- ============================================
         LEFT SIDE: HERO ILLUSTRATION
    ============================================ --}}
    <section class="login-hero" aria-label="Adventure Preview">

        {{-- Islamic Geometric Pattern Background --}}
        <div class="login-hero__pattern" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="islamicPattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <path d="M40 0 L80 40 L40 80 L0 40 Z" fill="none" stroke="rgba(251, 191, 36, 0.15)" stroke-width="1"/>
                        <circle cx="40" cy="40" r="15" fill="none" stroke="rgba(119, 85, 55, 0.1)" stroke-width="1"/>
                        <path d="M40 25 L55 40 L40 55 L25 40 Z" fill="none" stroke="rgba(251, 191, 36, 0.12)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#islamicPattern)"/>
            </svg>
        </div>

        {{-- Floating Particles --}}
        <div class="login-hero__particles" aria-hidden="true">
            @for($i = 0; $i < 20; $i++)
                <span class="login-hero__particle" style="--i: {{ $i }};"></span>
            @endfor
        </div>

        {{-- Glow Orbs --}}
        <div class="login-hero__orb login-hero__orb--1" aria-hidden="true"></div>
        <div class="login-hero__orb login-hero__orb--2" aria-hidden="true"></div>

        {{-- Hero Content --}}
        <div class="login-hero__content">

            {{-- Character Illustration --}}
            <div class="login-hero__character-wrapper">
                <div class="login-hero__character-glow" aria-hidden="true"></div>
                <div class="login-hero__character">
                    <span class="login-hero__character-emoji">💼</span>
                </div>
                <div class="login-hero__character-sparkles" aria-hidden="true">
                    <span class="sparkle sparkle--1">✨</span>
                    <span class="sparkle sparkle--2">⭐</span>
                    <span class="sparkle sparkle--3">✨</span>
                </div>
            </div>

            {{-- Level Progress Card --}}
            <div class="login-hero__level-card">
                <div class="login-hero__level-header">
                    <div class="login-hero__level-info">
                        <span class="login-hero__level-label">Level Saat Ini</span>
                        <span class="login-hero__level-value">12</span>
                    </div>
                    <div class="login-hero__level-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>

                <div class="login-hero__xp-info">
                    <span>XP</span>
                    <span class="login-hero__xp-value">2.450 / 3.000</span>
                </div>

                <div class="login-hero__progress-bar">
                    <div class="login-hero__progress-fill" style="--progress-width: 82%;"></div>
                    <div class="login-hero__progress-shine"></div>
                </div>

                <div class="login-hero__badges">
                    <div class="login-hero__badge">
                        <span class="login-hero__badge-icon">🏅</span>
                        <span class="login-hero__badge-text">8 Badge</span>
                    </div>
                    <div class="login-hero__badge">
                        <span class="login-hero__badge-icon">🔥</span>
                        <span class="login-hero__badge-text">12 Hari</span>
                    </div>
                    <div class="login-hero__badge">
                        <span class="login-hero__badge-icon">⭐</span>
                        <span class="login-hero__badge-text">2450 XP</span>
                    </div>
                </div>
            </div>

            {{-- Headline --}}
            <div class="login-hero__text">
                <h1 class="login-hero__title">
                    Lanjutkan <span class="login-hero__title-highlight">Petualangan</span> Bahasa Arabmu
                </h1>
                <p class="login-hero__subtitle">
                    Kumpulkan XP, naik level, buka karakter baru, dan kuasai Bahasa Arab melalui misi interaktif.
                </p>
            </div>

        </div>
    </section>


    {{-- ============================================
         RIGHT SIDE: LOGIN CARD
    ============================================ --}}
    <section class="login-form-section" aria-label="Login Form">
        <div class="login-card">

            {{-- Header --}}
            <div class="login-card__header">
                <div class="login-card__logo">
                    <div class="login-card__logo-icon">🕌</div>
                    <div class="login-card__logo-text">
                        <span class="login-card__logo-name">ArabicQuest</span>
                        <span class="login-card__logo-tagline">Learning Adventure</span>
                    </div>
                </div>

                <h2 class="login-card__title">Selamat Datang Kembali</h2>
                <p class="login-card__subtitle">Masuk untuk melanjutkan progres pembelajaranmu.</p>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
            <div class="login-alert login-alert--error" role="alert">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="login-alert login-alert--success" role="alert">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ secure_url('login.submit') }}" class="login-form" autocomplete="on">
                @csrf

                {{-- Email Field --}}
                <div class="login-form__group">
                    <label class="login-form__label" for="email">
                       
                        <span>Email</span>
                    </label>
                    <div class="login-form__input-wrapper">
                        <i class="fas fa-envelope login-form__input-icon" aria-hidden="true"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               class="login-form__input"
                               placeholder="contoh@email.com"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email">
                    </div>
                    @error('email')
                        <span class="login-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="login-form__group">
                    <label class="login-form__label" for="password">
                        
                        <span>Password</span>
                    </label>
                    <div class="login-form__input-wrapper">
                        <i class="fas fa-lock login-form__input-icon" aria-hidden="true"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               class="login-form__input"
                               placeholder="Masukkan password"
                               required
                               autocomplete="current-password">
                        <button type="button"
                                class="login-form__toggle-password"
                                onclick="togglePassword()"
                                aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="toggle-icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="login-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember & Forgot --}}
                <div class="login-form__options">
                    <label class="login-form__checkbox">
                        <input type="checkbox" name="remember" id="remember">
                        <span class="login-form__checkbox-custom"></span>
                        <span class="login-form__checkbox-label">Ingat Saya</span>
                    </label>
                    <a href="{{ route('forgot-password') }}" class="login-form__forgot">
                        Lupa Password?
                    </a>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="login-form__submit">
                    <span>Masuk & Lanjutkan Misi</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            {{-- Divider --}}
            <div class="login-divider">
                <span class="login-divider__line"></span>
                <!--<span class="login-divider__text">ATAU</span>-->
                <span class="login-divider__line"></span>
            </div>

            {{-- Google Login (Visual Only) --}}
            <!--<button type="button" class="login-social login-social--google">
                <svg class="login-social__icon" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </button>-->

            {{-- Footer --}}
            <div class="login-card__footer">
                <p class="login-card__footer-text">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="login-card__footer-link">
                        Daftar Sekarang
                    </a>
                </p>


            </div>

        </div>
    </section>

</div>

<script>
    // Toggle Password Visibility
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    // Animate Progress Bar on Load
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const progressFill = document.querySelector('.login-hero__progress-fill');
            if (progressFill) {
                progressFill.style.width = progressFill.style.getPropertyValue('--progress-width');
            }
        }, 500);
    });
</script>

</body>
</html>