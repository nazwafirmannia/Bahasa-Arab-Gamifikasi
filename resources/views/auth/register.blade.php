<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar - ArabicQuest | Mulai Petualanganmu</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>

<div class="register-page">

    {{-- ============================================
         LEFT SIDE: HERO SECTION
    ============================================ --}}
    <section class="register-hero" aria-label="Adventure Preview">

        {{-- Islamic Geometric Pattern Background --}}
        <div class="register-hero__pattern" aria-hidden="true">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="islamicPatternReg" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                        <path d="M40 0 L80 40 L40 80 L0 40 Z" fill="none" stroke="rgba(251, 191, 36, 0.15)" stroke-width="1"/>
                        <circle cx="40" cy="40" r="15" fill="none" stroke="rgba(119, 85, 55, 0.1)" stroke-width="1"/>
                        <path d="M40 25 L55 40 L40 55 L25 40 Z" fill="none" stroke="rgba(251, 191, 36, 0.12)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#islamicPatternReg)"/>
            </svg>
        </div>

        {{-- Floating Particles --}}
        <div class="register-hero__particles" aria-hidden="true">
            @for($i = 0; $i < 20; $i++)
                <span class="register-hero__particle" style="--i: {{ $i }};"></span>
            @endfor
        </div>

        {{-- Glow Orbs --}}
        <div class="register-hero__orb register-hero__orb--1" aria-hidden="true"></div>
        <div class="register-hero__orb register-hero__orb--2" aria-hidden="true"></div>

        {{-- Hero Content --}}
        <div class="register-hero__content">

            {{-- Character Evolution Journey --}}
            <div class="register-hero__evolution">
                <div class="register-hero__evolution-title">
                    <i class="fas fa-route" aria-hidden="true"></i>
                    <span>Perjalanan Evolusi Karaktermu</span>
                </div>

                <div class="register-hero__evolution-path">
                    {{-- Connecting Line (SVG) --}}
                    <svg class="register-hero__evolution-line" viewBox="0 0 60 400" preserveAspectRatio="none" aria-hidden="true">
                        <line x1="30" y1="30" x2="30" y2="370" stroke="rgba(251, 191, 36, 0.4)" stroke-width="2" stroke-dasharray="5,5"/>
                        <line class="register-hero__evolution-line-active" x1="30" y1="30" x2="30" y2="30" stroke="#FBBF24" stroke-width="3"/>
                    </svg>

                    {{-- Evolution Stages --}}
                    <div class="register-hero__evolution-stage register-hero__evolution-stage--active">
                        <div class="register-hero__evolution-node">
                            <span class="register-hero__evolution-emoji">🧒</span>
                        </div>
                        <div class="register-hero__evolution-info">
                            <span class="register-hero__evolution-name">Al-Mubtadi</span>
                            <span class="register-hero__evolution-subtitle">Sang Pemula</span>
                        </div>
                    </div>

                    <div class="register-hero__evolution-stage">
                        <div class="register-hero__evolution-node">
                            <span class="register-hero__evolution-emoji">🧑‍🎓</span>
                        </div>
                        <div class="register-hero__evolution-info">
                            <span class="register-hero__evolution-name">Al-Talib</span>
                            <span class="register-hero__evolution-subtitle">Sang Pelajar</span>
                        </div>
                    </div>

                    <div class="register-hero__evolution-stage">
                        <div class="register-hero__evolution-node">
                            <span class="register-hero__evolution-emoji">🧕</span>
                        </div>
                        <div class="register-hero__evolution-info">
                            <span class="register-hero__evolution-name">Al-Musafir</span>
                            <span class="register-hero__evolution-subtitle">Sang Pengembara</span>
                        </div>
                    </div>

                    <div class="register-hero__evolution-stage">
                        <div class="register-hero__evolution-node">
                            <span class="register-hero__evolution-emoji">🧙‍♂️</span>
                        </div>
                        <div class="register-hero__evolution-info">
                            <span class="register-hero__evolution-name">Al-Hakim</span>
                            <span class="register-hero__evolution-subtitle">Sang Bijaksana</span>
                        </div>
                    </div>

                    <div class="register-hero__evolution-stage register-hero__evolution-stage--final">
                        <div class="register-hero__evolution-node">
                            <span class="register-hero__evolution-emoji">👑</span>
                        </div>
                        <div class="register-hero__evolution-info">
                            <span class="register-hero__evolution-name">Al-Malik</span>
                            <span class="register-hero__evolution-subtitle">Sang Raja</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Welcome Card --}}
            <div class="register-hero__welcome">
                <h1 class="register-hero__title">
                    Mulai <span class="register-hero__title-highlight">Petualangan</span> Bahasa Arabmu
                </h1>
                <p class="register-hero__subtitle">
                    Buat akun dan mulai mengumpulkan XP, membuka badge, menyelesaikan misi, dan berevolusi menjadi ahli Bahasa Arab.
                </p>
            </div>

            {{-- Reward Preview (Glassmorphism) --}}
            <div class="register-hero__rewards">
                <div class="register-hero__reward-item">
                    <span class="register-hero__reward-icon">⭐</span>
                    <span class="register-hero__reward-text">Dapatkan XP</span>
                </div>
                <div class="register-hero__reward-item">
                    <span class="register-hero__reward-icon">🏅</span>
                    <span class="register-hero__reward-text">Koleksi Badge</span>
                </div>
                <div class="register-hero__reward-item">
                    <span class="register-hero__reward-icon">🔥</span>
                    <span class="register-hero__reward-text">Bangun Streak Harian</span>
                </div>
                <div class="register-hero__reward-item">
                    <span class="register-hero__reward-icon">📚</span>
                    <span class="register-hero__reward-text">Selesaikan Materi</span>
                </div>
                <div class="register-hero__reward-item">
                    <span class="register-hero__reward-icon">👑</span>
                    <span class="register-hero__reward-text">Naik Level Karakter</span>
                </div>
            </div>

        </div>
    </section>


    {{-- ============================================
         RIGHT SIDE: REGISTER CARD
    ============================================ --}}
    <section class="register-form-section" aria-label="Register Form">
        <div class="register-card">

            {{-- Header --}}
            <div class="register-card__header">
                <div class="register-card__logo">
                    <div class="register-card__logo-icon">🕌</div>
                    <div class="register-card__logo-text">
                        <span class="register-card__logo-name">ArabicQuest</span>
                        <span class="register-card__logo-tagline">Learning Adventure</span>
                    </div>
                </div>

                <h2 class="register-card__title">Buat Akun Baru</h2>
                <p class="register-card__subtitle">Mulai perjalananmu hari ini</p>
            </div>

            {{-- Alerts --}}
            @if($errors->any())
            <div class="register-alert register-alert--error" role="alert">
                <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session('success'))
            <div class="register-alert register-alert--success" role="alert">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="register-form" autocomplete="on">
                @csrf

                {{-- Name Field --}}
                <div class="register-form__group">
                    <label class="register-form__label" for="name">
                        
                        <span>Nama Lengkap</span>
                    </label>
                    <div class="register-form__input-wrapper">
                        <i class="fas fa-user register-form__input-icon" aria-hidden="true"></i>
                        <input type="text"
                               id="name"
                               name="name"
                               class="register-form__input"
                               placeholder="Nama lengkap kamu"
                               value="{{ old('name') }}"
                               required
                               autocomplete="name">
                    </div>
                    @error('name')
                        <span class="register-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email Field --}}
                <div class="register-form__group">
                    <label class="register-form__label" for="email">
                      
                    </label>
                    <div class="register-form__input-wrapper">
                        <i class="fas fa-envelope register-form__input-icon" aria-hidden="true"></i>
                        <input type="email"
                               id="email"
                               name="email"
                               class="register-form__input"
                               placeholder="contoh@email.com"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email">
                    </div>
                    @error('email')
                        <span class="register-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="register-form__group">
                    <label class="register-form__label" for="password">
                 
                        <span>Password</span>
                    </label>
                    <div class="register-form__input-wrapper">
                        <i class="fas fa-lock register-form__input-icon" aria-hidden="true"></i>
                        <input type="password"
                               id="password"
                               name="password"
                               class="register-form__input"
                               placeholder="Minimal 6 karakter"
                               required
                               autocomplete="new-password"
                               oninput="checkPasswordStrength(this.value)">
                        <button type="button"
                                class="register-form__toggle-password"
                                onclick="togglePassword('password', 'toggle-icon-password')"
                                aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="toggle-icon-password"></i>
                        </button>
                    </div>

                    {{-- Password Strength Indicator --}}
                    <div class="register-form__strength" id="password-strength">
                        <div class="register-form__strength-bar">
                            <div class="register-form__strength-fill" id="strength-fill"></div>
                        </div>
                        <div class="register-form__strength-info">
                            <span class="register-form__strength-label" id="strength-label">Masukkan password</span>
                            <span class="register-form__strength-hint" id="strength-hint">Minimal 6 karakter</span>
                        </div>
                    </div>

                    @error('password')
                        <span class="register-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password Field --}}
                <div class="register-form__group">
                    <label class="register-form__label" for="password_confirmation">
                   
                        <span>Konfirmasi Password</span>
                    </label>
                    <div class="register-form__input-wrapper">
                        <i class="fas fa-shield-alt register-form__input-icon" aria-hidden="true"></i>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="register-form__input"
                               placeholder="Ulangi password"
                               required
                               autocomplete="new-password">
                        <button type="button"
                                class="register-form__toggle-password"
                                onclick="togglePassword('password_confirmation', 'toggle-icon-confirm')"
                                aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="toggle-icon-confirm"></i>
                        </button>
                    </div>
                    <span class="register-form__match" id="password-match"></span>
                    @error('password_confirmation')
                        <span class="register-form__error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Bonus Card --}}
                {{--<div class="register-bonus">
                    <div class="register-bonus__header">
                        <i class="fas fa-gift" aria-hidden="true"></i>
                        <span>Bonus Member Baru</span>
                    </div>
                    <div class="register-bonus__items">
                        <div class="register-bonus__item">
                            <span class="register-bonus__item-icon">⭐</span>
                            <span class="register-bonus__item-text">+100 XP Awal</span>
                        </div>
                        <div class="register-bonus__item">
                            <span class="register-bonus__item-icon">🏅</span>
                            <span class="register-bonus__item-text">Badge Pemula</span>
                        </div>
                        <div class="register-bonus__item">
                            <span class="register-bonus__item-icon">🎯</span>
                            <span class="register-bonus__item-text">Misi Pertama Terbuka</span>
                        </div>
                    </div>
                </div>--}}

                {{-- Submit Button --}}
                <button type="submit" class="register-form__submit">
                    <span>Mulai Petualangan</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>
            </form>

            {{-- Footer --}}
            <div class="register-card__footer">
                <p class="register-card__footer-text">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="register-card__footer-link">
                        Login di sini
                    </a>
                </p>

                
            </div>

        </div>
    </section>

</div>

<script>
    // Toggle Password Visibility
    function togglePassword(inputId, iconId) {
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
    function checkPasswordStrength(password) {
        const strengthFill = document.getElementById('strength-fill');
        const strengthLabel = document.getElementById('strength-label');
        const strengthHint = document.getElementById('strength-hint');
        const strengthContainer = document.getElementById('password-strength');

        if (!password) {
            strengthFill.style.width = '0%';
            strengthFill.className = 'register-form__strength-fill';
            strengthLabel.textContent = 'Masukkan password';
            strengthLabel.className = 'register-form__strength-label';
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

        // Update UI based on strength
        if (strength <= 2) {
            // Weak
            strengthFill.style.width = '33%';
            strengthFill.className = 'register-form__strength-fill register-form__strength-fill--weak';
            strengthLabel.textContent = 'Lemah';
            strengthLabel.className = 'register-form__strength-label register-form__strength-label--weak';
            strengthHint.textContent = 'Tambahkan huruf besar, angka, atau simbol';
        } else if (strength <= 3) {
            // Medium
            strengthFill.style.width = '66%';
            strengthFill.className = 'register-form__strength-fill register-form__strength-fill--medium';
            strengthLabel.textContent = 'Sedang';
            strengthLabel.className = 'register-form__strength-label register-form__strength-label--medium';
            strengthHint.textContent = 'Cukup baik, tapi bisa lebih kuat';
        } else {
            // Strong
            strengthFill.style.width = '100%';
            strengthFill.className = 'register-form__strength-fill register-form__strength-fill--strong';
            strengthLabel.textContent = 'Kuat';
            strengthLabel.className = 'register-form__strength-label register-form__strength-label--strong';
            strengthHint.textContent = 'Password yang sangat aman!';
        }

        // Check password match
        checkPasswordMatch();
    }

    // Check Password Match
    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirm = document.getElementById('password_confirmation').value;
        const matchIndicator = document.getElementById('password-match');

        if (!confirm) {
            matchIndicator.textContent = '';
            matchIndicator.className = 'register-form__match';
            return;
        }

        if (password === confirm) {
            matchIndicator.innerHTML = '<i class="fas fa-check-circle"></i> Password cocok';
            matchIndicator.className = 'register-form__match register-form__match--success';
        } else {
            matchIndicator.innerHTML = '<i class="fas fa-times-circle"></i> Password tidak cocok';
            matchIndicator.className = 'register-form__match register-form__match--error';
        }
    }

    // Add event listener for password confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const confirmInput = document.getElementById('password_confirmation');
        if (confirmInput) {
            confirmInput.addEventListener('input', checkPasswordMatch);
        }

        // Animate evolution path on load
        setTimeout(() => {
            const activeLine = document.querySelector('.register-hero__evolution-line-active');
            if (activeLine) {
                activeLine.setAttribute('y2', '370');
            }
        }, 500);
    });
</script>

</body>
</html>