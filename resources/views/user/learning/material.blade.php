<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $material->title) - Arabic Quest</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Cinzel:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/material.css') }}">
    
    @stack('styles')
</head>
<body>

@php
    use App\Helpers\ArabicHelper;

    // ============================================
    // PARSING DATA — DIPANGGIL SEKALI DI AWAL
    // Semua logic dipindahkan ke ArabicHelper
    // ============================================
    $groupedVocab  = ArabicHelper::parseVocab($material->vocab_content ?? '');
    $parsedGrammar = ArabicHelper::parseGrammar($material->grammar_content ?? '');
    $dialogData    = ArabicHelper::parseDialog($material->dialog_content ?? '');

    $scene     = $dialogData['scene'];
    $time      = $dialogData['time'];
    $dialogues = $dialogData['dialogues'];

    // Progress dihitung dari field is_selesai yang SUDAH ADA di DB
    $userProgress     = $material->progress->where('id_user', Auth::id())->first();
    $materialProgress = ArabicHelper::calculateProgress($userProgress);
@endphp

<div class="material-page">
    
    {{-- ============================================
         BREADCRUMB
    ============================================ --}}
    <nav class="material-breadcrumb">
        <a href="{{ route('user.dashboard') }}" class="material-breadcrumb__link">
            <i class="fas fa-home"></i>
            <span>Akademi Bahasa Arab</span>
        </a>
        <i class="fas fa-chevron-right material-breadcrumb__sep"></i>
        <a href="{{ route('user.level', $material->level->id_level) }}" class="material-breadcrumb__link">
            <span>Level {{ $material->level->level_order }}</span>
        </a>
        <i class="fas fa-chevron-right material-breadcrumb__sep"></i>
        <span class="material-breadcrumb__current">{{ $material->title }}</span>
    </nav>


    {{-- ============================================
         MAIN LAYOUT (2 kolom di desktop)
    ============================================ --}}
    <div class="material-layout">

        {{-- ============================================
             MAIN CONTENT
        ============================================ --}}
        <div class="material-main">

            {{-- ============================================
                 MATERIAL HERO
            ============================================ --}}
            <section class="material-hero">
                <div class="material-hero__content">
                    <div class="material-hero__info">
                        <span class="material-hero__eyebrow">
                            <i class="fas fa-book-open"></i>
                            Level {{ $material->level->level_order }} · {{ $material->level->stage->stage_name ?? 'Materi' }}
                        </span>
                        <h1 class="material-hero__title">{{ $material->title }}</h1>
                        <div class="material-hero__meta">
                            <span class="material-hero__badge {{ $isCompleted ? 'material-hero__badge--done' : '' }}">
                                @if($isCompleted)
                                    <i class="fas fa-check-circle"></i> Selesai
                                @else
                                    <i class="fas fa-clock"></i> Dalam Progress
                                @endif
                            </span>
                            <span class="material-hero__badge material-hero__badge--reward">
                                <i class="fas fa-star"></i> {{ $material->xp_reward }} XP
                            </span>
                        </div>
                    </div>

                    <div class="material-hero__visual">
                        <div class="material-hero__icon-wrapper">
                            <div class="material-hero__icon">📖</div>
                            <div class="material-hero__icon-glow"></div>
                        </div>
                        <div class="material-hero__reward-box">
                            <span class="material-hero__reward-label">Reward</span>
                            <span class="material-hero__reward-value">
                                <i class="fas fa-star"></i> {{ $material->xp_reward }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Progress dinamis dari ArabicHelper::calculateProgress --}}
                <div class="material-hero__progress">
                    <div class="material-hero__progress-info">
                        <span class="material-hero__progress-label">
                            <i class="fas fa-route"></i> Progress Pembelajaran
                        </span>
                        <span class="material-hero__progress-text">{{ $materialProgress }}%</span>
                    </div>
                    <div class="material-hero__progress-bar">
                        <div class="material-hero__progress-fill" style="width: {{ $materialProgress }}%">
                            <span class="material-hero__progress-shine"></span>
                        </div>
                    </div>
                </div>
            </section>


            {{-- ============================================
                 MATERIAL JOURNEY (Timeline)
            ============================================ --}}
            <!--<section class="material-journey">
                <div class="material-journey__track">
                    <div class="material-journey__step material-journey__step--active" data-tab="vocab">
                        <div class="material-journey__circle">
                            <span>🔤</span>
                        </div>
                        <span class="material-journey__label">Vocabulary</span>
                    </div>
                    <div class="material-journey__line"></div>
                    <div class="material-journey__step" data-tab="grammar">
                        <div class="material-journey__circle">
                            <span>📐</span>
                        </div>
                        <span class="material-journey__label">Grammar</span>
                    </div>
                    <div class="material-journey__line"></div>
                    <div class="material-journey__step" data-tab="dialog">
                        <div class="material-journey__circle">
                            <span>💬</span>
                        </div>
                        <span class="material-journey__label">Dialog</span>
                    </div>
                    <div class="material-journey__line"></div>
                    <div class="material-journey__step material-journey__step--locked" data-tab="practice">
                        <div class="material-journey__circle">
                            <span>🔒</span>
                        </div>
                        <span class="material-journey__label">Practice</span>
                    </div>
                </div>
            </section>-->


            {{-- ============================================
                 TABS NAVIGATION (Segmented Control)
            ============================================ --}}
            <section class="material-tabs">
                <div class="material-tabs__nav">
                    <button onclick="switchTab('vocab')" id="btn-vocab" 
                            class="material-tabs__button material-tabs__button--active">
                        <span class="material-tabs__button-icon">🔤</span>
                        <span class="material-tabs__button-label">Vocab</span>
                    </button>
                    <button onclick="switchTab('grammar')" id="btn-grammar" 
                            class="material-tabs__button">
                        <span class="material-tabs__button-icon">📐</span>
                        <span class="material-tabs__button-label">Grammar</span>
                    </button>
                    <button onclick="switchTab('dialog')" id="btn-dialog" 
                            class="material-tabs__button">
                        <span class="material-tabs__button-icon">💬</span>
                        <span class="material-tabs__button-label">Dialog</span>
                    </button>
                </div>
            </section>


            {{-- ============================================
                 TABS CONTENT
            ============================================ --}}
            <div class="material-content">
                
                {{-- ============================================
                     TAB: VOCABULARY
                ============================================ --}}
                <div id="tab-vocab" class="tab-content">
                    @if(empty($groupedVocab))
                        @if(!empty(trim($material->vocab_content ?? '')))
                            <div class="material-vocab-warning">
                                <div class="material-vocab-warning__icon">⚠️</div>
                                <div class="material-vocab-warning__content">
                                    <p class="material-vocab-warning__title">Format vocab tidak terdeteksi</p>
                                    <pre class="material-vocab-warning__raw">{{ $material->vocab_content }}</pre>
                                </div>
                            </div>
                        @else
                            <div class="material-empty">
                                <div class="material-empty__icon">📭</div>
                                <p class="material-empty__text">Belum ada kosakata.</p>
                            </div>
                        @endif
                    @else
                        @foreach($groupedVocab as $groupName => $words)
                            <div class="material-vocab-section">
                                <div class="material-vocab-section__header">
                                    <div class="material-vocab-section__title-wrap">
                                        <span class="material-vocab-section__dot"></span>
                                        <h3 class="material-vocab-section__title">{{ $groupName }}</h3>
                                    </div>
                                    <span class="material-vocab-section__count">{{ count($words) }} kata</span>
                                </div>
                                <div class="material-vocab-grid">
                                    @foreach($words as $word)
                                    {{-- ✅ Menggunakan data-text attribute (aman dari XSS & karakter khusus) --}}
                                    <div class="material-vocab-card" 
                                         data-text="{{ $word['arabic'] }}"
                                         onclick="playAudio(this)">
                                        <div class="material-vocab-card__emoji">{{ $word['emoji'] }}</div>
                                        <p class="material-vocab-card__arabic" dir="rtl">{{ $word['arabic'] }}</p>
                                        @if($word['translit'])
                                            <p class="material-vocab-card__translit">{{ $word['translit'] }}</p>
                                        @endif
                                        <p class="material-vocab-card__meaning">{{ $word['meaning'] }}</p>
                                        <div class="material-vocab-card__listen">
                                            <i class="fas fa-volume-up"></i>
                                            <span>Tap untuk dengar</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>


                {{-- ============================================
                     TAB: GRAMMAR
                ============================================ --}}
                <div id="tab-grammar" class="tab-content hidden-tab">
                    <div class="material-grammar">
                        {{-- ✅ HTML sudah disanitasi oleh ArabicHelper::sanitizeHtml --}}
                        {!! $parsedGrammar !!}
                    </div>
                    
                    <div class="material-grammar-reference">
                        <div class="material-grammar-reference__header">
                            <span class="material-grammar-reference__icon">📌</span>
                            <h4 class="material-grammar-reference__title">Quick Reference</h4>
                        </div>
                        <ul class="material-grammar-reference__list">
                            <li class="material-grammar-reference__item">
                                <span class="material-grammar-reference__marker">•</span>
                                <span><strong>Marfu'</strong> = Dhommah (ـُ) → Subjek</span>
                            </li>
                            <li class="material-grammar-reference__item">
                                <span class="material-grammar-reference__marker">•</span>
                                <span><strong>Manshub</strong> = Fathah (ـَ) → Objek</span>
                            </li>
                            <li class="material-grammar-reference__item">
                                <span class="material-grammar-reference__marker">•</span>
                                <span><strong>Majrur</strong> = Kasroh (ـِ) → Setelah huruf jar</span>
                            </li>
                        </ul>
                    </div>
                </div>


                {{-- ============================================
                     TAB: DIALOG
                ============================================ --}}
                <div id="tab-dialog" class="tab-content hidden-tab">
                    @if(empty($dialogues))
                        @if(!empty(trim($material->dialog_content ?? '')))
                            <div class="material-vocab-warning">
                                <div class="material-vocab-warning__icon">⚠️</div>
                                <div class="material-vocab-warning__content">
                                    <p class="material-vocab-warning__title">Format dialog tidak terdeteksi</p>
                                    <pre class="material-vocab-warning__raw">{{ $material->dialog_content }}</pre>
                                </div>
                            </div>
                        @else
                            <div class="material-empty">
                                <div class="material-empty__icon">💬</div>
                                <p class="material-empty__text material-empty__text--italic">Konten dialog belum tersedia.</p>
                            </div>
                        @endif
                    @else
                        @if($scene)
                        <div class="material-dialog-scene">
                            <div class="material-dialog-scene__icon">📍</div>
                            <div class="material-dialog-scene__info">
                                <p class="material-dialog-scene__title">{{ $scene }}</p>
                                @if($time)
                                    <p class="material-dialog-scene__time">⏰ {{ $time }}</p>
                                @endif
                            </div>
                        </div>
                        @endif
                        
                        <div class="material-dialog-list">
                            @foreach($dialogues as $index => $dlg)
                            @php
                                $isRight = $dlg['isRight'] ?? ($index % 2 === 1);
                                $moodEmoji = match(strtolower($dlg['mood'] ?? '')) {
                                    'happy' => '😊', 'excited' => '🤩', 'confident' => '😎',
                                    'proud' => '🥰', 'curious' => '🤔', 'thinking' => '💭',
                                    'teaching' => '👨‍🏫', default => ''
                                };
                            @endphp
                            
                            {{-- ✅ Menggunakan data-text attribute di parent bubble --}}
                            <div class="material-dialog-bubble {{ $isRight ? 'material-dialog-bubble--right' : '' }}"
                                 data-text="{{ $dlg['arabic'] }}">
                                <div class="material-dialog-bubble__avatar" 
                                     onclick="playDialogAudio(event, this)"
                                     title="Klik untuk dengar">
                                    {{ $dlg['avatar'] ?? '👤' }}
                                    <span class="material-dialog-bubble__audio-hint">
                                        <i class="fas fa-volume-up"></i>
                                    </span>
                                </div>
                                
                                <div class="material-dialog-bubble__content">
                                    <div class="material-dialog-bubble__header {{ $isRight ? 'material-dialog-bubble__header--right' : '' }}">
                                        <span class="material-dialog-bubble__name">{{ $dlg['name'] ?? 'Karakter' }}</span>
                                        @if($moodEmoji)
                                            <span class="material-dialog-bubble__mood">{{ $moodEmoji }}</span>
                                        @endif
                                        <button onclick="playDialogAudio(event, this)" 
                                                class="material-dialog-bubble__audio-btn"
                                                title="🔊 Dengarkan">
                                            <i class="fas fa-volume-up"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="material-dialog-bubble__body {{ $isRight ? 'material-dialog-bubble__body--right' : '' }}"
                                         onclick="playDialogAudio(event, this)">
                                        @if(isset($dlg['action']))
                                            <p class="material-dialog-bubble__action">*{{ $dlg['action'] }}*</p>
                                        @endif
                                        
                                        @if(!empty($dlg['arabic']))
                                            <p class="material-dialog-bubble__arabic" dir="rtl">{{ $dlg['arabic'] }}</p>
                                        @endif
                                        
                                        @if(!empty($dlg['translation']))
                                            <p class="material-dialog-bubble__translation {{ $isRight ? 'material-dialog-bubble__translation--right' : '' }}">
                                                {{ $dlg['translation'] }}
                                            </p>
                                        @endif
                                        
                                        <div class="material-dialog-bubble__listen-hint">
                                            <i class="fas fa-volume-up"></i>
                                            <span>Klik untuk dengar</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="material-dialog-playall">
                            <button onclick="playAllDialogs()" class="material-dialog-playall__btn">
                                <i class="fas fa-play"></i>
                                <span>🎧 Putar Seluruh Dialog</span>
                            </button>
                        </div>
                    @endif
                </div>

            </div>


            {{-- ============================================
                 ACTION SECTION
            ============================================ --}}
            <section class="material-action">
                <div class="material-action__header">
                    <h3 class="material-action__title">
                        <span class="material-action__title-icon">🚀</span>
                        Langkah Selanjutnya
                    </h3>
                </div>
                
                <div class="material-action__buttons">
                    @if(!$isCompleted)
                    <form method="POST" action="{{ route('user.material.complete', $material->id_material) }}" 
                          class="material-action__form">
                        @csrf
                        <button type="submit" class="material-action__btn material-action__btn--primary">
                            <i class="fas fa-check-circle"></i>
                            <span>Tandai Selesai</span>
                            <span class="material-action__btn-badge">+{{ $material->xp_reward }} XP</span>
                        </button>
                    </form>
                    @else
                    <div class="material-action__completed">
                        <i class="fas fa-check-circle"></i>
                        <span>Materi Selesai! ✨</span>
                    </div>
                    @endif
                    
                @if($material->questions->count() > 0)
                <a href="{{ route('user.practice', $material->id_material) }}" 
                   class="material-sidebar__cta">
                    <i class="fas fa-play"></i>
                    <span>Mulai Latihan</span>
                </a>
                @endif
                </div>
            </section>

        </div>


        

    </div>
</div>


{{-- ============================================
     JAVASCRIPT
     - Arabic voice detection
     - Cross-browser event handling
     - data-text attribute untuk keamanan
============================================ --}}
<script>
    /**
     * ARABIC VOICE DETECTION
     * Cari voice Arab terlebih dahulu, fallback ke lang='ar-SA'
     */
    let arabicVoice = null;
    let voicesLoaded = false;

    function loadArabicVoice() {
        if (!('speechSynthesis' in window)) return;
        
        const voices = speechSynthesis.getVoices();
        if (voices.length === 0) return;

        // Cari voice Arab
        arabicVoice = voices.find(v => 
            v.lang.startsWith('ar') || 
            v.lang.includes('ar-SA') || 
            v.lang.includes('ar-EG') ||
            v.name.toLowerCase().includes('arabic')
        );

        voicesLoaded = true;
    }

    // Load voices saat tersedia (browser async)
    if ('speechSynthesis' in window) {
        loadArabicVoice();
        speechSynthesis.onvoiceschanged = loadArabicVoice;
    }

    /**
     * SWITCH TAB dengan sinkronisasi journey timeline
     */
     function switchTab(tabName) {

document.querySelectorAll('.tab-content')
    .forEach(el => el.classList.add('hidden-tab'));

document.querySelectorAll('.material-tabs__button')
    .forEach(btn => btn.classList.remove('material-tabs__button--active'));

const target = document.getElementById('tab-' + tabName);

if (target) {
    target.classList.remove('hidden-tab');
}

const activeBtn = document.getElementById('btn-' + tabName);

if (activeBtn) {
    activeBtn.classList.add('material-tabs__button--active');
}
}
    
    /**
     * PLAY AUDIO untuk Vocab
     * Menggunakan data-text attribute (aman dari XSS)
     */
    function playAudio(element) {
        const text = element.dataset.text;
        if (!text) return;

        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            
            // Gunakan Arabic voice jika ada
            if (arabicVoice) {
                utterance.voice = arabicVoice;
            } else {
                utterance.lang = 'ar-SA';
            }
            utterance.rate = 0.8;
            
            // Visual feedback
            const originalBorder = element.style.border;
            element.style.border = '2px solid #775537';
            
            utterance.onend = function() {
                element.style.border = originalBorder;
            };
            
            speechSynthesis.speak(utterance);
        } else {
            alert('🔊 Browser tidak mendukung audio. Silakan gunakan Chrome/Edge.');
        }
    }
    
    /**
     * PLAY DIALOG AUDIO
     * Event di-pass eksplisit sebagai parameter (cross-browser)
     * Text diambil dari data-text parent bubble
     */
    function playDialogAudio(event, element) {
        // Stop propagation agar tidak trigger parent
        if (event) event.stopPropagation();
        
        // Cari data-text dari parent bubble
        const bubble = element.closest('.material-dialog-bubble');
        const text = bubble ? bubble.dataset.text : element.dataset.text;
        if (!text) return;

        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utterance = new SpeechSynthesisUtterance(text);
            
            // Gunakan Arabic voice jika ada
            if (arabicVoice) {
                utterance.voice = arabicVoice;
            } else {
                utterance.lang = 'ar-SA';
            }
            utterance.rate = 0.7;
            
            // Visual feedback
            const originalBg = element.style.backgroundColor;
            element.style.backgroundColor = 'rgba(119, 85, 55, 0.3)';
            
            utterance.onend = function() {
                element.style.backgroundColor = originalBg;
            };
            
            speechSynthesis.speak(utterance);
        } else {
            alert('🔊 Browser tidak mendukung audio.');
        }
    }
    
    /**
     * PLAY ALL DIALOGS
     * Memutar seluruh dialog secara berurutan dengan delay
     */
    function playAllDialogs() {
        const bubbles = document.querySelectorAll('#tab-dialog .material-dialog-bubble');
        let delay = 0;
        
        bubbles.forEach((bubble) => {
            setTimeout(() => {
                const arabicText = bubble.querySelector('.material-dialog-bubble__arabic');
                if (arabicText) {
                    const text = bubble.dataset.text;
                    const avatar = bubble.querySelector('.material-dialog-bubble__avatar');
                    
                    if ('speechSynthesis' in window && text) {
                        const utterance = new SpeechSynthesisUtterance(text);
                        
                        if (arabicVoice) {
                            utterance.voice = arabicVoice;
                        } else {
                            utterance.lang = 'ar-SA';
                        }
                        utterance.rate = 0.7;
                        
                        // Highlight avatar saat diputar
                        avatar.style.transform = 'scale(1.2)';
                        bubble.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        
                        utterance.onend = function() {
                            avatar.style.transform = 'scale(1)';
                        };
                        
                        speechSynthesis.speak(utterance);
                    }
                }
            }, delay);
            
            delay += 3000; // 3 detik per dialog
        });
    }
</script>

@if(session('badge_popup'))

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    Swal.fire({
        title: '🏅 Badge Baru!',
        html: `
            <div style="font-size:70px">
                {{ session('badge_popup')['icon'] }}
            </div>

            <h3>{{ session('badge_popup')['name'] }}</h3>

            <p>Selamat! Kamu mendapatkan badge baru.</p>
        `,
        confirmButtonText: 'Keren!',
        confirmButtonColor: '#775537'
    });

});
</script>
@endif

</body>
</html>