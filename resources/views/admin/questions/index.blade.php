@extends('layouts.admin')
@section('title', 'Kelola Soal Latihan')
@section('content')

<div style="max-width: 1200px; margin: 0 auto;">
    <!-- Header: Judul & Tombol Tambah -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">📝 Kelola Soal Latihan</h1>
        <a href="{{ route('admin.questions.create') }}" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Soal
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; background: var(--bg-card); padding: 16px; border: 1px solid var(--border); border-radius: 12px;">
        <div style="flex: 2; min-width: 200px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Cari Soal</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik pertanyaan..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box;">
        </div>
        <div style="flex: 1; min-width: 120px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Difficulty</label>
            <select name="difficulty" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; background: white;">
                <option value="">Semua</option>
                <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
            </select>
        </div>
        <div style="flex: 1.5; min-width: 180px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Materi</label>
            <select name="material_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; background: white;">
                <option value="">Semua Materi</option>
                @foreach($materials as $mat)
                <option value="{{ $mat->id_material }}" {{ request('material_id') == $mat->id_material ? 'selected' : '' }}>
                    {{ $mat->title }} ({{ $mat->level->title_level ?? '-' }})
                </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; align-items: flex-end; gap: 8px;">
            <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">🔍 Filter</button>
            <a href="{{ route('admin.questions.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead style="background: var(--bg-card-hover);">
                <tr>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">ID</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 35%;">Pertanyaan</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 20%;">Materi</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 12%;">Tipe</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 10%;">Diff</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">PDF</th>
                    <th style="padding: 14px 16px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 7%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($questions as $q)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 14px 16px; font-size: 0.9rem; color: var(--text-muted);">#{{ $q->id_question }}</td>
                    <td style="padding: 14px 16px; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $q->question_text }}">{{ $q->question_text }}</td>
                    <td style="padding: 14px 16px; font-size: 0.9rem;">
                        <span style="font-weight: 600;">{{ $q->material->title ?? '-' }}</span>
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">{{ $q->material->level->title_level ?? '' }}</span>
                    </td>
                    <td style="padding: 14px 16px;">
                        @php
                        $badge = $q->question_type === 'mcq' 
                            ? 'background: #DBEAFE; color: #1E40AF;' 
                            : 'background: #F3E8FF; color: #6B21A8;';
                        $label = $q->question_type === 'mcq' ? 'MCQ' : 'Fill';
                        @endphp
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; {{ $badge }}">
                            {{ $label }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px;">
                        @php
                        $diff = match($q->difficulty) {
                            'easy' => 'background: #DCFCE7; color: #166534;',
                            'medium' => 'background: #FEF3C7; color: #92400E;',
                            'hard' => 'background: #FEE2E2; color: #991B1B;',
                            default => 'background: #E5E7EB; color: #374151;'
                        };
                        @endphp
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; {{ $diff }}">
                            {{ $q->difficulty }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        @if($q->pdf_file)
                        <a href="{{ asset('storage/'.$q->pdf_file) }}" target="_blank" style="color: var(--accent-terracotta); text-decoration: none; font-weight: 500;">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        @else
                        <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <a href="{{ route('admin.questions.edit', $q) }}" style="color: var(--accent-teal); text-decoration: none; margin-right: 12px; font-weight: 500;">Edit</a>
                        <form method="POST" action="{{ route('admin.questions.destroy', $q) }}" style="display: inline;" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: var(--accent-terracotta); background: none; border: none; cursor: pointer; font-weight: 500;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada soal latihan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination (tanpa Tailwind preset) -->
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
        {{ $questions->links() }}
    </div>
</div>
@endsection