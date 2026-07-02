@extends('layouts.app')
@section('title', 'Pusat Informasi')

@section('content')
<style>
/* ===========================================================
   PUSAT INFORMASI - ARABIC QUEST
   Premium Gamified Learning Platform
   =========================================================== */

/* Variables */
:root {
    --aq-primary: #775537;
    --aq-primary-light: #9b7049;
    --aq-primary-lighter: #c69a6a;
    --aq-gold: #D4AF37;
    --aq-gold-light: #E8D5A0;
    --aq-cream: #F8F1E5;
    --aq-cream-dark: #EFE4D0;
    --aq-white: #FFFFFF;
    --aq-text-dark: #2C1810;
    --aq-text-muted: #775537;
    --aq-border: #E5D5C0;
    --aq-success: #4CAF50;
    --aq-warning: #F59E0B;
    --aq-danger: #E53935;
    --aq-info: #2196F3;
    --aq-purple: #8B5CF6;
    --shadow-sm: 0 2px 8px rgba(119, 85, 55, 0.08);
    --shadow-md: 0 8px 24px rgba(119, 85, 55, 0.12);
    --shadow-lg: 0 16px 48px rgba(119, 85, 55, 0.18);
    --shadow-gold: 0 8px 24px rgba(212, 175, 55, 0.25);
    --radius-sm: 12px;
    --radius-md: 16px;
    --radius-lg: 24px;
    --radius-xl: 32px;
}

/* Page Wrapper */
.info-page {
    max-width: 1400px;
    margin: 0 auto;
    padding: 32px 24px 80px;
    font-family: 'Poppins', sans-serif;
    color: var(--aq-text-dark);
}

/* ===========================================================
   1. HERO SECTION
   =========================================================== */
.info-hero {
    position: relative;
    background: linear-gradient(135deg, #775537 0%, #9b7049 50%, #c69a6a 100%);
    border-radius: var(--radius-xl);
    padding: 64px 48px;
    margin-bottom: 48px;
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    text-align: center;
}

.info-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 500px;
    height: 500px;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.3) 0%, transparent 70%);
    pointer-events: none;
    animation: infoHeroGlow 4s ease-in-out infinite;
}

.info-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    pointer-events: none;
}

@keyframes infoHeroGlow {
    0%, 100% { opacity: 0.5; transform: scale(1); }
    50% { opacity: 1; transform: scale(1.1); }
}

.info-hero__badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1.5px solid rgba(255, 255, 255, 0.3);
    border-radius: 24px;
    color: #FFFFFF;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 24px;
    position: relative;
    z-index: 2;
}

.info-hero__icon {
    font-size: 72px;
    margin-bottom: 20px;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.2));
}

.info-hero__title {
    font-size: 42px;
    font-weight: 800;
    color: #FFFFFF;
    margin: 0 0 16px 0;
    line-height: 1.2;
    position: relative;
    z-index: 2;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.info-hero__desc {
    font-size: 17px;
    color: rgba(255, 255, 255, 0.9);
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
    position: relative;
    z-index: 2;
}

/* ===========================================================
   2. SECTION TITLE
   =========================================================== */
.info-section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 28px;
    font-weight: 800;
    color: var(--aq-primary);
    margin: 0 0 32px 0;
}

.info-section-title i {
    color: var(--aq-gold);
    font-size: 32px;
}

/* ===========================================================
   3. ROADMAP SECTION
   =========================================================== */
.info-roadmap {
    margin-bottom: 64px;
}

.info-roadmap__track {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    position: relative;
    padding: 20px 0;
}

.info-roadmap__step {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    position: relative;
    z-index: 2;
}

.info-roadmap__card {
    width: 100%;
    max-width: 200px;
    background: var(--aq-white);
    border: 2px solid var(--aq-border);
    border-radius: var(--radius-lg);
    padding: 24px 16px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.info-roadmap__card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-md);
    border-color: var(--aq-gold);
}

.info-roadmap__icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 12px;
    background: linear-gradient(135deg, var(--aq-cream), var(--aq-cream-dark));
    border: 2px solid var(--aq-border);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    transition: all 0.3s ease;
}

.info-roadmap__card:hover .info-roadmap__icon {
    background: linear-gradient(135deg, var(--aq-gold-light), var(--aq-gold));
    border-color: var(--aq-gold);
    transform: scale(1.1);
}

.info-roadmap__label {
    font-size: 15px;
    font-weight: 700;
    color: var(--aq-primary);
    margin: 0;
}

.info-roadmap__arrow {
    font-size: 24px;
    color: var(--aq-gold);
    flex-shrink: 0;
}

/* ===========================================================
   4. GAME SYSTEM CARDS
   =========================================================== */
.info-game-system {
    margin-bottom: 64px;
}

.info-game-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.info-game-card {
    background: var(--aq-white);
    border: 2px solid var(--aq-border);
    border-radius: var(--radius-lg);
    padding: 32px 24px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.info-game-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--aq-gold), var(--aq-primary-light), var(--aq-gold));
    opacity: 0;
    transition: opacity 0.3s ease;
}

.info-game-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-md);
    border-color: var(--aq-gold);
}

.info-game-card:hover::before {
    opacity: 1;
}

.info-game-card__icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    transition: all 0.3s ease;
}

.info-game-card--xp .info-game-card__icon {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    border: 2px solid #F59E0B;
    color: #92400E;
}

.info-game-card--badge .info-game-card__icon {
    background: linear-gradient(135deg, #EDE9FE, #DDD6FE);
    border: 2px solid #8B5CF6;
    color: #5B21B6;
}

.info-game-card--streak .info-game-card__icon {
    background: linear-gradient(135deg, #FFEDD5, #FED7AA);
    border: 2px solid #F97316;
    color: #9A3412;
}

.info-game-card--quiz .info-game-card__icon {
    background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
    border: 2px solid #22C55E;
    color: #166534;
}

.info-game-card--materi .info-game-card__icon {
    background: linear-gradient(135deg, #DBEAFE, #BFDBFE);
    border: 2px solid #3B82F6;
    color: #1E40AF;
}

.info-game-card:hover .info-game-card__icon {
    transform: scale(1.1) rotate(5deg);
}

.info-game-card__title {
    font-size: 20px;
    font-weight: 800;
    color: var(--aq-primary);
    margin: 0 0 12px 0;
}

.info-game-card__desc {
    font-size: 14px;
    color: var(--aq-text-muted);
    line-height: 1.6;
    margin: 0;
}

/* ===========================================================
   5. STRUCTURE CARDS
   =========================================================== */
.info-structure {
    margin-bottom: 64px;
}

.info-structure-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.info-structure-card {
    background: var(--aq-white);
    border: 2px solid var(--aq-border);
    border-radius: var(--radius-lg);
    padding: 32px;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.info-structure-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-md);
    border-color: var(--aq-gold);
}

.info-structure-card__header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 2px dashed var(--aq-border);
}

.info-structure-card__icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    flex-shrink: 0;
}

.info-structure-card--pemula .info-structure-card__icon {
    background: linear-gradient(135deg, #DCFCE7, #BBF7D0);
    border: 2px solid #22C55E;
}

.info-structure-card--menengah .info-structure-card__icon {
    background: linear-gradient(135deg, #FEF3C7, #FDE68A);
    border: 2px solid #F59E0B;
}

.info-structure-card--mahir .info-structure-card__icon {
    background: linear-gradient(135deg, #EDE9FE, #DDD6FE);
    border: 2px solid #8B5CF6;
}

.info-structure-card__title {
    font-size: 22px;
    font-weight: 800;
    color: var(--aq-primary);
    margin: 0;
}

.info-structure-card__subtitle {
    font-size: 13px;
    color: var(--aq-text-muted);
    margin: 4px 0 0 0;
}

.info-structure-card__list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.info-structure-card__list li {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--aq-cream);
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-weight: 600;
    color: var(--aq-text-dark);
    transition: all 0.2s ease;
}

.info-structure-card__list li:hover {
    background: var(--aq-cream-dark);
    transform: translateX(4px);
}

.info-structure-card__list li i {
    color: var(--aq-gold);
    font-size: 16px;
}

/* ===========================================================
   6. BADGE COLLECTION
   =========================================================== */
.info-badges {
    margin-bottom: 64px;
}

.info-badges-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-badge-card {
    background: var(--aq-white);
    border: 2px solid var(--aq-border);
    border-radius: var(--radius-lg);
    padding: 28px 20px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.info-badge-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.info-badge-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: var(--shadow-md);
    border-color: var(--aq-gold);
}

.info-badge-card:hover::before {
    opacity: 1;
}

.info-badge-card__icon {
    width: 72px;
    height: 72px;
    margin: 0 auto 16px;
    background: linear-gradient(135deg, var(--aq-gold-light), var(--aq-gold));
    border: 3px solid var(--aq-gold);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    box-shadow: var(--shadow-gold);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.info-badge-card:hover .info-badge-card__icon {
    transform: scale(1.1) rotate(10deg);
    box-shadow: 0 12px 32px rgba(212, 175, 55, 0.4);
}

.info-badge-card__title {
    font-size: 17px;
    font-weight: 800;
    color: var(--aq-primary);
    margin: 0 0 6px 0;
    position: relative;
    z-index: 2;
}

.info-badge-card__desc {
    font-size: 12px;
    color: var(--aq-text-muted);
    margin: 0;
    position: relative;
    z-index: 2;
}

/* ===========================================================
   7. FAQ ACCORDION
   =========================================================== */
.info-faq {
    margin-bottom: 64px;
}

.info-faq-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    max-width: 900px;
    margin: 0 auto;
}

.info-faq-item {
    background: var(--aq-white);
    border: 2px solid var(--aq-border);
    border-radius: var(--radius-md);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
}

.info-faq-item:hover {
    border-color: var(--aq-gold);
    box-shadow: var(--shadow-md);
}

.info-faq-item.active {
    border-color: var(--aq-gold);
    box-shadow: var(--shadow-md);
}

.info-faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 20px 24px;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}

.info-faq-question:hover {
    background: var(--aq-cream);
}

.info-faq-question__text {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 700;
    color: var(--aq-primary);
    flex: 1;
}

.info-faq-question__text i {
    color: var(--aq-gold);
    font-size: 18px;
    flex-shrink: 0;
}

.info-faq-question__icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--aq-cream);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--aq-primary);
    font-size: 14px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.info-faq-item.active .info-faq-question__icon {
    background: var(--aq-gold);
    color: var(--aq-white);
    transform: rotate(180deg);
}

.info-faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.4s ease;
    padding: 0 24px;
}

.info-faq-item.active .info-faq-answer {
    max-height: 300px;
    padding: 0 24px 24px;
}

.info-faq-answer__content {
    padding-top: 16px;
    border-top: 2px dashed var(--aq-border);
    font-size: 14px;
    color: var(--aq-text-muted);
    line-height: 1.7;
}

/* ===========================================================
   8. TIPS SECTION
   =========================================================== */
.info-tips {
    background: linear-gradient(135deg, var(--aq-cream), var(--aq-cream-dark));
    border: 2px solid var(--aq-border);
    border-radius: var(--radius-xl);
    padding: 40px;
    box-shadow: var(--shadow-sm);
}

.info-tips__title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 26px;
    font-weight: 800;
    color: var(--aq-primary);
    margin: 0 0 24px 0;
}

.info-tips__title i {
    color: var(--aq-gold);
    font-size: 30px;
}

.info-tips__list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

.info-tips__list li {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 18px 20px;
    background: var(--aq-white);
    border: 1.5px solid var(--aq-border);
    border-radius: var(--radius-md);
    font-size: 14px;
    color: var(--aq-text-dark);
    line-height: 1.6;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
}

.info-tips__list li:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-md);
    border-color: var(--aq-gold);
}

.info-tips__list li i {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--aq-gold-light), var(--aq-gold));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--aq-white);
    font-size: 14px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

/* ===========================================================
   9. RESPONSIVE
   =========================================================== */
@media (max-width: 1024px) {
    .info-hero {
        padding: 48px 32px;
    }

    .info-hero__title {
        font-size: 36px;
    }

    .info-roadmap__track {
        flex-wrap: wrap;
        justify-content: center;
    }

    .info-roadmap__arrow {
        display: none;
    }

    .info-roadmap__step {
        flex: 0 0 calc(50% - 16px);
    }
}

@media (max-width: 768px) {
    .info-page {
        padding: 20px 16px 60px;
    }

    .info-hero {
        padding: 40px 24px;
        border-radius: var(--radius-lg);
    }

    .info-hero__icon {
        font-size: 56px;
    }

    .info-hero__title {
        font-size: 28px;
    }

    .info-hero__desc {
        font-size: 15px;
    }

    .info-section-title {
        font-size: 24px;
    }

    .info-section-title i {
        font-size: 26px;
    }

    .info-roadmap__step {
        flex: 0 0 100%;
    }

    .info-game-grid {
        grid-template-columns: 1fr;
    }

    .info-structure-grid {
        grid-template-columns: 1fr;
    }

    .info-badges-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .info-faq-question {
        padding: 16px 18px;
    }

    .info-faq-question__text {
        font-size: 14px;
    }

    .info-tips {
        padding: 28px 20px;
    }

    .info-tips__title {
        font-size: 22px;
    }

    .info-tips__list {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .info-hero {
        padding: 32px 20px;
    }

    .info-hero__icon {
        font-size: 48px;
    }

    .info-hero__title {
        font-size: 24px;
    }

    .info-hero__desc {
        font-size: 14px;
    }

    .info-badges-grid {
        grid-template-columns: 1fr;
    }

    .info-badge-card {
        padding: 24px 16px;
    }

    .info-game-card {
        padding: 28px 20px;
    }

    .info-structure-card {
        padding: 24px 20px;
    }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    .info-hero::before,
    .info-game-card,
    .info-badge-card,
    .info-roadmap__card,
    .info-structure-card,
    .info-tips__list li {
        transition: none !important;
    }

    .info-game-card:hover,
    .info-badge-card:hover,
    .info-roadmap__card:hover,
    .info-structure-card:hover,
    .info-tips__list li:hover {
        transform: none !important;
    }
}
</style>

<div class="info-page">

    {{-- ============================================
         1. HERO SECTION
    ============================================ --}}
    <section class="info-hero">
        <div class="info-hero__badge">
            <i class="fas fa-gamepad"></i>
            <span>Gamified Learning Platform</span>
        </div>
        <div class="info-hero__icon">📖</div>
        <h1 class="info-hero__title">Pusat Informasi Arabic Quest</h1>
        <p class="info-hero__desc">
            Pelajari sistem pembelajaran, XP, Badge, Streak, Quiz, dan perjalanan belajar Bahasa Arab di Arabic Quest.
        </p>
    </section>


    {{-- ============================================
         2. ROADMAP PEMBELAJARAN
    ============================================ --}}
    <section class="info-roadmap">
        <h2 class="info-section-title">
            <i class="fas fa-route"></i>
            <span>Roadmap Pembelajaran</span>
        </h2>

        <div class="info-roadmap__track">
            <div class="info-roadmap__step">
                <div class="info-roadmap__card">
                    <div class="info-roadmap__icon">📖</div>
                    <p class="info-roadmap__label">Materi</p>
                </div>
            </div>
            <i class="fas fa-chevron-right info-roadmap__arrow"></i>

            <div class="info-roadmap__step">
                <div class="info-roadmap__card">
                    <div class="info-roadmap__icon">✍️</div>
                    <p class="info-roadmap__label">Latihan</p>
                </div>
            </div>
            <i class="fas fa-chevron-right info-roadmap__arrow"></i>

            <div class="info-roadmap__step">
                <div class="info-roadmap__card">
                    <div class="info-roadmap__icon">🎯</div>
                    <p class="info-roadmap__label">Quiz Evaluasi</p>
                </div>
            </div>
            <i class="fas fa-chevron-right info-roadmap__arrow"></i>

            <div class="info-roadmap__step">
                <div class="info-roadmap__card">
                    <div class="info-roadmap__icon">🔓</div>
                    <p class="info-roadmap__label">Level Baru</p>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         3. SISTEM GAME
    ============================================ --}}
    <section class="info-game-system">
        <h2 class="info-section-title">
            <i class="fas fa-gamepad"></i>
            <span>Sistem Game Arabic Quest</span>
        </h2>

        <div class="info-game-grid">
            {{-- XP System --}}
            <div class="info-game-card info-game-card--xp">
                <div class="info-game-card__icon">⭐</div>
                <h3 class="info-game-card__title">Sistem XP</h3>
                <p class="info-game-card__desc">
                    XP diperoleh dari menyelesaikan materi, latihan, quiz, dan bonus badge. Kumpulkan XP untuk naik level!
                </p>
            </div>

            {{-- Badge System --}}
            <div class="info-game-card info-game-card--badge">
                <div class="info-game-card__icon">🏅</div>
                <h3 class="info-game-card__title">Sistem Badge</h3>
                <p class="info-game-card__desc">
                    Badge diperoleh ketika pemain mencapai pencapaian tertentu. Koleksi semua badge untuk menjadi master!
                </p>
            </div>

            {{-- Streak System --}}
            <div class="info-game-card info-game-card--streak">
                <div class="info-game-card__icon">🔥</div>
                <h3 class="info-game-card__title">Sistem Streak</h3>
                <p class="info-game-card__desc">
                    Jaga streak harianmu dengan belajar setiap hari. Streak yang panjang memberikan bonus XP ekstra!
                </p>
            </div>

            {{-- Quiz System --}}
            <div class="info-game-card info-game-card--quiz">
                <div class="info-game-card__icon">🎯</div>
                <h3 class="info-game-card__title">Quiz Evaluasi</h3>
                <p class="info-game-card__desc">
                    Setiap level memiliki quiz evaluasi. Lulus quiz untuk membuka level berikutnya dan mendapatkan badge!
                </p>
            </div>

            {{-- Materi System --}}
            <div class="info-game-card info-game-card--materi">
                <div class="info-game-card__icon">📚</div>
                <h3 class="info-game-card__title">Materi & Latihan</h3>
                <p class="info-game-card__desc">
                    Materi tersusun sistematis dari pemula hingga mahir. Setiap materi dilengkapi latihan interaktif.
                </p>
            </div>
        </div>
    </section>


    {{-- ============================================
         4. STRUKTUR PEMBELAJARAN
    ============================================ --}}
    <section class="info-structure">
        <h2 class="info-section-title">
            <i class="fas fa-layer-group"></i>
            <span>Struktur Pembelajaran</span>
        </h2>

        <div class="info-structure-grid">
            {{-- Stage 1 - Pemula --}}
            <div class="info-structure-card info-structure-card--pemula">
                <div class="info-structure-card__header">
                    <div class="info-structure-card__icon">🌱</div>
                    <div>
                        <h3 class="info-structure-card__title">Stage 1 — Pemula</h3>
                        <p class="info-structure-card__subtitle">Dasar-dasar Bahasa Arab</p>
                    </div>
                </div>
                <ul class="info-structure-card__list">
                    <li><i class="fas fa-book"></i> Level 1: Huruf Hijaiyah</li>
                    <li><i class="fas fa-book"></i> Level 2: Kosakata Dasar</li>
                    <li><i class="fas fa-book"></i> Level 3: Angka & Warna</li>
                </ul>
            </div>

            {{-- Stage 2 - Menengah --}}
            <div class="info-structure-card info-structure-card--menengah">
                <div class="info-structure-card__header">
                    <div class="info-structure-card__icon">🌿</div>
                    <div>
                        <h3 class="info-structure-card__title">Stage 2 — Menengah</h3>
                        <p class="info-structure-card__subtitle">Tata bahasa & percakapan</p>
                    </div>
                </div>
                <ul class="info-structure-card__list">
                    <li><i class="fas fa-book"></i> Level 1: Fi'il Madhi</li>
                    <li><i class="fas fa-book"></i> Level 2: Fi'il Mudhari</li>
                    <li><i class="fas fa-book"></i> Level 3: Harf Jar</li>
                </ul>
            </div>

            {{-- Stage 3 - Mahir --}}
            <div class="info-structure-card info-structure-card--mahir">
                <div class="info-structure-card__header">
                    <div class="info-structure-card__icon">🌳</div>
                    <div>
                        <h3 class="info-structure-card__title">Stage 3 — Mahir</h3>
                        <p class="info-structure-card__subtitle">Nahwu & Shorof lanjutan</p>
                    </div>
                </div>
                <ul class="info-structure-card__list">
                    <li><i class="fas fa-book"></i> Level 1: I'rab</li>
                    <li><i class="fas fa-book"></i> Level 2: Wazan Fi'il</li>
                    <li><i class="fas fa-book"></i> Level 3: Inna wa Akhawatuha</li>
                </ul>
            </div>
        </div>
    </section>


    {{-- ============================================
         5. KOLEKSI BADGE
    ============================================ --}}
    <section class="info-badges">
        <h2 class="info-section-title">
            <i class="fas fa-trophy"></i>
            <span>Koleksi Badge</span>
        </h2>

        <div class="info-badges-grid">
            <div class="info-badge-card">
                <div class="info-badge-card__icon">🏅</div>
                <h3 class="info-badge-card__title">Explorer</h3>
                <p class="info-badge-card__desc">Selesaikan 10 materi pertama</p>
            </div>

            <div class="info-badge-card">
                <div class="info-badge-card__icon">📚</div>
                <h3 class="info-badge-card__title">Scholar</h3>
                <p class="info-badge-card__desc">Lulus 5 quiz evaluasi</p>
            </div>

            <div class="info-badge-card">
                <div class="info-badge-card__icon">🔥</div>
                <h3 class="info-badge-card__title">Streak Master</h3>
                <p class="info-badge-card__desc">Jaga streak 7 hari berturut-turut</p>
            </div>

            <div class="info-badge-card">
                <div class="info-badge-card__icon">🎯</div>
                <h3 class="info-badge-card__title">Quiz Champion</h3>
                <p class="info-badge-card__desc">Raih skor 100% di quiz</p>
            </div>

            <div class="info-badge-card">
                <div class="info-badge-card__icon">⭐</div>
                <h3 class="info-badge-card__title">XP Hunter</h3>
                <p class="info-badge-card__desc">Kumpulkan 1000 XP</p>
            </div>
        </div>
    </section>


    {{-- ============================================
         6. FAQ
    ============================================ --}}
    <section class="info-faq">
        <h2 class="info-section-title">
            <i class="fas fa-question-circle"></i>
            <span>Pertanyaan Umum (FAQ)</span>
        </h2>

        <div class="info-faq-list">
            <div class="info-faq-item">
                <div class="info-faq-question" onclick="toggleFaq(this)">
                    <div class="info-faq-question__text">
                        <i class="fas fa-lock"></i>
                        <span>Kenapa level saya terkunci?</span>
                    </div>
                    <div class="info-faq-question__icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="info-faq-answer">
                    <div class="info-faq-answer__content">
                        Level akan terbuka secara otomatis setelah kamu menyelesaikan level sebelumnya dan lulus quiz evaluasi. Pastikan kamu sudah menyelesaikan semua materi dan latihan di level sebelumnya.
                    </div>
                </div>
            </div>

            <div class="info-faq-item">
                <div class="info-faq-question" onclick="toggleFaq(this)">
                    <div class="info-faq-question__text">
                        <i class="fas fa-medal"></i>
                        <span>Bagaimana mendapatkan badge?</span>
                    </div>
                    <div class="info-faq-question__icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="info-faq-answer">
                    <div class="info-faq-answer__content">
                        Badge diperoleh secara otomatis ketika kamu mencapai pencapaian tertentu, seperti menyelesaikan jumlah materi tertentu, menjaga streak, atau meraih skor sempurna di quiz. Setiap badge memiliki kriteria yang berbeda.
                    </div>
                </div>
            </div>

            <div class="info-faq-item">
                <div class="info-faq-question" onclick="toggleFaq(this)">
                    <div class="info-faq-question__text">
                        <i class="fas fa-redo"></i>
                        <span>Apakah latihan bisa diulang?</span>
                    </div>
                    <div class="info-faq-question__icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="info-faq-answer">
                    <div class="info-faq-answer__content">
                        Ya! Kamu bisa mengulang materi dan latihan sebanyak yang kamu mau. Ini sangat berguna untuk memperkuat pemahaman. Namun, XP hanya akan diberikan sekali untuk setiap materi yang diselesaikan pertama kali.
                    </div>
                </div>
            </div>

            <div class="info-faq-item">
                <div class="info-faq-question" onclick="toggleFaq(this)">
                    <div class="info-faq-question__text">
                        <i class="fas fa-minus-circle"></i>
                        <span>Apakah XP bisa berkurang?</span>
                    </div>
                    <div class="info-faq-question__icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="info-faq-answer">
                    <div class="info-faq-answer__content">
                        Tidak! XP yang sudah kamu kumpulkan tidak akan berkurang. XP bersifat permanen dan akan terus bertambah seiring kamu menyelesaikan materi, latihan, dan quiz. Ini adalah progres belajarmu yang sebenarnya.
                    </div>
                </div>
            </div>

            <div class="info-faq-item">
                <div class="info-faq-question" onclick="toggleFaq(this)">
                    <div class="info-faq-question__text">
                        <i class="fas fa-unlock"></i>
                        <span>Bagaimana cara membuka level berikutnya?</span>
                    </div>
                    <div class="info-faq-question__icon">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <div class="info-faq-answer">
                    <div class="info-faq-answer__content">
                        Untuk membuka level berikutnya, kamu harus: (1) Menyelesaikan semua materi di level saat ini, (2) Mengerjakan semua latihan, dan (3) Lulus quiz evaluasi dengan skor minimal 70%. Setelah semua syarat terpenuhi, level berikutnya akan otomatis terbuka.
                    </div>
                </div>
            </div>
        </div>
    </section>


    {{-- ============================================
         7. TIPS BELAJAR
    ============================================ --}}
    <section class="info-tips">
        <h2 class="info-tips__title">
            <i class="fas fa-lightbulb"></i>
            <span>Tips Belajar Efektif</span>
        </h2>

        <ul class="info-tips__list">
            <li>
                <i class="fas fa-check"></i>
                <span>Pelajari materi secara berurutan untuk membangun pemahaman yang kuat dari dasar.</span>
            </li>
            <li>
                <i class="fas fa-check"></i>
                <span>Kerjakan latihan setelah memahami materi untuk menguji pemahamanmu.</span>
            </li>
            <li>
                <i class="fas fa-check"></i>
                <span>Jaga streak harian dengan belajar setiap hari untuk bonus XP ekstra.</span>
            </li>
            <li>
                <i class="fas fa-check"></i>
                <span>Kumpulkan badge sebanyak mungkin sebagai simbol pencapaianmu.</span>
            </li>
            <li>
                <i class="fas fa-check"></i>
                <span>Ulangi materi jika belum memahami konsep. Belajar adalah proses!</span>
            </li>
        </ul>
    </section>

</div>


@push('scripts')
<script>
function toggleFaq(element) {
    const faqItem = element.closest('.info-faq-item');
    const isActive = faqItem.classList.contains('active');

    // Close all FAQ items
    document.querySelectorAll('.info-faq-item').forEach(item => {
        item.classList.remove('active');
    });

    // Toggle current item
    if (!isActive) {
        faqItem.classList.add('active');
    }
}
</script>
@endpush
@endsection