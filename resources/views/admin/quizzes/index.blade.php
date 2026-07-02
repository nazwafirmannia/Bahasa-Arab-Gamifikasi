@extends('layouts.admin')
@section('title', 'Kelola Quiz Level')
@section('content')

<div style="max-width: 1100px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">🎯 Kelola Quiz Level</h1>
        <a href="{{ route('admin.quizzes.create') }}" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Quiz
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; background: var(--bg-card); padding: 16px; border: 1px solid var(--border); border-radius: 12px;">
        <div style="flex: 2; min-width: 200px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Cari Quiz</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul quiz..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box;">
        </div>
        <div style="flex: 1; min-width: 180px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Level</label>
            <select name="level_id" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; background: white;">
                <option value="">Semua Level</option>
                @foreach($levels as $lvl)
                <option value="{{ $lvl->id_level }}" {{ request('level_id') == $lvl->id_level ? 'selected' : '' }}>
                    {{ $lvl->title_level }} ({{ $lvl->stage->stage_name ?? '-' }})
                </option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; align-items: flex-end; gap: 8px;">
            <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">🔍 Filter</button>
            <a href="{{ route('admin.quizzes.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead style="background: var(--bg-card-hover);">
                <tr>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 30%;">Judul Quiz</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 20%;">Level</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 12%;">Format</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 12%;">Passing</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 10%;">Status</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">PDF</th>
                    <th style="padding: 14px 16px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 14px 16px; font-weight: 600;">{{ $quiz->title }}</td>
                    <td style="padding: 14px 16px;">{{ $quiz->level->title_level ?? '-' }}</td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #DBEAFE; color: #1E40AF;">
                            {{ $quiz->quiz_format === 'mcq' ? 'MCQ' : 'Campuran' }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; font-weight: 600;">{{ $quiz->passing_score }}%</td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; {{ $quiz->status === 'active' ? 'background: #DCFCE7; color: #166534;' : 'background: #E5E7EB; color: #374151;' }}">
                            {{ $quiz->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        @if($quiz->pdf_file)
                        <a href="{{ secure_asset('storage/'.$quiz->pdf_file) }}" target="_blank" style="color: var(--accent-terracotta); text-decoration: none;">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        @else
                        <span style="color: var(--text-muted);">-</span>
                        @endif
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <a href="{{ route('admin.quizzes.edit', $quiz) }}" style="color: var(--accent-teal); text-decoration: none; margin-right: 12px; font-weight: 500;">Edit</a>
                        <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" style="display: inline;" onsubmit="return confirm('Hapus quiz ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: var(--accent-terracotta); background: none; border: none; cursor: pointer; font-weight: 500;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada quiz level.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection