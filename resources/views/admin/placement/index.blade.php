@extends('layouts.admin')
@section('title', 'Placement Test Management')
@section('page-title', 'Placement Test Management')

@section('content')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-placement.css') }}">
@endpush

@php
    $totalQuestions = $questions->total();
    $easyCount = \App\Models\PlacementQuestion::where('difficulty', 'easy')->count();
    $mediumCount = \App\Models\PlacementQuestion::where('difficulty', 'medium')->count();
    $hardCount = \App\Models\PlacementQuestion::where('difficulty', 'hard')->count();
    $isReady = $easyCount >= 5 && $mediumCount >= 5 && $hardCount >= 5;
@endphp

<div class="placement-admin">

    {{-- ============================================
         SECTION 1: HEADER
    ============================================ --}}
    <section class="placement-header">
        <div class="placement-header__info">
            <h1 class="placement-header__title">
                <span class="placement-header__icon">📝</span>
                <span>Placement Test Management</span>
            </h1>
            <p class="placement-header__desc">
                Kelola soal placement test untuk menentukan level awal siswa sebelum memulai pembelajaran.
            </p>
        </div>
        <a href="{{ route('admin.placement.create') }}" class="placement-header__btn">
            <i class="fas fa-plus"></i>
            <span>Tambah Soal</span>
        </a>
    </section>


    {{-- ============================================
         SECTION 2: STATISTICS
    ============================================ --}}
    <section class="placement-stats">
        <div class="placement-stat placement-stat--total">
            <div class="placement-stat__icon">
                <i class="fas fa-layer-group"></i>
            </div>
            <div class="placement-stat__info">
                <span class="placement-stat__label">Total Soal</span>
                <span class="placement-stat__value">{{ $totalQuestions }}</span>
            </div>
        </div>

        <div class="placement-stat placement-stat--easy">
            <div class="placement-stat__icon">
                <i class="fas fa-seedling"></i>
            </div>
            <div class="placement-stat__info">
                <span class="placement-stat__label">Soal Easy</span>
                <span class="placement-stat__value">{{ $easyCount }}</span>
            </div>
        </div>

        <div class="placement-stat placement-stat--medium">
            <div class="placement-stat__icon">
                <i class="fas fa-tree"></i>
            </div>
            <div class="placement-stat__info">
                <span class="placement-stat__label">Soal Medium</span>
                <span class="placement-stat__value">{{ $mediumCount }}</span>
            </div>
        </div>

        <div class="placement-stat placement-stat--hard">
            <div class="placement-stat__icon">
                <i class="fas fa-mountain"></i>
            </div>
            <div class="placement-stat__info">
                <span class="placement-stat__label">Soal Hard</span>
                <span class="placement-stat__value">{{ $hardCount }}</span>
            </div>
        </div>
    </section>


    {{-- ============================================
         SECTION 3: WARNING PANEL
    ============================================ --}}
    @if(!$isReady)
    <section class="placement-warning">
        <div class="placement-warning__icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="placement-warning__content">
            <h3 class="placement-warning__title">Placement Test Belum Siap Digunakan</h3>
            <p class="placement-warning__desc">Minimal harus tersedia:</p>
            <ul class="placement-warning__list">
                <li class="{{ $easyCount < 5 ? 'placement-warning__list-item--missing' : '' }}">
                    <i class="fas {{ $easyCount < 5 ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                    <span>5 soal Easy (saat ini: {{ $easyCount }})</span>
                </li>
                <li class="{{ $mediumCount < 5 ? 'placement-warning__list-item--missing' : '' }}">
                    <i class="fas {{ $mediumCount < 5 ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                    <span>5 soal Medium (saat ini: {{ $mediumCount }})</span>
                </li>
                <li class="{{ $hardCount < 5 ? 'placement-warning__list-item--missing' : '' }}">
                    <i class="fas {{ $hardCount < 5 ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                    <span>5 soal Hard (saat ini: {{ $hardCount }})</span>
                </li>
            </ul>
        </div>
    </section>
    @endif


    {{-- ============================================
         SECTION 4: FILTER BAR
    ============================================ --}}
    <form method="GET" action="{{ route('admin.placement.index') }}" class="placement-filter">
        <div class="placement-filter__group placement-filter__group--search">
            <label class="placement-filter__label">
                <i class="fas fa-search"></i>
                <span>Cari Soal</span>
            </label>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Ketik kata kunci soal..."
                   class="placement-filter__input">
        </div>

        <div class="placement-filter__group">
            <label class="placement-filter__label">
                <i class="fas fa-signal"></i>
                <span>Difficulty</span>
            </label>
            <select name="difficulty" class="placement-filter__select">
                <option value="">Semua</option>
                <option value="easy" {{ request('difficulty') === 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ request('difficulty') === 'hard' ? 'selected' : '' }}>Hard</option>
            </select>
        </div>

        <div class="placement-filter__group">
            <label class="placement-filter__label">
                <i class="fas fa-toggle-on"></i>
                <span>Status</span>
            </label>
            <select name="status" class="placement-filter__select">
                <option value="">Semua</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <div class="placement-filter__actions">
            <button type="submit" class="placement-filter__btn placement-filter__btn--primary">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <a href="{{ route('admin.placement.index') }}" class="placement-filter__btn placement-filter__btn--secondary">
                <i class="fas fa-redo"></i>
                <span>Reset</span>
            </a>
        </div>
    </form>


    {{-- ============================================
         SECTION 5: QUESTION GRID
    ============================================ --}}
    <div class="placement-grid">
        @forelse($questions as $question)
        <article class="question-card question-card--{{ $question->difficulty }} {{ !$question->is_active ? 'question-card--inactive' : '' }}">

            {{-- Header Card --}}
            <div class="question-card__header">
                <span class="question-card__difficulty question-card__difficulty--{{ $question->difficulty }}">
                    @if($question->difficulty === 'easy')
                        <i class="fas fa-seedling"></i>
                    @elseif($question->difficulty === 'medium')
                        <i class="fas fa-tree"></i>
                    @else
                        <i class="fas fa-mountain"></i>
                    @endif
                    <span>{{ ucfirst($question->difficulty) }}</span>
                </span>
                <span class="question-card__status {{ $question->is_active ? 'question-card__status--active' : 'question-card__status--inactive' }}">
                    {{ $question->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            {{-- Question Text --}}
            <div class="question-card__body">
                <p class="question-card__text">{{ Str::limit($question->question_text, 150) }}</p>

                {{-- Options --}}
                <ul class="question-card__options">
                    @foreach(['a' => $question->option_a, 'b' => $question->option_b, 'c' => $question->option_c, 'd' => $question->option_d] as $key => $opt)
                    @if($opt)
                    <li class="question-card__option {{ $question->correct_answer === $key ? 'question-card__option--correct' : '' }}">
                        <span class="question-card__option-key">{{ strtoupper($key) }}</span>
                        <span class="question-card__option-text">{{ Str::limit($opt, 50) }}</span>
                        @if($question->correct_answer === $key)
                            <i class="fas fa-check-circle question-card__option-check"></i>
                        @endif
                    </li>
                    @endif
                    @endforeach
                </ul>

                {{-- Correct Answer --}}
                <div class="question-card__answer">
                    <span class="question-card__answer-label">Jawaban Benar:</span>
                    <span class="question-card__answer-value">{{ strtoupper($question->correct_answer) }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="question-card__actions">
                <a href="{{ route('admin.placement.edit', $question) }}" class="question-card__btn question-card__btn--edit">
                    <i class="fas fa-edit"></i>
                    <span>Edit</span>
                </a>
                <button type="button"
                        class="question-card__btn question-card__btn--delete"
                        onclick="openDeleteModal('{{ route('admin.placement.destroy', $question) }}')">
                    <i class="fas fa-trash"></i>
                    <span>Hapus</span>
                </button>
            </div>
        </article>
        @empty
        <div class="placement-empty">
            <div class="placement-empty__icon">📝</div>
            <h3 class="placement-empty__title">Belum Ada Soal</h3>
            <p class="placement-empty__desc">
                Tambahkan soal placement test pertama untuk memulai.
            </p>
            <a href="{{ route('admin.placement.create') }}" class="placement-empty__btn">
                <i class="fas fa-plus"></i>
                <span>Tambah Soal Pertama</span>
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($questions->hasPages())
    <div class="placement-pagination">
        {{ $questions->withQueryString()->links() }}
    </div>
    @endif

</div>


{{-- ============================================
     DELETE MODAL
============================================ --}}
<div id="deleteModal" class="placement-modal">
    <div class="placement-modal__overlay" onclick="closeDeleteModal()"></div>
    <div class="placement-modal__content">
        <div class="placement-modal__header">
            <div class="placement-modal__icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3 class="placement-modal__title">Konfirmasi Hapus Soal</h3>
        </div>

        <div class="placement-modal__body">
            <p>Apakah Anda yakin ingin menghapus soal placement ini?</p>
            <div class="placement-modal__warning">
                <i class="fas fa-info-circle"></i>
                <span>Tindakan ini tidak dapat dibatalkan. Soal yang sudah dijawab siswa akan tetap tercatat di riwayat.</span>
            </div>
        </div>

        <form id="deleteForm" method="POST" class="placement-modal__form">
            @csrf
            @method('DELETE')
            <div class="placement-modal__actions">
                <button type="button" class="placement-modal__btn placement-modal__btn--cancel" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                    <span>Batal</span>
                </button>
                <button type="submit" class="placement-modal__btn placement-modal__btn--danger">
                    <i class="fas fa-trash"></i>
                    <span>Ya, Hapus</span>
                </button>
            </div>
        </form>
    </div>
</div>


@push('scripts')
<script>
function openDeleteModal(url) {
    document.getElementById('deleteForm').action = url;
    document.getElementById('deleteModal').classList.add('placement-modal--active');
    document.body.style.overflow = 'hidden';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('placement-modal--active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection