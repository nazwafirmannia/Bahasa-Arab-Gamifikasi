@extends('layouts.app')
@section('title', 'Quiz Evaluasi - ' . $quiz->title)

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/quiz.css') }}">
<style>
    #sidebar { display: none !important; }
    .flex.h-screen { overflow: hidden; }
    .flex-1 { width: 100% !important; }
    main { padding: 0 !important; max-width: 100% !important; }
</style>
@endpush

<div class="quiz-page">

    {{-- ============================================
         QUEST HEADER (Hero Section)
    ============================================ --}}
    <header class="quest-header">


        <a href="{{ route('user.level', $level->id_level) }}" class="quest-header__back">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            <span>Kembali ke Level</span>
        </a>

        <div class="quest-header__content">
            <div class="quest-header__info">
                <div class="quest-header__badges">
                    <span class="quest-header__badge quest-header__badge--quest">
                        <i class="fas fa-scroll" aria-hidden="true"></i>
                        <span>QUEST CHALLENGE</span>
                    </span>
                    <span class="quest-header__badge quest-header__badge--level">
                        <i class="fas fa-layer-group" aria-hidden="true"></i>
                        <span>Level {{ $level->level_order ?? 1 }}</span>
                    </span>
                </div>

                <h1 class="quest-header__title">
                    <span class="quest-header__title-icon" aria-hidden="true">🎯</span>
                    <span>{{ $quiz->title }}</span>
                </h1>

                <p class="quest-header__subtitle">
                    Selesaikan {{ $questions->count() }} tantangan untuk membuktikan kemampuanmu!
                </p>
            </div>

            <div class="quest-header__stats">
                <div class="quest-header__stat">
                    <div class="quest-header__stat-icon">
                        <i class="fas fa-list-ol" aria-hidden="true"></i>
                    </div>
                    <div class="quest-header__stat-info">
                        <span class="quest-header__stat-label">Soal Saat Ini</span>
                        <span class="quest-header__stat-value">
                            <span id="current-q">1</span>
                            <span class="quest-header__stat-divider">/</span>
                            <span>{{ $questions->count() }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="quest-progress">
            <div class="quest-progress__header">
                <span class="quest-progress__label">
                    <i class="fas fa-route" aria-hidden="true"></i>
                    <span>Progress Quest</span>
                </span>
                <span class="quest-progress__percent">
                    <span id="progress-percent">0</span>%
                </span>
            </div>

            <div class="quest-progress__bar">
                <div id="progress-fill" class="quest-progress__fill" style="width: 0%">
                    <span class="quest-progress__shine" aria-hidden="true"></span>
                </div>
            </div>

            <div class="quest-progress__milestones" aria-hidden="true">
                <span class="quest-progress__milestone" style="left: 25%;">
                    <span class="quest-progress__milestone-dot"></span>
                </span>
                <span class="quest-progress__milestone" style="left: 50%;">
                    <span class="quest-progress__milestone-dot"></span>
                </span>
                <span class="quest-progress__milestone" style="left: 75%;">
                    <span class="quest-progress__milestone-dot"></span>
                </span>
                <span class="quest-progress__milestone quest-progress__milestone--final" style="left: 100%;">
                    <span class="quest-progress__milestone-dot"></span>
                    <span class="quest-progress__milestone-label">🏁</span>
                </span>
            </div>
        </div>
    </header>


    {{-- ============================================
         QUIZ FORM
    ============================================ --}}
    <form method="POST"
          action="{{ route('user.level.quiz.submit', ['levelId' => $level->id_level, 'quizId' => $quiz->id_quiz]) }}"
          id="quiz-form">
        @csrf

        @foreach($questions as $index => $item)
        <article class="question-card {{ $index !== 0 ? 'hidden' : '' }}"
                 id="q-{{ $item->id_item }}"
                 data-index="{{ $index }}">

            {{-- Question Header --}}
            <header class="question-card__header">
                <div class="question-card__header-top">
                    <div class="question-card__number">
                        <span class="question-card__number-text">{{ $index + 1 }}</span>
                        <span class="question-card__number-glow" aria-hidden="true"></span>
                    </div>
                    <div class="question-card__type">
                        @if($item->item_type === 'mcq')
                            <i class="fas fa-th-list" aria-hidden="true"></i>
                            <span>Pilihan Ganda</span>
                        @else
                            <i class="fas fa-pen" aria-hidden="true"></i>
                            <span>Isian</span>
                        @endif
                    </div>
                </div>

                <div class="question-card__text">
                    {!! $item->question_text !!}
                </div>
            </header>

            {{-- Question Body --}}
            <div class="question-card__body">
                @if($item->item_type === 'mcq')
                    <div class="option-list">
                        @foreach(['a'=>$item->option_a, 'b'=>$item->option_b, 'c'=>$item->option_c, 'd'=>$item->option_d] as $key => $opt)
                        @if($opt)
                        <label class="option-item">
                            <input type="radio"
                                   name="answers[{{ $item->id_item }}]"
                                   value="{{ $key }}"
                                   required
                                   class="option-item__radio">

                            <span class="option-item__key">{{ strtoupper($key) }}</span>
                            <span class="option-item__text">{{ $opt }}</span>
                            <span class="option-item__check" aria-hidden="true">
                                <i class="fas fa-check"></i>
                            </span>
                        </label>
                        @endif
                        @endforeach
                    </div>
                @else
                    <div class="text-answer">
                        <label class="text-answer__label" for="text-{{ $item->id_item }}">
                            <i class="fas fa-pen-fancy" aria-hidden="true"></i>
                            <span>Ketik jawabanmu:</span>
                        </label>
                        <div class="text-answer__wrapper">
                            <input type="text"
                                   id="text-{{ $item->id_item }}"
                                   name="answers[{{ $item->id_item }}]"
                                   placeholder="اكتب إجابتك هنا..."
                                   class="text-answer__input"
                                   required>
                        </div>
                        <p class="text-answer__hint">
                            <i class="fas fa-info-circle" aria-hidden="true"></i>
                            <span>Gunakan keyboard Arab atau ketik langsung</span>
                        </p>
                    </div>
                @endif
            </div>

            {{-- Question Footer --}}
            <footer class="question-card__footer">
                <div class="question-card__footer-info">
                    <i class="fas fa-lightbulb" aria-hidden="true"></i>
                    <span>Jawab dengan teliti sebelum melanjutkan</span>
                </div>
            </footer>
        </article>
        @endforeach


        {{-- ============================================
             NAVIGATION BAR (Sticky Bottom)
        ============================================ --}}
        <nav class="quiz-nav">
            <div class="quiz-nav__progress-mini">
                <div class="quiz-nav__progress-bar">
                    <div class="quiz-nav__progress-fill" id="nav-progress-fill" style="width: 0%"></div>
                </div>
            </div>

            <div class="quiz-nav__actions">
                <button type="button" id="btn-prev" class="quiz-nav__btn quiz-nav__btn--prev" disabled>
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Sebelumnya</span>
                </button>

                <div class="quiz-nav__indicator">
                    <span>Soal </span>
                    <strong id="nav-current">1</strong>
                    <span> dari {{ $questions->count() }}</span>
                </div>

                <button type="button" id="btn-next" class="quiz-nav__btn quiz-nav__btn--next">
                    <span>Selanjutnya</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>

                <button type="submit" id="btn-submit" class="quiz-nav__btn quiz-nav__btn--submit hidden">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                    <span>Kumpulkan Jawaban</span>
                </button>
            </div>
        </nav>
    </form>

</div>


@push('scripts')
<script>
(function() {
    let currentIndex = 0;
    const totalQuestions = {{ $questions->count() }};
    const cards = document.querySelectorAll('.question-card');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const btnSubmit = document.getElementById('btn-submit');
    const progressFill = document.getElementById('progress-fill');
    const progressPercent = document.getElementById('progress-percent');
    const currentQ = document.getElementById('current-q');
    const navCurrent = document.getElementById('nav-current');

    // ✅ Tambahan: sync mini progress bar di nav
    const navProgressFill = document.getElementById('nav-progress-fill');

    function showQuestion(index) {
        cards.forEach((card, i) => {
            if (i === index) {
                card.classList.remove('hidden');
                card.classList.add('question-card--animate');
                setTimeout(() => card.classList.remove('question-card--animate'), 500);
            } else {
                card.classList.add('hidden');
            }
        });
        
        btnPrev.disabled = index === 0;
        if (index === totalQuestions - 1) {
            btnNext.classList.add('hidden');
            btnSubmit.classList.remove('hidden');
        } else {
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        }
        const percent = Math.round(((index + 1) / totalQuestions) * 100);
        progressFill.style.width = percent + '%';
        progressPercent.textContent = percent;
        currentQ.textContent = index + 1;
        navCurrent.textContent = index + 1;

        // ✅ Sync mini progress bar
        if (navProgressFill) navProgressFill.style.width = percent + '%';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ✅ Event listener untuk radio (jika diklik langsung)
    document.querySelectorAll('.option-item__radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const parent = this.closest('.question-card');
            parent.querySelectorAll('.option-item').forEach(el => {
                el.classList.remove('option-item--selected');
            });
            this.closest('.option-item').classList.add('option-item--selected');
        });
    });

    btnPrev.addEventListener('click', () => {
        if (currentIndex > 0) {
            currentIndex--;
            showQuestion(currentIndex);
        }
    });

    btnNext.addEventListener('click', () => {
        const currentCard = cards[currentIndex];
        const radioSelected = currentCard.querySelector('input[type="radio"]:checked');
        const textInput = currentCard.querySelector('input[type="text"][name^="answers"]');
        const hasTextAnswer = textInput && textInput.value.trim() !== '';

        if (!radioSelected && !hasTextAnswer) {
            alert('Jawab soal ini terlebih dahulu!');
            return;
        }
        if (currentIndex < totalQuestions - 1) {
            currentIndex++;
            showQuestion(currentIndex);
        }
    });

    document.getElementById('quiz-form').addEventListener('submit', function(e) {
        console.log('🚀 Submitting quiz...');

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

        return true;
    });

    document.addEventListener('DOMContentLoaded', function() {
        showQuestion(0);

document.querySelectorAll('.option-item').forEach(item => {
    item.addEventListener('click', function() {

        const radio = this.querySelector('.option-item__radio');

        if (radio) {
            radio.checked = true;
            radio.dispatchEvent(new Event('change'));
        }
    });
});

document.querySelectorAll('.option-item__radio').forEach(radio => {
    radio.addEventListener('change', function() {

        const parent = this.closest('.question-card');

        parent.querySelectorAll('.option-item').forEach(el => {
            el.classList.remove('option-item--selected');
        });

        this.closest('.option-item')
            .classList.add('option-item--selected');

    });
});
    });
})(); 
</script>
@endpush
@endsection