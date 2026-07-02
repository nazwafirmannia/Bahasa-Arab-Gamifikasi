@extends('layouts.admin')
@section('title', isset($question) ? 'Edit Soal Placement' : 'Tambah Soal Placement')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-placement-form.css') }}">
<link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
@endpush

@php
    $isEdit = isset($question);
    $formTitle = $isEdit ? 'Edit Soal Placement' : 'Tambah Soal Placement';
    $formAction = $isEdit ? route('admin.placement.update', $question) : route('admin.placement.store');
    $formMethod = $isEdit ? 'PUT' : 'POST';
@endphp

<div class="placement-form-page">

    {{-- ============================================
         HEADER
    ============================================ --}}
    <section class="form-header">
        <div class="form-header__info">
            <a href="{{ route('admin.placement.index') }}" class="form-header__back">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Soal</span>
            </a>
            <h1 class="form-header__title">
                <span class="form-header__icon">{{ $isEdit ? '✏️' : '➕' }}</span>
                <span>{{ $formTitle }}</span>
            </h1>
            <p class="form-header__desc">
                {{ $isEdit ? 'Perbarui informasi soal placement test di bawah ini.' : 'Buat soal placement test baru untuk menentukan level awal siswa.' }}
            </p>
        </div>
    </section>


    {{-- ============================================
         VALIDATION ERRORS
    ============================================ --}}
    @if($errors->any())
    <div class="form-alert form-alert--error">
        <div class="form-alert__icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="form-alert__content">
            <h4 class="form-alert__title">Terdapat Kesalahan Validasi</h4>
            <ul class="form-alert__list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif


    {{-- ============================================
         FORM GRID
    ============================================ --}}
    <div class="form-grid">

        {{-- LEFT: FORM --}}
        <div class="form-main">
            <form method="POST" action="{{ $formAction }}" class="placement-form" id="placementForm">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                {{-- Question Text --}}
                <div class="form-group">
                    <label class="form-label" for="question_text">
                        <i class="fas fa-question-circle"></i>
                        <span>Pertanyaan <span class="form-label__required">*</span></span>
                    </label>
                    <textarea id="question_text"
                              name="question_text"
                              class="form-textarea form-textarea--arabic"
                              placeholder="Tulis pertanyaan dalam Bahasa Arab atau Indonesia..."
                              rows="4"
                              required>{{ old('question_text', $question->question_text ?? '') }}</textarea>
                    @error('question_text')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Options Grid --}}
                <div class="form-options">
                    <label class="form-label form-label--section">
                        <i class="fas fa-list-ol"></i>
                        <span>Pilihan Jawaban <span class="form-label__required">*</span></span>
                    </label>

                    <div class="form-options__grid">
                        @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                        <div class="form-option">
                            <label class="form-option__label" for="option_{{ $key }}">
                                <span class="form-option__key">{{ $label }}</span>
                                <span>Pilihan {{ $label }}</span>
                            </label>
                            <input type="text"
                                   id="option_{{ $key }}"
                                   name="option_{{ $key }}"
                                   value="{{ old('option_' . $key, $question->{'option_' . $key} ?? '') }}"
                                   class="form-input"
                                   placeholder="Tulis pilihan jawaban {{ $label }}..."
                                   required>
                            @error('option_' . $key)
                                <span class="form-error">{{ $message }}</span>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Correct Answer & Difficulty --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="correct_answer">
                            <i class="fas fa-check-double"></i>
                            <span>Jawaban Benar <span class="form-label__required">*</span></span>
                        </label>
                        <select id="correct_answer" name="correct_answer" class="form-select" required>
                            <option value="">-- Pilih Jawaban --</option>
                            @foreach(['A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D'] as $key => $label)
                                <option value="{{ $key }}" {{ old('correct_answer', $question->correct_answer ?? '') === $key ? 'selected' : '' }}>
                                    Pilihan {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('correct_answer')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="difficulty">
                            <i class="fas fa-signal"></i>
                            <span>Tingkat Kesulitan <span class="form-label__required">*</span></span>
                        </label>
                        <select id="difficulty" name="difficulty" class="form-select" required>
                            <option value="">-- Pilih Difficulty --</option>
                            <option value="easy" {{ old('difficulty', $question->difficulty ?? '') === 'easy' ? 'selected' : '' }}>🌱 Easy</option>
                            <option value="medium" {{ old('difficulty', $question->difficulty ?? '') === 'medium' ? 'selected' : '' }}>🌿 Medium</option>
                            <option value="hard" {{ old('difficulty', $question->difficulty ?? '') === 'hard' ? 'selected' : '' }}>🌳 Hard</option>
                        </select>
                        @error('difficulty')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- Explanation --}}
                <div class="form-group">
                    <label class="form-label" for="explanation">
                        <i class="fas fa-lightbulb"></i>
                        <span>Penjelasan (Opsional)</span>
                    </label>
                    <textarea id="explanation"
                              name="explanation"
                              class="form-textarea"
                              placeholder="Berikan penjelasan mengapa jawaban tersebut benar..."
                              rows="3">{{ old('explanation', $question->explanation ?? '') }}</textarea>
                    @error('explanation')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group form-group--checkbox">
                    <label class="form-checkbox">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox"
                               name="is_active"
                               value="1"
                               class="form-checkbox__input"
                               {{ old('is_active', $question->is_active ?? true) ? 'checked' : '' }}>
                        <span class="form-checkbox__box">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="form-checkbox__label">
                            <strong>Aktifkan Soal</strong>
                            <small>Soal yang aktif akan tampil dalam placement test</small>
                        </span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="form-actions">
                    <a href="{{ route('admin.placement.index') }}" class="form-actions__btn form-actions__btn--cancel">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="form-actions__btn form-actions__btn--submit">
                        <i class="fas fa-save"></i>
                        <span>{{ $isEdit ? 'Update Soal' : 'Simpan Soal' }}</span>
                    </button>
                </div>
            </form>
        </div>


        {{-- RIGHT: PREVIEW PANEL --}}
        <aside class="form-preview">
            <div class="preview-card">
                <div class="preview-card__header">
                    <i class="fas fa-eye"></i>
                    <span>Preview Soal</span>
                </div>

                <div class="preview-card__body">
                    {{-- Difficulty Badge --}}
                    <div class="preview-difficulty" id="previewDifficulty">
                        <span class="preview-difficulty__badge preview-difficulty__badge--empty">
                            <i class="fas fa-info-circle"></i>
                            <span>Belum dipilih</span>
                        </span>
                    </div>

                    {{-- Question --}}
                    <div class="preview-question" id="previewQuestion">
                        <span class="preview-question__placeholder">Pertanyaan akan muncul di sini...</span>
                    </div>

                    {{-- Options --}}
                    <ul class="preview-options" id="previewOptions">
                        @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                        <li class="preview-option" data-key="{{ $key }}">
                            <span class="preview-option__key">{{ $label }}</span>
                            <span class="preview-option__text" id="previewOption{{ strtoupper($key) }}">Pilihan {{ $label }}...</span>
                        </li>
                        @endforeach
                    </ul>

                    {{-- Correct Answer --}}
                    <div class="preview-answer" id="previewAnswer">
                        <span class="preview-answer__label">Jawaban Benar:</span>
                        <span class="preview-answer__value" id="previewCorrectAnswer">-</span>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionText = document.getElementById('question_text');
    const options = {
        a: document.getElementById('option_a'),
        b: document.getElementById('option_b'),
        c: document.getElementById('option_c'),
        d: document.getElementById('option_d')
    };
    const correctAnswer = document.getElementById('correct_answer');
    const difficulty = document.getElementById('difficulty');

    const previewQuestion = document.getElementById('previewQuestion');
    const previewOptions = {
        A: document.getElementById('previewOptionA'),
        B: document.getElementById('previewOptionB'),
        C: document.getElementById('previewOptionC'),
        D: document.getElementById('previewOptionD')
    };
    const previewCorrectAnswer = document.getElementById('previewCorrectAnswer');
    const previewDifficulty = document.getElementById('previewDifficulty');

    function updatePreview() {
        // Question
        const qText = questionText.value.trim();
        if (qText) {
            previewQuestion.innerHTML = '<p class="preview-question__text">' + escapeHtml(qText) + '</p>';
        } else {
            previewQuestion.innerHTML = '<span class="preview-question__placeholder">Pertanyaan akan muncul di sini...</span>';
        }

        // Options
        Object.keys(options).forEach(key => {
            const optText = options[key].value.trim();
            const upperKey = key.toUpperCase();
            if (optText) {
                previewOptions[upperKey].textContent = optText;
                previewOptions[upperKey].classList.remove('preview-option__text--empty');
            } else {
                previewOptions[upperKey].textContent = 'Pilihan ' + upperKey + '...';
                previewOptions[upperKey].classList.add('preview-option__text--empty');
            }
        });

        // Correct Answer
        const correct = correctAnswer.value;
        if (correct) {
            previewCorrectAnswer.textContent = correct.toUpperCase();
            previewCorrectAnswer.classList.add('preview-answer__value--filled');

            // Highlight correct option
            document.querySelectorAll('.preview-option').forEach(opt => {
                opt.classList.remove('preview-option--correct');
            });
            const correctOpt = document.querySelector('.preview-option[data-key="' + correct + '"]');
            if (correctOpt) correctOpt.classList.add('preview-option--correct');
        } else {
            previewCorrectAnswer.textContent = '-';
            previewCorrectAnswer.classList.remove('preview-answer__value--filled');
            document.querySelectorAll('.preview-option').forEach(opt => {
                opt.classList.remove('preview-option--correct');
            });
        }

        // Difficulty
        const diff = difficulty.value;
        if (diff) {
            const diffMap = {
                easy: { icon: 'fa-seedling', label: 'Easy', class: 'preview-difficulty__badge--easy' },
                medium: { icon: 'fa-tree', label: 'Medium', class: 'preview-difficulty__badge--medium' },
                hard: { icon: 'fa-mountain', label: 'Hard', class: 'preview-difficulty__badge--hard' }
            };
            const d = diffMap[diff];
            previewDifficulty.innerHTML = '<span class="preview-difficulty__badge ' + d.class + '"><i class="fas ' + d.icon + '"></i><span>' + d.label + '</span></span>';
        } else {
            previewDifficulty.innerHTML = '<span class="preview-difficulty__badge preview-difficulty__badge--empty"><i class="fas fa-info-circle"></i><span>Belum dipilih</span></span>';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Attach listeners
    questionText.addEventListener('input', updatePreview);
    Object.values(options).forEach(opt => opt.addEventListener('input', updatePreview));
    correctAnswer.addEventListener('change', updatePreview);
    difficulty.addEventListener('change', updatePreview);

    // Initial preview
    updatePreview();
});
</script>
@endpush
@endsection