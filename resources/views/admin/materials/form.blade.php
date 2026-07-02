@extends('layouts.admin')
@section('title', isset($material) ? 'Edit Materi' : 'Tambah Materi')
@section('content')

<div style="max-width: 850px; margin: 0 auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">{{ isset($material) ? '️ Edit Materi' : '➕ Tambah Materi' }}</h1>
        <a href="{{ route('admin.materials.index') }}" style="color: var(--text-muted); text-decoration: none;">← Kembali</a>
    </div>

    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; padding: 24px; box-shadow: var(--shadow); overflow: hidden;">
        <form method="POST" action="{{ isset($material) ? route('admin.materials.update', $material) : route('admin.materials.store') }}" enctype="multipart/form-data">
            @csrf @if(isset($material)) @method('PUT') @endif

            <!-- Level & Judul -->
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Level *</label>
                    <select name="id_level" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; background: white;" required>
                        <option value="">Pilih Level</option>
                        @foreach($levels as $lvl)
                        <option value="{{ $lvl->id_level }}" {{ (old('id_level', $material->id_level ?? '') == $lvl->id_level) ? 'selected' : '' }}>{{ $lvl->title_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Judul Materi *</label>
                    <input type="text" name="title" value="{{ old('title', $material->title ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" required>
                </div>
            </div>

            <!-- Urutan & Referensi -->
            <div style="display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 16px;">
                <div style="flex: 1; min-width: 150px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Urutan</label>
                    <input type="number" name="order" value="{{ old('order', $material->order ?? '1') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Referensi Kitab</label>
                    <input type="text" name="textbook_reference" value="{{ old('textbook_reference', $material->textbook_reference ?? '') }}" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem;" placeholder="bayna_yadaik_j1">
                </div>
            </div>

            <!-- Vocab -->
            <div style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px; margin-bottom: 16px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 12px;">🔤 Konten Vocab</h3>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Isi Manual</label>
                <textarea name="vocab_content" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; font-family: monospace; background: #f9f9f9;">{{ old('vocab_content', $material->vocab_content ?? '') }}</textarea>
                <div style="margin-top: 10px; padding: 12px; background: rgba(138,154,102,0.1); border: 1px solid rgba(138,154,102,0.3); border-radius: 8px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">📎 Upload File Vocab</label>
                    <input type="file" name="vocab_file" accept=".pdf,.doc,.docx" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; background: white; box-sizing: border-box;">
                </div>
            </div>

            <!-- Grammar -->
            <div style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px; margin-bottom: 16px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 12px;">📐 Konten Grammar</h3>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Isi Manual</label>
                <textarea name="grammar_content" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; font-family: monospace; background: #f9f9f9;">{{ old('grammar_content', $material->grammar_content ?? '') }}</textarea>
                <div style="margin-top: 10px; padding: 12px; background: rgba(107,142,122,0.1); border: 1px solid rgba(107,142,122,0.3); border-radius: 8px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;"> Upload File Grammar</label>
                    <input type="file" name="grammar_file" accept=".pdf,.doc,.docx" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; background: white; box-sizing: border-box;">
                </div>
            </div>

            <!-- Dialog -->
            <div style="border-top: 1px solid var(--border); padding-top: 20px; margin-top: 20px; margin-bottom: 16px;">
                <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--primary); margin-bottom: 12px;"> Konten Dialog</h3>
                <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Isi Manual</label>
                <textarea name="dialog_content" rows="3" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box; font-size: 0.95rem; font-family: monospace; background: #f9f9f9;">{{ old('dialog_content', $material->dialog_content ?? '') }}</textarea>
                <div style="margin-top: 10px; padding: 12px; background: rgba(168,93,74,0.1); border: 1px solid rgba(168,93,74,0.3); border-radius: 8px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">📎 Upload File Dialog</label>
                    <input type="file" name="dialog_file" accept=".pdf,.doc,.docx" style="width: 100%; padding: 8px; border: 1px solid var(--border); border-radius: 6px; background: white; box-sizing: border-box;">
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px; padding-top: 16px; border-top: 1px solid var(--border);">
                <a href="{{ route('admin.materials.index') }}" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); text-decoration: none; background: var(--bg-card);">Batal</a>
                <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection