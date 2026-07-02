@extends('layouts.placement')
@section('title', 'Placement Test - Soal ' . ($currentIndex ?? 1))

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/placement.css') }}">
@endpush

<div class="placement-page">

    {{-- ============================================
         BACKGROUND EFFECTS
    ============================================ --}}




    {{-- ============================================
         MAIN CARD (Glassmorphism)
    ============================================ --}}
    <div class="placement-card fade-in">

        {{-- ============================================
             HEADER PROGRESS (Quest Style)
        ============================================ --}}
        <div class="placement-card__header">
            <div class="placement-card__header-top">
                <div class="placement-card__quest-badge">
                    <span class="placement-card__quest-icon">📜</span>
                    <span class="placement-card__quest-text">Placement Quest</span>
                </div>
                <div class="placement-card__counter">
                    Soal <span id="current-num">1</span> dari 15
                </div>
            </div>

            <div class="placement-card__progress-wrap">
                <div class="placement-card__progress">
                    <div id="progress-bar" class="placement-card__progress-fill" style="width: 6.66%"></div>
                    <div class="placement-card__progress-shine"></div>
                </div>
                <div class="placement-card__progress-milestones" aria-hidden="true">
                    <span class="placement-card__milestone" style="left: 25%;"></span>
                    <span class="placement-card__milestone" style="left: 50%;"></span>
                    <span class="placement-card__milestone" style="left: 75%;"></span>
                    <span class="placement-card__milestone placement-card__milestone--final" style="left: 100%;"></span>
                </div>
            </div>
        </div>


        {{-- ============================================
             FORM UTAMA
        ============================================ --}}
        <form method="POST" action="{{ route('placement.submit') }}" id="placement-form">
            @csrf

            <div id="question-container" class="placement-card__body">
                @foreach($questions as $index => $question)
                <div class="question-slide placement-slide {{ $index === 0 ? 'block' : 'hidden' }}"
                     data-index="{{ $index }}"
                     data-id="{{ $question->id_question }}">

                    {{-- Question --}}
                    <div class="placement-question">
                        <span class="placement-question__number">Q{{ $index + 1 }}</span>
                        <h3 class="placement-question__text">
                            {{ $question->question_text }}
                        </h3>
                    </div>

                    {{-- Options --}}
                    <div id="options-area-{{ $index }}" class="placement-options">
                        @php $options = ['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d]; @endphp
                        @foreach($options as $key => $opt)
                        @if($opt)
                        <label class="option-label placement-option" data-value="{{ $opt }}">
                            <input type="radio"
                                   name="answers[{{ $question->id_question }}]"
                                   value="{{ $opt }}"
                                   class="placement-option__radio"
                                   required>
                            <span class="placement-option__key">{{ strtoupper($key) }}</span>
                            <span class="placement-option__text">{{ $opt }}</span>
                            <span class="placement-option__check" aria-hidden="true">
                                <i class="fas fa-check"></i>
                            </span>
                        </label>
                        @endif
                        @endforeach
                    </div>

                    {{-- Feedback --}}
                    <div id="feedback-area-{{ $index }}" class="placement-feedback hidden">
                        <div class="placement-feedback__content">
                            <div class="placement-feedback__icon-wrap">
                                <span id="feedback-icon-{{ $index }}" class="placement-feedback__icon"></span>
                            </div>
                            <div class="placement-feedback__info">
                                <p id="feedback-title-{{ $index }}" class="placement-feedback__title"></p>
                                <p id="feedback-explanation-{{ $index }}" class="placement-feedback__expl"></p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Hidden Inputs --}}
            @foreach($questions as $question)
            <input type="hidden" name="answers[{{ $question->id_question }}]" id="hidden-{{ $question->id_question }}">
            @endforeach

            {{-- Footer Navigation --}}
            <div class="placement-card__footer">
                <div class="placement-card__instruction" id="instruction-text">
                    <i class="fas fa-lightbulb" aria-hidden="true"></i>
                    <span>Pilih jawaban, lalu klik "Cek Jawaban"</span>
                </div>

                <button type="button" id="check-btn" class="placement-btn placement-btn--primary">
                    <span>Cek Jawaban</span>
                    <i class="fas fa-arrow-right"></i>
                </button>

                <button type="submit" id="submit-btn" class="placement-btn placement-btn--gold hidden">
                    <i class="fas fa-trophy"></i>
                    <span>Lihat Hasil Placement Test</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>


    {{-- ============================================
         MASCOT (Visual Only)
    ============================================ --}}
    <div class="placement-mascot" aria-hidden="true">
        <div class="placement-mascot__bubble">
            <span class="placement-mascot__name">Mentor ArabicQuest</span>
            <p class="placement-mascot__text">Ayo mulai petualanganmu! Setiap jawaban membawamu lebih dekat menjadi master Bahasa Arab.</p>
        </div>
        <div class="placement-mascot__avatar">
            <span>🧕</span>
            <div class="placement-mascot__glow"></div>
        </div>
    </div>

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.question-slide');
    const progressBar = document.getElementById('progress-bar');
    const currentNum = document.getElementById('current-num');
    const checkBtn = document.getElementById('check-btn');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.getElementById('placement-form');
    const instructionText = document.getElementById('instruction-text');

    const total = slides.length;
    let currentIdx = 0;
    let isFeedbackShown = false;

    function showSlide(index) {
        slides.forEach(s => s.classList.add('hidden'));
        slides[index].classList.remove('hidden');

        progressBar.style.width = ((index + 1) / total * 100) + '%';
        currentNum.textContent = index + 1;

        const slide = slides[index];
        const optionsArea = document.getElementById('options-area-' + index);
        const feedbackArea = document.getElementById('feedback-area-' + index);

        optionsArea.classList.remove('hidden');
        feedbackArea.classList.add('hidden');
        feedbackArea.classList.remove('bg-green-50', 'bg-red-50', 'border-green-500', 'border-red-500');

        slide.querySelectorAll('.option-label').forEach(label => {
            label.classList.remove('border-green-500', 'border-red-500', 'bg-green-50', 'bg-red-50', 'opacity-50');
            label.classList.add('border-gray-100');
            label.querySelector('input').disabled = false;
        });

        isFeedbackShown = false;
        checkBtn.innerHTML =
        '<span>Cek Jawaban</span><i class="fas fa-arrow-right"></i>';
        checkBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
        instructionText.innerHTML = '<i class="fas fa-lightbulb" aria-hidden="true"></i><span>Pilih jawaban, lalu klik "Cek Jawaban"</span>';
    }

    function handleCheckOrNext() {
        if (!isFeedbackShown) {
            const slide = slides[currentIdx];
            const selected = slide.querySelector('input[type="radio"]:checked');

            if (!selected) {
                alert('Silakan pilih jawaban terlebih dahulu!');
                return;
            }

            const qId = slide.dataset.id;
            document.getElementById('hidden-' + qId).value = selected.value;

            const correct = slide.dataset.correct.trim().toLowerCase();
            const userAns = selected.value.trim().toLowerCase();
            const isCorrect = userAns === correct;

            const feedbackArea = document.getElementById('feedback-area-' + currentIdx);
            const icon = document.getElementById('feedback-icon-' + currentIdx);
            const title = document.getElementById('feedback-title-' + currentIdx);
            const expl = document.getElementById('feedback-explanation-' + currentIdx);

            feedbackArea.classList.remove('hidden');
            feedbackArea.classList.add(isCorrect ? 'bg-green-50' : 'bg-red-50');
            feedbackArea.classList.add('border-l-4', isCorrect ? 'border-green-500' : 'border-red-500');

            icon.textContent = isCorrect ? '✅' : '❌';
            title.textContent = isCorrect ? 'Jawaban Benar!' : 'Kurang Tepat';
            title.className = 'font-bold text-lg mb-1 ' + (isCorrect ? 'text-green-700' : 'text-red-700');
            expl.textContent = slide.dataset.explanation;

            slide.querySelectorAll('input[type="radio"]').forEach(i => i.disabled = true);
            slide.querySelectorAll('.option-label').forEach(label => {
                label.style.cursor = 'default';
                label.style.opacity = '0.8';
                const val = label.dataset.value.trim().toLowerCase();
                if (val === correct) {
                    label.classList.remove('border-gray-100');
                    label.classList.add('border-green-500', 'bg-green-50');
                } else if (!isCorrect && val === userAns) {
                    label.classList.remove('border-gray-100');
                    label.classList.add('border-red-500', 'bg-red-50');
                }
            });

            isFeedbackShown = true;

checkBtn.innerHTML =
currentIdx === total - 1
? '<span>Selesai & Lihat Hasil</span><i class="fas fa-trophy"></i>'
: '<span>Lanjut ke Soal Berikutnya</span><i class="fas fa-arrow-right"></i>';

instructionText.innerHTML = isCorrect
? '<i class="fas fa-check-circle"></i><span>Paham? Klik lanjut ya!</span>'
: '<i class="fas fa-book"></i><span>Catat penjelasannya, lalu klik lanjut!</span>';

        } else {
            if (currentIdx < total - 1) {
                currentIdx++;
                showSlide(currentIdx);
            } else {
                form.submit();
            }
        }
    }

    checkBtn.addEventListener('click', handleCheckOrNext);

    @foreach($questions as $index => $question)
    const slide{{ $index }} = document.querySelector('.question-slide[data-index="{{ $index }}"]');
    slide{{ $index }}.dataset.correct = @json($question->correct_answer);
    slide{{ $index }}.dataset.explanation = @json($question->explanation ?? 'Penjelasan tidak tersedia.');
    @endforeach

    showSlide(0);
});
</script>
@endpush
@endsection