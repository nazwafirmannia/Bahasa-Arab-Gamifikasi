@extends('layouts.admin')
@section('title', 'Kelola Materi')
@section('content')

<div style="max-width: 1200px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">📚 Kelola Materi</h1>
        <a href="{{ route('admin.materials.create') }}" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Materi
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; background: var(--bg-card); padding: 16px; border: 1px solid var(--border); border-radius: 12px;">
        <div style="flex: 2; min-width: 200px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Cari Judul</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul materi..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box;">
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
            <a href="{{ route('admin.materials.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead style="background: var(--bg-card-hover);">
                <tr>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">ID</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 25%;">Judul</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 20%;">Level/Stage</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">Urutan</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 8%;">Soal</th>
                    <th style="padding: 14px 16px; text-align: center; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 18%;">📎 File</th>
                    <th style="padding: 14px 16px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 13%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $mat)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 14px 16px; font-size: 0.9rem; color: var(--text-muted);">#{{ $mat->id_material }}</td>
                    <td style="padding: 14px 16px; font-size: 0.9rem; font-weight: 600;">{{ $mat->title }}</td>
                    <td style="padding: 14px 16px; font-size: 0.9rem;">
                        {{ $mat->level->title_level ?? '-' }}
                        <span style="font-size: 0.8rem; color: var(--text-muted); display: block;">({{ $mat->level->stage->stage_name ?? '-' }})</span>
                    </td>
                    <td style="padding: 14px 16px; font-size: 0.9rem;">{{ $mat->order }}</td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #DBEAFE; color: #1E40AF;">
                            {{ $mat->questions->count() }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 12px;">
                            <a href="{{ $mat->vocab_file ? asset('storage/'.$mat->vocab_file) : '#' }}" target="{{ $mat->vocab_file ? '_blank' : '' }}" style="color: {{ $mat->vocab_file ? 'var(--accent-green)' : 'var(--text-muted)' }}; text-decoration: none;" title="Vocab">
                                <i class="fas fa-file-alt"></i>
                            </a>
                            <a href="{{ $mat->grammar_file ? asset('storage/'.$mat->grammar_file) : '#' }}" target="{{ $mat->grammar_file ? '_blank' : '' }}" style="color: {{ $mat->grammar_file ? 'var(--accent-teal)' : 'var(--text-muted)' }}; text-decoration: none;" title="Grammar">
                                <i class="fas fa-file-code"></i>
                            </a>
                            <a href="{{ $mat->dialog_file ? asset('storage/'.$mat->dialog_file) : '#' }}" target="{{ $mat->dialog_file ? '_blank' : '' }}" style="color: {{ $mat->dialog_file ? 'var(--accent-terracotta)' : 'var(--text-muted)' }}; text-decoration: none;" title="Dialog">
                                <i class="fas fa-comments"></i>
                            </a>
                        </div>
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <a href="{{ route('admin.materials.edit', $mat) }}" style="color: var(--accent-teal); text-decoration: none; margin-right: 12px; font-weight: 500;">Edit</a>
                        <form method="POST" action="{{ route('admin.materials.destroy', $mat) }}" style="display: inline;" onsubmit="return confirm('Hapus materi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: var(--accent-terracotta); background: none; border: none; cursor: pointer; font-weight: 500;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada materi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
        {{ $materials->links() }}
    </div>
</div>
@endsection