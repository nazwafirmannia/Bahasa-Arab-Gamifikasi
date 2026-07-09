@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Akademi Bahasa Arab')

@section('content')

<div class="dashboard-container">
    {{-- ============================================
         SECTION 1: HERO LEARNING
    ============================================ --}}
    <div class="max-w-6xl mx-auto px-4 py-6">
    <section class="hero-learning">
        <div class="hero-learning__avatar-wrapper">
            <div class="hero-learning__avatar">
    
                <img
                    src="{{ asset('images/characters/'. (Auth::user()->level ?? 1) . '.jpg') }}"
                    alt="{{ $currentCharacter ? $currentCharacter->name : 'Character' }}">
    
            </div>
            <div class="hero-learning__level-badge">
                Lv. {{ Auth::user()->stat->current_level ?? 1 }}
            </div>
        </div>

        <div class="hero-learning__content">

            <h1 class="hero-learning__name">
                {{ Auth::user()->name_user }}
                <span class="hero-learning__comma">,</span>
                <span class="hero-learning__greeting">
                    أَهْلًا وَسَهْلًا
                </span>
            </h1>
        
            <p class="hero-learning__stage">
                <span class="hero-learning__stage-icon">📍</span>
                Stage Aktif:
                <strong>{{ $currentStage->stage_name ?? 'Belum Dimulai' }}</strong>
            </p>
        
        </div>

           <!-- <div class="hero-learning__stats">
                <div class="hero-learning__stat">
                    <span class="hero-learning__stat-icon">⭐</span>
                    <div class="hero-learning__stat-info">
                        <span class="hero-learning__stat-label">Total XP</span>
                        <span class="hero-learning__stat-value">{{ number_format(Auth::user()->stat->xp_total ?? 0) }}</span>
                    </div>
                </div>

                <div class="hero-learning__stat">
                    <span class="hero-learning__stat-icon">🏅</span>
                    <div class="hero-learning__stat-info">
                        <span class="hero-learning__stat-label">Badge</span>
                        <span class="hero-learning__stat-value">{{ Auth::user()->badges->count() ?? 0 }}</span>
                    </div>
                </div>

                <div class="hero-learning__stat hero-learning__stat--streak">
                    <span class="hero-learning__stat-icon">🔥</span>
                    <div class="hero-learning__stat-info">
                        <span class="hero-learning__stat-label">Streak</span>
                        <span class="hero-learning__stat-value">{{ Auth::user()->stat->streak ?? 0 }} Hari</span>
                    </div>
                </div>
            </div>
        </div>-->

        <!--<div class="hero-learning__action">
            <a href="{{ route('user.level', $currentStage->levels->first()?->id_level ?? '#') }}"
               class="hero-learning__btn">
                <span>Lanjutkan Belajar</span>
                <span class="hero-learning__btn-arrow">→</span>
            </a>
            <div class="hero-learning__progress-mini">
                <span class="hero-learning__progress-label">Progress Stage</span>
                <div class="hero-learning__progress-bar">
                    <div class="hero-learning__progress-fill"
                         style="width: {{ min(100, $currentStage->progress_percent ?? 0) }}%">
                    </div>
                </div>
                
                <div class="hero-learning__progress-detail">
                    {{ $currentStage->progress_percent ?? 0 }}% selesai
                </div>
                <span class="hero-learning__progress-text">{{ $currentStage->progress_percent ?? 0 }}%</span>
            </div>
        </div>-->
    </section>

    <div class="quick-stats">

        <div class="quick-stat-orb">
            <span class="quick-stat-orb__icon">⭐</span>
            <span class="quick-stat-orb__number">
                {{ Auth::user()->stat->xp_total }}
            </span>
            <span class="quick-stat-orb__label">
                XP
            </span>
        </div>
    
        <div class="quick-stat-orb">
            <span class="quick-stat-orb__icon">🔥</span>
            <span class="quick-stat-orb__number">
                {{ Auth::user()->stat->streak }}
            </span>
            <span class="quick-stat-orb__label">
                STREAK
            </span>
        </div>
    
        <div class="quick-stat-orb">
            <span class="quick-stat-orb__icon">🏅</span>
            <span class="quick-stat-orb__number">
                {{ Auth::user()->badges->count() }}
            </span>
            <span class="quick-stat-orb__label">
                BADGE
            </span>
        </div>
    
    </div>


    {{-- ============================================
         SECTION 2: QUEST PANEL (Misi Harian)
    ============================================ --}}
    <section class="quest-panel">
        <div class="quest-panel__header">
            <h2 class="quest-panel__title">
                <span class="quest-panel__icon">🎯</span>
                Misi Hari Ini
            </h2>
            @php
                $missionDone = 0;
                if($todayMaterial >= 1) $missionDone++;
                if($todayPractice >= 1) $missionDone++;
                if($todayXp >= 50) $missionDone++;
            @endphp
            <span class="quest-panel__counter">{{ $missionDone }}/3 Selesai</span>
        </div>

        <div class="quest-panel__list">
            <div class="quest-panel__item {{ $todayMaterial >= 1 ? 'quest-panel__item--done' : '' }}">
                <div class="quest-panel__item-check">
                    {!! $todayMaterial >= 1 ? '✅' : '⬜' !!}
                </div>
                <div class="quest-panel__item-content">
                    <span class="quest-panel__item-label">Selesaikan 1 Materi</span>
                    <span class="quest-panel__item-reward">+20 XP</span>
                </div>
            </div>

            <div class="quest-panel__item {{ $todayPractice >= 1 ? 'quest-panel__item--done' : '' }}">
                <div class="quest-panel__item-check">
                    {!! $todayPractice >= 1 ? '✅' : '⬜' !!}
                </div>
                <div class="quest-panel__item-content">
                    <span class="quest-panel__item-label">Kerjakan 1 Latihan</span>
                    <span class="quest-panel__item-reward">+15 XP</span>
                </div>
            </div>

            <div class="quest-panel__item {{ $todayXp >= 50 ? 'quest-panel__item--done' : '' }}">
                <div class="quest-panel__item-check">
                    {!! $todayXp >= 50 ? '✅' : '⬜' !!}
                </div>
                <div class="quest-panel__item-content">
                    <span class="quest-panel__item-label">Dapatkan 50 XP</span>
                    <span class="quest-panel__item-reward">+10 XP Bonus</span>
                </div>
            </div>
        </div>

        <div class="quest-panel__progress">
            <div class="quest-panel__progress-bar">
                <div class="quest-panel__progress-fill"
                     style="width: {{ ($missionDone / 3) * 100 }}%">
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         SECTION 3: LEARNING JOURNEY
    ============================================ --}}
    <section class="learning-journey">
        <div class="learning-journey__header">
            <h2 class="learning-journey__title">
                <span class="learning-journey__icon">🧭</span>
                Perjalanan Belajarmu
            </h2>
            <p class="learning-journey__subtitle">
                Selesaikan setiap stage untuk membuka petualangan baru!
            </p>
        </div>

        <div class="learning-journey__path">
            @foreach($stages as $index => $stage)
                @php
                    $isUnlocked = $stage->urutan <= ($currentStage->id_stage ?? 1);
                    $isCurrent  = $stage->id_stage == ($currentStage->id_stage ?? null);
                    $isCompleted = ($stage->progress_percent ?? 0) >= 100;
                    $progress   = $stage->progress_percent ?? 0;
                @endphp

                <div class="journey-stage
                    {{ $isCurrent ? 'journey-stage--current' : '' }}
                    {{ $isCompleted ? 'journey-stage--completed' : '' }}
                    {{ !$isUnlocked ? 'journey-stage--locked' : '' }}">

                    <div class="journey-stage__connector">
                        @if(!$loop->last)
                            <div class="journey-stage__line {{ $isCompleted ? 'journey-stage__line--active' : '' }}"></div>
                        @endif
                    </div>

                    <!--<div class="journey-stage__node">
                        <div class="journey-stage__circle">
                            @if($isCompleted)
                                
                            @elseif($isCurrent)
                                <span></span>
                            @elseif($isUnlocked)
                                <span>{{ $stage->urutan }}</span>
                            @else
                                <span>🔒</span>
                            @endif
                        </div>
                    </div>-->

                    <div class="journey-stage__content">
                        <div class="journey-stage__header">
                            <h3 class="journey-stage__name">{{ $stage->stage_name }}</h3>
                            <!--<div class="journey-stage__meta">
                                {{ $stage->levels->count() }} Level
                            </div>-->
                            @if($isCurrent)
                                <span class="journey-stage__badge-current">AKTIF</span>
                            @elseif($isCompleted)
                                <span class="journey-stage__badge-done">SELESAI</span>
                            @elseif(!$isUnlocked)
                                <span class="journey-stage__badge-locked">TERKUNCI</span>
                            @endif
                        </div>

                        @if(!empty($stage->description))
                        <p class="journey-stage__desc">
                            {{ $stage->description }}
                        </p>
                    @endif

                        @if($isUnlocked)
                            <div class="journey-stage__progress">
                                <div class="journey-stage__progress-bar">
                                    <div class="journey-stage__progress-fill"
                                         style="width: {{ min(100, $progress) }}%">
                                    </div>
                                </div>
                                <span class="journey-stage__progress-text">{{ $progress }}%</span>
                            </div>

                            <a href="{{ route('user.level', $stage->levels->first()?->id_level ?? '#') }}"
                               class="journey-stage__btn">
                                {{ $isCurrent ? 'Lanjutkan Belajar' : ($isCompleted ? 'Ulangi Materi' : 'Masuk Stage') }}
                                <span>→</span>
                            </a>
                        @else
                            <p class="journey-stage__lock-msg">
                                Selesaikan stage sebelumnya untuk membuka
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    {{-- ============================================
         SECTION 4: ACHIEVEMENT GALLERY
    ============================================ --}}
    <section class="achievement-gallery">
        <div class="achievement-gallery__header">
            <h2 class="achievement-gallery__title">
                <span class="achievement-gallery__icon">🏅</span>
                Koleksi Badge
            </h2>
            <span class="achievement-gallery__count">
                {{ Auth::user()->badges->count() ?? 0 }} Badge Diperoleh
            </span>
        </div>

        <div class="achievement-gallery__grid">
            {{-- Badge yang sudah didapat --}}
            @foreach(Auth::user()->badges as $badge)
                <div class="achievement-card achievement-card--unlocked">
                    <div class="achievement-card__icon">{{ $badge->icon }}</div>
                    <div class="achievement-card__info">
                        <h4 class="achievement-card__name">{{ $badge->name }}</h4>
                        <p class="achievement-card__desc">{{ $badge->description ?? 'Badge pencapaian' }}</p>
                    </div>
                </div>
            @endforeach

            @foreach($lockedBadges as $badge)
                <div class="achievement-card achievement-card--locked">
                    <div class="achievement-card__icon achievement-card__icon--locked">🔒</div>
                    <div class="achievement-card__info">
                        <h4 class="achievement-card__name">{{ $badge->name }}</h4>
                        <p class="achievement-card__desc">{{ $badge->description ?? 'Terus belajar untuk membuka' }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    {{-- ============================================
         SECTION 5: LEADERBOARD BANNER
    ============================================ --}}
    <section class="leaderboard-banner">
        <div class="leaderboard-banner__content">
            <div class="leaderboard-banner__info">
                <h2 class="leaderboard-banner__title">
                    <span class="leaderboard-banner__icon">🏆</span>
                    Papan Peringkat
                </h2>
                <p class="leaderboard-banner__desc">
                    Bersaing sehat dengan teman-temanmu! Lihat posisi kamu di leaderboard.
                </p>

            </div>
            <a href="{{ route('user.leaderboard') }}" class="leaderboard-banner__btn">
                <span>Lihat Peringkat</span>
                <span class="leaderboard-banner__btn-arrow">→</span>
            </a>
        </div>
        <div class="leaderboard-banner__decoration">
            <span class="leaderboard-banner__emoji">🥇</span>
            <span class="leaderboard-banner__emoji">🥈</span>
            <span class="leaderboard-banner__emoji">🥉</span>
        </div>
    </section>

</div>


{{-- ✅ CONFETTI ANIMATION (jika ada achievement baru) --}}
@if(session('badge_earned') || session('level_up'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colors = ['#C9A961', '#8A9A66', '#8B6B47', '#A85D4A'];
    for(let i = 0; i < 30; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'confetti';
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.background = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.animationDelay = Math.random() * 2 + 's';
        document.body.appendChild(confetti);
        setTimeout(() => confetti.remove(), 3000);
    }
});
</script>
@endif

@if(session('badge_popup'))

<div id="badgePopup" class="badge-popup-overlay">

    <div class="badge-popup-card">

        <div class="badge-popup-icon">
            {{ session('badge_popup.icon') }}
        </div>

        <h2>Badge Baru Terbuka!</h2>

        <p>
            {{ session('badge_popup.name') }}
        </p>

        <button onclick="closeBadgePopup()">
            Lanjutkan
        </button>

    </div>

</div>

<script>

function closeBadgePopup()
{
    document.getElementById('badgePopup').remove();
}

</script>

@endif
@endsection