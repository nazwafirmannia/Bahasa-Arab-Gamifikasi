@extends('layouts.admin')
@section('title', isset($question) ? 'Edit Soal' : 'Tambah Soal')
@section('content')

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ isset($question) ? '✏️ Edit Soal Latihan' : '➕ Tambah Soal Latihan' }}</h1>
        <a href="{{ route('admin.questions.index') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); overflow: hidden;">
        <form method="POST" action="{{ isset($question) ? route('admin.questions.update', $question) : route('admin.questions.store') }}">
            @csrf @if(isset($question)) @method('PUT') @endif

            <!-- Materi & Tipe -->
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1.5; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Materi Terkait</label>
                    <select name="id_material" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="">Pilih Materi</option>
                        @foreach($materials as $mat)
                        <option value="{{ $mat->id_material }}" {{ (old('id_material', $question->id_material ?? '') == $mat->id_material) ? 'selected' : '' }}>{{ $mat->title }} ({{ $mat->level->title_level ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Tipe Soal</label>
                    <select name="question_type" id="question_type" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="mcq" {{ old('question_type', $question->question_type ?? 'mcq') == 'mcq' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="fill" {{ old('question_type', $question->question_type ?? '') == 'fill' ? 'selected' : '' }}>Isian Singkat</option>
                    </select>
                </div>
            </div>

            <!-- Pertanyaan -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Pertanyaan</label>
                <textarea name="question_text" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>{{ old('question_text', $question->question_text ?? '') }}</textarea>
            </div>

            <!-- Opsi MCQ (Hidden kalau Fill) -->
            <div id="options-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 16px;">
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi A</label><input type="text" name="option_a" value="{{ old('option_a', $question->option_a ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi B</label><input type="text" name="option_b" value="{{ old('option_b', $question->option_b ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi C</label><input type="text" name="option_c" value="{{ old('option_c', $question->option_c ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
                <div><label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Opsi D</label><input type="text" name="option_d" value="{{ old('option_d', $question->option_d ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;"></div>
            </div>

            <!-- Jawaban & Difficulty -->
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1.5; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Jawaban Benar</label>
                    <input type="text" name="correct_answer" value="{{ old('correct_answer', $question->correct_answer ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Difficulty</label>
                    <select name="difficulty" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="easy" {{ old('difficulty', $question->difficulty ?? 'easy') == 'easy' ? 'selected' : '' }}> Easy</option>
                        <option value="medium" {{ old('difficulty', $question->difficulty ?? '') == 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="hard" {{ old('difficulty', $question->difficulty ?? '') == 'hard' ? 'selected' : '' }}>🔴 Hard</option>
                    </select>
                </div>
            </div>

            <!-- Penjelasan -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Penjelasan / Feedback</label>
                <textarea name="explanation" rows="2" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;">{{ old('explanation', $question->explanation ?? '') }}</textarea>
            </div>

            <!-- PDF Upload -->
            <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px; margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">📎 Lampiran PDF Soal (Opsional)</label>
                <input type="file" name="pdf_file" accept=".pdf" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; background: white; box-sizing: border-box;">
                @if(isset($question) && $question->pdf_file)
                <div style="margin-top: 8px; font-size: 0.85rem; color: var(--text-muted);">
                    <i class="fas fa-file-pdf" style="color: #dc2626;"></i> File saat ini: <a href="{{ secure_asset('storage/'.$question->pdf_file) }}" target="_blank" style="color: var(--primary);">{{ basename($question->pdf_file) }}</a>
                </div>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
                <a href="{{ route('admin.questions.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Batal</a>
                <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">💾 Simpan Soal</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('question_type');
    const optionsContainer = document.getElementById('options-container');
    function toggleOptions() {
        optionsContainer.style.display = typeSelect.value === 'fill' ? 'none' : 'grid';
    }
    typeSelect.addEventListener('change', toggleOptions);
    toggleOptions();
});
</script>
@endpush
@endsection