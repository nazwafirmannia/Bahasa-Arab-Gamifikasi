@extends('layouts.app')
@section('title', 'Latihan: ' . $material->title)

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/practice.css') }}">
<style>
    #sidebar { display: none !important; }
    .flex.h-screen { overflow: hidden; }
    .flex-1 { width: 100% !important; }
    main { padding: 0 !important; max-width: 100% !important; }
</style>
@endpush

@php
    use App\Helpers\ArabicHelper;
    $totalQuestions = $questions->count();
    $totalXp = $questions->sum('xp_reward');
    $levelOrder = $material->level->level_order ?? 1;
    // Difficulty berdasarkan jumlah soal & XP
    $difficulty = $totalXp >= 200 ? 'Hard' : ($totalXp >= 100 ? 'Medium' : 'Easy');
    $difficultyIcon = $difficulty === 'Hard' ? '🔥' : ($difficulty === 'Medium' ? '⚡' : '🌱');
@endphp

<div class="practice-page">

    {{-- ============================================
         PRACTICE HERO — Premium Challenge Header
    ============================================ --}}
    <section class="practice-hero" aria-label="Challenge Information">
        <a href="{{ route('user.material', $material->id_material) }}"
           class="practice-hero__back"
           aria-label="Kembali ke materi {{ $material->title }}">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            <span>Kembali ke Materi</span>
        </a>

        <div class="practice-hero__layout">
            {{-- Left: Info --}}
            <div class="practice-hero__info">
                <div class="practice-hero__badge" aria-label="Challenge Mode Active">
                    <span aria-hidden="true">🏆</span>
                    <span>Challenge Mode</span>
                </div>

                <h1 class="practice-hero__title">
                    <span class="practice-hero__title-icon" aria-hidden="true">📝</span>
                    <span>Latihan: {{ $material->title }}</span>
                </h1>

                <p class="practice-hero__subtitle">
                    Selesaikan tantangan ini untuk mendapatkan XP dan membuka achievement baru!
                </p>

                <div class="practice-hero__stats">
                    <div class="practice-hero__stat">
                        <div class="practice-hero__stat-icon" aria-hidden="true">📋</div>
                        <div class="practice-hero__stat-info">
                            <span class="practice-hero__stat-label">Total Soal</span>
                            <span class="practice-hero__stat-value">{{ $totalQuestions }}</span>
                        </div>
                    </div>
                    <div class="practice-hero__stat">
                        <div class="practice-hero__stat-icon" aria-hidden="true">⭐</div>
                        <div class="practice-hero__stat-info">
                            <span class="practice-hero__stat-label">Total XP</span>
                            <span class="practice-hero__stat-value">+{{ $totalXp }}</span>
                        </div>
                    </div>
                    <div class="practice-hero__stat">
                        <div class="practice-hero__stat-icon" aria-hidden="true">🎯</div>
                        <div class="practice-hero__stat-info">
                            <span class="practice-hero__stat-label">Level</span>
                            <span class="practice-hero__stat-value">{{ $levelOrder }}</span>
                        </div>
                    </div>
                    <div class="practice-hero__stat practice-hero__stat--difficulty">
                        <div class="practice-hero__stat-icon" aria-hidden="true">{{ $difficultyIcon }}</div>
                        <div class="practice-hero__stat-info">
                            <span class="practice-hero__stat-label">Difficulty</span>
                            <span class="practice-hero__stat-value">{{ $difficulty }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Progress Ring --}}
            <div class="practice-hero__ring-wrapper" aria-hidden="true">
                <div class="practice-hero__ring">
                    <svg viewBox="0 0 120 120" class="practice-hero__ring-svg">
                        <circle class="practice-hero__ring-bg" cx="60" cy="60" r="52"/>
                        <circle class="practice-hero__ring-fg"
                                cx="60" cy="60" r="52"
                                stroke-dasharray="{{ 2 * 3.14 * 52 }}"
                                stroke-dashoffset="{{ 2 * 3.14 * 52 }}"
                                id="hero-ring"/>
                    </svg>
                    <div class="practice-hero__ring-content">
                        <span class="practice-hero__ring-icon">🏆</span>
                        <span class="practice-hero__ring-percent" id="hero-percent">0%</span>
                    </div>
                </div>
                <span class="practice-hero__ring-caption">Progress Challenge</span>
            </div>
        </div>
    </section>


    {{-- ============================================
         QUEST PROGRESS — Accurate State Tracking
    ============================================ --}}
   {{-- <section class="quest-progress" aria-label="Quest Progress Tracker">
        <div class="quest-progress__header">
            <div class="quest-progress__title">
                <i class="fas fa-route" aria-hidden="true"></i>
                <span>Perjalanan Quest</span>
            </div>
            <div class="quest-progress__counter">
                <span id="answered-count">0</span> / {{ $totalQuestions }} Dijawab
            </div>
        </div>

        <div class="quest-progress__dots" role="tablist" aria-label="Question navigator">
            @foreach($questions as $index => $q)
                <button type="button"
                        class="quest-progress__dot {{ $index === 0 ? 'quest-progress__dot--active' : 'quest-progress__dot--locked' }}"
                        data-index="{{ $index }}"
                        data-state="pending"
                        role="tab"
                        aria-label="Soal {{ $index + 1 }}"
                        aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                        onclick="goToQuestion({{ $index }})">
                    <span class="quest-progress__dot-number">{{ $index + 1 }}</span>
                    <span class="quest-progress__dot-check" aria-hidden="true">✓</span>
                </button>
            @endforeach
        </div>

        <p class="quest-progress__hint">
            <i class="fas fa-info-circle" aria-hidden="true"></i>
            <span>Kerjakan soal secara berurutan. Dot akan berubah emas setelah dijawab.</span>
        </p>
    </section>--}}


    {{-- ============================================
         QUIZ FORM
    ============================================ --}}
    <form method="POST"
          action="{{ route('user.practice.submit', $material->id_material) }}"
          id="quiz-form"
          novalidate>
        @csrf

        @foreach($questions as $index => $question)
        @php
            $avatar = match($question->category) {
                'vocab'   => ['emoji' => '🔤', 'class' => 'vocab'],
                'grammar' => ['emoji' => '📐', 'class' => 'grammar'],
                'dialog'  => ['emoji' => '💬', 'class' => 'dialog'],
                default   => ['emoji' => '', 'class' => 'default'],
            };
            // ✅ SECURITY: Sanitize question_text untuk mencegah XSS
            $safeQuestionText = ArabicHelper::sanitizeHtml($question->question_text);
        @endphp

        <article class="question-card {{ $index !== 0 ? 'question-card--hidden' : '' }}"
                 id="q-{{ $question->id_question }}"
                 data-index="{{ $index }}"
                 data-question-id="{{ $question->id_question }}"
                 role="tabpanel"
                 aria-label="Soal {{ $index + 1 }}">

            {{-- Question Header --}}
            <header class="question-card__header">
                <div class="question-card__top">
                    <div class="question-card__number" aria-hidden="true">{{ $index + 1 }}</div>
                    <div class="question-card__category question-card__category--{{ $avatar['class'] }}">
                        <span aria-hidden="true">{{ $avatar['emoji'] }}</span>
                        <span>{{ ucfirst($question->category) }}</span>
                    </div>
                    <div class="question-card__xp-badge" aria-label="Reward {{ $question->xp_reward }} XP">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        <span>+{{ $question->xp_reward }} XP</span>
                    </div>
                </div>
                <div class="question-card__text">
                    {!! $safeQuestionText !!}
                </div>
            </header>

            {{-- Question Body --}}
            <div class="question-card__body">
                @php
                    $isEssay = empty($question->option_a) && empty($question->option_b)
                               && empty($question->option_c) && empty($question->option_d);
                @endphp

                @if($isEssay)
                    {{-- ============================================
                         ESSAY MODE — Premium Arabic Input
                    ============================================ --}}
                    <div class="essay-section">
                        <label class="essay-section__label" for="essay-{{ $question->id_question }}">
                            <i class="fas fa-pen-fancy" aria-hidden="true"></i>
                            <span>Ketik jawabanmu dalam Bahasa Arab:</span>
                        </label>

                        <div class="essay-section__input-wrapper">
                            <textarea
                                   name="answers[{{ $question->id_question }}]"
                                   id="essay-{{ $question->id_question }}"
                                   class="essay-section__input"
                                   placeholder="اكتب إجابتك هنا..."
                                   required
                                   autocomplete="off"
                                   dir="rtl"
                                   lang="ar"
                                   aria-label="Jawaban essay dalam Bahasa Arab"
                                   data-question-id="{{ $question->id_question }}"
                                   ></textarea>

                            <button type="button"
                                    onclick="toggleArabicKeyboard({{ $question->id_question }})"
                                    class="essay-section__keyboard-toggle"
                                    aria-label="Toggle Arabic keyboard"
                                    aria-expanded="false"
                                    id="keyboard-toggle-{{ $question->id_question }}">
                                <i class="fas fa-keyboard" aria-hidden="true"></i>
                                <span>⌨️ Keyboard</span>
                            </button>
                        </div>

                        <div class="essay-section__meta">
                            <span class="essay-section__counter" id="counter-{{ $question->id_question }}">
                                0 karakter
                            </span>
                            <span class="essay-section__helper">
                                <i class="fas fa-lightbulb" aria-hidden="true"></i>
                                Klik tombol keyboard untuk input Arab
                            </span>
                        </div>

                        {{-- Arabic Keyboard (rendered by JS) --}}
                        <div id="keyboard-{{ $question->id_question }}"
                             class="arabic-keyboard arabic-keyboard--hidden"
                             role="region"
                             aria-label="Arabic virtual keyboard"></div>
                    </div>

                @else
                    {{-- ============================================
                         MULTIPLE CHOICE — Accessible Options
                    ============================================ --}}
                    <div class="option-list" role="radiogroup" aria-label="Pilihan jawaban">
                        @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $option)
                        @if($option)
                        <label class="option-item"
                               tabindex="0"
                               role="radio"
                               aria-checked="false"
                               onkeydown="handleOptionKey(event, this)"
                               onclick="selectOption(this, {{ $question->id_question }})">
                            {{-- ✅ ACCESSIBILITY: sr-only, bukan hidden --}}
                            <input type="radio"
                                   name="answers[{{ $question->id_question }}]"
                                   value="{{ $key }}"
                                   required
                                   class="option-item__radio-sr"
                                   aria-label="Pilihan {{ strtoupper($key) }}: {{ strip_tags($option) }}">

                            <div class="option-item__radio-visual" aria-hidden="true"></div>
                            <div class="option-item__key" aria-hidden="true">{{ strtoupper($key) }}</div>
                            <div class="option-item__text">{{ $option }}</div>
                            <div class="option-item__check" aria-hidden="true">
                                <i class="fas fa-check"></i>
                            </div>
                        </label>
                        @endif
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Question Footer --}}
            {{--<footer class="question-card__footer">
                <span class="question-card__reward-label">Potensi Reward:</span>
                <div class="question-card__reward-value">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    <span>+{{ $question->xp_reward }} XP</span>
                </div>
            </footer>--}}

            
        </article>
        @endforeach

        <nav class="practice-nav" aria-label="Quiz navigation">
            <div class="practice-nav__progress-mini">
                <div class="practice-nav__progress-bar">
                    <div class="practice-nav__progress-fill" id="nav-progress-fill" style="width: 0%"></div>
                </div>
                <div class="practice-nav__progress-info">
                    <span id="nav-answered-count">0</span> / {{ $totalQuestions }} dijawab
                </div>
            </div>

            <div class="practice-nav__actions">
                <button type="button"
                        id="btn-prev"
                        class="practice-nav__btn practice-nav__btn--prev"
                        disabled
                        aria-label="Soal sebelumnya">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                    <span>Sebelumnya</span>
                </button>

                <div class="practice-nav__indicator">
                    <span>Soal </span>
                    <strong id="nav-current">1</strong>
                    <span> dari {{ $totalQuestions }}</span>
                </div>

                {{-- Helper text (muncul saat belum menjawab) --}}
                <div class="practice-nav__helper" id="nav-helper" aria-live="polite">
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                    <span>Pilih salah satu jawaban untuk melanjutkan</span>
                </div>

                <button type="button"
                        id="btn-next"
                        class="practice-nav__btn practice-nav__btn--next practice-nav__btn--disabled"
                        disabled
                        aria-label="Soal selanjutnya">
                    <span>Selanjutnya</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </button>

                <button type="submit"
                        id="btn-submit"
                        class="practice-nav__btn practice-nav__btn--submit practice-nav__btn--hidden"
                        disabled
                        aria-label="Kirim semua jawaban">
                    <i class="fas fa-paper-plane" aria-hidden="true"></i>
                    <span>Kirim Jawaban</span>
                </button>
            </div>
        </nav>
        {{-- ============================================
             FLOATING NAVIGATION BAR — Enhanced
        ============================================ --}}
    </form>

</div>


@push('scripts')
<script>
/* ===========================================================
   ARABIC QUEST PRACTICE — Enhanced JavaScript
   Features:
   - Accurate quest progress tracking
   - Linear navigation mode (jump prevention)
   - Accessible multiple choice
   - Auto-enable Next button
   - No alert() for validation
   =========================================================== */

// ============================================
// ARABIC CHARACTERS
// ============================================
const arabicLetters = ['ا','ب','ت','ث','ج','ح','خ','د','ذ','ر','ز','س','ش','ص','ض','ط','ظ','ع','غ','ف','ق','ك','ل','م','ن','ه','و','ي'];
const arabicDiacritics = [
    {char: 'َ', name: 'Fathah'},
    {char: 'ُ', name: 'Dammah'},
    {char: 'ِ', name: 'Kasrah'},
    {char: 'ّ', name: 'Shaddah'},
    {char: 'ْ', name: 'Sukun'},
    {char: 'ً', name: 'Fathatain'},
    {char: 'ٌ', name: 'Dammatan'},
    {char: 'ٍ', name: 'Kasratain'}
];

// ============================================
// STATE MANAGEMENT
// ============================================
const totalQuestions = {{ $totalQuestions }};
let currentIndex = 0;
const cards = document.querySelectorAll('.question-card');
const dots  = document.querySelectorAll('.quest-progress__dot');

// ✅ Track state per question (accurate progress)
const questionState = Array.from({ length: totalQuestions }, () => ({
    answered: false,
    visited: false
}));
questionState[0].visited = true; // First question visited

// ============================================
// INITIALIZE
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Arabic keyboards
    document.querySelectorAll('textarea[id^="essay-"]').forEach(input => {
        const questionId = input.dataset.questionId;
        initArabicKeyboard(questionId);
    });

    // Attach listeners to all inputs for accurate tracking
    attachAnswerListeners();

    // Show first question
    showQuestion(0);
    updateGlobalProgress();
});

// ============================================
// ATTACH ANSWER LISTENERS
// Track when user actually answers (not just navigates)
// ============================================
function attachAnswerListeners() {
    // Multiple choice radios
    document.querySelectorAll('input[type="radio"][name^="answers"]').forEach(radio => {
        radio.addEventListener('change', function() {
            console.log('RADIO CHANGED');
            const card = this.closest('.question-card');
            const index = parseInt(card.dataset.index);
            questionState[index].answered = true;
            updateDotState(index);
            updateNavigationState();
            updateGlobalProgress();
        });
    });

    // Essay inputs
    document.querySelectorAll('textarea[id^="essay-"]').forEach(input => {
        input.addEventListener('input', function() {
            handleEssayInput(this);
            const card = this.closest('.question-card');
            const index = parseInt(card.dataset.index);
            questionState[index].answered = this.value.trim().length > 0;
            updateDotState(index);
            updateNavigationState();
            updateGlobalProgress();
        });
    });
}

// ============================================
// DOT STATE UPDATE (Accurate)
// ============================================
function updateDotState(index) {
    const dot = dots[index];
    if (!dot) return;

    // Remove all state classes
    dot.classList.remove(
        'quest-progress__dot--active',
        'quest-progress__dot--completed',
        'quest-progress__dot--locked',
        'quest-progress__dot--visited'
    );

    if (questionState[index].answered) {
        dot.classList.add('quest-progress__dot--completed');
        dot.dataset.state = 'completed';
        dot.setAttribute('aria-label', `Soal ${index + 1} - Sudah dijawab`);
    } else if (index === currentIndex) {
        dot.classList.add('quest-progress__dot--active');
        dot.dataset.state = 'active';
        dot.setAttribute('aria-label', `Soal ${index + 1} - Sedang aktif`);
    } else if (questionState[index].visited) {
        dot.classList.add('quest-progress__dot--visited');
        dot.dataset.state = 'visited';
        dot.setAttribute('aria-label', `Soal ${index + 1} - Sudah dikunjungi`);
    } else {
        dot.classList.add('quest-progress__dot--locked');
        dot.dataset.state = 'locked';
        dot.setAttribute('aria-label', `Soal ${index + 1} - Terkunci`);
    }
}

// ============================================
// UPDATE ALL DOTS
// ============================================
function updateAllDots() {
    dots.forEach((dot, i) => updateDotState(i));
}

// ============================================
// GLOBAL PROGRESS UPDATE
// ============================================
function updateGlobalProgress() {
    const answeredCount = questionState.filter(s => s.answered).length;
    const percent = Math.round((answeredCount / totalQuestions) * 100);

    // Update counters
    const answeredCountEls = document.querySelectorAll('#answered-count, #nav-answered-count');
    answeredCountEls.forEach(el => el.textContent = answeredCount);

    // Update nav progress bar
    const navFill = document.getElementById('nav-progress-fill');
    if (navFill) navFill.style.width = percent + '%';

    // Update hero ring
    const heroRing = document.getElementById('hero-ring');
    const heroPercent = document.getElementById('hero-percent');
    if (heroRing) {
        const circumference = 2 * 3.14 * 52;
        const offset = circumference * (1 - percent / 100);
        heroRing.style.strokeDashoffset = offset;
    }
    if (heroPercent) heroPercent.textContent = percent + '%';
}

// ============================================
// NAVIGATION STATE
// Auto-enable/disable Next button based on answer
// ============================================
function updateNavigationState() {
    const currentCard = cards[currentIndex];
    if (!currentCard) return;

    const radioSelected = currentCard.querySelector('input[type="radio"]:checked');
    const textInput = currentCard.querySelector('textarea[id^="essay-"]');
    const hasTextAnswer = textInput && textInput.value.trim().length > 0;
    const isAnswered = !!radioSelected || hasTextAnswer;

    const btnNext = document.getElementById('btn-next');
    const btnSubmit = document.getElementById('btn-submit');
    const navHelper = document.getElementById('nav-helper');

    if (currentIndex === totalQuestions - 1) {
        // Last question → show submit
        btnNext.classList.add('practice-nav__btn--hidden');
        btnSubmit.classList.remove('practice-nav__btn--hidden');
        btnSubmit.disabled = !isAnswered;
        btnSubmit.classList.toggle('practice-nav__btn--disabled', !isAnswered);
    } else {
        // Not last → show next
        btnNext.classList.remove('practice-nav__btn--hidden');
        btnSubmit.classList.add('practice-nav__btn--hidden');
        btnNext.disabled = !isAnswered;
        btnNext.classList.toggle('practice-nav__btn--disabled', !isAnswered);
    }

    // Helper text visibility
    if (navHelper) {
        navHelper.classList.toggle('practice-nav__helper--visible', !isAnswered);
    }
}

// ============================================
// SHOW QUESTION (Linear Navigation)
// ============================================
function showQuestion(index) {
    if (index < 0 || index >= totalQuestions) return;

    cards.forEach((card, i) => {
        card.classList.toggle(
            'question-card--hidden',
            i !== index
        );
    });

    currentIndex = index;
    questionState[index].visited = true;

    document.getElementById('nav-current').textContent =
        index + 1;

    document.getElementById('btn-prev').disabled =
        index === 0;

    updateAllDots();
    updateNavigationState();

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// ============================================
// GO TO QUESTION (Dot Click)
// ============================================
function goToQuestion(index) {
    // ✅ LINEAR MODE: Only allow going to visited questions or next
    if (!questionState[index].visited && index > currentIndex) {
        // Shake animation for locked dot
        const dot = dots[index];
        if (dot) {
            dot.classList.add('quest-progress__dot--shake');
            setTimeout(() => dot.classList.remove('quest-progress__dot--shake'), 500);
        }
        return;
    }
    showQuestion(index);
}

// ============================================
// SELECT OPTION (Multiple Choice)
// ============================================
function selectOption(element)
{
    const parent = element.closest('.question-card');

    parent.querySelectorAll('.option-item')
    .forEach(el => {
        el.classList.remove('option-item--selected');
        el.setAttribute('aria-checked','false');
    });

    element.classList.add('option-item--selected');
    element.setAttribute('aria-checked','true');

    const radio = element.querySelector('input[type="radio"]');

    if (radio)
    {
        radio.checked = true;
        radio.dispatchEvent(
            new Event('change', { bubbles:true })
        );
    }
}

// ============================================
// KEYBOARD ACCESSIBILITY FOR OPTIONS
// ============================================
function handleOptionKey(event, element) {
    if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        element.click();
    } else if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
        event.preventDefault();
        const next = element.nextElementSibling;
        if (next && next.classList.contains('option-item')) next.focus();
    } else if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
        event.preventDefault();
        const prev = element.previousElementSibling;
        if (prev && prev.classList.contains('option-item')) prev.focus();
    }
}



// ============================================
// ESSAY INPUT HANDLER
// ============================================
function handleEssayInput(input) {
    const questionId = input.dataset.questionId;
    const counter = document.getElementById('counter-' + questionId);
    if (counter) {
        counter.textContent = input.value.length + ' karakter';
    }
}

// ============================================
// NAVIGATION BUTTONS
// ============================================
document.getElementById('btn-prev').addEventListener('click', () => {
    if (currentIndex > 0) showQuestion(currentIndex - 1);
});

document.getElementById('btn-next')
.addEventListener('click', () => {

    const nextIndex = currentIndex + 1;

    if(nextIndex < totalQuestions)
    {
        questionState[nextIndex].visited = true;
        showQuestion(nextIndex);
    }
});

// ============================================
// FORM SUBMISSION
// ============================================
document.getElementById('quiz-form').addEventListener('submit', function(e) {
    const unanswered = questionState.filter(s => !s.answered).length;

    if (unanswered > 0) {
        e.preventDefault();
        // ✅ NO ALERT — Find first unanswered and navigate there
        const firstUnanswered = questionState.findIndex(s => !s.answered);
        if (firstUnanswered !== -1) {
            // Mark as visited so user can jump there
            questionState[firstUnanswered].visited = true;
            showQuestion(firstUnanswered);

            // Highlight the unanswered card
            const card = cards[firstUnanswered];
            if (card) {
                card.classList.add('question-card--highlight');
                setTimeout(() => card.classList.remove('question-card--highlight'), 2000);
            }
        }
        return;
    }

    const btnSubmit = document.getElementById('btn-submit');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> <span>Mengirim...</span>';
});

// ============================================
// ARABIC KEYBOARD
// ============================================
function initArabicKeyboard(questionId) {
    const keyboard = document.getElementById('keyboard-' + questionId);
    if (!keyboard) return;

    keyboard.innerHTML = '';

    // Header (sticky)
    const header = document.createElement('div');
    header.className = 'arabic-keyboard__header';
    header.innerHTML = `
        <div class="arabic-keyboard__title">
            <i class="fas fa-keyboard" aria-hidden="true"></i>
            <span>Arabic Keyboard</span>
        </div>
        <button type="button"
                class="arabic-keyboard__close"
                onclick="toggleArabicKeyboard(${questionId})"
                aria-label="Tutup keyboard">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
    `;
    keyboard.appendChild(header);

    // Scrollable content
    const content = document.createElement('div');
    content.className = 'arabic-keyboard__content';

    // Section: Huruf
    const lettersSection = createKeyboardSection('Huruf Arab', 'fa-font', arabicLetters.map(l => ({
        char: l, type: 'letter'
    })), questionId);
    content.appendChild(lettersSection);

    // Section: Harakat
    const diacriticsSection = createKeyboardSection('Harakat', 'fa-spell-check', arabicDiacritics.map(d => ({
        char: d.char, type: 'diacritic', name: d.name
    })), questionId);
    content.appendChild(diacriticsSection);

    // Section: Aksi
    const actionsSection = document.createElement('div');
    actionsSection.className = 'arabic-keyboard__section';
    actionsSection.innerHTML = '<div class="arabic-keyboard__section-title"><i class="fas fa-cogs" aria-hidden="true"></i> Aksi</div>';

    const actionsGrid = document.createElement('div');
    actionsGrid.className = 'arabic-keyboard__grid arabic-keyboard__grid--actions';

    const actions = [
        { label: '⎵ Spasi', char: ' ', class: '', action: 'insert' },
        { label: '⌫ Hapus', char: '', class: 'arabic-keyboard__btn--action', action: 'backspace' },
        { label: '🗑️ Reset', char: '', class: 'arabic-keyboard__btn--danger', action: 'clear' }
    ];

    actions.forEach(act => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'arabic-keyboard__btn ' + act.class;
        btn.textContent = act.label;
        btn.onclick = () => {
            if (act.action === 'insert') insertChar(questionId, act.char);
            else if (act.action === 'backspace') backspaceArabic(questionId);
            else if (act.action === 'clear') clearArabicInput(questionId);
        };
        actionsGrid.appendChild(btn);
    });

    actionsSection.appendChild(actionsGrid);
    content.appendChild(actionsSection);

    // Info
    const info = document.createElement('div');
    info.className = 'arabic-keyboard__info';
    info.innerHTML = '<i class="fas fa-lightbulb" aria-hidden="true"></i> Ketik huruf dulu, baru harakat. Contoh: ب + َ = بَ';
    content.appendChild(info);

    keyboard.appendChild(content);
}

function createKeyboardSection(title, icon, items, questionId) {
    const section = document.createElement('div');
    section.className = 'arabic-keyboard__section';
    section.innerHTML = `<div class="arabic-keyboard__section-title"><i class="fas ${icon}" aria-hidden="true"></i> ${title}</div>`;

    const grid = document.createElement('div');
    grid.className = 'arabic-keyboard__grid';

    items.forEach(item => {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'arabic-keyboard__btn' + (item.type === 'diacritic' ? ' arabic-keyboard__btn--diacritic' : '');
        btn.textContent = item.char;
        if (item.name) btn.title = item.name;
        btn.setAttribute('aria-label', item.name || item.char);
        btn.onclick = () => insertChar(questionId, item.char);
        grid.appendChild(btn);
    });

    section.appendChild(grid);
    return section;
}

function toggleArabicKeyboard(questionId) {
    const keyboard = document.getElementById('keyboard-' + questionId);
    const toggle = document.getElementById('keyboard-toggle-' + questionId);
    if (!keyboard) return;

    const isHidden = keyboard.classList.toggle('arabic-keyboard--hidden');
    if (toggle) toggle.setAttribute('aria-expanded', !isHidden);
}

function insertChar(questionId, char) {
    const input = document.getElementById('essay-' + questionId);
    if (!input) return;

    const isDiacritic = arabicDiacritics.some(d => d.char === char);

    if (isDiacritic && input.value.length === 0) {
        // Visual feedback instead of alert
        input.classList.add('essay-section__input--shake');
        setTimeout(() => input.classList.remove('essay-section__input--shake'), 500);
        return;
    }

    const start = input.selectionStart;
    const end = input.selectionEnd;
    const value = input.value;

    input.value = value.slice(0, start) + char + value.slice(end);

    const newPos = start + char.length;
    input.setSelectionRange(newPos, newPos);
    input.focus();

    // Trigger input event for state tracking
    input.dispatchEvent(new Event('input', { bubbles: true }));
}

function clearArabicInput(questionId) {
    const input = document.getElementById('essay-' + questionId);
    if (input) {
        input.value = '';
        input.focus();
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
}

function backspaceArabic(questionId) {
    const input = document.getElementById('essay-' + questionId);
    if (input && input.value.length > 0) {
        input.value = input.value.slice(0, -1);
        input.focus();
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
}
</script>
@endpush
@endsection