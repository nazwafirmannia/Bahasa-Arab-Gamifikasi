@extends('layouts.admin')
@section('title', isset($character) ? 'Edit Karakter Evolusi' : 'Tambah Karakter Evolusi')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/admin-character-form.css') }}">
@endpush

@php
    $isEdit = isset($character);
    $formTitle = $isEdit ? 'Edit Karakter Evolusi' : 'Tambah Karakter Evolusi';
    $formAction = $isEdit ? route('admin.characters.update', $character) : route('admin.characters.store');
    $formMethod = $isEdit ? 'PUT' : 'POST';
    
    // Existing image URL for preview
    $existingImage = null;
    if ($isEdit && $character->image) {
        $existingImage = filter_var($character->image, FILTER_VALIDATE_URL) 
            ? $character->image 
            : asset('storage/' . $character->image);
    }
@endphp

<div class="character-form-page">

    {{-- ============================================
         HERO HEADER
    ============================================ --}}
    <section class="character-form-header">
        <div class="character-form-header__info">
            <a href="{{ route('admin.characters.index') }}" class="character-form-header__back">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar Avatar</span>
            </a>
            <h1 class="character-form-header__title">
                <span class="character-form-header__icon">{{ $isEdit ? '✏️' : '➕' }}</span>
                <span>{{ $formTitle }}</span>
            </h1>
            <p class="character-form-header__desc">
                {{ $isEdit ? 'Perbarui informasi avatar evolusi yang akan terbuka otomatis sesuai level pengguna.' : 'Buat avatar evolusi baru yang akan terbuka otomatis saat user mencapai level tertentu.' }}
            </p>
        </div>
    </section>


    {{-- ============================================
         VALIDATION ERRORS
    ============================================ --}}
    @if($errors->any())
    <div class="character-form-alert character-form-alert--error">
        <div class="character-form-alert__icon">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div class="character-form-alert__content">
            <h4 class="character-form-alert__title">Terdapat Kesalahan Validasi</h4>
            <ul class="character-form-alert__list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif


    {{-- ============================================
         FORM GRID (Form + Preview)
    ============================================ --}}
    <div class="character-form-grid">

        {{-- LEFT: FORM --}}
        <div class="character-form-main">
            <form method="POST" 
                  action="{{ $formAction }}" 
                  enctype="multipart/form-data" 
                  class="character-form" 
                  id="characterForm">
                @csrf
                @if($isEdit)
                    @method('PUT')
                @endif

                {{-- Nama Karakter --}}
                <div class="character-form-group">
                    <label class="character-form-label" for="name">
                        <i class="fas fa-user-tag"></i>
                        <span>Nama Karakter <span class="character-form-label__required">*</span></span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $character->name ?? '') }}" 
                           class="character-form-input" 
                           placeholder="Contoh: Avatar Pemula, Avatar Penjelajah" 
                           required>
                    @error('name')
                        <span class="character-form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Level Unlock --}}
                <div class="character-form-group">
                    <label class="character-form-label" for="unlock_level">
                        <i class="fas fa-medal"></i>
                        <span>Level Unlock <span class="character-form-label__required">*</span></span>
                    </label>
                    <input type="number" 
                           id="unlock_level" 
                           name="unlock_level" 
                           value="{{ old('unlock_level', $character->unlock_level ?? 1) }}" 
                           class="character-form-input" 
                           placeholder="Masukkan level unlock (minimal 1)" 
                           min="1" 
                           required>
                    <small class="character-form-hint">
                        <i class="fas fa-info-circle"></i>
                        Avatar akan terbuka otomatis saat user mencapai level ini
                    </small>
                    @error('unlock_level')
                        <span class="character-form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="character-form-group">
                    <label class="character-form-label" for="description">
                        <i class="fas fa-align-left"></i>
                        <span>Deskripsi</span>
                    </label>
                    <textarea id="description" 
                              name="description" 
                              class="character-form-textarea" 
                              placeholder="Deskripsi singkat tentang karakter ini..." 
                              rows="3">{{ old('description', $character->description ?? '') }}</textarea>
                    @error('description')
                        <span class="character-form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Upload Gambar --}}
                <div class="character-form-group">
                    <label class="character-form-label" for="image">
                        <i class="fas fa-image"></i>
                        <span>Gambar Avatar <span class="character-form-label__required">*</span></span>
                    </label>
                    
                    <div class="character-form-upload">
                        <input type="file" 
                               id="image" 
                               name="image" 
                               class="character-form-upload__input" 
                               accept="image/*"
                               {{ !$isEdit ? 'required' : '' }}>
                        <div class="character-form-upload__placeholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik untuk upload gambar atau drag & drop</span>
                            <small>Format: JPG, PNG, WebP. Maksimal 2MB.</small>
                        </div>
                    </div>

                    @if($existingImage)
                    <div class="character-form-upload__current">
                        <span class="character-form-upload__current-label">Gambar saat ini:</span>
                        <img src="{{ asset('images/characters/' . $character->id . '.jpg') }}" 
                             alt="{{ $character->name ?? 'Current avatar' }}" 
                             class="character-form-upload__current-image"
                             id="existingImagePreview">
                    </div>
                    @endif

                    @error('image')
                        <span class="character-form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Status Aktif --}}
                <div class="character-form-group character-form-group--checkbox">
                    <label class="character-form-checkbox">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" 
                               name="is_active" 
                               value="1" 
                               class="character-form-checkbox__input" 
                               id="is_active"
                               {{ old('is_active', $character->is_active ?? true) ? 'checked' : '' }}>
                        <span class="character-form-checkbox__box">
                            <i class="fas fa-check"></i>
                        </span>
                        <span class="character-form-checkbox__label">
                            <strong>Aktifkan Avatar</strong>
                            <small>Avatar yang aktif akan tampil dalam sistem evolusi</small>
                        </span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="character-form-actions">
                    <a href="{{ route('admin.characters.index') }}" class="character-form-actions__btn character-form-actions__btn--cancel">
                        <i class="fas fa-times"></i>
                        <span>Batal</span>
                    </a>
                    <button type="submit" class="character-form-actions__btn character-form-actions__btn--submit">
                        <i class="fas fa-save"></i>
                        <span>{{ $isEdit ? 'Update Karakter' : 'Simpan Karakter' }}</span>
                    </button>
                </div>
            </form>
        </div>


        {{-- RIGHT: PREVIEW PANEL --}}
        <aside class="character-form-preview">
            <div class="preview-card">
                <div class="preview-card__header">
                    <i class="fas fa-eye"></i>
                    <span>Preview Avatar</span>
                </div>

                <div class="preview-card__body">
                    {{-- Image Preview --}}
                    <div class="preview-image" id="previewImageContainer">
                        @if($existingImage)
                        <img src="{{ asset('images/characters/' . $character->id . '.jpg') }}" 
                                 alt="Preview" 
                                 class="preview-image__img" 
                                 id="previewImage">
                        @else
                            <div class="preview-image__placeholder" id="previewImagePlaceholder">
                                <i class="fas fa-image"></i>
                                <span>Belum ada gambar</span>
                            </div>
                        @endif
                    </div>

                    {{-- Character Info --}}
                    <div class="preview-info">
                        <h3 class="preview-info__name" id="previewName">
                            {{ old('name', $character->name ?? 'Nama Karakter') }}
                        </h3>

                        <div class="preview-info__level" id="previewLevel">
                            <i class="fas fa-medal"></i>
                            <span>Level {{ old('unlock_level', $character->unlock_level ?? 1) }}</span>
                        </div>

                        <div class="preview-info__status {{ (old('is_active', $character->is_active ?? true)) ? 'preview-info__status--active' : 'preview-info__status--inactive' }}" id="previewStatus">
                            {{ (old('is_active', $character->is_active ?? true)) ? 'Aktif' : 'Nonaktif' }}
                        </div>

                        <p class="preview-info__desc" id="previewDesc">
                            {{ old('description', $character->description ?? 'Deskripsi karakter akan muncul di sini...') }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const levelInput = document.getElementById('unlock_level');
    const descInput = document.getElementById('description');
    const activeInput = document.getElementById('is_active');
    const imageInput = document.getElementById('image');

    const previewName = document.getElementById('previewName');
    const previewLevel = document.getElementById('previewLevel');
    const previewDesc = document.getElementById('previewDesc');
    const previewStatus = document.getElementById('previewStatus');
    const previewImage = document.getElementById('previewImage');
    const previewImagePlaceholder = document.getElementById('previewImagePlaceholder');
    const previewImageContainer = document.getElementById('previewImageContainer');

    // Update preview on input change
    function updatePreview() {
        // Name
        const name = nameInput.value.trim();
        previewName.textContent = name || 'Nama Karakter';

        // Level
        const level = levelInput.value || 1;
        previewLevel.innerHTML = '<i class="fas fa-medal"></i><span>Level ' + level + '</span>';

        // Description
        const desc = descInput.value.trim();
        previewDesc.textContent = desc || 'Deskripsi karakter akan muncul di sini...';

        // Status
        const isActive = activeInput.checked;
        previewStatus.textContent = isActive ? 'Aktif' : 'Nonaktif';
        previewStatus.className = 'preview-info__status ' + (isActive ? 'preview-info__status--active' : 'preview-info__status--inactive');
    }

    // Image preview from file input
    function handleImageUpload(e) {
        const file = e.target.files[0];
        if (!file) return;

        if (!file.type.match('image.*')) {
            alert('Hanya file gambar yang diperbolehkan!');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB!');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            // Remove placeholder if exists
            if (previewImagePlaceholder) {
                previewImagePlaceholder.style.display = 'none';
            }

            // Create or update image
            if (!previewImage) {
                const img = document.createElement('img');
                img.id = 'previewImage';
                img.className = 'preview-image__img';
                img.src = e.target.result;
                img.alt = 'Preview';
                previewImageContainer.appendChild(img);
            } else {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
            }
        };
        reader.readAsDataURL(file);
    }

    // Attach event listeners
    nameInput.addEventListener('input', updatePreview);
    levelInput.addEventListener('input', updatePreview);
    descInput.addEventListener('input', updatePreview);
    activeInput.addEventListener('change', updatePreview);
    imageInput.addEventListener('change', handleImageUpload);

    // Drag and drop support
    const uploadArea = document.querySelector('.character-form-upload');
    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('character-form-upload--dragover');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('character-form-upload--dragover');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('character-form-upload--dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                imageInput.files = files;
                handleImageUpload({ target: { files: files } });
            }
        });

        // Click to upload
        uploadArea.addEventListener('click', function() {
            imageInput.click();
        });
    }
});
</script>
@endpush
@endsection