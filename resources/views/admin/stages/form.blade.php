@extends('layouts.admin')
@section('title', isset($stage) ? 'Edit Stage' : 'Tambah Stage')
@section('content')

<div style="max-width: 750px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ isset($stage) ? '✏️ Edit Stage' : '➕ Tambah Stage' }}</h1>
        <a href="{{ route('admin.stages.index') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); overflow: hidden;">
        <form method="POST" action="{{ isset($stage) ? route('admin.stages.update', $stage) : route('admin.stages.store') }}">
            @csrf @if(isset($stage)) @method('PUT') @endif

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Nama Stage</label>
                <input type="text" name="stage_name" value="{{ old('stage_name', $stage->stage_name ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Urutan</label>
                    <input type="number" name="urutan" value="{{ old('urutan', $stage->urutan ?? '1') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">XP Multiplier</label>
                    <input type="number" step="0.1" name="xp_multiplier" value="{{ old('xp_multiplier', $stage->xp_multiplier ?? '1.0') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
            </div>

            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Coin Multiplier</label>
                    <input type="number" step="0.1" name="coin_multiplier" value="{{ old('coin_multiplier', $stage->coin_multiplier ?? '1.0') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
                <div style="flex: 1; min-width: 140px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Base XP/Materi</label>
                    <input type="number" name="base_xp_material" value="{{ old('base_xp_material', $stage->base_xp_material ?? '10') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Base Coin/Materi</label>
                <input type="number" name="base_coin_material" value="{{ old('base_coin_material', $stage->base_coin_material ?? '5') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
                <a href="{{ route('admin.stages.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Batal</a>
                <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection