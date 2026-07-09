@extends('layouts.admin')
@section('title', 'Kelola Avatar Level')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ secure_asset('css/admin-avatar.css') }}">
@endpush

@php
    // Stats calculation - flexible approach
    // Jika controller mengirim $stats, gunakan itu. Jika tidak, hitung dari $characters
    $totalAvatars = isset($stats) ? $stats['total'] : $characters->count();
    $activeAvatars = isset($stats) ? $stats['active'] : $characters->where('is_active', true)->count();
    $inactiveAvatars = isset($stats) ? $stats['inactive'] : $characters->where('is_active', false)->count();
    $highestLevel = isset($stats) ? $stats['highest_level'] : ($characters->max('unlock_level') ?? 0);
@endphp

<div class="avatar-admin-page">

    {{-- ============================================
         HEADER
    ============================================ --}}
    <div class="avatar-admin-header">
        <div class="avatar-admin-header__info">
            <h1 class="avatar-admin-header__title">
                <span class="avatar-admin-header__icon">🖼️</span>
                Kelola Avatar Level
            </h1>
            <p class="avatar-admin-header__desc">
                Kelola avatar profil yang akan terbuka secara otomatis sesuai level pengguna.
            </p>
        </div>
        <a href="{{ route('admin.characters.create') }}" class="avatar-admin-header__btn">
            <i class="fas fa-plus"></i>
            <span>Tambah Avatar</span>
        </a>
    </div>


    {{-- ============================================
         STATS DASHBOARD
    ============================================ --}}
    <div class="avatar-stats-grid">
        <div class="avatar-stat-card avatar-stat-card--total">
            <div class="avatar-stat-card__icon">
                <i class="fas fa-images"></i>
            </div>
            <div class="avatar-stat-card__info">
                <span class="avatar-stat-card__label">Total Avatar</span>
                <span class="avatar-stat-card__value">{{ $totalAvatars }}</span>
            </div>
        </div>

        <div class="avatar-stat-card avatar-stat-card--active">
            <div class="avatar-stat-card__icon">
                <i class="fas fa-lock-open"></i>
            </div>
            <div class="avatar-stat-card__info">
                <span class="avatar-stat-card__label">Avatar Aktif</span>
                <span class="avatar-stat-card__value">{{ $activeAvatars }}</span>
            </div>
        </div>

        <div class="avatar-stat-card avatar-stat-card--inactive">
            <div class="avatar-stat-card__icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="avatar-stat-card__info">
                <span class="avatar-stat-card__label">Avatar Nonaktif</span>
                <span class="avatar-stat-card__value">{{ $inactiveAvatars }}</span>
            </div>
        </div>

        <div class="avatar-stat-card avatar-stat-card--level">
            <div class="avatar-stat-card__icon">
                <i class="fas fa-medal"></i>
            </div>
            <div class="avatar-stat-card__info">
                <span class="avatar-stat-card__label">Level Tertinggi</span>
                <span class="avatar-stat-card__value">{{ $highestLevel }}</span>
            </div>
        </div>
    </div>


    {{-- ============================================
         FILTER FORM
    ============================================ --}}
    <form method="GET" class="avatar-filter" action="{{ route('admin.characters.index') }}">
        <div class="avatar-filter__group avatar-filter__group--search">
            <label class="avatar-filter__label">
                <i class="fas fa-search"></i>
                <span>Cari Nama Avatar</span>
            </label>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Ketik nama avatar..." 
                   class="avatar-filter__input">
        </div>

        <div class="avatar-filter__group">
            <label class="avatar-filter__label">
                <i class="fas fa-layer-group"></i>
                <span>Level Unlock</span>
            </label>
            <select name="unlock_level" class="avatar-filter__select">
                <option value="">Semua Level</option>
                @for($i = 1; $i <= 30; $i++)
                    <option value="{{ $i }}" {{ request('unlock_level') == $i ? 'selected' : '' }}>
                        Level {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        <div class="avatar-filter__group">
            <label class="avatar-filter__label">
                <i class="fas fa-toggle-on"></i>
                <span>Status</span>
            </label>
            <select name="status" class="avatar-filter__select">
                <option value="">Semua</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="avatar-filter__actions">
            <button type="submit" class="avatar-filter__btn avatar-filter__btn--primary">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <a href="{{ route('admin.characters.index') }}" class="avatar-filter__btn avatar-filter__btn--secondary">
                <i class="fas fa-redo"></i>
                <span>Reset</span>
            </a>
        </div>
    </form>


    {{-- ============================================
         AVATAR GRID
    ============================================ --}}
    <div class="avatar-grid">
        @forelse($characters as $char)
        @php
            $imgSrc = asset('images/characters/' . $char->id . '.jpg'); 
        @endphp

        <div class="avatar-card {{ $char->is_active ? 'avatar-card--active' : 'avatar-card--inactive' }}">
            {{-- Image --}}
            <div class="avatar-card__image-wrapper">
                <img src="{{ $imgSrc }}" 
                     alt="{{ $char->name }}" 
                     class="avatar-card__image"
                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($char->name) }}&background=775537&color=FBE29C&size=256'">
                
                {{-- Level Badge --}}
                <div class="avatar-card__level-badge">
                    <i class="fas fa-medal"></i>
                    <span>Level {{ $char->unlock_level }}</span>
                </div>

                {{-- Status Badge --}}
                <div class="avatar-card__status-badge {{ $char->is_active ? 'avatar-card__status-badge--active' : 'avatar-card__status-badge--inactive' }}">
                    {{ $char->is_active ? 'Aktif' : 'Nonaktif' }}
                </div>
            </div>

            {{-- Content --}}
            <div class="avatar-card__content">
                <h3 class="avatar-card__name">{{ $char->name }}</h3>
                
                @if($char->description)
                <p class="avatar-card__desc">{{ Str::limit($char->description, 80) }}</p>
                @else
                <p class="avatar-card__desc avatar-card__desc--empty">Tidak ada deskripsi</p>
                @endif

                <div class="avatar-card__meta">
                    <div class="avatar-card__meta-item">
                        <i class="fas fa-unlock"></i>
                        <span>Unlock di Level {{ $char->unlock_level }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="avatar-card__actions">
                <a href="{{ route('admin.characters.edit', $char) }}" class="avatar-card__btn avatar-card__btn--edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <button type="button" 
                        class="avatar-card__btn avatar-card__btn--delete"
                        onclick="openDeleteModal('{{ route('admin.characters.destroy', $char) }}')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </div>
        @empty
        <div class="avatar-empty">
            <div class="avatar-empty__icon">🖼️</div>
            <h3 class="avatar-empty__title">Belum Ada Avatar</h3>
            <p class="avatar-empty__desc">
                Tambahkan avatar pertama untuk mulai membangun progres visual pengguna.
            </p>
            <a href="{{ route('admin.characters.create') }}" class="avatar-empty__btn">
                <i class="fas fa-plus"></i>
                <span>Tambah Avatar</span>
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($characters->hasPages())
    <div class="avatar-pagination">
        {{ $characters->links() }}
    </div>
    @endif

</div>


{{-- ============================================
     DELETE MODAL
============================================ --}}
<div id="deleteModal" class="avatar-modal">
    <div class="avatar-modal__overlay" onclick="closeDeleteModal()"></div>
    <div class="avatar-modal__content">
        <div class="avatar-modal__header">
            <div class="avatar-modal__icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="avatar-modal__title">Konfirmasi Hapus</h3>
        </div>
        
        <div class="avatar-modal__body">
            <p>Apakah Anda yakin ingin menghapus avatar ini?</p>
            <p class="avatar-modal__warning">
                <i class="fas fa-info-circle"></i>
                Tindakan ini tidak dapat dibatalkan. Avatar yang sudah terbuka oleh user akan tetap terlihat.
            </p>
        </div>

        <form id="deleteForm" method="POST" class="avatar-modal__form">
            @csrf
            @method('DELETE')
            <div class="avatar-modal__actions">
                <button type="button" class="avatar-modal__btn avatar-modal__btn--cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
                <button type="submit" class="avatar-modal__btn avatar-modal__btn--danger">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
function openDeleteModal(url) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.add('avatar-modal--active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('avatar-modal--active');
    document.body.style.overflow = '';
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection