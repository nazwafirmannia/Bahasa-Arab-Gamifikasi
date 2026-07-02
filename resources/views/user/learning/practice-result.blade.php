@extends('layouts.app')
@section('title', 'Hasil Latihan')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/practice-result.css') }}">
<style>
    #sidebar { display: none !important; }
    .flex.h-screen { overflow: hidden; }
    .flex-1 { width: 100% !important; }
    main { padding: 2rem !important; max-width: 100% !important; }
</style>
@endpush

<div class="result-page">
    <!-- Review Mode Banner -->
    @if($result['is_review_mode'] ?? false)
    <div class="result-banner result-banner--review">
        <p class="result-banner__text">🔁 <strong>Mode Review:</strong> Jawaban tidak memengaruhi XP.</p>
    </div>
    @endif
    
    <!-- Result Header -->
    <div class="result-hero">
        <div class="result-hero__icon-wrapper {{ $result['score'] >= 70 ? 'result-hero__icon-wrapper--passed' : 'result-hero__icon-wrapper--failed' }}">
            @if($result['score'] >= 70) <i class="fas fa-trophy result-hero__icon"></i>
            @else <i class="fas fa-graduation-cap result-hero__icon"></i> @endif
        </div>
        
        <h1 class="result-hero__title">🎯 Hasil Latihan</h1>
        <p class="result-hero__subtitle">{{ $result['material_title'] }}</p>
        
        <!-- Score Circle -->
        <div class="result-hero__score-circle {{ $result['score'] >= 70 ? 'result-hero__score-circle--passed' : 'result-hero__score-circle--failed' }}">
            <span class="result-hero__score-value {{ $result['score'] >= 70 ? 'result-hero__score-value--passed' : 'result-hero__score-value--failed' }}">{{ $result['score'] }}%</span>
        </div>
        
        <!-- Stats -->
        <div class="result-hero__stats">
            <div class="result-hero__stat">
                <p class="result-hero__stat-value">{{ $result['correct_count'] }}/{{ $result['total_questions'] }}</p>
                <p class="result-hero__stat-label">Benar</p>
            </div>
            <div class="result-hero__stat result-hero__stat--xp">
                <p class="result-hero__stat-value result-hero__stat-value--xp">+{{ $result['xp_earned'] }}</p>
                <p class="result-hero__stat-label">XP</p>
            </div>

        </div>
    </div>

    <!-- Detailed Answer Review with Explanations -->
    <div class="result-review">
        <div class="result-review__header">
            <h3 class="result-review__title">📋 Penjelasan & Review Jawaban</h3>
        </div>
        <div class="result-review__list">
            @foreach($result['results'] as $res)
            @php
                $question = $questions->get($res['question_id']) ?? null;
                if (!$question) continue; 

                $options = [
                    'a' => $question->option_a,
                    'b' => $question->option_b,
                    'c' => $question->option_c,
                    'd' => $question->option_d,
                ];

                $userAns = $res['user_answer'];
                $correctAns = $res['correct_answer'];
                
                $userDisplay = $options[$userAns] ?? $userAns;
                $correctDisplay = $options[$correctAns] ?? $correctAns;
                
                $userLabel = in_array(strtolower($userAns), ['a','b','c','d']) ? strtoupper($userAns) . '. ' : '';
                $correctLabel = in_array(strtolower($correctAns), ['a','b','c','d']) ? strtoupper($correctAns) . '. ' : '';
            @endphp
            <div class="result-review__item {{ $res['is_correct'] ? 'result-review__item--correct' : 'result-review__item--wrong' }}">
                <div class="result-review__item-content">
                    <span class="result-review__item-number {{ $res['is_correct'] ? 'result-review__item-number--correct' : 'result-review__item-number--wrong' }}">
                        {{ $loop->iteration }}
                    </span>
                    <div class="result-review__item-body">
                        <p class="result-review__item-question">{!! $question->question_text !!}</p>
                        
                        <!-- User Answer -->
                        <div class="result-review__answer">
                            <span class="result-review__answer-label">Jawabanmu:</span>
                            <p class="result-review__answer-value {{ $res['is_correct'] ? 'result-review__answer-value--correct' : 'result-review__answer-value--wrong' }}">
                                {{ $userLabel }}{{ $userDisplay }}
                                @if(!$res['is_correct']) <i class="fas fa-times result-review__answer-icon result-review__answer-icon--wrong"></i>
                                @else <i class="fas fa-check result-review__answer-icon result-review__answer-icon--correct"></i> @endif
                            </p>
                        </div>
                        
                        <!-- Correct Answer (if wrong) -->
                        @if(!$res['is_correct'])
                        <div class="result-review__answer">
                            <span class="result-review__answer-label">Jawaban benar:</span>
                            <p class="result-review__answer-value result-review__answer-value--correct-answer">
                                {{ $correctLabel }}{{ $correctDisplay }} <i class="fas fa-check result-review__answer-icon result-review__answer-icon--correct"></i>
                            </p>
                        </div>
                        @endif
                        
                        <!-- ✅ EXPLANATION MATERI TETAP ADA -->
                        @if($res['explanation'])
                        <div class="result-review__explanation">
                            <p class="result-review__explanation-text">
                                <strong>💡 Penjelasan:</strong> {!! $res['explanation'] !!}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons (TOMBOL REVIEW SOAL SUDAH DIHAPUS) -->
    <div class="result-actions">
        <!-- Ulangi Materi -->
        <a href="{{ route('user.material', $result['material_id']) }}" 
           class="result-actions__btn result-actions__btn--secondary">
            <i class="fas fa-book"></i> <span>Ulangi Materi</span>
        </a>
        
        <!-- ✅ Lanjut ke Materi Berikutnya -->
        <a href="{{ route('user.material.next', $result['material_id']) }}" 
           class="result-actions__btn result-actions__btn--primary">
            <i class="fas fa-arrow-right"></i> <span>Lanjut ke Materi Berikutnya →</span>
        </a>
        
        <!-- Conditional: Lanjut Level (jika >=70) -->
        @if($result['score'] >= 70)
            <a href="{{ route('user.level', $material->level->id_level) }}" 
               class="result-actions__btn result-actions__btn--success">
                <i class="fas fa-arrow-right"></i> <span>Lanjut Level</span>
            </a>
        @endif
    </div>
</div>

@push('scripts')
<script>
@if($result['score'] >= 70)
// Simple confetti for passing
document.addEventListener('DOMContentLoaded', function() {
    for(let i = 0; i < 30; i++) {
        const confetti = document.createElement('div');
        confetti.className = 'fixed w-2 h-2 rounded-full';
        confetti.style.left = Math.random() * 100 + 'vw';
        confetti.style.top = '-10px';
        confetti.style.backgroundColor = ['#fbbf24', '#10b981', '#3b82f6'][Math.floor(Math.random() * 3)];
        confetti.style.animation = `fall ${Math.random() * 2 + 2}s linear forwards`;
        document.body.appendChild(confetti);
        setTimeout(() => confetti.remove(), 4000);
    }
    const style = document.createElement('style');
    style.textContent = `@keyframes fall { to { transform: translateY(100vh) rotate(360deg); opacity: 0; } }`;
    document.head.appendChild(style);
});
@endif
</script>

@if(!empty($result['badge_earned']))
setTimeout(() => {

    Swal.fire({
        title: '🏅 Badge Baru!',
        html: `
            <div style="font-size:70px">
                {{ $result['badge_earned']['icon'] }}
            </div>

            <h3>{{ $result['badge_earned']['name'] }}</h3>
        `,
        confirmButtonText: 'Mantap!'
    });

}, 1000);
@endif

@endpush
@endsection