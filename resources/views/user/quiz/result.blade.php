@extends('layouts.app')
@section('title', 'Hasil Quiz Evaluasi')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/quiz-result.css') }}">
<style>
    #sidebar { display: none !important; }
    .flex.h-screen { overflow: hidden; }
    .flex-1 { width: 100% !important; }
    main { padding: 0 !important; max-width: 100% !important; }
</style>
@endpush

@php
    $isPassed = $result->status === 'lulus';
    $score = $result->score;
    $passingScore = $quiz->passing_score;
@endphp

<div class="quiz-result-page {{ $isPassed ? 'quiz-result-page--passed' : 'quiz-result-page--failed' }}">

    {{-- ============================================
         DECORATIVE PARTICLES (Lulus saja)
    ============================================ --}}
    @if($isPassed)
    <div class="quiz-result-page__particles" aria-hidden="true">
        @for($i = 0; $i < 12; $i++)
            <span class="quiz-result-page__particle" style="--i: {{ $i }};"></span>
        @endfor
    </div>
    @endif


    {{-- ============================================
         RESULT HERO
    ============================================ --}}
    <section class="result-hero">
        <div class="result-hero__glow" aria-hidden="true"></div>

        {{-- Badge Status --}}
        <div class="result-hero__badge {{ $isPassed ? 'result-hero__badge--success' : 'result-hero__badge--failed' }}">
            @if($isPassed)
                <i class="fas fa-trophy" aria-hidden="true"></i>
                <span>QUEST COMPLETED</span>
            @else
                <i class="fas fa-shield-alt" aria-hidden="true"></i>
                <span>QUEST FAILED</span>
            @endif
        </div>

        {{-- Icon Besar --}}
        <div class="result-hero__icon {{ $isPassed ? 'result-hero__icon--success' : 'result-hero__icon--failed' }}" aria-hidden="true">
            @if($isPassed)
                🏆
            @else
                ⚔️
            @endif
        </div>

        {{-- Title --}}
        <h1 class="result-hero__title">
            {{ $isPassed ? 'Selamat! Kamu Lulus!' : 'Belum Lulus' }}
        </h1>

        {{-- Subtitle --}}
        <p class="result-hero__subtitle">
            Quiz Evaluasi telah selesai.
        </p>
    </section>


    {{-- ============================================
         SCORE CIRCLE
    ============================================ --}}
    <section class="result-score-section">
        <div class="result-score-circle {{ $isPassed ? 'result-score-circle--passed' : 'result-score-circle--failed' }}"
             aria-label="Skor {{ $score }} persen">

            @php
                $radius = 80;
                $circumference = 2 * pi() * $radius;
            @endphp

            <svg class="result-score-circle__svg" viewBox="0 0 200 200">
                <circle class="result-score-circle__bg" cx="100" cy="100" r="{{ $radius }}"/>
                <circle class="result-score-circle__fg"
                        cx="100" cy="100" r="{{ $radius }}"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $circumference }}"
                        id="score-ring"/>
            </svg>

            <div class="result-score-circle__content">
                <span class="result-score-circle__value" id="score-value">{{ $score }}</span>
                <span class="result-score-circle__unit">%</span>
            </div>
        </div>

        <p class="result-score-section__label">
            Skor Kamu
        </p>
    </section>


    {{-- ============================================
         STATISTIC CARDS
    ============================================ --}}
    <section class="result-stats">


        @if($isPassed)
        <div class="result-stat result-stat--xp">
            <div class="result-stat__icon">
                <i class="fas fa-star" aria-hidden="true"></i>
            </div>
            <div class="result-stat__info">
                <span class="result-stat__label">XP Reward</span>
                <span class="result-stat__value">+100 XP</span>
            </div>
        </div>
        @else
        <div class="result-stat result-stat--target">
            <div class="result-stat__icon">
                <i class="fas fa-bullseye" aria-hidden="true"></i>
            </div>
            <div class="result-stat__info">
                <span class="result-stat__label">Target Lulus</span>
                <span class="result-stat__value">{{ $passingScore }}%</span>
            </div>
        </div>
        @endif

    </section>


    {{-- ============================================
         RESULT MESSAGE
    ============================================ --}}
    @if($isPassed)
    <section class="result-message result-message--success">
        <div class="result-message__icon" aria-hidden="true">🎁</div>
        <div class="result-message__content">
            <h3 class="result-message__title">Kamu mendapatkan <strong>+100 XP</strong>!</h3>
            <p class="result-message__text">✅ Level berikutnya sudah terbuka!</p>
        </div>
    </section>
    @else
    <section class="result-message result-message--failed">
        <div class="result-message__icon" aria-hidden="true">⚠️</div>
        <div class="result-message__content">
            <h3 class="result-message__title">
                Kamu perlu skor minimal <strong>{{ $passingScore }}%</strong> untuk lulus.
            </h3>
            <p class="result-message__text">Coba pelajari materi lagi ya!</p>
        </div>
    </section>
    @endif


    {{-- ============================================
         ACTION BUTTONS
    ============================================ --}}
    <section class="result-actions">
        @if($isPassed)
            @php
                $nextLevel = \App\Models\Level::where('id_stage', $quiz->level->id_stage)
                    ->where('level_order', '>', $quiz->level->level_order)
                    ->orderBy('level_order')
                    ->first();
            @endphp

            @if($nextLevel)
            <a href="{{ route('user.level', $nextLevel->id_level) }}"
               class="result-actions__btn result-actions__btn--primary">
                <i class="fas fa-arrow-right" aria-hidden="true"></i>
                <span>Lanjut ke Level {{ $nextLevel->level_order }}</span>
            </a>
            @else
            <a href="{{ route('user.dashboard') }}"
               class="result-actions__btn result-actions__btn--primary">
                <i class="fas fa-trophy" aria-hidden="true"></i>
                <span>Kembali ke Dashboard</span>
            </a>
            @endif

            <a href="{{ route('user.level', $quiz->level->id_level) }}"
               class="result-actions__btn result-actions__btn--secondary">
                <i class="fas fa-book" aria-hidden="true"></i>
                <span>Lihat Materi</span>
            </a>

        @else
            <a href="{{ route('user.level', $quiz->level->id_level) }}"
               class="result-actions__btn result-actions__btn--secondary">
                <i class="fas fa-book" aria-hidden="true"></i>
                <span>Ulangi Materi</span>
            </a>

            <a href="{{ route('user.level.quiz', [
    'levelId' => $quiz->level->id_level,
    'quizId' => $quiz->id_quiz
]) }}"
class="result-actions__btn result-actions__btn--primary">

    <i class="fas fa-redo"></i>
    <span>Coba Lagi</span>

</a>
        @endif
    </section>

</div>


@push('scripts')
@if($isPassed)
<script>
/* ===========================================================
   QUIZ RESULT — Confetti Animation (Pure JS)
   Hanya muncul saat halaman berhasil dimuat dan status = lulus
   =========================================================== */
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // ANIMATE SCORE COUNTER
    // ============================================
    const targetScore = Math.min(Math.max({{ $score }}, 0),100);  
    const scoreValueEl = document.getElementById('score-value');
    const scoreRingEl = document.getElementById('score-ring');

    if (scoreValueEl && scoreRingEl) {
        const duration = 1800;
        const startTime = performance.now();
        const circumference = 2 * Math.PI * 80;

        function animateScore(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            // Easing: ease-out cubic
            const eased = 1 - Math.pow(1 - progress, 3);

            const currentValue = Math.round(eased * targetScore);
            scoreValueEl.textContent = currentValue;

            const offset = circumference * (1 - (eased * targetScore) / 100);
            scoreRingEl.style.strokeDashoffset = offset;

            if (progress < 1) {
                requestAnimationFrame(animateScore);
            }
        }

        requestAnimationFrame(animateScore);
    }

    // ============================================
    // CONFETTI EFFECT
    // ============================================
    const colors = ['#FBBF24', '#775537', '#5C4028', '#F59E0B', '#10B981'];
    const confettiCount = 60;

    for (let i = 0; i < confettiCount; i++) {
        setTimeout(() => {
            const confetti = document.createElement('div');
            confetti.className = 'quiz-result-confetti';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
            confetti.style.animationDuration = (Math.random() * 2 + 2.5) + 's';
            confetti.style.animationDelay = (Math.random() * 0.5) + 's';
            confetti.style.width = (Math.random() * 8 + 6) + 'px';
            confetti.style.height = (Math.random() * 8 + 6) + 'px';
            document.body.appendChild(confetti);

            setTimeout(() => confetti.remove(), 5000);
        }, i * 40);
    }
});
</script>
@else
<script>
/* ===========================================================
   QUIZ RESULT — Score Animation (Failed State)
   =========================================================== */
document.addEventListener('DOMContentLoaded', function() {
    const targetScore = {{ $score }};
    const scoreValueEl = document.getElementById('score-value');
    const scoreRingEl = document.getElementById('score-ring');

    if (scoreValueEl && scoreRingEl) {
        const duration = 1500;
        const startTime = performance.now();
        const circumference = 2 * Math.PI * 80;

        function animateScore(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);

            const currentValue = Math.round(eased * targetScore);
            scoreValueEl.textContent = currentValue;

            const offset = circumference * (1 - (eased * targetScore) / 100);
            scoreRingEl.style.strokeDashoffset = offset;

            if (progress < 1) {
                requestAnimationFrame(animateScore);
            }
        }

        requestAnimationFrame(animateScore);
    }
});
</script>
@endif
@endpush
@endsection