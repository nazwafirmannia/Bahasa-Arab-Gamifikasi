@extends('layouts.admin')
@section('title', 'Kelola Stage')
@section('content')

<div style="max-width: 900px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">🌱 Kelola Stage</h1>
        <a href="{{ route('admin.stages.create') }}" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; text-decoration: none;">
            <i class="fas fa-plus"></i> Tambah Stage
        </a>
    </div>

    <!-- Filter -->
    <form method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; background: var(--bg-card); padding: 16px; border: 1px solid var(--border); border-radius: 12px;">
        <div style="flex: 2; min-width: 200px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Cari Stage</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama stage..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box;">
        </div>
        <div style="display: flex; align-items: flex-end; gap: 8px;">
            <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">🔍 Cari</button>
            <a href="{{ route('admin.stages.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Reset</a>
        </div>
    </form>

    <!-- Table -->
    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--bg-card-hover);">
                <tr>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 50%;">Nama Stage</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 15%;">Urutan</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 15%;">Level</th>
                    <th style="padding: 14px 16px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 20%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stages as $s)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 14px 16px; font-weight: 600;">{{ $s->stage_name }}</td>
                    <td style="padding: 14px 16px;">{{ $s->urutan }}</td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: var(--accent-teal); color: white;">
                            {{ $s->levels_count }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <a href="{{ route('admin.stages.edit', $s) }}" style="color: var(--accent-teal); text-decoration: none; margin-right: 12px; font-weight: 500;">Edit</a>
                        <form method="POST" action="{{ route('admin.stages.destroy', $s) }}" style="display: inline;" onsubmit="return confirm('Hapus stage ini? Level terkait akan ikut terhapus.')">
                            @csrf @method('DELETE')
                            <button type="submit" style="color: var(--accent-terracotta); background: none; border: none; cursor: pointer; font-weight: 500;">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada stage.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
        {{ $stages->links() }}
    </div>
</div>
@endsection