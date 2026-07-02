@extends('layouts.admin')
@section('title', isset($level) ? 'Edit Level' : 'Tambah Level')
@section('content')

<div style="max-width: 700px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ isset($level) ? '✏️ Edit Level' : '➕ Tambah Level' }}</h1>
        <a href="{{ route('admin.levels.index') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); overflow: hidden;">
        <form method="POST" action="{{ isset($level) ? route('admin.levels.update', $level) : route('admin.levels.store') }}">
            @csrf @if(isset($level)) @method('PUT') @endif

            <!-- Stage -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Stage *</label>
                <select name="id_stage" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                    <option value="">Pilih Stage</option>
                    @foreach($stages as $s)
                    <option value="{{ $s->id_stage }}" {{ (old('id_stage', $level->id_stage ?? '') == $s->id_stage) ? 'selected' : '' }}>{{ $s->stage_name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Judul Level -->
            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Judul Level *</label>
                <input type="text" name="title_level" value="{{ old('title_level', $level->title_level ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
            </div>

            <!-- Baris Urutan & Kunci -->
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Urutan</label>
                    <input type="number" name="level_order" value="{{ old('level_order', $level->level_order ?? '1') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
                
                <!-- Checkbox Area -->
                <div style="flex: 1; min-width: 150px; display: flex; align-items: center; gap: 10px; padding-top: 28px;">
                    <input type="checkbox" name="status_kunci" id="status_kunci" value="1" {{ old('status_kunci', $level->status_kunci ?? false) ? 'checked' : '' }} style="width: 20px; height: 20px; cursor: pointer; accent-color: var(--primary);">
                    <label for="status_kunci" style="font-weight: 600; font-size: 0.9rem; color: var(--text-dark); cursor: pointer; margin: 0;">Terkunci (Default)</label>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
                <a href="{{ route('admin.levels.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Batal</a>
                <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection