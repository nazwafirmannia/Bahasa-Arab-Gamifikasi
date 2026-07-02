@extends('layouts.app')
@section('title', $level->title_level)
@section('page-title', '📚 ' . $level->title_level)

@section('content')
<div class="level-page">

    {{-- ============================================
         BREADCRUMB
    ============================================ --}}
    <nav class="level-breadcrumb">
        <a href="{{ route('user.dashboard') }}" class="level-breadcrumb__link">
            <i class="fas fa-home"></i>
            <span>Akademi Bahasa Arab</span>
        </a>
        <i class="fas fa-chevron-right level-breadcrumb__sep"></i>
        <span class="level-breadcrumb__item">{{ $level->stage->stage_name }}</span>
        <i class="fas fa-chevron-right level-breadcrumb__sep"></i>
        <span class="level-breadcrumb__item level-breadcrumb__item--active">{{ $level->title_level }}</span>
    </nav>

    @php
    $stageLevels = $level->stage->levels;
    @endphp

<section class="level-progression">
    <div class="level-progression__header">
        <h2>🗺️ Progress Stage</h2>
        <span>{{ $level->stage->stage_name }}</span>
    </div>
    <div class="level-progression__timeline">
        @foreach($stageLevels as $stageLevel)
        @php

        $status = 'locked';
        
        if($stageLevel->level_order == 1){
            $status = 'unlocked';
        }
        else{
        
            $previousLevel = $stageLevels
                ->where('level_order', $stageLevel->level_order - 1)
                ->first();
        
            if($previousLevel){
                $previousQuiz = $previousLevel->quiz;
                $passedQuiz = false;
                if($previousQuiz){
                    $passedQuiz = \App\Models\RiwayatKuis::where(
                        'id_user',
                        Auth::id()
                    )
                    ->where('id_quiz', $previousQuiz->id_quiz)
                    ->where('status', 'lulus')
                    ->exists();
                }
        
                if($passedQuiz){
                    $status = 'unlocked';
                }
            }
        }
        
        $currentQuiz = $stageLevel->quiz;
        if($currentQuiz){
            $isCompleted = \App\Models\RiwayatKuis::where(
                'id_user',
                Auth::id()
            )
            ->where('id_quiz', $currentQuiz->id_quiz)
            ->where('status', 'lulus')
            ->exists();
        
            if($isCompleted){
                $status = 'completed';
            }
        }
        
        if($stageLevel->id_level == $level->id_level){
            $status = 'current';
        }
        
        @endphp

@if($status != 'locked')
<a href="{{ route('user.level', $stageLevel->id_level) }}"
   class="level-node level-node--{{ $status }}">
@else
<div class="level-node level-node--locked">
@endif

    <div class="level-node__circle">
        @if($status == 'completed')
        ✅
        @elseif($status == 'current')
        📖
        @elseif($status == 'unlocked')
        🔓
    @else
        🔒
    @endif
    </div>

    <div class="level-node__content">
        <h4>{{ $stageLevel->title_level }}</h4>

        @if($status == 'completed')
        <span>Selesai</span>
        @elseif($status == 'current')
        <span>Sedang Dipelajari</span>
        @elseif($status == 'unlocked')
        <span>Terbuka</span>
        @else
        <span>Terkunci</span>
    @endif
    </div>

@if($status != 'locked')
</a>
@else
</div>
@endif
        @endforeach
    </div>
</div>
</section>


    {{-- ============================================
         LEVEL HERO
    ============================================ --}}
    <section class="level-hero">
        <div class="level-hero__content">
            <div class="level-hero__info">
                <span class="level-hero__eyebrow">
                    <i class="fas fa-book-open"></i>
                    {{ $level->stage->stage_name }}
                </span>
                <h1 class="level-hero__title">{{ $level->title_level }}</h1>
                <p class="level-hero__desc">
                    {{ $level->description ?? 'Pelajari materi bahasa Arab sesuai kurikulum standar.' }}
                </p>

                <div class="level-hero__meta">
                    <span class="level-hero__badge">
                        <span class="level-hero__badge-icon">📚</span>
                        {{ $level->materials->count() }} Materi
                    </span>
                    <span class="level-hero__badge">
                        <span class="level-hero__badge-icon">🎯</span>
                        Quiz Evaluasi
                    </span>
                    @if($level->textbook_reference)
                    <span class="level-hero__badge">
                        <span class="level-hero__badge-icon">📖</span>
                        {{ str_replace('_', ' ', ucfirst($level->textbook_reference)) }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="level-hero__progress-ring-wrapper">
                <div class="level-hero__progress-ring">
                    <svg class="level-hero__progress-svg" viewBox="0 0 64 64">
                        <circle class="level-hero__progress-bg" cx="32" cy="32" r="28"/>
                        <circle class="level-hero__progress-fg" cx="32" cy="32" r="28"
                                stroke-dasharray="{{ 2 * 3.14 * 28 }}"
                                stroke-dashoffset="{{ 2 * 3.14 * 28 * (1 - ($level->progress_percent ?? 0) / 100) }}"/>
                    </svg>
                    <div class="level-hero__progress-ring-label">
                        <span class="level-hero__progress-ring-value">{{ $level->progress_percent ?? 0 }}</span>
                        <span class="level-hero__progress-ring-unit">%</span>
                    </div>
                </div>
                <span class="level-hero__progress-ring-caption">Progress Level</span>
            </div>
        </div>

        @php
            $selesai = $level->materials->filter(function($m) {
                return $m->progress->where('id_user', auth()->id())->where('is_selesai', true)->isNotEmpty();
            })->count();
            $total = $level->materials->count();
            $persen = $total > 0 ? round(($selesai / $total) * 100) : 0;
        @endphp
        <div class="level-hero__bar-wrapper">
            <div class="level-hero__bar-info">
                <span class="level-hero__bar-label">
                    <i class="fas fa-check-circle"></i> {{ $selesai }} Materi Selesai
                </span>
                <span class="level-hero__bar-total">{{ $total }} Total Materi</span>
            </div>
            <div class="level-hero__bar">
                <div class="level-hero__bar-fill" style="width: {{ $persen }}%">
                    <span class="level-hero__bar-shine"></span>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         MATERIALS LIST
    ============================================ --}}
    <section class="level-materials">
        <div class="level-materials__header">
            <h2 class="level-materials__title">
                <span class="level-materials__title-icon">📋</span>
                Daftar Materi
            </h2>
            <span class="level-materials__count">{{ $level->materials->count() }} Modul</span>
        </div>

        <div class="level-materials__grid">
            @forelse($level->materials->sortBy('order') as $material)
            @php
                $progress = $material->progress->where('id_user', Auth::id())->first();
                if (!$progress) {
                    $progress = \App\Models\UserMaterialProgress::where('id_user', Auth::id())
                        ->where('id_material', $material->id_material)->first();
                }
                $isCompleted = $progress?->is_selesai ?? false;
            @endphp

            <a href="{{ route('user.material', $material->id_material) }}"
               class="level-material-card {{ $isCompleted ? 'level-material-card--completed' : '' }}">

                <div class="level-material-card__top">
                    <div class="level-material-card__number-wrap">
                        <span class="level-material-card__number">{{ $loop->iteration }}</span>
                    </div>
                    <div class="level-material-card__title-wrap">
                        <h3 class="level-material-card__title">{{ $material->title }}</h3>
                        @if($isCompleted)
                            <span class="level-material-card__status">
                                <i class="fas fa-check-circle"></i> Selesai
                            </span>
                        @else
                            <span class="level-material-card__status level-material-card__status--pending">
                                <i class="far fa-circle"></i> Belum dimulai
                            </span>
                        @endif
                    </div>
                </div>

                <p class="level-material-card__desc">
                    {{ Str::limit(strip_tags($material->vocab_content ?? $material->grammar_content ?? $material->dialog_content ?? ''), 120) }}
                </p>

                <div class="level-material-card__footer">
                    <span class="level-material-card__questions">
                        <i class="fas fa-question-circle"></i>
                        {{ optional($material->questions)->count() ?? 0 }} Soal
                    </span>
                    <span class="level-material-card__action">
                        {{ $isCompleted ? 'Ulangi' : 'Mulai' }}
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </div>

                @if($isCompleted)
                    <div class="level-material-card__ribbon">✓</div>
                @endif
            </a>
            @empty
            <div class="level-materials__empty">
                <div class="level-materials__empty-icon">📭</div>
                <p class="level-materials__empty-text">Belum ada materi untuk level ini.</p>
            </div>
            @endforelse
        </div>
    </section>


    {{-- ============================================
         FINAL CHALLENGE (Quiz)
    ============================================ --}}
    @if(($level->progress_percent ?? 0) == 100)
    @php
        $quiz = $level->quiz()->where('status', 'active')->first();
        $lastAttempt = $quiz ? $quiz->attempts()->where('id_user', Auth::id())->orderBy('attempt_number', 'desc')->first() : null;
    @endphp
    @if($quiz)
    <section class="final-challenge">
        <div class="final-challenge__glow"></div>
        <div class="final-challenge__content">
            <div class="final-challenge__info">
                <span class="final-challenge__badge">🏆 FINAL CHALLENGE</span>
                <h3 class="final-challenge__title">Quiz Evaluasi Level</h3>
                <p class="final-challenge__desc">
                    Uji pemahamanmu sebelum lanjut ke level berikutnya!
                </p>

                @if($lastAttempt)
                <div class="final-challenge__attempt">
                    <div class="final-challenge__attempt-icon">
                        {{ $lastAttempt->status === 'lulus' ? '✅' : '❌' }}
                    </div>
                    <div class="final-challenge__attempt-info">
                        <span class="final-challenge__attempt-label">Attempt Terakhir</span>
                        <span class="final-challenge__attempt-score">
                            {{ $lastAttempt->score }}%
                            <span class="final-challenge__attempt-status">
                                ({{ $lastAttempt->status === 'lulus' ? 'Lulus' : 'Gagal' }})
                            </span>
                        </span>
                    </div>
                </div>
                @endif
            </div>

            <div class="final-challenge__action">
                @if(!$lastAttempt || $lastAttempt->status === 'gagal')
                <a href="{{ route('user.level.quiz', ['levelId' => $level->id_level, 'quizId' => $quiz->id_quiz]) }}"
                   class="final-challenge__btn final-challenge__btn--primary">
                    <i class="fas fa-play"></i>
                    <span>Mulai Quiz</span>
                </a>
                @else
                <a href="{{ route('user.level.result', ['levelId' => $level->id_level, 'quizId' => $quiz->id_quiz]) }}"
                   class="final-challenge__btn final-challenge__btn--success">
                    <i class="fas fa-trophy"></i>
                    <span>Lihat Hasil</span>
                </a>
                @endif
            </div>
        </div>
    </section>
    @endif
    @endif


    {{-- ============================================
         FOOTER
    ============================================ --}}
    <div class="level-footer">
        <a href="{{ route('user.dashboard') }}" class="level-footer__link">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Akademi Bahasa Arab</span>
        </a>
    </div>

</div>
@endsection