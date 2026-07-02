<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ArabicQuest - Platform pembelajaran Bahasa Arab berbasis game dengan sistem XP, Badge, dan Character Evolution untuk anak SD/MI.">
    <title>ArabicQuest — Petualangan Belajar Bahasa Arab Berbasis Game</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

{{-- ============================================
     NAVBAR
============================================ --}}
<nav class="landing-nav" id="navbar">
    <div class="landing-nav__container">
        <a href="#" class="landing-nav__brand">
            <span class="landing-nav__logo">🕌</span>
            <span class="landing-nav__name">ArabicQuest</span>
        </a>

        <div class="landing-nav__menu" id="navMenu">
            <a href="#features" class="landing-nav__link">Fitur</a>
            <a href="#how-it-works" class="landing-nav__link">Cara Kerja</a>
            <a href="#characters" class="landing-nav__link">Karakter</a>
            <a href="#curriculum" class="landing-nav__link">Kurikulum</a>
            <a href="#testimonials" class="landing-nav__link">Testimoni</a>
        </div>

        <div class="landing-nav__actions">
            <a href="{{ route('login') }}" class="landing-nav__btn landing-nav__btn--ghost">Masuk</a>
            <a href="{{ route('register') }}" class="landing-nav__btn landing-nav__btn--primary">Mulai Gratis</a>
            <button class="landing-nav__toggle" id="navToggle" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</nav>


{{-- ============================================
     SECTION 1: HERO
============================================ --}}
<section class="hero" id="hero">
    {{-- Background Effects --}}
    <div class="hero__pattern" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="heroPattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                    <path d="M50 0 L100 50 L50 100 L0 50 Z" fill="none" stroke="rgba(251, 191, 36, 0.08)" stroke-width="1"/>
                    <circle cx="50" cy="50" r="20" fill="none" stroke="rgba(119, 85, 55, 0.06)" stroke-width="1"/>
                    <path d="M50 30 L70 50 L50 70 L30 50 Z" fill="none" stroke="rgba(251, 191, 36, 0.06)" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#heroPattern)"/>
        </svg>
    </div>

    <div class="hero__particles" aria-hidden="true">
        @for($i = 0; $i < 25; $i++)
            <span class="hero__particle" style="--i: {{ $i }};"></span>
        @endfor
    </div>

    <div class="hero__orb hero__orb--1" aria-hidden="true"></div>
    <div class="hero__orb hero__orb--2" aria-hidden="true"></div>

    {{-- Hero Content --}}
    <div class="hero__container">
        <div class="hero__content">

            {{-- Badge --}}
            <div class="hero__badge">
                <span class="hero__badge-dot"></span>
                <span>🎮 Game-Based Arabic Learning</span>
            </div>

            {{-- Headline --}}
            <h1 class="hero__title">
                Belajar Bahasa Arab <br>
                <span class="hero__title-highlight">Seperti Bermain Game</span>
            </h1>

            {{-- Subheadline --}}
            <ul class="hero__features">
                <li><i class="fas fa-arrow-up"></i> Naik level</li>
                <li><i class="fas fa-star"></i> Kumpulkan XP</li>
                <li><i class="fas fa-user-astronaut"></i> Buka karakter baru</li>
                <li><i class="fas fa-medal"></i> Dapatkan badge</li>
                <li><i class="fas fa-graduation-cap"></i> Kuasai Bahasa Arab sambil bertualang</li>
            </ul>

            {{-- CTAs --}}
            <div class="hero__cta">
                <a href="{{ route('register') }}" class="hero__btn hero__btn--primary">
                    <i class="fas fa-rocket" aria-hidden="true"></i>
                    <span>Mulai Petualangan</span>
                </a>
                <a href="#how-it-works" class="hero__btn hero__btn--secondary">
                    <i class="fas fa-play-circle" aria-hidden="true"></i>
                    <span>Lihat Cara Bermain</span>
                </a>
            </div>

            {{-- Trust Indicators --}}
            <div class="hero__trust">
                <div class="hero__trust-item">
                    <span class="hero__trust-icon">⭐</span>
                    <span><strong>1.200+</strong> siswa</span>
                </div>
                <div class="hero__trust-divider"></div>
                <div class="hero__trust-item">
                    <span class="hero__trust-icon">🏅</span>
                    <span><strong>5.000+</strong> badge terbuka</span>
                </div>
                <div class="hero__trust-divider"></div>
                <div class="hero__trust-item">
                    <span class="hero__trust-icon">📚</span>
                    <span><strong>100+</strong> materi interaktif</span>
                </div>
            </div>

        </div>

        {{-- Right Side: Game Dashboard Mockup --}}
        <div class="hero__mockup">
            <div class="hero__mockup-glow" aria-hidden="true"></div>

            <div class="game-dashboard">
                <div class="game-dashboard__header">
                    <div class="game-dashboard__user">
                        <div class="game-dashboard__avatar">
                            <span>🧕</span>
                            <span class="game-dashboard__avatar-level">12</span>
                        </div>
                        <div class="game-dashboard__user-info">
                            <span class="game-dashboard__username">Al-Musafir</span>
                            <span class="game-dashboard__title">Sang Pengembara</span>
                        </div>
                    </div>
                    <div class="game-dashboard__coin">
                        <span class="game-dashboard__coin-icon">🪙</span>
                        <span class="game-dashboard__coin-value">850</span>
                    </div>
                </div>

                <div class="game-dashboard__xp">
                    <div class="game-dashboard__xp-header">
                        <span class="game-dashboard__xp-label">⭐ Level Progress</span>
                        <span class="game-dashboard__xp-value">2.450 / 3.000 XP</span>
                    </div>
                    <div class="game-dashboard__xp-bar">
                        <div class="game-dashboard__xp-fill" style="--xp-width: 82%;"></div>
                        <div class="game-dashboard__xp-shine"></div>
                    </div>
                </div>

                <div class="game-dashboard__stats">
                    <div class="game-dashboard__stat">
                        <span class="game-dashboard__stat-icon">🏅</span>
                        <div class="game-dashboard__stat-info">
                            <span class="game-dashboard__stat-value">12</span>
                            <span class="game-dashboard__stat-label">Badge</span>
                        </div>
                    </div>
                    <div class="game-dashboard__stat">
                        <span class="game-dashboard__stat-icon">🔥</span>
                        <div class="game-dashboard__stat-info">
                            <span class="game-dashboard__stat-value">7</span>
                            <span class="game-dashboard__stat-label">Streak</span>
                        </div>
                    </div>
                    <div class="game-dashboard__stat">
                        <span class="game-dashboard__stat-icon">🏆</span>
                        <div class="game-dashboard__stat-info">
                            <span class="game-dashboard__stat-value">#3</span>
                            <span class="game-dashboard__stat-label">Rank</span>
                        </div>
                    </div>
                </div>

                <div class="game-dashboard__mission">
                    <div class="game-dashboard__mission-header">
                        <span>🎯 Misi Harian</span>
                        <span class="game-dashboard__mission-progress">2/3</span>
                    </div>
                    <div class="game-dashboard__mission-list">
                        <div class="game-dashboard__mission-item game-dashboard__mission-item--done">
                            <i class="fas fa-check-circle"></i>
                            <span>Selesaikan 1 materi</span>
                        </div>
                        <div class="game-dashboard__mission-item game-dashboard__mission-item--done">
                            <i class="fas fa-check-circle"></i>
                            <span>Kerjakan 1 latihan</span>
                        </div>
                        <div class="game-dashboard__mission-item">
                            <i class="far fa-circle"></i>
                            <span>Dapatkan 50 XP</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Floating Elements --}}
            <div class="hero__float hero__float--badge" aria-hidden="true">
                <span>🏅</span>
                <div>
                    <small>Badge Baru!</small>
                    <strong>Streak Master</strong>
                </div>
            </div>

            <div class="hero__float hero__float--xp" aria-hidden="true">
                <span>+25 XP</span>
            </div>

            <div class="hero__float hero__float--coin" aria-hidden="true">
                <span>🪙 +50</span>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 2: CHARACTER EVOLUTION
============================================ --}}
<section class="characters" id="characters">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">✨ Character Evolution</span>
            <h2 class="section-title">Pilih <span class="gradient-text">Karaktermu</span></h2>
            <p class="section-subtitle">Evolusi karaktermu seiring progres belajar. Setiap karakter memiliki skill unik dan bonus XP.</p>
        </div>

        <div class="characters__grid">
            {{-- Character 1 --}}
            <div class="character-card character-card--bronze">
                <div class="character-card__glow" aria-hidden="true"></div>
                <div class="character-card__avatar">
                    <span class="character-card__emoji">🧒</span>
                    <span class="character-card__rank">Lv 1-5</span>
                </div>
                <div class="character-card__body">
                    <h3 class="character-card__name">Al-Mubtadi</h3>
                    <p class="character-card__subtitle">Sang Pemula</p>
                    <p class="character-card__desc">Petualang baru yang memulai perjalanan belajar Bahasa Arab.</p>

                    <div class="character-card__skills">
                        <div class="character-card__skill">
                            <i class="fas fa-book"></i>
                            <span>+10% XP Vocab</span>
                        </div>
                        <div class="character-card__skill">
                            <i class="fas fa-bolt"></i>
                            <span>Fast Learner</span>
                        </div>
                    </div>

                    <div class="character-card__evolution">
                        <span class="character-card__evolution-label">Evolusi ke:</span>
                        <span class="character-card__evolution-next">🧑‍🎓 Al-Talib</span>
                    </div>
                </div>
            </div>

            {{-- Character 2 --}}
            <div class="character-card character-card--silver">
                <div class="character-card__glow" aria-hidden="true"></div>
                <div class="character-card__avatar">
                    <span class="character-card__emoji">🧑‍🎓</span>
                    <span class="character-card__rank">Lv 6-15</span>
                </div>
                <div class="character-card__body">
                    <h3 class="character-card__name">Al-Mutafawwiq</h3>
                    <p class="character-card__subtitle">Sang Berprestasi</p>
                    <p class="character-card__desc">Pelajar tekun yang menguasai dasar-dasar bahasa Arab.</p>

                    <div class="character-card__skills">
                        <div class="character-card__skill">
                            <i class="fas fa-scroll"></i>
                            <span>+15% XP Grammar</span>
                        </div>
                        <div class="character-card__skill">
                            <i class="fas fa-shield-alt"></i>
                            <span>Grammar Shield</span>
                        </div>
                    </div>

                    <div class="character-card__evolution">
                        <span class="character-card__evolution-label">Evolusi ke:</span>
                        <span class="character-card__evolution-next">🧕 Al-Musafir</span>
                    </div>
                </div>
            </div>

            {{-- Character 3 --}}
            <div class="character-card character-card--gold">
                <div class="character-card__glow" aria-hidden="true"></div>
                <div class="character-card__avatar">
                    <span class="character-card__emoji">🧕</span>
                    <span class="character-card__rank">Lv 16-25</span>
                </div>
                <div class="character-card__body">
                    <h3 class="character-card__name">Al-Musafir</h3>
                    <p class="character-card__subtitle">Sang Pengembara</p>
                    <p class="character-card__desc">Penjelajah yang aktif mempelajari budaya dan peradaban Arab.</p>

                    <div class="character-card__skills">
                        <div class="character-card__skill">
                            <i class="fas fa-comments"></i>
                            <span>+20% XP Dialog</span>
                        </div>
                        <div class="character-card__skill">
                            <i class="fas fa-globe"></i>
                            <span>Cultural Master</span>
                        </div>
                    </div>

                    <div class="character-card__evolution">
                        <span class="character-card__evolution-label">Evolusi ke:</span>
                        <span class="character-card__evolution-next">⚔️ Al-Mujahid Ilmi</span>
                    </div>
                </div>
            </div>

            {{-- Character 4 --}}
            <div class="character-card character-card--legendary">
                <div class="character-card__glow" aria-hidden="true"></div>
                <div class="character-card__avatar">
                    <span class="character-card__emoji">⚔️</span>
                    <span class="character-card__rank">Lv 26+</span>
                </div>
                <div class="character-card__body">
                    <h3 class="character-card__name">Al-Mujahid Ilmi</h3>
                    <p class="character-card__subtitle">Sang Pejuang Ilmu</p>
                    <p class="character-card__desc">Ahli bahasa Arab yang telah mencapai tingkat mahir dan bijaksana.</p>

                    <div class="character-card__skills">
                        <div class="character-card__skill">
                            <i class="fas fa-crown"></i>
                            <span>+30% All XP</span>
                        </div>
                        <div class="character-card__skill">
                            <i class="fas fa-star"></i>
                            <span>Master Scholar</span>
                        </div>
                    </div>

                    <div class="character-card__evolution">
                        <span class="character-card__evolution-label">Status:</span>
                        <span class="character-card__evolution-next character-card__evolution-next--max">👑 MAX EVOLUTION</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Evolution Tree Visual --}}
        <div class="evolution-tree">
            <div class="evolution-tree__line" aria-hidden="true"></div>
            <div class="evolution-tree__node evolution-tree__node--active">
                <span>🧒</span>
                <small>Al-Mubtadi</small>
            </div>
            <div class="evolution-tree__node">
                <span>🧑‍🎓</span>
                <small>Al-Mutafawwiq</small>
            </div>
            <div class="evolution-tree__node">
                <span>🧕</span>
                <small>Al-Musafir</small>
            </div>
            <div class="evolution-tree__node evolution-tree__node--final">
                <span>⚔️</span>
                <small>Al-Mujahid Ilmi</small>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 3: HOW THE GAME WORKS
============================================ --}}
<section class="how-it-works" id="how-it-works">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">🗺️ Adventure Timeline</span>
            <h2 class="section-title">Bagaimana <span class="gradient-text">Petualanganmu</span> Dimulai?</h2>
            <p class="section-subtitle">6 langkah petualangan yang akan membawamu menjadi master Bahasa Arab.</p>
        </div>

        <div class="adventure-map">
            <div class="adventure-map__path" aria-hidden="true"></div>

            <div class="adventure-step">
                <div class="adventure-step__number">1</div>
                <div class="adventure-step__icon">📝</div>
                <div class="adventure-step__content">
                    <h3 class="adventure-step__title">Placement Test</h3>
                    <p class="adventure-step__desc">Kerjakan tes penempatan untuk menentukan level awal sesuai kemampuanmu.</p>
                </div>
            </div>

            <div class="adventure-step">
                <div class="adventure-step__number">2</div>
                <div class="adventure-step__icon">📚</div>
                <div class="adventure-step__content">
                    <h3 class="adventure-step__title">Mulai Quest</h3>
                    <p class="adventure-step__desc">Pelajari materi interaktif dengan 3 tab: Vocab, Grammar, dan Dialog.</p>
                </div>
            </div>

            <div class="adventure-step">
                <div class="adventure-step__number">3</div>
                <div class="adventure-step__icon">⭐</div>
                <div class="adventure-step__content">
                    <h3 class="adventure-step__title">Dapatkan XP</h3>
                    <p class="adventure-step__desc">Setiap aktivitas menghasilkan XP. Kumpulkan untuk naik level!</p>
                </div>
            </div>

            <div class="adventure-step">
                <div class="adventure-step__number">4</div>
                <div class="adventure-step__icon">🪙</div>
                <div class="adventure-step__content">
                    <h3 class="adventure-step__title">Kumpulkan Coin</h3>
                    <p class="adventure-step__desc">Gunakan coin untuk membuka item eksklusif di shop karakter.</p>
                </div>
            </div>

            <div class="adventure-step">
                <div class="adventure-step__number">5</div>
                <div class="adventure-step__icon">📈</div>
                <div class="adventure-step__content">
                    <h3 class="adventure-step__title">Naik Level</h3>
                    <p class="adventure-step__desc">Unlock materi baru dan evolusi karaktermu ke bentuk berikutnya.</p>
                </div>
            </div>

            <div class="adventure-step adventure-step--final">
                <div class="adventure-step__number">6</div>
                <div class="adventure-step__icon">👑</div>
                <div class="adventure-step__content">
                    <h3 class="adventure-step__title">Jadi Master Bahasa Arab</h3>
                    <p class="adventure-step__desc">Capai rank tertinggi dan kuasai Bahasa Arab seperti native speaker!</p>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 4: FEATURES
============================================ --}}
<section class="features" id="features">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">✨ Fitur Unggulan</span>
            <h2 class="section-title">Semua yang Kamu Butuhkan <span class="gradient-text">dalam Satu Platform</span></h2>
            <p class="section-subtitle">Dirancang khusus untuk pengalaman belajar Bahasa Arab yang seru dan efektif.</p>
        </div>

        <div class="features__grid">
            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--brown">🎯</div>
                <h3 class="feature-card__title">Smart Placement Test</h3>
                <p class="feature-card__desc">Tes penempatan cerdas yang menyesuaikan level belajar berdasarkan kemampuan awal.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--gold">📚</div>
                <h3 class="feature-card__title">Kurikulum Al-Arabiyyah Bayna Yadayk</h3>
                <p class="feature-card__desc">Materi disusun berdasarkan kitab standar pesantren yang terpercaya dan teruji.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--blue">🎮</div>
                <h3 class="feature-card__title">Gamification System</h3>
                <p class="feature-card__desc">Sistem XP, Coin, Badge, dan Level Up yang membuat belajar terasa seperti bermain game RPG.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--red">🏆</div>
                <h3 class="feature-card__title">Quiz Boss Battle</h3>
                <p class="feature-card__desc">Quiz evaluasi di setiap akhir level. Lulus untuk membuka materi dan karakter baru!</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--green">🛍️</div>
                <h3 class="feature-card__title">Coin Shop</h3>
                <p class="feature-card__desc">Tukarkan coin dengan karakter eksklusif, item spesial, dan boost XP.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--purple">👨‍👩‍👧</div>
                <h3 class="feature-card__title">Parent Dashboard</h3>
                <p class="feature-card__desc">Orang tua dapat memantau progress belajar anak secara real-time dan detail.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--orange">🏅</div>
                <h3 class="feature-card__title">Achievement Badge</h3>
                <p class="feature-card__desc">Kumpulkan badge prestasi dari setiap milestone yang kamu capai dalam perjalanan.</p>
            </div>

            <div class="feature-card">
                <div class="feature-card__icon feature-card__icon--teal">🌍</div>
                <h3 class="feature-card__title">Leaderboard</h3>
                <p class="feature-card__desc">Bersaing sehat dengan teman-teman dan raih peringkat tertinggi di leaderboard global.</p>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 5: GAMEPLAY PREVIEW
============================================ --}}
<section class="gameplay" id="gameplay">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">🎮 Gameplay Preview</span>
            <h2 class="section-title">Seperti Ini <span class="gradient-text">Dashboard Petualanganmu</span></h2>
            <p class="section-subtitle">Semua progres, misi, dan achievement dalam satu dashboard yang intuitif.</p>
        </div>

        <div class="gameplay-preview">
            <div class="gameplay-preview__glow" aria-hidden="true"></div>

            <div class="gameplay-preview__main">
                {{-- Sidebar --}}
                <aside class="gameplay-preview__sidebar">
                    <div class="gameplay-preview__profile">
                        <div class="gameplay-preview__avatar">🧕</div>
                        <div class="gameplay-preview__profile-info">
                            <strong>Al-Musafir</strong>
                            <small>Level 12</small>
                        </div>
                    </div>

                    <nav class="gameplay-preview__menu">
                        <a href="#" class="gameplay-preview__menu-item gameplay-preview__menu-item--active">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                        <a href="#" class="gameplay-preview__menu-item">
                            <i class="fas fa-map"></i> Learning Path
                        </a>
                        <a href="#" class="gameplay-preview__menu-item">
                            <i class="fas fa-trophy"></i> Leaderboard
                        </a>
                        <a href="#" class="gameplay-preview__menu-item">
                            <i class="fas fa-medal"></i> Achievements
                        </a>
                        <a href="#" class="gameplay-preview__menu-item">
                            <i class="fas fa-store"></i> Shop
                        </a>
                    </nav>

                    <div class="gameplay-preview__resources">
                        <div class="gameplay-preview__resource">
                            <span>⭐</span>
                            <span>2,450 XP</span>
                        </div>
                        <div class="gameplay-preview__resource">
                            <span>🪙</span>
                            <span>850</span>
                        </div>
                        <div class="gameplay-preview__resource">
                            <span>🔥</span>
                            <span>7 Hari</span>
                        </div>
                    </div>
                </aside>

                {{-- Main Content --}}
                <main class="gameplay-preview__content">
                    <div class="gameplay-preview__welcome">
                        <h3>Selamat datang kembali, <strong>Pengembara!</strong></h3>
                        <p>Lanjutkan petualanganmu hari ini.</p>
                    </div>

                    <div class="gameplay-preview__grid">
                        <div class="gameplay-preview__card gameplay-preview__card--quest">
                            <div class="gameplay-preview__card-header">
                                <span>🎯 Current Quest</span>
                                <span class="gameplay-preview__card-badge">ACTIVE</span>
                            </div>
                            <h4>Stage 2: Intermediate</h4>
                            <p>Level 4 dari 8</p>
                            <div class="gameplay-preview__progress">
                                <div class="gameplay-preview__progress-fill" style="width: 50%;"></div>
                            </div>
                        </div>

                        <div class="gameplay-preview__card gameplay-preview__card--daily">
                            <div class="gameplay-preview__card-header">
                                <span>📅 Daily Mission</span>
                                <span>2/3</span>
                            </div>
                            <ul class="gameplay-preview__mission-list">
                                <li class="done"><i class="fas fa-check"></i> 1 Materi</li>
                                <li class="done"><i class="fas fa-check"></i> 1 Latihan</li>
                                <li><i class="far fa-circle"></i> 50 XP</li>
                            </ul>
                        </div>

                        <div class="gameplay-preview__card gameplay-preview__card--weekly">
                            <div class="gameplay-preview__card-header">
                                <span>📆 Weekly Mission</span>
                                <span>4/7</span>
                            </div>
                            <div class="gameplay-preview__progress">
                                <div class="gameplay-preview__progress-fill" style="width: 57%;"></div>
                            </div>
                            <small>Reward: +200 XP</small>
                        </div>

                        <div class="gameplay-preview__card gameplay-preview__card--leaderboard">
                            <div class="gameplay-preview__card-header">
                                <span>🏆 Leaderboard</span>
                                <span>Minggu Ini</span>
                            </div>
                            <div class="gameplay-preview__rank">
                                <span class="gameplay-preview__rank-position">#3</span>
                                <div>
                                    <strong>Dari 1,200 siswa</strong>
                                    <small>Naik 2 posisi</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 6: LEARNING PATH
============================================ --}}
<section class="learning-path" id="curriculum">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">🗺️ Learning Path</span>
            <h2 class="section-title">Perjalanan <span class="gradient-text">Belajarmu</span></h2>
            <p class="section-subtitle">3 stage pembelajaran yang dirancang bertahap dari pemula hingga mahir.</p>
        </div>

        <div class="journey-map">
            <div class="journey-map__line" aria-hidden="true"></div>

            <div class="journey-stage journey-stage--beginner">
                <div class="journey-stage__icon">🌱</div>
                <div class="journey-stage__content">
                    <div class="journey-stage__header">
                        <span class="journey-stage__tag">Stage 1</span>
                        <h3>Beginner</h3>
                    </div>
                    <p class="journey-stage__subtitle">Dasar-dasar Bahasa Arab</p>
                    <ul class="journey-stage__list">
                        <li><i class="fas fa-book"></i> Vocabulary dasar</li>
                        <li><i class="fas fa-scroll"></i> Grammar sederhana</li>
                        <li><i class="fas fa-comments"></i> Dialog perkenalan</li>
                        <li><i class="fas fa-tasks"></i> Quiz evaluasi</li>
                    </ul>
                    <div class="journey-stage__reward">
                        <span>🎁 Reward:</span>
                        <span>🧒 Al-Mubtadi Character</span>
                    </div>
                </div>
            </div>

            <div class="journey-stage journey-stage--intermediate">
                <div class="journey-stage__icon">🌿</div>
                <div class="journey-stage__content">
                    <div class="journey-stage__header">
                        <span class="journey-stage__tag">Stage 2</span>
                        <h3>Intermediate</h3>
                    </div>
                    <p class="journey-stage__subtitle">Tata bahasa & percakapan</p>
                    <ul class="journey-stage__list">
                        <li><i class="fas fa-book"></i> Vocabulary lanjutan</li>
                        <li><i class="fas fa-scroll"></i> Nahwu & Shorof</li>
                        <li><i class="fas fa-comments"></i> Dialog sehari-hari</li>
                        <li><i class="fas fa-tasks"></i> Quiz evaluasi</li>
                    </ul>
                    <div class="journey-stage__reward">
                        <span>🎁 Reward:</span>
                        <span>🧕 Al-Musafir Character</span>
                    </div>
                </div>
            </div>

            <div class="journey-stage journey-stage--advanced">
                <div class="journey-stage__icon">🌳</div>
                <div class="journey-stage__content">
                    <div class="journey-stage__header">
                        <span class="journey-stage__tag">Stage 3</span>
                        <h3>Advanced</h3>
                    </div>
                    <p class="journey-stage__subtitle">Mahir Bahasa Arab</p>
                    <ul class="journey-stage__list">
                        <li><i class="fas fa-book"></i> Vocabulary mahir</li>
                        <li><i class="fas fa-scroll"></i> I'rab & Wazan</li>
                        <li><i class="fas fa-comments"></i> Diskusi kompleks</li>
                        <li><i class="fas fa-tasks"></i> Final Boss Quiz</li>
                    </ul>
                    <div class="journey-stage__reward">
                        <span>🎁 Reward:</span>
                        <span>⚔️ Al-Mujahid Ilmi Character</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 7: REWARDS
============================================ --}}
<section class="rewards" id="rewards">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">🎁 Reward System</span>
            <h2 class="section-title">Belajar Tidak Pernah <span class="gradient-text">Semeyenangkan Ini</span></h2>
            <p class="section-subtitle">Setiap langkahmu dihargai dengan reward yang memotivasi.</p>
        </div>

        <div class="rewards__grid">
            <div class="reward-card reward-card--xp">
                <div class="reward-card__chest" aria-hidden="true">
                    <span>⭐</span>
                </div>
                <h3 class="reward-card__title">XP Points</h3>
                <p class="reward-card__desc">Kumpulkan XP dari setiap aktivitas untuk naik level dan evolusi karakter.</p>
                <div class="reward-card__amount">+10 s/d +100 XP</div>
            </div>

            <div class="reward-card reward-card--coin">
                <div class="reward-card__chest" aria-hidden="true">
                    <span>🪙</span>
                </div>
                <h3 class="reward-card__title">Gold Coin</h3>
                <p class="reward-card__desc">Tukarkan coin dengan karakter eksklusif dan item spesial di shop.</p>
                <div class="reward-card__amount">+5 s/d +50 Coin</div>
            </div>

            <div class="reward-card reward-card--badge">
                <div class="reward-card__chest" aria-hidden="true">
                    <span>🏅</span>
                </div>
                <h3 class="reward-card__title">Achievement Badge</h3>
                <p class="reward-card__desc">Kumpulkan badge unik dari setiap milestone yang kamu capai.</p>
                <div class="reward-card__amount">50+ Badge Unik</div>
            </div>

            <div class="reward-card reward-card--skin">
                <div class="reward-card__chest" aria-hidden="true">
                    <span>👑</span>
                </div>
                <h3 class="reward-card__title">Character Skin</h3>
                <p class="reward-card__desc">Buka skin karakter eksklusif sebagai simbol pencapaianmu.</p>
                <div class="reward-card__amount">4 Evolution Forms</div>
            </div>

            <div class="reward-card reward-card--daily">
                <div class="reward-card__chest" aria-hidden="true">
                    <span>🎁</span>
                </div>
                <h3 class="reward-card__title">Daily Reward</h3>
                <p class="reward-card__desc">Login setiap hari untuk mendapatkan bonus reward harian yang meningkat.</p>
                <div class="reward-card__amount">7-Day Streak Bonus</div>
            </div>

            <div class="reward-card reward-card--streak">
                <div class="reward-card__chest" aria-hidden="true">
                    <span>🔥</span>
                </div>
                <h3 class="reward-card__title">Streak Reward</h3>
                <p class="reward-card__desc">Pertahankan streak belajar harianmu untuk multiplier XP dan bonus coin.</p>
                <div class="reward-card__amount">Up to 2x Multiplier</div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 8: LEADERBOARD
============================================ --}}
<section class="leaderboard-section" id="leaderboard">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">🏆 Hall of Fame</span>
            <h2 class="section-title">Bersaing dengan <span class="gradient-text">Petualang Lain</span></h2>
            <p class="section-subtitle">Raih peringkat tertinggi dan namamu akan terukir di Hall of Fame.</p>
        </div>

        <div class="leaderboard-showcase">
            {{-- Podium Top 3 --}}
            <div class="leaderboard-podium">
                <div class="podium-item podium-item--second">
                    <div class="podium-item__avatar">
                        <span>🧑‍🎓</span>
                        <span class="podium-item__medal">🥈</span>
                    </div>
                    <div class="podium-item__info">
                        <strong>Siti Nurhaliza</strong>
                        <small>Level 10 • 2,850 XP</small>
                    </div>
                    <div class="podium-item__block podium-item__block--second">2</div>
                </div>

                <div class="podium-item podium-item--first">
                    <div class="podium-item__avatar">
                        <span>🧕</span>
                        <span class="podium-item__crown">👑</span>
                        <span class="podium-item__medal">🥇</span>
                    </div>
                    <div class="podium-item__info">
                        <strong>Ali Rahman</strong>
                        <small>Level 15 • 3,500 XP</small>
                    </div>
                    <div class="podium-item__block podium-item__block--first">1</div>
                </div>

                <div class="podium-item podium-item--third">
                    <div class="podium-item__avatar">
                        <span>🧒</span>
                        <span class="podium-item__medal">🥉</span>
                    </div>
                    <div class="podium-item__info">
                        <strong>Ahmad Fauzi</strong>
                        <small>Level 8 • 2,100 XP</small>
                    </div>
                    <div class="podium-item__block podium-item__block--third">3</div>
                </div>
            </div>

            {{-- Your Rank --}}
            <div class="leaderboard-your-rank">
                <div class="leaderboard-your-rank__label">Posisimu Saat Ini</div>
                <div class="leaderboard-your-rank__card">
                    <span class="leaderboard-your-rank__position">#12</span>
                    <div class="leaderboard-your-rank__avatar">
                        <span>🧒</span>
                    </div>
                    <div class="leaderboard-your-rank__info">
                        <strong>Kamu</strong>
                        <small>Level 3 • 1,250 XP</small>
                    </div>
                    <div class="leaderboard-your-rank__progress">
                        <small>150 XP lagi untuk naik peringkat</small>
                        <div class="leaderboard-your-rank__progress-bar">
                            <div style="width: 75%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 9: TESTIMONIALS
============================================ --}}
<section class="testimonials" id="testimonials">
    <div class="container">
        <div class="section-header">
            <span class="section-badge">💬 Testimoni</span>
            <h2 class="section-title">Apa Kata <span class="gradient-text">Mereka?</span></h2>
            <p class="section-subtitle">Cerita nyata dari orang tua, guru, dan siswa yang sudah bergabung.</p>
        </div>

        <div class="testimonials__grid">
            <div class="testimonial-card testimonial-card--parent">
                <div class="testimonial-card__quote">"</div>
                <div class="testimonial-card__stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-card__text">"Anak saya jadi semangat belajar bahasa Arab! Dulu malas, sekarang malah nanya 'Kapan bisa belajar lagi?' karena ingin kumpulkan coin dan naik level."</p>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar">👩</div>
                    <div class="testimonial-card__info">
                        <strong>Ibu Sari</strong>
                        <small>Orang Tua Siswa, Jakarta</small>
                    </div>
                </div>
            </div>

            <div class="testimonial-card testimonial-card--teacher">
                <div class="testimonial-card__quote">"</div>
                <div class="testimonial-card__stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <p class="testimonial-card__text">"Materinya sesuai dengan kitab di pesantren, jadi bisa jadi pendamping belajar. Fitur quiz level-nya bagus untuk evaluasi siswa secara berkala."</p>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar">👨‍🏫</div>
                    <div class="testimonial-card__info">
                        <strong>Ustadz Ahmad</strong>
                        <small>Guru Bahasa Arab, Bandung</small>
                    </div>
                </div>
            </div>

            <div class="testimonial-card testimonial-card--student">
                <div class="testimonial-card__quote">"</div>
                <div class="testimonial-card__stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <p class="testimonial-card__text">"Aku suka banget sistem badge-nya! Setiap lulus level dapat badge baru. Jadi pengen terus belajar biar koleksi badge-nya lengkap dan karaktermu berevolusi!"</p>
                <div class="testimonial-card__author">
                    <div class="testimonial-card__avatar">🧒</div>
                    <div class="testimonial-card__info">
                        <strong>Zidan, 10 tahun</strong>
                        <small>Siswa, Surabaya</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     SECTION 10: FINAL CTA
============================================ --}}
<section class="final-cta">
    <div class="final-cta__pattern" aria-hidden="true">
        <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="ctaPattern" x="0" y="0" width="100" height="100" patternUnits="userSpaceOnUse">
                    <path d="M50 0 L100 50 L50 100 L0 50 Z" fill="none" stroke="rgba(251, 191, 36, 0.1)" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#ctaPattern)"/>
        </svg>
    </div>

    <div class="final-cta__particles" aria-hidden="true">
        @for($i = 0; $i < 15; $i++)
            <span class="final-cta__particle" style="--i: {{ $i }};"></span>
        @endfor
    </div>

    <div class="container">
        <div class="final-cta__content">
            <div class="final-cta__icon" aria-hidden="true">🚀</div>
            <h2 class="final-cta__title">
                Siap Memulai <span class="final-cta__title-highlight">Petualangan Bahasa Arabmu?</span>
            </h2>
            <p class="final-cta__subtitle">
                Masuk ke dunia ArabicQuest dan ubah belajar Bahasa Arab menjadi pengalaman bermain yang seru. Bergabunglah dengan 1.200+ petualang lainnya!
            </p>

            <div class="final-cta__buttons">
                <a href="{{ route('register') }}" class="final-cta__btn final-cta__btn--primary">
                    <i class="fas fa-rocket" aria-hidden="true"></i>
                    <span>Mulai Gratis Sekarang</span>
                </a>
                <a href="{{ route('login') }}" class="final-cta__btn final-cta__btn--secondary">
                    <span>Sudah Punya Akun? Masuk</span>
                </a>
            </div>

            <div class="final-cta__trust">
                <span><i class="fas fa-check-circle"></i> Gratis Selamanya</span>
                <span><i class="fas fa-check-circle"></i> Tanpa Kartu Kredit</span>
                <span><i class="fas fa-check-circle"></i> Akses di HP & Laptop</span>
            </div>
        </div>
    </div>
</section>


{{-- ============================================
     FOOTER
============================================ --}}
<footer class="landing-footer">
    <div class="container">
        <div class="landing-footer__grid">
            <div class="landing-footer__brand">
                <div class="landing-footer__logo">
                    <span class="landing-footer__logo-icon">🕌</span>
                    <span class="landing-footer__logo-name">ArabicQuest</span>
                </div>
                <p class="landing-footer__desc">Platform pembelajaran Bahasa Arab gamified untuk anak SD/MI. Belajar jadi seru, efektif, dan menyenangkan!</p>
                <div class="landing-footer__social">
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <div class="landing-footer__column">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#how-it-works">Cara Kerja</a></li>
                    <li><a href="#characters">Karakter</a></li>
                    <li><a href="#curriculum">Kurikulum</a></li>
                </ul>
            </div>

            <div class="landing-footer__column">
                <h4>Akun</h4>
                <ul>
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                    <li><a href="#">Lupa Password</a></li>
                </ul>
            </div>

            <div class="landing-footer__column">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#">Kebijakan Privasi</a></li>
                    <li><a href="#">Syarat & Ketentuan</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>

        <div class="landing-footer__bottom">
            <p>&copy; {{ date('Y') }} ArabicQuest. All rights reserved. Dibuat dengan ❤️ untuk generasi pencinta Bahasa Arab.</p>
        </div>
    </div>
</footer>


{{-- ============================================
     SCRIPTS
============================================ --}}
<script>
    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('landing-nav--scrolled');
        } else {
            navbar.classList.remove('landing-nav--scrolled');
        }
    });

    // Mobile menu toggle
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');
    if (navToggle) {
        navToggle.addEventListener('click', () => {
            navMenu.classList.toggle('landing-nav__menu--open');
        });
    }

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && !href.includes('http') && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({ behavior: 'smooth' });
                navMenu.classList.remove('landing-nav__menu--open');
            }
        });
    });

    // Scroll reveal animation
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    revealElements.forEach(el => revealObserver.observe(el));

    // XP counter animation
    const xpElements = document.querySelectorAll('[data-count]');
    const countObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.dataset.count);
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        el.textContent = target.toLocaleString('id-ID');
                        clearInterval(timer);
                    } else {
                        el.textContent = Math.floor(current).toLocaleString('id-ID');
                    }
                }, 30);
                countObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    xpElements.forEach(el => countObserver.observe(el));
</script>

</body>
</html>