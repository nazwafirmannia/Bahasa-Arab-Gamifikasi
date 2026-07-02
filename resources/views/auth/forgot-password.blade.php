<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pulihkan Akun - ArabicQuest</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ secure_asset('css/forgot-password.css') }}">
</head>
<body>

<div class="forgot-page">

    {{-- ============================================
         LEFT SIDE: HERO SECTION
    ============================================ --}}
    <section class="forgot-hero" aria-label="Recovery Preview">

        {{-- Islamic Geometric Pattern Background --}}
        <div class="forgot-hero__pattern" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="islamicPatternForgot" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <path d="M40 0 L80 40 L40 80 L0 40 Z" fill="none" stroke="rgba(251, 191, 36, 0.15)" stroke-width="1"/>
                        <circle cx="40" cy="40" r="15" fill="none" stroke="rgba(119, 85, 55, 0.1)" stroke-width="1"/>
                        <path d="M40 25 L55 40 L40 55 L25 40 Z" fill="none" stroke="rgba(251, 191, 36, 0.12)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#islamicPatternForgot)"/>
            </svg>
        </div>

        {{-- Floating Particles --}}
        <div class="forgot-hero__particles" aria-hidden="true">
            @for($i = 0; $i < 20; $i++)
                <span class="forgot-hero__particle" style="--i: {{ $i }};"></span>
            @endfor
        </div>

        {{-- Glow Orbs --}}
        <div class="forgot-hero__orb forgot-hero__orb--1" aria-hidden="true"></div>
        <div class="forgot-hero__orb forgot-hero__orb--2" aria-hidden="true"></div>

        {{-- Hero Content --}}
        <div class="forgot-hero__content">

            {{-- Character Recovery Illustration --}}
            <div class="forgot-hero__character-wrapper">
                <div class="forgot-hero__character-glow" aria-hidden="true"></div>

                {{-- Islamic Ornament (Crescent Moon) --}}
                <div class="forgot-hero__ornament" aria-hidden="true">
                    <span class="forgot-hero__ornament-moon">🌙</span>
                </div>

                {{-- Main Character --}}
                <div class="forgot-hero__character">
                    <span class="forgot-hero__character-emoji">🧕</span>
                </div>

                {{-- Magic Scroll --}}
                <div class="forgot-hero__scroll">
                    <span class="forgot-hero__scroll-emoji">📜</span>
                    <div class="forgot-hero__scroll-glow" aria-hidden="true"></div>
                </div>

                {{-- Sparkles --}}
                <div class="forgot-hero__sparkles" aria-hidden="true">
                    <span class="forgot-sparkle forgot-sparkle--1">✨</span>
                    <span class="forgot-sparkle forgot-sparkle--2">⭐</span>
                    <span class="forgot-sparkle forgot-sparkle--3">✨</span>
                    <span class="forgot-sparkle forgot-sparkle--4">✨</span>
                </div>
            </div>

            {{-- Headline --}}
            <div class="forgot-hero__text">
                <h1 class="forgot-hero__title">
                    Jangan Biarkan <span class="forgot-hero__title-highlight">Petualanganmu</span> Terhenti
                </h1>
                <p class="forgot-hero__subtitle">
                    Reset password dan lanjutkan perjalanan mengumpulkan XP, badge, dan level yang telah kamu capai.
                </p>
            </div>

            {{-- Recovery Progress Preview --}}
            <div class="forgot-hero__recovery">
                <div class="forgot-hero__recovery-title">
                    <i class="fas fa-shield-alt" aria-hidden="true"></i>
                    <span>Progresmu Aman</span>
                </div>

                <div class="forgot-hero__recovery-items">
                    <div class="forgot-hero__recovery-item">
                        <span class="forgot-hero__recovery-icon">🏅</span>
                        <span class="forgot-hero__recovery-text">Badge Tetap Aman</span>
                    </div>
                    <div class="forgot-hero__recovery-item">
                        <span class="forgot-hero__recovery-icon">⭐</span>
                        <span class="forgot-hero__recovery-text">XP Tidak Akan Hilang</span>
                    </div>
                    <div class="forgot-hero__recovery-item">
                        <span class="forgot-hero__recovery-icon">🔥</span>
                        <span class="forgot-hero__recovery-text">Streak Tetap Tersimpan</span>
                    </div>
                    <div class="forgot-hero__recovery-item">
                        <span class="forgot-hero__recovery-icon">👑</span>
                        <span class="forgot-hero__recovery-text">Evolusi Karakter Tetap Berjalan</span>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- ============================================
         RIGHT SIDE: FORM CARD
    ============================================ --}}
    <section class="forgot-form-section" aria-label="Password Recovery Form">
        <div class="forgot-card">

            {{-- Header --}}
            <div class="forgot-card__header">
                <div class="forgot-card__logo">
                    <div class="forgot-card__logo-icon">🔐</div>
                    <div class="forgot-card__logo-text">
                        <span class="forgot-card__logo-name">ArabicQuest</span>
                        <span class="forgot-card__logo-tagline">Account Recovery</span>
                    </div>
                </div>

                <h2 class="forgot-card__title">Pulihkan Akun</h2>
                <p class="forgot-card__subtitle">Masukkan data untuk membuat password baru</p>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
            <div class="forgot-alert forgot-alert--success" role="alert">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="forgot-alert forgot-alert--error" role="alert">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Recovery Form --}}
            <form method="POST" action="{{ route('forgot-password.reset') }}" class="forgot-form" autocomplete="on">
                @csrf

                {{-- Email Field --}}
                <div class="forgot-form__group">
                    <label class="forgot-form__label" for="email">
                        <i class="fas fa-envelope" aria-hidden="true"></i>
                        <span>Email</span>
                    </label>
                    <div class="forgot-form__input-wrapper">
                        <i class="fas fa-envelope forgot-form__input-icon" aria-hidden="true"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               class="forgot-form__input"
                               placeholder="contoh@email.com"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email">
                    </div>
                    @error('email')
                        <span class="forgot-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- New Password Field --}}
                <div class="forgot-form__group">
                    <label class="forgot-form__label" for="new_password">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <span>Password Baru</span>
                    </label>
                    <div class="forgot-form__input-wrapper">
                        <i class="fas fa-lock forgot-form__input-icon" aria-hidden="true"></i>
                        <input type="password"
                               id="new_password"
                               name="new_password"
                               class="forgot-form__input"
                               placeholder="Minimal 6 karakter"
                               required
                               autocomplete="new-password"
                               oninput="checkForgotPasswordStrength(this.value)">
                        <button type="button"
                                class="forgot-form__toggle-password"
                                onclick="toggleForgotPassword('new_password', 'toggle-icon-new')"
                                aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="toggle-icon-new"></i>
                        </button>
                    </div>

                    {{-- Password Strength Indicator --}}
                    <div class="forgot-form__strength" id="password-strength">
                        <div class="forgot-form__strength-bar">
                            <div class="forgot-form__strength-fill" id="strength-fill"></div>
                        </div>
                        <div class="forgot-form__strength-info">
                            <span class="forgot-form__strength-label" id="strength-label">Masukkan password</span>
                            <span class="forgot-form__strength-hint" id="strength-hint">Minimal 6 karakter</span>
                        </div>
                    </div>

                    @error('new_password')
                        <span class="forgot-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password Field --}}
                <div class="forgot-form__group">
                    <label class="forgot-form__label" for="new_password_confirmation">
                        <i class="fas fa-shield-alt" aria-hidden="true"></i>
                        <span>Konfirmasi Password Baru</span>
                    </label>
                    <div class="forgot-form__input-wrapper">
                        <i class="fas fa-shield-alt forgot-form__input-icon" aria-hidden="true"></i>
                        <input type="password"
                               id="new_password_confirmation"
                               name="new_password_confirmation"
                               class="forgot-form__input"
                               placeholder="Ulangi password baru"
                               required
                               autocomplete="new-password">
                        <button type="button"
                                class="forgot-form__toggle-password"
                                onclick="toggleForgotPassword('new_password_confirmation', 'toggle-icon-confirm')"
                                aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="toggle-icon-confirm"></i>
                        </button>
                    </div>
                    <span class="forgot-form__match" id="password-match"></span>
                    @error('new_password_confirmation')
                        <span class="forgot-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Security Card --}}
                <div class="forgot-security">
                    <div class="forgot-security__header">
                        <i class="fas fa-shield-alt" aria-hidden="true"></i>
                        <span>Keamanan Akun</span>
                    </div>
                    <div class="forgot-security__items">
                        <div class="forgot-security__item">
                            <i class="fas fa-check" aria-hidden="true"></i>
                            <span>Data pembelajaran tetap tersimpan</span>
                        </div>
                        <div class="forgot-security__item">
                            <i class="fas fa-check" aria-hidden="true"></i>
                            <span>XP dan Badge tidak akan hilang</span>
                        </div>
                        <div class="forgot-security__item">
                            <i class="fas fa-check" aria-hidden="true"></i>
                            <span>Riwayat progres tetap aman</span>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="forgot-form__submit">
                    <i class="fas fa-scroll" aria-hidden="true"></i>
                    <span>Pulihkan Akun</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            {{-- Footer --}}
            <div class="forgot-card__footer">
                <a href="{{ route('login') }}" class="forgot-card__back-link">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Kembali ke Login</span>
                </a>

                <div class="forgot-card__features">
                    <span class="forgot-card__feature">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        <span>XP Progress</span>
                    </span>
                    <span class="forgot-card__feature">
                        <i class="fas fa-medal" aria-hidden="true"></i>
                        <span>Achievement Badge</span>
                    </span>
                    <span class="forgot-card__feature">
                        <i class="fas fa-fire" aria-hidden="true"></i>
                        <span>Daily Streak</span>
                    </span>
                    <span class="forgot-card__feature">
                        <i class="fas fa-crown" aria-hidden="true"></i>
                        <span>Character Evolution</span>
                    </span>
                </div>
            </div>

        </div>
    </section>

</div>

<script>
    // Toggle Password Visibility
    function toggleForgotPassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

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

    // Password Strength Checker
    function checkForgotPasswordStrength(password) {
        const strengthFill = document.getElementById('strength-fill');
        const strengthLabel = document.getElementById('strength-label');
        const strengthHint = document.getElementById('strength-hint');

        if (!password) {
            strengthFill.style.width = '0%';
            strengthFill.className = 'forgot-form__strength-fill';
            strengthLabel.textContent = 'Masukkan password';
            strengthLabel.className = 'forgot-form__strength-label';
            strengthHint.textContent = 'Minimal 6 karakter';
            return;
        }

        let strength = 0;
        const checks = {
            length: password.length >= 6,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        strength = Object.values(checks).filter(Boolean).length;

        if (strength <= 2) {
            strengthFill.style.width = '33%';
            strengthFill.className = 'forgot-form__strength-fill forgot-form__strength-fill--weak';
            strengthLabel.textContent = '⚠ Lemah';
            strengthLabel.className = 'forgot-form__strength-label forgot-form__strength-label--weak';
            strengthHint.textContent = 'Tambahkan huruf besar, angka, atau simbol';
        } else if (strength <= 3) {
            strengthFill.style.width = '66%';
            strengthFill.className = 'forgot-form__strength-fill forgot-form__strength-fill--medium';
            strengthLabel.textContent = '🟡 Sedang';
            strengthLabel.className = 'forgot-form__strength-label forgot-form__strength-label--medium';
            strengthHint.textContent = 'Cukup baik, tapi bisa lebih kuat';
        } else {
            strengthFill.style.width = '100%';
            strengthFill.className = 'forgot-form__strength-fill forgot-form__strength-fill--strong';
            strengthLabel.textContent = '🟢 Kuat';
            strengthLabel.className = 'forgot-form__strength-label forgot-form__strength-label--strong';
            strengthHint.textContent = 'Password yang sangat aman!';
        }

        checkForgotPasswordMatch();
    }

    // Check Password Match
    function checkForgotPasswordMatch() {
        const password = document.getElementById('new_password').value;
        const confirm = document.getElementById('new_password_confirmation').value;
        const matchIndicator = document.getElementById('password-match');

        if (!confirm) {
            matchIndicator.textContent = '';
            matchIndicator.className = 'forgot-form__match';
            return;
        }

        if (password === confirm) {
            matchIndicator.innerHTML = '<i class="fas fa-check-circle"></i> Password cocok';
            matchIndicator.className = 'forgot-form__match forgot-form__match--success';
        } else {
            matchIndicator.innerHTML = '<i class="fas fa-times-circle"></i> Password tidak cocok';
            matchIndicator.className = 'forgot-form__match forgot-form__match--error';
        }
    }

    // Add event listener for password confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const confirmInput = document.getElementById('new_password_confirmation');
        if (confirmInput) {
            confirmInput.addEventListener('input', checkForgotPasswordMatch);
        }
    });
</script>

</body>
</html>