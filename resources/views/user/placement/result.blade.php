@extends('layouts.placement')
@section('title', 'Hasil Placement')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/placement-result.css') }}">
@endpush

<div class="result-page">

    {{-- ============================================
         BACKGROUND EFFECTS
    ============================================ --}}
    <div class="result-page__pattern" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="resultPattern" x="0" y="0" width="120" height="120" patternUnits="userSpaceOnUse">
                    <path d="M60 0 L120 60 L60 120 L0 60 Z" fill="none" stroke="rgba(251, 191, 36, 0.08)" stroke-width="1"/>
                    <circle cx="60" cy="60" r="25" fill="none" stroke="rgba(119, 85, 55, 0.06)" stroke-width="1"/>
                    <path d="M60 35 L85 60 L60 85 L35 60 Z" fill="none" stroke="rgba(251, 191, 36, 0.06)" stroke-width="1"/>
                    <circle cx="60" cy="60" r="10" fill="none" stroke="rgba(119, 85, 55, 0.04)" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#resultPattern)"/>
        </svg>
    </div>

    <div class="result-page__particles" aria-hidden="true">
        @for($i = 0; $i < 25; $i++)
            <span class="result-page__particle" style="--i: {{ $i }};"></span>
        @endfor
    </div>

    <div class="result-page__glow result-page__glow--1" aria-hidden="true"></div>
    <div class="result-page__glow result-page__glow--2" aria-hidden="true"></div>
    <div class="result-page__glow result-page__glow--3" aria-hidden="true"></div>


    {{-- ============================================
         QUEST COMPLETE BADGE
    ============================================ --}}
    <div class="quest-complete-badge" aria-label="Quest Complete">
        <i class="fas fa-flag-checkered" aria-hidden="true"></i>
        <span>QUEST COMPLETE</span>
    </div>


    {{-- ============================================
         MAIN RESULT CARD
    ============================================ --}}
    <div class="result-card fade-in">

        {{-- ============================================
             ACHIEVEMENT HERO AREA
        ============================================ --}}
        <section class="result-hero" aria-label="Achievement">
            <div class="result-hero__glow" aria-hidden="true"></div>

            <div class="result-hero__icon-wrapper {{ $stageColor }}">
                <div class="result-hero__icon-ring" aria-hidden="true"></div>
                <div class="result-hero__icon-ring result-hero__icon-ring--outer" aria-hidden="true"></div>
                <div class="result-hero__icon {{ $stageBg }}">
                    <span>{{ $stageIcon }}</span>
                </div>
                <div class="result-hero__sparkles" aria-hidden="true">
                    <span class="sparkle sparkle--1">✨</span>
                    <span class="sparkle sparkle--2">⭐</span>
                    <span class="sparkle sparkle--3">✨</span>
                    <span class="sparkle sparkle--4">💫</span>
                </div>
            </div>

            <h1 class="result-hero__title">{{ $statusTitle }}</h1>
            <p class="result-hero__desc">{{ $statusDesc }}</p>
        </section>


        {{-- ============================================
             SCORE SHOWCASE
        ============================================ --}}
        <section class="result-score" aria-label="Placement Score">
            <div class="result-score__label">
                <span class="result-score__label-dot"></span>
                <span>PLACEMENT SCORE</span>
            </div>

            <div class="result-score__circle">
                <svg class="result-score__svg" viewBox="0 0 200 200">
                    <circle class="result-score__bg" cx="100" cy="100" r="85"/>
                    <circle class="result-score__fg {{ $stageBar }}"
                            cx="100" cy="100" r="85"
                            stroke-dasharray="534.07"
                            stroke-dashoffset="534.07"
                            id="score-ring"/>
                </svg>

                <div class="result-score__content">
                    <span class="result-score__value {{ $stageTextColor }}" id="score-value">0</span>
                    <span class="result-score__unit">%</span>
                </div>
            </div>

            <p class="result-score__subtitle">
                Kemampuan awal Bahasa Arab kamu berada pada level ini.
            </p>
        </section>


        {{-- ============================================
             STAGE UNLOCK CARD
        ============================================ --}}
        <section class="result-unlock" aria-label="Stage Unlocked">
            <div class="result-unlock__shine" aria-hidden="true"></div>

            <div class="result-unlock__header">
                <span class="result-unlock__lock-icon" aria-hidden="true">🔓</span>
                <span class="result-unlock__label">STAGE UNLOCKED</span>
            </div>

            <div class="result-unlock__content">
                <div class="result-unlock__stage-icon">
                    <span>{{ $stageIcon }}</span>
                </div>
                <div class="result-unlock__info">
                    <h2 class="result-unlock__stage-name">{{ $stageName }}</h2>
                    <p class="result-unlock__stage-equiv">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                        <span>Setara Kelas {{ $kelasEquivalent }}</span>
                    </p>
                </div>
            </div>

            <div class="result-unlock__badge {{ $stageBadge }}">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                <span>Stage Aktif & Siap Dimulai</span>
            </div>
        </section>


        {{-- ============================================
             LEARNING JOURNEY TIMELINE
        ============================================ --}}
        <section class="result-journey" aria-label="Learning Journey">
            <div class="result-journey__header">
                <span class="result-journey__icon" aria-hidden="true">🗺️</span>
                <h2 class="result-journey__title">Petualangan Belajarmu Dimulai</h2>
            </div>

            <div class="result-journey__timeline">
                <div class="result-journey__line" aria-hidden="true"></div>

                <div class="result-journey__step result-journey__step--done">
                    <div class="result-journey__node">
                        <i class="fas fa-check" aria-hidden="true"></i>
                    </div>
                    <div class="result-journey__info">
                        <span class="result-journey__step-label">SELESAI</span>
                        <h3 class="result-journey__step-title">Placement Test</h3>
                    </div>
                </div>

                <div class="result-journey__step result-journey__step--active">
                    <div class="result-journey__node">
                        <span>{{ $stageIcon }}</span>
                    </div>
                    <div class="result-journey__info">
                        <span class="result-journey__step-label">AKTIF SEKARANG</span>
                        <h3 class="result-journey__step-title">Stage Terpilih</h3>
                    </div>
                </div>

                <div class="result-journey__step">
                    <div class="result-journey__node">
                        <i class="fas fa-book" aria-hidden="true"></i>
                    </div>
                    <div class="result-journey__info">
                        <span class="result-journey__step-label">BERIKUTNYA</span>
                        <h3 class="result-journey__step-title">Belajar Materi</h3>
                    </div>
                </div>

                <div class="result-journey__step">
                    <div class="result-journey__node">
                        <i class="fas fa-trophy" aria-hidden="true"></i>
                    </div>
                    <div class="result-journey__info">
                        <span class="result-journey__step-label">NANTI</span>
                        <h3 class="result-journey__step-title">Quiz Level</h3>
                    </div>
                </div>

                <div class="result-journey__step result-journey__step--final">
                    <div class="result-journey__node">
                        <i class="fas fa-crown" aria-hidden="true"></i>
                    </div>
                    <div class="result-journey__info">
                        <span class="result-journey__step-label">TUJUAN</span>
                        <h3 class="result-journey__step-title">Naik Level</h3>
                    </div>
                </div>
            </div>
        </section>


        {{-- ============================================
             BREAKDOWN SECTION
        ============================================ --}}
        @if(isset($breakdown))
        <section class="result-breakdown" aria-label="Answer Breakdown">
            <div class="result-breakdown__header">
                <span class="result-breakdown__icon" aria-hidden="true">📊</span>
                <h2 class="result-breakdown__title">Detail Jawaban</h2>
            </div>

            <div class="result-breakdown__grid">
                {{-- Easy --}}
                <div class="breakdown-card breakdown-card--easy">
                    <div class="breakdown-card__header">
                        <span class="breakdown-card__emoji">🟢</span>
                        <span class="breakdown-card__level">EASY</span>
                    </div>
                    <div class="breakdown-card__score">
                        <span class="breakdown-card__correct">{{ $breakdown['easy'] }}</span>
                        <span class="breakdown-card__divider">/</span>
                        <span class="breakdown-card__total">5</span>
                    </div>
                    <div class="breakdown-card__bar">
                        <div class="breakdown-card__bar-fill breakdown-card__bar-fill--easy"
                             style="--bar-width: {{ ($breakdown['easy'] / 5) * 100 }}%;"></div>
                    </div>
                    <span class="breakdown-card__label">Benar</span>
                </div>

                {{-- Medium --}}
                <div class="breakdown-card breakdown-card--medium">
                    <div class="breakdown-card__header">
                        <span class="breakdown-card__emoji">🟡</span>
                        <span class="breakdown-card__level">MEDIUM</span>
                    </div>
                    <div class="breakdown-card__score">
                        <span class="breakdown-card__correct">{{ $breakdown['medium'] }}</span>
                        <span class="breakdown-card__divider">/</span>
                        <span class="breakdown-card__total">5</span>
                    </div>
                    <div class="breakdown-card__bar">
                        <div class="breakdown-card__bar-fill breakdown-card__bar-fill--medium"
                             style="--bar-width: {{ ($breakdown['medium'] / 5) * 100 }}%;"></div>
                    </div>
                    <span class="breakdown-card__label">Benar</span>
                </div>

                {{-- Hard --}}
                <div class="breakdown-card breakdown-card--hard">
                    <div class="breakdown-card__header">
                        <span class="breakdown-card__emoji">🔴</span>
                        <span class="breakdown-card__level">HARD</span>
                    </div>
                    <div class="breakdown-card__score">
                        <span class="breakdown-card__correct">{{ $breakdown['hard'] }}</span>
                        <span class="breakdown-card__divider">/</span>
                        <span class="breakdown-card__total">5</span>
                    </div>
                    <div class="breakdown-card__bar">
                        <div class="breakdown-card__bar-fill breakdown-card__bar-fill--hard"
                             style="--bar-width: {{ ($breakdown['hard'] / 5) * 100 }}%;"></div>
                    </div>
                    <span class="breakdown-card__label">Benar</span>
                </div>
            </div>
        </section>
        @endif


        {{-- ============================================
             REWARD SECTION
        ============================================ --}}
        <section class="result-rewards" aria-label="Starter Rewards">
            <div class="result-rewards__header">
                <span class="result-rewards__icon" aria-hidden="true">🎁</span>
                <h2 class="result-rewards__title">Bonus Awal untuk Petualang Baru</h2>
            </div>

            <div class="result-rewards__grid">
                <div class="reward-card reward-card--coin">
                    <div class="reward-card__icon-wrap">
                        <span class="reward-card__icon">🪙</span>
                    </div>
                    <div class="reward-card__info">
                        <span class="reward-card__label">50 Coin</span>
                        <span class="reward-card__desc">Bonus Awal</span>
                    </div>
                </div>

                <div class="reward-card reward-card--badge">
                    <div class="reward-card__icon-wrap">
                        <span class="reward-card__icon">⭐</span>
                    </div>
                    <div class="reward-card__info">
                        <span class="reward-card__label">Starter Badge</span>
                        <span class="reward-card__desc">Pencapaian Pertama</span>
                    </div>
                </div>

                <div class="reward-card reward-card--xp">
                    <div class="reward-card__icon-wrap">
                        <span class="reward-card__icon">✨</span>
                    </div>
                    <div class="reward-card__info">
                        <span class="reward-card__label">100 XP</span>
                        <span class="reward-card__desc">Quest Bonus</span>
                    </div>
                </div>
            </div>
        </section>


        {{-- ============================================
             CTA AREA
        ============================================ --}}
        <section class="result-cta" aria-label="Continue Adventure">
            <a href="{{ route('user.dashboard') }}" class="result-cta__button">
                <span class="result-cta__icon" aria-hidden="true">
                    <i class="fas fa-rocket"></i>
                </span>
                <div class="result-cta__text">
                    <span class="result-cta__main">Mulai Belajar Sekarang</span>
                    <span class="result-cta__sub">Masuki dashboard dan lanjutkan petualangan Bahasa Arabmu</span>
                </div>
                <i class="fas fa-arrow-right result-cta__arrow" aria-hidden="true"></i>
            </a>
        </section>

    </div>


    {{-- ============================================
         MASCOT
    ============================================ --}}
    <div class="result-mascot" aria-hidden="true">
        <div class="result-mascot__bubble">
            <span class="result-mascot__name">Mentor ArabicQuest</span>
            <p class="result-mascot__text">Hebat! Aku sudah menyiapkan materi yang sesuai dengan kemampuanmu. Ayo mulai petualanganmu!</p>
        </div>
        <div class="result-mascot__avatar">
            <span>🧕</span>
            <div class="result-mascot__glow"></div>
        </div>
    </div>

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetScore = {{ (int) $score }};
    const scoreValue = document.getElementById('score-value');
    const scoreRing = document.getElementById('score-ring');

    if (scoreValue && scoreRing) {
        const circumference = 534.07;
        const duration = 1800;
        const startTime = performance.now();

        function animateScore(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);

            const currentValue = Math.round(eased * targetScore);
            scoreValue.textContent = currentValue;

            const offset = circumference * (1 - (eased * targetScore) / 100);
            scoreRing.style.strokeDashoffset = offset;

            if (progress < 1) {
                requestAnimationFrame(animateScore);
            }
        }

        requestAnimationFrame(animateScore);
    }

    // Celebration confetti for good scores
    if (targetScore >= 60) {
        const colors = ['#FBBF24', '#775537', '#F59E0B', '#10B981', '#8B5CF6'];
        const confettiCount = 30;

        for (let i = 0; i < confettiCount; i++) {
            setTimeout(() => {
                const confetti = document.createElement('div');
                confetti.className = 'result-confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDuration = (Math.random() * 2 + 2.5) + 's';
                confetti.style.animationDelay = (Math.random() * 0.5) + 's';
                confetti.style.width = (Math.random() * 8 + 6) + 'px';
                confetti.style.height = (Math.random() * 8 + 6) + 'px';
                document.body.appendChild(confetti);
                setTimeout(() => confetti.remove(), 5000);
            }, i * 50);
        }
    }
});
</script>
@endpush
@endsection