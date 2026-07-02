@extends('layouts.admin')
@section('title', isset($item) ? 'Edit Soal Quiz' : 'Tambah Soal Quiz')
@section('content')

@php
    $item = $item ?? $quizItem ?? null;
    $gameData = is_array($item?->game_data) 
        ? $item->game_data 
        : json_decode($item?->game_data ?? '{}', true);
@endphp

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
            {{ isset($item) ? '✏️ Edit Soal Quiz' : '➕ Tambah Soal Quiz' }}
        </h1>
        <a href="{{ route('admin.quiz-items.index') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); overflow: hidden;">
        <form method="POST" action="{{ isset($item) ? route('admin.quiz-items.update', $item) : route('admin.quiz-items.store') }}" enctype="multipart/form-data">
            @csrf 
            @if(isset($item)) @method('PUT') @endif

            <!-- Quiz & Order -->
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 2; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Quiz Terkait *</label>
                    <select name="id_quiz" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="">Pilih Quiz</option>
                        @foreach($quizzes as $q)
                        <option value="{{ $q->id_quiz }}" {{ old('id_quiz', $item?->id_quiz ?? '') == $q->id_quiz ? 'selected' : '' }}>{{ $q->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 120px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Urutan</label>
                    <input type="number" name="order_index" min="1" value="{{ old('order_index', $item?->order_index ?? '1') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
            </div>

            <!-- Tipe Soal -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Tipe Soal *</label>
                <select name="item_type" id="item_type" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                    <option value="mcq" {{ old('item_type', $item?->item_type ?? 'mcq') == 'mcq' ? 'selected' : '' }}>Pilihan Ganda (MCQ)</option>
                    <option value="fill" {{ old('item_type', $item?->item_type ?? '') == 'fill' ? 'selected' : '' }}>Isian Singkat (Fill)</option>
                    <option value="flashcard" {{ old('item_type', $item?->item_type ?? '') == 'flashcard' ? 'selected' : '' }}>🃏 Flashcard</option>
                </select>
            </div>

            <!-- Pertanyaan -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Pertanyaan / Teks Depan *</label>
                <textarea name="question_text" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>{{ old('question_text', $item?->question_text ?? '') }}</textarea>
            </div>

            <!-- Opsi MCQ -->
            <div id="options-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi A</label><input type="text" name="option_a" value="{{ old('option_a', $item?->option_a ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi B</label><input type="text" name="option_b" value="{{ old('option_b', $item?->option_b ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi C</label><input type="text" name="option_c" value="{{ old('option_c', $item?->option_c ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi D</label><input type="text" name="option_d" value="{{ old('option_d', $item?->option_d ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
            </div>

            <!-- Flashcard Data -->
            <div id="flashcard-container" style="display: none; border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px;">
                <h4 style="font-weight: 600; color: var(--primary); margin-bottom: 12px;">🃏 Data Flashcard</h4>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Teks Belakang (Arti/Definisi)</label>
                    <input type="text" name="flashcard_back" value="{{ old('flashcard_back', $gameData['back_text'] ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;">
                </div>
            </div>

            <!-- Jawaban Benar -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Jawaban Benar *</label>
                <input type="text" name="correct_answer" value="{{ old('correct_answer', $item?->correct_answer ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">*Untuk MCQ: a/b/c/d. Untuk Fill/Flashcard: kata kunci.</p>
            </div>

            <!-- Tombol -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
                <a href="{{ route('admin.quiz-items.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Batal</a>
                <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('item_type');
    const optionsContainer = document.getElementById('options-container');
    const flashcardContainer = document.getElementById('flashcard-container');

    function toggleFields() {
        const type = typeSelect.value;
        optionsContainer.style.display = (type === 'mcq') ? 'grid' : 'none';
        flashcardContainer.style.display = (type === 'flashcard') ? 'block' : 'none';
    }

    typeSelect.addEventListener('change', toggleFields);
    toggleFields();
});
</script>
@endpush
@endsection