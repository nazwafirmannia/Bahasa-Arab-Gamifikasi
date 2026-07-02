@extends('layouts.admin')
@section('title', isset($quiz) ? 'Edit Quiz' : 'Tambah Quiz')
@section('content')

<div style="max-width: 800px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ isset($quiz) ? '✏️ Edit Quiz Level' : '➕ Tambah Quiz Level' }}</h1>
        <a href="{{ route('admin.quizzes.index') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); overflow: hidden;">
        <form method="POST" action="{{ isset($quiz) ? route('admin.quizzes.update', $quiz) : route('admin.quizzes.store') }}" enctype="multipart/form-data">
            @csrf @if(isset($quiz)) @method('PUT') @endif

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Judul Quiz</label>
                <input type="text" name="title" value="{{ old('title', $quiz->title ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Level Target</label>
                    <select name="id_level" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="">Pilih Level</option>
                        @foreach($levels as $lvl)
                        <option value="{{ $lvl->id_level }}" {{ (old('id_level', $quiz->id_level ?? '') == $lvl->id_level) ? 'selected' : '' }}>{{ $lvl->title_level }} ({{ $lvl->stage->stage_name ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Format Quiz</label>
                    <select name="quiz_format" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="mcq" {{ old('quiz_format', $quiz->quiz_format ?? 'mcq') == 'mcq' ? 'selected' : '' }}>Pilihan Ganda</option>
                        <option value="mix" {{ old('quiz_format', $quiz->quiz_format ?? '') == 'mix' ? 'selected' : '' }}>Campuran</option>
                    </select>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Passing Score (%)</label>
                    <input type="number" name="passing_score" min="0" max="100" value="{{ old('passing_score', $quiz->passing_score ?? '70') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Waktu (Detik)</label>
                    <input type="number" name="time_limit_sec" min="0" value="{{ old('time_limit_sec', $quiz->time_limit_sec ?? '900') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;">
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">0 = Unlimited</p>
                </div>
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Maksimum Attempt</label>
                    <input type="number" name="max_attempts" min="1" value="{{ old('max_attempts', $quiz->max_attempts ?? '3') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Status</label>
                <select name="status" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                    <option value="active" {{ old('status', $quiz->status ?? 'active') == 'active' ? 'selected' : '' }}>Active (Aktif)</option>
                    <option value="inactive" {{ old('status', $quiz->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive (Nonaktif)</option>
                </select>
            </div>

            <div style="border-top: 1px solid var(--border); padding-top: 16px; margin-top: 16px; margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">📎 Lampiran PDF Quiz (Opsional)</label>
                <input type="file" name="pdf_file" accept=".pdf" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; background: white; box-sizing: border-box;">
                @if(isset($quiz) && $quiz->pdf_file)
                <div style="margin-top: 8px; font-size: 0.85rem; color: var(--text-muted);">
                    <i class="fas fa-file-pdf" style="color: #dc2626;"></i> File saat ini: <a href="{{ asset('storage/'.$quiz->pdf_file) }}" target="_blank" style="color: var(--primary);">{{ basename($quiz->pdf_file) }}</a>
                </div>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
                <a href="{{ route('admin.quizzes.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Batal</a>
                <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection