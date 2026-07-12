@extends('layouts.app')
@section('title', 'Pengaturan Profil')
@section('page-title', 'Pengaturan Profil')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/profile-edit.css') }}">
<style>
    #sidebar { display: none !important; }
    .flex.h-screen { overflow: hidden; }
    .flex-1 { width: 100% !important; }
    main { padding: 0 !important; max-width: 100% !important; }
</style>
@endpush

    @php

$user = Auth::user();
$stat = $user->stat;
$currentXp = $stat->xp_total ?? 0;
$currentLevel = $stat->current_level ?? 1;
$currentCharacter = \App\Models\Character::where(
        'unlock_level',
        '<=',
        $currentLevel
    )
    ->where('is_active', true)
    ->orderByDesc('unlock_level')
    ->first();

$nextCharacter = \App\Models\Character::where(
        'unlock_level',
        '>',
        $currentLevel
    )
    ->where('is_active', true)
    ->orderBy('unlock_level')
    ->first();

$streak = $stat->streak ?? 0;

$badgeCount = $user->badges->count();

$materialDone = $user->materialProgress
    ->where('is_selesai', true)
    ->count();

$totalBadges = \App\Models\Badge::where(
    'is_active',
    true
)->count();

@endphp

<div class="profile-edit-page">

    {{-- ============================================
         SECTION 1: HERO SECTION
    ============================================ --}}
    <section class="profile-edit-hero" aria-label="Profile Header">
        <div class="profile-edit-hero__glow" aria-hidden="true"></div>
        <div class="profile-edit-hero__particles" aria-hidden="true">
            @for($i = 0; $i < 12; $i++)
                <span class="profile-edit-hero__particle" style="--i: {{ $i }};"></span>
            @endfor
        </div>

        <div class="profile-edit-hero__content">
            <div class="profile-edit-hero__avatar-section">
                <div class="profile-edit-hero__avatar-ring">
                    <div class="profile-edit-hero__avatar">
                        @if($currentCharacter)

                        <img
                        src="{{ asset('images/characters/'.$currentLevel.'.jpg') }}"
                        alt="{{ $currentCharacter->name }}"
                        class="profile-edit-hero__avatar-image">

@endif
                    </div>
                    <div class="profile-edit-hero__avatar-glow" aria-hidden="true"></div>
                </div>
                <div class="profile-edit-hero__level-badge">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    <span>Lv. {{ $currentLevel }}</span>
                </div>
            </div>

            <div class="profile-edit-hero__info">
                <div class="profile-edit-hero__badge">
                    <i class="fas fa-cog" aria-hidden="true"></i>
                    <span>PENGATURAN PROFIL</span>
                </div>

                <h1 class="profile-edit-hero__name">{{ $user->name_user }}</h1>

                <p class="profile-edit-hero__email">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span>{{ $user->email_user }}</span>
                </p>

                <p class="profile-edit-hero__character">
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                    <span>{{ $currentCharacter->name ?? 'Character' }}</span>
                    <span class="profile-edit-hero__separator">•</span>
                    <span class="profile-edit-hero__separator">•</span>
                    <span>Level {{ $currentLevel }}</span>
                </p>

                <div class="profile-edit-hero__stats">
                    <div class="profile-edit-hero__stat">
                        <div class="profile-edit-hero__stat-icon">
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-hero__stat-info">
                            <span class="profile-edit-hero__stat-label">Total XP</span>
                            <span class="profile-edit-hero__stat-value">{{ number_format($currentXp) }}</span>
                        </div>
                    </div>

                    <div class="profile-edit-hero__stat">
                        <div class="profile-edit-hero__stat-icon">
                            <i class="fas fa-trophy" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-hero__stat-info">
                            <span class="profile-edit-hero__stat-label">Level</span>
                            <span class="profile-edit-hero__stat-value">{{ $currentLevel }}</span>
                        </div>
                    </div>

                    <div class="profile-edit-hero__stat">
                        <div class="profile-edit-hero__stat-icon">
                            <i class="fas fa-fire" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-hero__stat-info">
                            <span class="profile-edit-hero__stat-label">Streak</span>
                            <span class="profile-edit-hero__stat-value">{{ $streak }} Hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         MAIN GRID LAYOUT
    ============================================ --}}
    <div class="profile-edit-grid">

        {{-- ============================================
             LEFT COLUMN
        ============================================ --}}
        <div class="profile-edit-grid__main">

            {{-- ============================================
                 SECTION 2: CHARACTER EVOLUTION CARD
            ============================================ --}}
            <section class="profile-edit-card profile-edit-card--evolution" aria-label="Character Evolution">
                <div class="profile-edit-card__header">
                    <span class="profile-edit-card__icon" aria-hidden="true">✨</span>
                    <h2 class="profile-edit-card__title">Evolusi Karakter</h2>
                </div>

                <div class="profile-edit-card__body">
                    <div class="profile-edit-evolution">
                        <div class="profile-edit-evolution__current">
                            <div class="profile-edit-evolution__avatar">

                                <img
                                    src="{{ asset('images/characters/'.$currentLevel.'.jpg') }}"
                                    alt="{{ $currentCharacter->name }}"
                                    class="profile-edit-hero__avatar-image">
                            
                                <div class="profile-edit-evolution__glow" aria-hidden="true"></div>
                            
                            </div>

                            <div class="profile-edit-evolution__info">
                                <span class="profile-edit-evolution__label">Karakter Saat Ini</span>
                                <h3 class="profile-edit-evolution__name">{{ $currentCharacter->name }}</h3>
                                <p class="profile-edit-evolution__subtitle">
                                    Level {{ $currentLevel }}
                                </p>
                                <p class="profile-edit-evolution__desc">
                                    {{ $currentCharacter->description ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="profile-edit-evolution__notice">
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                            <span>Avatar berevolusi otomatis saat level meningkat. Terus kumpulkan XP untuk naik level dan membuka bentuk berikutnya!</span>
                        </div>

                        @if($nextCharacter)
                        <div class="profile-edit-evolution__next">
                        
                            <span class="profile-edit-evolution__next-label">
                                Evolusi Berikutnya Level {{ $nextCharacter->unlock_level }}:
                            </span>
                        
                            <div class="profile-edit-evolution__next-card">
                        
                                <img
                                    src="{{ asset('images/characters/'.$nextCharacter->unlock_level.'.jpg') }}"
                                    alt="{{ $nextCharacter->name }}"
                                    class="profile-edit-evolution__next-image">
                        
                                <div class="profile-edit-evolution__next-info">
                                    <span class="profile-edit-evolution__next-name">
                                        {{ $nextCharacter->name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @else
                        <div class="profile-edit-evolution__max">
                            <i class="fas fa-crown" aria-hidden="true"></i>
                            <span>Anda telah membuka seluruh Character Evolution.</span>
                        </div>
                        @endif

                    </div>
                </div>
            </section>


            {{-- ============================================
                 SECTION 3: EDIT PROFILE CARD
            ============================================ --}}
            <section class="profile-edit-card profile-edit-card--edit" aria-label="Edit Profile">
                <div class="profile-edit-card__header">
                    <span class="profile-edit-card__icon" aria-hidden="true">✏️</span>
                    <h2 class="profile-edit-card__title">Edit Profil</h2>
                </div>

                <div class="profile-edit-card__body">
                    @if(session('success'))
                    <div class="profile-edit-alert profile-edit-alert--success">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if($errors->any() && !$errors->has('current_password') && !$errors->has('new_password'))
                    <div class="profile-edit-alert profile-edit-alert--error">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        <ul>
                            @foreach($errors->all() as $error)
                                @if(!\Str::contains($error, ['password', 'Password']))
                                    <li>{{ $error }}</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.update') }}" class="profile-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="profile-edit-form__group">
                            <label class="profile-edit-form__label" for="name_user">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   id="name_user"
                                   name="name_user"
                                   value="{{ old('name_user', $user->name_user) }}"
                                   class="profile-edit-form__input"
                                   placeholder="Masukkan nama lengkap"
                                   required>
                            @error('name_user')
                                <span class="profile-edit-form__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-edit-form__group">
                            <label class="profile-edit-form__label" for="email_user">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                Email
                            </label>
                            <input type="email"
                                   id="email_user"
                                   name="email_user"
                                   value="{{ old('email_user', $user->email_user) }}"
                                   class="profile-edit-form__input"
                                   placeholder="contoh@email.com"
                                   required>
                            @error('email_user')
                                <span class="profile-edit-form__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-edit-form__notice">
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                            <span>Avatar akan berubah otomatis sesuai level yang kamu capai.</span>
                        </div>

                        <button type="submit" class="profile-edit-form__submit profile-edit-form__submit--primary">
                            <i class="fas fa-save" aria-hidden="true"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </section>


            {{-- ============================================
                 SECTION 4: CHANGE PASSWORD CARD
            ============================================ --}}
            <section class="profile-edit-card profile-edit-card--password" aria-label="Change Password">
                <div class="profile-edit-card__header">
                    <span class="profile-edit-card__icon" aria-hidden="true">🔒</span>
                    <h2 class="profile-edit-card__title">Ganti Password</h2>
                </div>

                <div class="profile-edit-card__body">
                    @if($errors->has('current_password') || $errors->has('new_password'))
                    <div class="profile-edit-alert profile-edit-alert--error">
                        <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                        <ul>
                            @if($errors->has('current_password'))
                                <li>{{ $errors->first('current_password') }}</li>
                            @endif
                            @if($errors->has('new_password'))
                                <li>{{ $errors->first('new_password') }}</li>
                            @endif
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('user.profile.password') }}" class="profile-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="profile-edit-form__group">
                            <label class="profile-edit-form__label" for="current_password">
                                <i class="fas fa-key" aria-hidden="true"></i>
                                Password Lama
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   class="profile-edit-form__input"
                                   placeholder="Masukkan password lama"
                                   required>
                        </div>

                        <div class="profile-edit-form__group">
                            <label class="profile-edit-form__label" for="new_password">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                Password Baru
                            </label>
                            <input type="password"
                                   id="new_password"
                                   name="new_password"
                                   class="profile-edit-form__input"
                                   placeholder="Minimal 6 karakter"
                                   required
                                   minlength="6">
                        </div>

                        <div class="profile-edit-form__group">
                            <label class="profile-edit-form__label" for="new_password_confirmation">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                Konfirmasi Password Baru
                            </label>
                            <input type="password"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   class="profile-edit-form__input"
                                   placeholder="Ulangi password baru"
                                   required>
                        </div>

                        <button type="submit" class="profile-edit-form__submit profile-edit-form__submit--gold">
                            <i class="fas fa-shield-alt" aria-hidden="true"></i>
                            <span>Ubah Password</span>
                        </button>
                    </form>
                </div>
            </section>

        </div>


        {{-- ============================================
             RIGHT COLUMN (SIDEBAR)
        ============================================ --}}
        <aside class="profile-edit-grid__sidebar">

            {{-- ============================================
                 SECTION 5: STATISTICS CARD
            ============================================ --}}
            <div class="profile-edit-card profile-edit-card--stats">
                <div class="profile-edit-card__header">
                    <span class="profile-edit-card__icon" aria-hidden="true">📊</span>
                    <h2 class="profile-edit-card__title">Statistik</h2>
                </div>

                <div class="profile-edit-card__body">
                    <div class="profile-edit-stat profile-edit-stat--xp">
                        <div class="profile-edit-stat__icon">
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-stat__info">
                            <span class="profile-edit-stat__label">Total XP</span>
                            <span class="profile-edit-stat__value">{{ number_format($currentXp) }}</span>
                        </div>
                    </div>

                    <div class="profile-edit-stat profile-edit-stat--level">
                        <div class="profile-edit-stat__icon">
                            <i class="fas fa-trophy" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-stat__info">
                            <span class="profile-edit-stat__label">Level</span>
                            <span class="profile-edit-stat__value">{{ $currentLevel }}</span>
                        </div>
                    </div>

                    <div class="profile-edit-stat profile-edit-stat--streak">
                        <div class="profile-edit-stat__icon">
                            <i class="fas fa-fire" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-stat__info">
                            <span class="profile-edit-stat__label">Streak Hari</span>
                            <span class="profile-edit-stat__value">{{ $streak }} 🔥</span>
                        </div>
                    </div>

                    <div class="profile-edit-stat profile-edit-stat--badge">
                        <div class="profile-edit-stat__icon">
                            <i class="fas fa-medal" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-stat__info">
                            <span class="profile-edit-stat__label">Badge Dimiliki</span>
                            <span class="profile-edit-stat__value">{{ $badgeCount }} 🏅</span>
                        </div>
                    </div>

                    <div class="profile-edit-stat profile-edit-stat--material">
                        <div class="profile-edit-stat__icon">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                        </div>
                        <div class="profile-edit-stat__info">
                            <span class="profile-edit-stat__label">Materi Selesai</span>
                            <span class="profile-edit-stat__value">{{ $materialDone }} ✅</span>
                        </div>
                    </div>
                </div>
            </div>


            {{-- ============================================
                 SECTION 6: BADGES COLLECTION CARD
            ============================================ --}}
            <div class="profile-edit-card profile-edit-card--badges">
                <div class="profile-edit-card__header">
                    <span class="profile-edit-card__icon" aria-hidden="true">🏅</span>
                    <h2 class="profile-edit-card__title">Koleksi Badge</h2>
                    <span class="profile-edit-card__counter">{{ $badgeCount }} / {{ $totalBadges }}</span>
                </div>

                <div class="profile-edit-card__body">
                    @if($user->badges->isEmpty())
                    <div class="profile-edit-badges__empty">
                        <div class="profile-edit-badges__empty-icon" aria-hidden="true">🔒</div>
                        <p class="profile-edit-badges__empty-title">Belum Ada Badge</p>
                        <p class="profile-edit-badges__empty-text">Terus belajar untuk membuka badge pertama kamu!</p>
                    </div>
                    @else
                    <div class="profile-edit-badges__grid">
                        @foreach($user->badges as $badge)
                        @php
                            $rarity = match($badge->name) {
                                'Sempurna 100%', 'Pejuang 1 Bulan' => 'legendary',
                                'Konsisten 1 Minggu', 'XP 1000' => 'epic',
                                'Streak 3 Hari', 'XP 500', 'Lulus Level 1' => 'rare',
                                default => 'common'
                            };
                        @endphp
                        <div class="profile-edit-badge profile-edit-badge--{{ $rarity }}"
                             title="Diperoleh: {{ \Carbon\Carbon::parse($badge->pivot->obtained_at)->format('d M Y') }}&#10;{{ $badge->description }}">
                            <div class="profile-edit-badge__icon">{{ $badge->icon }}</div>
                            <div class="profile-edit-badge__info">
                                <h4 class="profile-edit-badge__name">{{ $badge->name }}</h4>
                                @if($badge->xp_bonus > 0 || ($badge->coin_bonus ?? 0) > 0)
                                <p class="profile-edit-badge__bonus">
                                    @if($badge->xp_bonus > 0)+{{ $badge->xp_bonus }}XP @endif
                                    @if(($badge->coin_bonus ?? 0) > 0)+{{ $badge->coin_bonus }}🪙 @endif
                                </p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

        </aside>
    </div>

</div>
@endsection