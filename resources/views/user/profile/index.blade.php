@extends('layouts.app')
@section('title', 'Profil Karakter')
@section('page-title', 'Character Evolution')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/profile.css') }}">

@endpush

@php
    $user = Auth::user();
    $stat = $user->stat;
    $currentXp = $stat->xp_total ?? 0;
    $currentLevel = $stat->current_level ?? 1;

    // Stats
    $streak = $stat->streak ?? 0;
    $badgeCount = $user->badges->count();
    $materialDone = $user->materialProgress->where('is_selesai', true)->count();
    $totalBadges = \App\Models\Badge::where('is_active', true)->count();
@endphp

<div class="profile-page">

    {{-- ============================================
         SECTION 1: HERO PROFILE
    ============================================ --}}
    <section class="profile-hero" aria-label="Character Profile">


        <div class="profile-hero__avatar">
            @if($avatarCharacter && $avatarCharacter->image)
                <img
                    src="{{ asset('storage/'.$avatarCharacter->image) }}"
                    alt="{{ $avatarCharacter->name }}"
                    class="profile-hero__avatar-image">
            @else
                <img
                    src="{{ asset('images/default-avatar.png') }}"
                    alt="Default Avatar"
                    class="profile-hero__avatar-image">
            @endif
        </div>

            <div class="profile-hero__info">
                <div class="profile-hero__badge">
                    <i class="fas fa-scroll" aria-hidden="true"></i>
                    <span>CHARACTER EVOLUTION</span>
                </div>

                <h1 class="profile-hero__name">{{ $user->name_user }}</h1>

                <p class="profile-hero__email">
                    <i class="fas fa-envelope" aria-hidden="true"></i>
                    <span>{{ $user->email_user }}</span>
                </p>

                <p class="profile-hero__character">
                    <i class="fas fa-user-circle" aria-hidden="true"></i>
                    <span>{{ $avatarCharacter->name ?? 'Pemula' }}</span>
                    <span class="profile-hero__stage-separator">•</span>
                    <span>Level {{ $currentLevel }}</span>
                </p>

                <div class="profile-hero__stats">
                    <div class="profile-hero__stat">
                        <div class="profile-hero__stat-icon">
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <div class="profile-hero__stat-info">
                            <span class="profile-hero__stat-label">Total XP</span>
                            <span class="profile-hero__stat-value">{{ number_format($currentXp) }}</span>
                        </div>
                    </div>

                    <div class="profile-hero__stat">
                        <div class="profile-hero__stat-icon">
                            <i class="fas fa-trophy" aria-hidden="true"></i>
                        </div>
                        <div class="profile-hero__stat-info">
                            <span class="profile-hero__stat-label">Level</span>
                            <span class="profile-hero__stat-value">{{ $currentLevel }}</span>
                        </div>
                    </div>

                    <div class="profile-hero__stat">
                        <div class="profile-hero__stat-icon">
                            <i class="fas fa-fire" aria-hidden="true"></i>
                        </div>
                        <div class="profile-hero__stat-info">
                            <span class="profile-hero__stat-label">Streak</span>
                            <span class="profile-hero__stat-value">{{ $streak }} Hari</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         SECTION 2: CHARACTER EVOLUTION CARD
    ============================================ --}}
    <section class="evolution-showcase" aria-label="Character Evolution">
        <div class="evolution-showcase__header">
            <span class="evolution-showcase__icon" aria-hidden="true">✨</span>
            <h2 class="evolution-showcase__title">Character Evolution</h2>
        </div>

        <div class="evolution-showcase__content">
            <div class="evolution-showcase__current">
                <div class="evolution-showcase__current-avatar">
                    @if($avatarCharacter && $avatarCharacter->image)
                    <img
                        src="{{ asset('storage/'.$avatarCharacter->image) }}"
                        alt="{{ $avatarCharacter->name }}"
                        class="evolution-showcase__avatar-image">
                    @endif

                </div>

                <div class="evolution-showcase__current-info">
                    <span class="evolution-showcase__current-label">Karakter Saat Ini</span>
                    <h3 class="evolution-showcase__current-name">
                        {{ $avatarCharacter->name ?? 'Pemula' }}
                    </h3>
                    <p class="evolution-showcase__current-desc">
                        {{ $avatarCharacter->description ?? 'Karakter evolusi berdasarkan level.' }}
                    </p>

                    <div class="evolution-showcase__notice">
                        <i class="fas fa-info-circle" aria-hidden="true"></i>
                        <span>Avatar berevolusi otomatis saat level meningkat. Terus kumpulkan XP untuk naik level dan membuka bentuk berikutnya!</span>
                    </div>

                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         MAIN CONTENT GRID
    ============================================ --}}
    <div class="profile-grid">

        {{-- ============================================
             LEFT COLUMN: Info + Edit + Password
        ============================================ --}}
        <div class="profile-grid__main">

            {{-- Profile Information --}}
            <div class="profile-card profile-card--info">
                <div class="profile-card__header">
                    <span class="profile-card__icon" aria-hidden="true">📋</span>
                    <h3 class="profile-card__title">Informasi Profil</h3>
                </div>

                <div class="profile-card__body">
                    <div class="profile-info-row">
                        <span class="profile-info-row__label">
                            <i class="fas fa-user" aria-hidden="true"></i>
                            Nama Lengkap
                        </span>
                        <span class="profile-info-row__value">{{ $user->name_user }}</span>
                    </div>

                    <div class="profile-info-row">
                        <span class="profile-info-row__label">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                            Email
                        </span>
                        <span class="profile-info-row__value">{{ $user->email_user }}</span>
                    </div>

                    <div class="profile-info-row">
                        <span class="profile-info-row__label">
                            <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                            Bergabung
                        </span>
                        <span class="profile-info-row__value">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </span>
                    </div>

                    <div class="profile-info-row">
                        <span class="profile-info-row__label">
                            <i class="fas fa-layer-group" aria-hidden="true"></i>
                            Level Saat Ini
                        </span>
                        <span class="profile-info-row__value profile-info-row__value--highlight">
                            Level {{ $currentLevel }} - {{ $avatarCharacter->name ?? 'Pemula' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Edit Profile Form --}}
            <div class="profile-card profile-card--edit">
                <div class="profile-card__header">
                    <span class="profile-card__icon" aria-hidden="true">✏️</span>
                    <h3 class="profile-card__title">Edit Profil</h3>
                </div>

                <div class="profile-card__body">
                    @if(session('success'))
                    <div class="profile-form__alert profile-form__alert--success">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    @endif

                    @if($errors->any() && !$errors->has('current_password') && !$errors->has('new_password'))
                    <div class="profile-form__alert profile-form__alert--error">
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

                    <form method="POST" action="{{ route('user.profile.update') }}" class="profile-form">
                        @csrf
                        @method('PUT')

                        <div class="profile-form__group">
                            <label class="profile-form__label" for="name_user">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                Nama Lengkap
                            </label>
                            <input type="text"
                                   id="name_user"
                                   name="name_user"
                                   value="{{ old('name_user', $user->name_user) }}"
                                   class="profile-form__input"
                                   required>
                            @error('name_user')
                                <span class="profile-form__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-form__group">
                            <label class="profile-form__label" for="email_user">
                                <i class="fas fa-envelope" aria-hidden="true"></i>
                                Email
                            </label>
                            <input type="email"
                                   id="email_user"
                                   name="email_user"
                                   value="{{ old('email_user', $user->email_user) }}"
                                   class="profile-form__input"
                                   required>
                            @error('email_user')
                                <span class="profile-form__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="profile-form__notice">
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                            <span>Avatar akan berubah otomatis sesuai level yang kamu capai.</span>
                        </div>

                        <button type="submit" class="profile-form__submit">
                            <i class="fas fa-save" aria-hidden="true"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="profile-card profile-card--password">
                <div class="profile-card__header">
                    <span class="profile-card__icon" aria-hidden="true">🔒</span>
                    <h3 class="profile-card__title">Ganti Password</h3>
                </div>

                <div class="profile-card__body">
                    @if($errors->has('current_password') || $errors->has('new_password'))
                    <div class="profile-form__alert profile-form__alert--error">
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

                    <form method="POST" action="{{ route('user.profile.password') }}" class="profile-form">
                        @csrf
                        @method('PUT')

                        <div class="profile-form__group">
                            <label class="profile-form__label" for="current_password">
                                <i class="fas fa-key" aria-hidden="true"></i>
                                Password Lama
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   class="profile-form__input"
                                   required>
                        </div>

                        <div class="profile-form__group">
                            <label class="profile-form__label" for="new_password">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                Password Baru
                            </label>
                            <input type="password"
                                   id="new_password"
                                   name="new_password"
                                   class="profile-form__input"
                                   required
                                   minlength="6">
                        </div>

                        <div class="profile-form__group">
                            <label class="profile-form__label" for="new_password_confirmation">
                                <i class="fas fa-lock" aria-hidden="true"></i>
                                Konfirmasi Password Baru
                            </label>
                            <input type="password"
                                   id="new_password_confirmation"
                                   name="new_password_confirmation"
                                   class="profile-form__input"
                                   required>
                        </div>

                        <button type="submit" class="profile-form__submit profile-form__submit--secondary">
                            <i class="fas fa-shield-alt" aria-hidden="true"></i>
                            <span>Ubah Password</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        {{-- ============================================
             RIGHT COLUMN: Stats + Badges
        ============================================ --}}
        <aside class="profile-grid__sidebar">

            {{-- Statistics --}}
            <div class="profile-card profile-card--stats">
                <div class="profile-card__header">
                    <span class="profile-card__icon" aria-hidden="true">📊</span>
                    <h3 class="profile-card__title">Statistik</h3>
                </div>

                <div class="profile-card__body">
                    <div class="stat-item stat-item--xp">
                        <div class="stat-item__icon">
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <div class="stat-item__info">
                            <span class="stat-item__label">Total XP</span>
                            <span class="stat-item__value">{{ number_format($currentXp) }}</span>
                        </div>
                    </div>

                    <div class="stat-item stat-item--level">
                        <div class="stat-item__icon">
                            <i class="fas fa-level" aria-hidden="true"></i>
                        </div>
                        <div class="stat-item__info">
                            <span class="stat-item__label">Level</span>
                            <span class="stat-item__value">{{ $currentLevel }}</span>
                        </div>
                    </div>

                    <div class="stat-item stat-item--streak">
                        <div class="stat-item__icon">
                            <i class="fas fa-fire" aria-hidden="true"></i>
                        </div>
                        <div class="stat-item__info">
                            <span class="stat-item__label">Streak Hari</span>
                            <span class="stat-item__value">{{ $streak }}</span>
                        </div>
                    </div>

                    <div class="stat-item stat-item--badge">
                        <div class="stat-item__icon">
                            <i class="fas fa-medal" aria-hidden="true"></i>
                        </div>
                        <div class="stat-item__info">
                            <span class="stat-item__label">Badge</span>
                            <span class="stat-item__value">{{ $badgeCount }}</span>
                        </div>
                    </div>

                    <div class="stat-item stat-item--material">
                        <div class="stat-item__icon">
                            <i class="fas fa-check-circle" aria-hidden="true"></i>
                        </div>
                        <div class="stat-item__info">
                            <span class="stat-item__label">Materi Selesai</span>
                            <span class="stat-item__value">{{ $materialDone }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Badges Collection --}}
            <div class="profile-card profile-card--badges">
                <div class="profile-card__header">
                    <span class="profile-card__icon" aria-hidden="true">🏅</span>
                    <h3 class="profile-card__title">Koleksi Badge</h3>
                    <span class="profile-card__counter">{{ $badgeCount }} / {{ $totalBadges }}</span>
                </div>

                <div class="profile-card__body">
                    @if($user->badges->isEmpty())
                    <div class="profile-badges__empty">
                        <div class="profile-badges__empty-icon" aria-hidden="true">🔒</div>
                        <p class="profile-badges__empty-text">Belum ada badge. Terus belajar untuk membukanya!</p>
                    </div>
                    @else
                    <div class="profile-badges__grid">
                        @foreach($user->badges as $badge)
                        @php
                            $rarity = match($badge->name) {
                                'Sempurna 100%', 'Pejuang 1 Bulan' => 'legendary',
                                'Konsisten 1 Minggu', 'XP 1000' => 'epic',
                                'Streak 3 Hari', 'XP 500', 'Lulus Level 1' => 'rare',
                                default => 'common'
                            };
                        @endphp
                        <div class="profile-badge profile-badge--{{ $rarity }}"
                             title="Diperoleh: {{ \Carbon\Carbon::parse($badge->pivot->obtained_at)->format('d M Y') }}&#10;{{ $badge->description }}">
                            <div class="profile-badge__icon">{{ $badge->icon }}</div>
                            <div class="profile-badge__info">
                                <h4 class="profile-badge__name">{{ $badge->name }}</h4>
                                @if($badge->xp_bonus > 0 || ($badge->coin_bonus ?? 0) > 0)
                                <p class="profile-badge__bonus">
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