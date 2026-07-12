@extends('layouts.app')
@section('title', 'Leaderboard')
@section('page-title', 'Leaderboard')

@section('content')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">

@endpush

@php
    $currentUserStat = $rankings->firstWhere('id_user', Auth::id());
    $topXp = $top3->isNotEmpty() ? $top3->first()->xp_total : 0;
@endphp

<div class="leaderboard-page">

    {{-- ============================================
         HERO SECTION
    ============================================ --}}
    <section class="leaderboard-hero" aria-label="Leaderboard Header">
        <div class="leaderboard-hero__glow" aria-hidden="true"></div>
        <div class="leaderboard-hero__particles" aria-hidden="true">
            @for($i = 0; $i < 15; $i++)
                <span class="leaderboard-hero__particle" style="--i: {{ $i }};"></span>
            @endfor
        </div>

        <div class="leaderboard-hero__content">
            <div class="leaderboard-hero__badge">
                <i class="fas fa-trophy" aria-hidden="true"></i>
                <span>HALL OF FAME</span>
            </div>

            <h1 class="leaderboard-hero__title">
                <span class="leaderboard-hero__title-icon" aria-hidden="true">🏆</span>
                <span>Hall of Fame</span>
            </h1>

            <p class="leaderboard-hero__subtitle">
                Kumpulkan XP sebanyak mungkin dan raih peringkat tertinggi.
            </p>

            @if($rankings->total() > 0)
            <div class="leaderboard-hero__stat">
                <div class="leaderboard-hero__stat-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>
                <div class="leaderboard-hero__stat-info">
                    <span class="leaderboard-hero__stat-label">Total Peserta</span>
                    <span class="leaderboard-hero__stat-value">{{ $rankings->total() }}</span>
                </div>
            </div>
            @endif
        </div>
    </section>


    {{-- ============================================
         FILTER SECTION (Segmented Control)
    ============================================ --}}
    <section class="leaderboard-filter" aria-label="Filter Leaderboard">
        <div class="leaderboard-filter__control">
            <a href="{{ route('user.leaderboard', ['filter' => 'global']) }}"
               class="leaderboard-filter__btn {{ $filter === 'global' ? 'leaderboard-filter__btn--active' : '' }}">
                <i class="fas fa-globe" aria-hidden="true"></i>
                <span>Global</span>
            </a>
            <a href="{{ route('user.leaderboard', ['filter' => 'stage', 'stage_id' => Auth::user()->stat->current_stage_id]) }}"
               class="leaderboard-filter__btn {{ $filter === 'stage' ? 'leaderboard-filter__btn--active' : '' }}">
                <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                <span>Stage Saya</span>
            </a>
        </div>
    </section>


    {{-- ============================================
         TOP 3 PODIUM
    ============================================ --}}
    @if($top3->isNotEmpty())
    <section class="leaderboard-podium" aria-label="Top 3 Ranking">
        <div class="leaderboard-podium__header">
            <span class="leaderboard-podium__icon" aria-hidden="true">🏆</span>
            <h2 class="leaderboard-podium__title">Top 3 Peringkat</h2>
        </div>

        <div class="leaderboard-podium__stage">
            {{-- Rank 2 (Left) --}}
            @if($top3->count() >= 2)
            <div class="podium-item podium-item--second">
                <div class="podium-item__avatar-wrapper">
                    <img
                    src="{{ asset('images/characters/' . ($top3[1]->current_level ?? 1) . '.jpg') }}"
                    class="podium-item__avatar podium-item__avatar--first"
                    alt="{{ $top3[1]->user->name_user }}">
                    <span class="podium-item__medal" aria-hidden="true">🥈</span>
                </div>
                <div class="podium-item__info">
                    <p class="podium-item__name">{{ Str::limit($top3[1]->user->name_user, 12) }}</p>
                    <div class="podium-item__xp-card">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        <span>{{ number_format($top3[1]->xp_total) }} XP</span>
                    </div>
                </div>
                <div class="podium-item__block podium-item__block--second">
                    <span class="podium-item__rank">2</span>
                </div>
            </div>
            @endif

            {{-- Rank 1 (Center - Tallest) --}}
            @if($top3->count() >= 1)
            <div class="podium-item podium-item--first">
                <div class="podium-item__avatar-wrapper">
                    <img
                    src="{{ asset('images/characters/' . ($top3[0]->current_level ?? 1) . '.jpg') }}"
                    class="podium-item__avatar podium-item__avatar--first"
                    alt="{{ $top3[0]->user->name_user }}">
                    <span class="podium-item__crown" aria-hidden="true">👑</span>
                    <span class="podium-item__medal" aria-hidden="true">🥇</span>
                </div>
                <div class="podium-item__info">
                    <p class="podium-item__name podium-item__name--first">{{ Str::limit($top3[0]->user->name_user, 12) }}</p>
                    <div class="podium-item__xp-card podium-item__xp-card--first">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        <span>{{ number_format($top3[0]->xp_total) }} XP</span>
                    </div>
                </div>
                <div class="podium-item__block podium-item__block--first">
                    <span class="podium-item__rank">1</span>
                </div>
            </div>
            @endif

            {{-- Rank 3 (Right) --}}
            @if($top3->count() >= 3)
            <div class="podium-item podium-item--third">
                <div class="podium-item__avatar-wrapper">
                    <img
                    src="{{ asset('images/characters/' . ($top3[2]->current_level ?? 1) . '.jpg') }}"
                    class="podium-item__avatar podium-item__avatar--first"
                    alt="{{ $top3[2]->user->name_user }}">
                    <span class="podium-item__medal" aria-hidden="true">🥉</span>
                </div>
                <div class="podium-item__info">
                    <p class="podium-item__name">{{ Str::limit($top3[2]->user->name_user, 12) }}</p>
                    <div class="podium-item__xp-card">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        <span>{{ number_format($top3[2]->xp_total) }} XP</span>
                    </div>
                </div>
                <div class="podium-item__block podium-item__block--third">
                    <span class="podium-item__rank">3</span>
                </div>
            </div>
            @endif
        </div>
    </section>
    @endif


    {{-- ============================================
         STATISTICS CARDS
    ============================================ --}}
    @if($currentUserStat || $topXp > 0)
    <section class="leaderboard-stats" aria-label="Your Statistics">
        @if($topXp > 0)
        <div class="leaderboard-stat leaderboard-stat--top">
            <div class="leaderboard-stat__icon">
                <i class="fas fa-crown" aria-hidden="true"></i>
            </div>
            <div class="leaderboard-stat__info">
                <span class="leaderboard-stat__label">Top XP</span>
                <span class="leaderboard-stat__value">{{ number_format($topXp) }}</span>
            </div>
        </div>
        @endif

        @if($currentUserStat)
        @php
            $userRank = $rankings->search(function($item) {
                return $item->id_user == Auth::id();
            });
            $userRankNumber = $userRank !== false ? $rankings->firstItem() + $userRank : null;
        @endphp

        @if($userRankNumber)
        <div class="leaderboard-stat leaderboard-stat--rank">
            <div class="leaderboard-stat__icon">
                <i class="fas fa-medal" aria-hidden="true"></i>
            </div>
            <div class="leaderboard-stat__info">
                <span class="leaderboard-stat__label">Ranking Kamu</span>
                <span class="leaderboard-stat__value">#{{ $userRankNumber }}</span>
            </div>
        </div>
        @endif

        <div class="leaderboard-stat leaderboard-stat--stage">
            <div class="leaderboard-stat__icon">
                <i class="fas fa-layer-group" aria-hidden="true"></i>
            </div>
            <div class="leaderboard-stat__info">
                <span class="leaderboard-stat__label">Stage Kamu</span>
                <span class="leaderboard-stat__value">{{ $currentUserStat->currentStage->stage_name ?? 'Beginner' }}</span>
            </div>
        </div>
        @endif
    </section>
    @endif


    {{-- ============================================
         RANKING LIST (Card-Based)
    ============================================ --}}
    <section class="leaderboard-list" aria-label="Full Ranking List">
        <div class="leaderboard-list__header">
            <span class="leaderboard-list__icon" aria-hidden="true">📊</span>
            <h2 class="leaderboard-list__title">Daftar Peringkat</h2>
        </div>

        <div class="leaderboard-list__content">
            @forelse($rankings as $index => $stat)
            @php
                $rank = $rankings->firstItem() + $index;
                $isMe = $stat->id_user == Auth::id();
            @endphp

            <article class="ranking-card {{ $rank <= 3 ? 'ranking-card--top' : '' }} {{ $isMe ? 'ranking-card--me' : '' }}">
                <div class="ranking-card__rank">
                    @if($rank == 1)
                        <span class="ranking-card__medal ranking-card__medal--gold" aria-label="Rank 1">🥇</span>
                    @elseif($rank == 2)
                        <span class="ranking-card__medal ranking-card__medal--silver" aria-label="Rank 2">🥈</span>
                    @elseif($rank == 3)
                        <span class="ranking-card__medal ranking-card__medal--bronze" aria-label="Rank 3">🥉</span>
                    @else
                        <span class="ranking-card__number">#{{ $rank }}</span>
                    @endif
                </div>

                <div class="ranking-card__avatar-wrapper">
                    @php
                    $characterImage = $stat->character?->image;
                    @endphp
                    
                    <img
                    src="{{ asset('images/characters/' . ($stat->current_level ?? 1) . '.jpg') }}"
                    class="ranking-card__avatar"
                    alt="{{ $stat->user->name_user }}">
                </div>

                <div class="ranking-card__info">
                    <div class="ranking-card__name-row">
                        <h3 class="ranking-card__name">
                            {{ $stat->user->name_user }}
                            @if($isMe)
                                <span class="ranking-card__me-badge">Kamu</span>
                            @endif
                        </h3>
                    </div>
                    <div class="ranking-card__stage">
                        <i class="fas fa-graduation-cap" aria-hidden="true"></i>
                        <span>{{ $stat->currentStage->stage_name ?? 'Beginner' }}</span>
                    </div>
                </div>

                <div class="ranking-card__xp">
                    <span class="ranking-card__xp-value">{{ number_format($stat->xp_total) }}</span>
                    <span class="ranking-card__xp-label">XP</span>
                </div>
            </article>
            @empty
            <div class="leaderboard-empty">
                <div class="leaderboard-empty__icon" aria-hidden="true">🏆</div>
                <h3 class="leaderboard-empty__title">Belum ada petualang yang masuk leaderboard.</h3>
                <p class="leaderboard-empty__text">Jadilah yang pertama mengumpulkan XP.</p>
            </div>
            @endforelse
        </div>

        @if($rankings->hasPages())
        <div class="leaderboard-list__pagination">
            {{ $rankings->links('pagination::tailwind') }}
        </div>
        @endif
    </section>

</div>
@endsection