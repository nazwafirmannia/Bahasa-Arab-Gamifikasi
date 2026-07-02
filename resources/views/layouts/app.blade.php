<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'ArabicQuest')</title>
        
        <!-- 1. Tailwind CDN (Utility Layout) -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <!-- 2. Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        <!-- 3. ✅ ALPINE.JS (WAJIB untuk dropdown & interaktif!) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- 3. Google Fonts: Poppins + Amiri (Arabic) -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <!-- 4. ✅ CSS TEMA PUSAT (WAJIB DI-LOAD SETELAH TAILWIND) -->
        <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
        
        @stack('styles')
    </head>


    <body class="bg-stone-50 text-stone-900 font-sans">

        <div class="flex h-screen overflow-hidden">
            
            <!-- SIDEBAR -->
            <aside
id="sidebar"

class="sidebar fixed lg:relative top-0 left-0 h-screen w-64 overflow-y-auto bg-primary shadow-xl transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-50 flex flex-col"
<!-- Logo - NO ICON, NO UNDERLINE -->
<div class="h-20 flex items-center justify-center px-4">
    <h1 class="text-2xl font-extrabold tracking-wider" 
        style="font-family: 'Cinzel', serif !important; color: #FFFFFF !important; text-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;">
        ARABIC QUEST
    </h1>
</div>
    
                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto py-4">

                    @auth
                        @if(Auth::user()->role === 'user')
                            <!-- User Menu -->
                            <a href="{{ route('user.dashboard') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-white/85 hover:text-white transition {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">
                                <i class="fas fa-home w-5"></i>
                                <span class="menu-text font-medium">Akademi</span>
                            </a>

                            <!-- <a href="{{ route('user.shop.index') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-white/85 hover:text-white transition {{ request()->routeIs('user.shop.*') ? 'active' : '' }}">
                                <i class="fas fa-shopping-bag w-5"></i>
                                <span class="menu-text font-medium">Toko Karakter</span>
                            </a> -->
                            
                            <a href="{{ route('user.leaderboard') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-white/85 hover:text-white transition {{ request()->routeIs('user.leaderboard') ? 'active' : '' }}">
                                <i class="fas fa-trophy w-5"></i>
                                <span class="menu-text font-medium">Leaderboard</span>
                            </a>
    
                            <a href="{{ route('user.profile') }}" class="nav-item flex items-center gap-3 px-6 py-3 text-white/85 hover:text-white transition {{ request()->routeIs('user.profile') ? 'active' : '' }}">
                                <i class="fas fa-user-circle w-5"></i>
                                <span class="menu-text font-medium">Profile</span>
                            </a>
    
                            <a href="{{ route('user.informasi') }}" class="sidebar-menu__item">
                                <i class="fas fa-circle-info"></i>
                                <span>Pusat Informasi</span>
                            </a>
                            
                            <div class="border-t border-white/10 my-2"></div>
                            
                            <!-- My Progress - WHITE TEXT -->
                            <div class="px-6 py-2">
                                <p class="menu-text text-xs font-semibold text-white uppercase mb-3 tracking-wide">Progres Saya</p>
                                <div class="bg-white/10 rounded-lg p-3 mb-2 backdrop-blur-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-bold text-white">Total XP</span>
                                        <i class="fas fa-star text-accent-yellow"></i>
                                    </div>
                                    <p class="text-lg font-bold text-white">{{ Auth::user()->stat->xp_total ?? 0 }}</p>
                                </div>
                                <!--<div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-bold text-white">Coin</span>
                                        <i class="fas fa-coins text-accent-yellow"></i>
                                    </div>
                                    <p class="text-lg font-bold text-white">{{ Auth::user()->stat->coin_balance ?? 0 }}</p>
                                </div>
                            </div>-->
                        @endif
                    @endauth
                </nav>
    
                <!-- User Profile - WHITE TEXT -->
                @auth
                <div class="border-t border-white/10 p-4 bg-primary-dark/30">

                    @php
                    $avatar = \App\Models\Character::where(
                            'unlock_level',
                            '<=',
                            Auth::user()->stat->current_level ?? 1
                        )
                        ->where('is_active', true)
                        ->orderByDesc('unlock_level')
                        ->first();
                    @endphp
                
                    <div class="flex items-center gap-3">
                
                        <img
                            src="{{ $avatar
                                    ? asset('storage/'.$avatar->image)
                                    : asset('images/default-avatar.png') }}"
                            class="w-10 h-10 rounded-full border-2 border-accent-yellow shadow-md"
                            alt="Avatar">
                
                        <div class="menu-text flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">
                                {{ Auth::user()->name_user }}
                            </p>
                
                            <!--<p class="text-xs text-white/70 truncate">
                                Level {{ Auth::user()->stat->current_level ?? 1 }}
                            </p>-->
                        </div>
                
                    </div>
                </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500/20 text-white rounded-lg hover:bg-red-500/30 transition text-sm font-medium">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="menu-text">Logout</span>
                        </button>
                    </form>
         
                @endauth
            </aside>

            <div
    id="sidebarOverlay"
    class="fixed inset-0 bg-black/50 hidden z-40 lg:hidden">
</div>

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            
            <!-- TOP HEADER -->
            <header class="sticky top-0 z-30 h-16 bg-white shadow-sm flex items-center justify-between px-6 border-b border-gray-100">
                <div class="flex items-center gap-4">
                    <button
                    id="menuToggle"
                    class="lg:hidden text-2xl text-primary">
            
                    <i class="fas fa-bars"></i>
            
                </button>
                    <h1 class="text-xl font-bold text-neutral-text">@yield('page-title', 'Akademi')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                    <!-- ✅ NOTIFICATIONS DROPDOWN (Alpine.js) -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="relative p-2 text-neutral-text/60 hover:text-primary transition">
                            <i class="fas fa-bell text-xl"></i>
                            @if(Auth::user()->unread_count > 0)
                            <span class="notification-badge absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">
                                {{ min(Auth::user()->unread_count, 9) }}{{ Auth::user()->unread_count > 9 ? '+' : '' }}
                            </span>
                            @endif
                        </button>
                        
                        <!-- Dropdown Panel -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-100 z-50 overflow-hidden"
                             style="display: none;">
                            
                            <!-- Header -->
                            <div class="p-4 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-primary/5 to-accent-yellow/10">
                                <h3 class="font-bold text-primary text-sm flex items-center gap-2">
                                    <i class="fas fa-bell text-accent-yellow"></i> Notifikasi
                                </h3>
                                <a href="{{ route('user.notifications') }}" class="text-xs text-primary hover:underline font-medium">Lihat Semua</a>
                            </div>
                            
                            <!-- List (max 5) -->
                            <div class="max-h-80 overflow-y-auto">
                                @php $recentNotifs = Auth::user()->notifications()->take(5)->get(); @endphp
                                @forelse($recentNotifs as $notif)
                                <a href="{{ $notif->action_url ?? '#' }}" 
                                   class="block p-4 border-b border-gray-50 hover:bg-gray-50 transition {{ !$notif->read_at ? 'bg-primary/5 border-l-2 border-primary' : '' }}"
                                   @if(!$notif->read_at) onclick="markAsRead({{ $notif->id_notification }}); return true;" @endif>
                                    <div class="flex items-start gap-3">
                                        <span class="text-lg">{{ $notif->icon }}</span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-neutral-text {{ !$notif->read_at ? 'text-primary' : '' }}">
                                                {{ $notif->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">
                                                {{ Str::limit($notif->message, 60) }}
                                            </p>
                                            <p class="text-[10px] text-gray-400 mt-1">
                                                {{ $notif->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if(!$notif->read_at)
                                        <span class="w-2 h-2 bg-primary rounded-full flex-shrink-0 animate-pulse-soft"></span>
                                        @endif
                                    </div>
                                </a>
                                @empty
                                <div class="p-4 text-center text-gray-500 text-sm">
                                    🔕 Tidak ada notifikasi baru
                                </div>
                                @endforelse
                            </div>
                            
                            <!-- Footer -->
                            @if(Auth::user()->unread_count > 0)
                            <div class="p-3 border-t border-gray-100 bg-gradient-to-r from-primary/5 to-accent-yellow/10">
                                <button onclick="markAllAsRead(); return false;" class="w-full text-xs text-center text-primary hover:text-primary-dark font-medium transition flex items-center justify-center gap-1">
                                    <i class="fas fa-check-double"></i> Tandai Semua Dibaca
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Streak Badge -->
                    <div class="hidden md:flex items-center gap-2 px-3 py-1.5 bg-gradient-to-r from-orange-100 to-red-100 rounded-full border border-orange-200">
                        <i class="fas fa-fire text-orange-500"></i>
                        <span class="text-sm font-bold text-orange-700">{{ Auth::user()->stat->streak ?? 0 }} Hari</span>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- MAIN CONTENT AREA -->
            <main class="flex-1 overflow-y-auto bg-neutral-bg p-6">
                
                {{-- ✅ NOTIFIKASI SUCCESS (Updated colors) --}}
                @if(session('success'))
                <div class="mb-4 p-4 bg-primary/10 border-l-4 border-primary text-primary-dark rounded-r-lg flex items-center gap-3 animate-fade-in">
                    <i class="fas fa-check-circle text-xl"></i>
                    <div>
                        <p class="font-semibold">Berhasil!</p>
                        <p>{!! session('success') !!}</p>
                    </div>
                </div>
                @endif
                
                {{-- ✅ NOTIFIKASI ERROR --}}
                @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <div>
                        <p class="font-semibold">Error!</p>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                
                {{-- ✅ NOTIFIKASI STREAK BONUS --}}
                @if(session('streak_bonus'))
                <div class="mb-4 p-4 bg-accent-yellow/20 border-l-4 border-accent-yellow text-amber-800 rounded-r-lg flex items-center gap-3 animate-fade-in">
                    <i class="fas fa-fire text-xl"></i>
                    <div>
                        <p class="font-bold">🔥 Streak Bonus!</p>
                        <p>{{ session('streak_bonus') }}</p>
                    </div>
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
        
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
        
            if (menuToggle && sidebar && overlay) {
        
                // Buka / Tutup Sidebar
                menuToggle.addEventListener('click', function () {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                });
        
                // Klik area gelap untuk menutup sidebar
                overlay.addEventListener('click', function () {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
        
                // Otomatis menutup sidebar setelah memilih menu (khusus mobile)
                document.querySelectorAll('.nav-item, .sidebar-menu__item').forEach(function(item){
        
                    item.addEventListener('click', function () {
        
                        if (window.innerWidth < 1024) {
                            sidebar.classList.add('-translate-x-full');
                            overlay.classList.add('hidden');
                        }
        
                    });
        
                });
        
                // Jika layar berubah menjadi desktop, tampilkan sidebar kembali
                window.addEventListener('resize', function () {

if (window.matchMedia("(min-width:1024px)").matches) {

    sidebar.classList.remove('-translate-x-full');
    overlay.classList.add('hidden');

} else {

    sidebar.classList.add('-translate-x-full');

}

});
        
            }
        
        });
        </script>

    {{-- ✅ Notification AJAX Handlers --}}
    <script>
    function markAsRead(id) {
        fetch(`/learning/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function markAllAsRead() {
        fetch(`/learning/notifications/read-all`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
    </script>

@stack('scripts')

@if(session('badge_earned'))
<script>
document.addEventListener('DOMContentLoaded', function () {

    const badgeName = @json(session('badge_earned'));

    Swal.fire({
        title: '🏅 Achievement Unlocked!',
        html: `
            <div style="text-align:center">
                <div style="font-size:72px;">🏆</div>

                <h3 style="
                    font-size:24px;
                    font-weight:bold;
                    margin-top:10px;
                    color:#775537;
                ">
                    ${badgeName}
                </h3>

                <p style="margin-top:10px;color:#666;">
                    Selamat! Kamu mendapatkan badge baru.
                </p>
            </div>
        `,
        confirmButtonText: 'Lanjutkan',
        confirmButtonColor: '#775537',
        width: 500
    });

});
</script>
@endif

{{-- ✅ ARABIC VIRTUAL KEYBOARD SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Arabic letters + diacritics
    const arabicLetters = [
        'ا','ب','ت','ث','ج','ح','خ','د','ذ','ر','ز','س','ش','ص',
        'ض','ط','ظ','ع','غ','ف','ق','ك','ل','م','ن','ه','و','ي',
        'ء','آ','أ','إ','ؤ','ئ','ة','ى','لآ','لأ','لإ','لا'
    ];
    const arabicDiacritics = ['ً','ٌ','ٍ','َ','ُ','ِ','ّ','ْ','ٰ'];
    
    // Initialize all Arabic input fields
    document.querySelectorAll('.arabic-input-field').forEach(input => {
        initArabicInput(input);
    });
    
    function initArabicInput(input) {
        // Create wrapper if not exists
        if (!input.parentNode.classList.contains('input-wrapper')) {
            const wrapper = document.createElement('div');
            wrapper.className = 'input-wrapper';
            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);
        }
        const wrapper = input.closest('.input-wrapper');
        
        // Create toggle button
        let toggle = wrapper.querySelector('.arabic-toggle');
        if (!toggle) {
            toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'arabic-toggle';
            toggle.innerHTML = '<i class="fas fa-keyboard"></i> <span class="hidden sm:inline">عربي</span>';
            toggle.setAttribute('aria-label', 'Toggle Arabic keyboard');
            wrapper.appendChild(toggle);
        }
        
        // Create virtual keyboard
        let keyboard = wrapper.querySelector('.arabic-keyboard');
        if (!keyboard) {
            keyboard = document.createElement('div');
            keyboard.className = 'arabic-keyboard';
            
            // Letters grid
            const keysGrid = document.createElement('div');
            keysGrid.className = 'arabic-keys';
            arabicLetters.forEach(letter => {
                const key = document.createElement('button');
                key.type = 'button';
                key.className = 'arabic-key';
                key.textContent = letter;
                key.setAttribute('aria-label', `Insert ${letter}`);
                key.addEventListener('click', (e) => {
                    e.preventDefault();
                    insertAtCursor(input, letter);
                    input.focus();
                });
                keysGrid.appendChild(key);
            });
            keyboard.appendChild(keysGrid);
            
            // Diacritics row
            const diacriticsRow = document.createElement('div');
            diacriticsRow.className = 'arabic-diacritics';
            arabicDiacritics.forEach(dia => {
                const key = document.createElement('button');
                key.type = 'button';
                key.className = 'arabic-key';
                key.textContent = dia;
                key.setAttribute('aria-label', `Insert ${dia}`);
                key.addEventListener('click', (e) => {
                    e.preventDefault();
                    insertAtCursor(input, dia);
                    input.focus();
                });
                diacriticsRow.appendChild(key);
            });
            keyboard.appendChild(diacriticsRow);
            
            wrapper.appendChild(keyboard);
        }
        
        // Toggle functionality
        let isArabicMode = false;
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            isArabicMode = !isArabicMode;
            
            if (isArabicMode) {
                input.classList.add('arabic-input');
                input.setAttribute('lang', 'ar');
                keyboard.classList.add('active');
                toggle.classList.add('active');
            } else {
                input.classList.remove('arabic-input');
                input.removeAttribute('lang');
                keyboard.classList.remove('active');
                toggle.classList.remove('active');
            }
        });
    }
    
    // Helper: Insert text at cursor position
    function insertAtCursor(input, text) {
        const start = input.selectionStart;
        const end = input.selectionEnd;
        const value = input.value;
        
        input.value = value.slice(0, start) + text + value.slice(end);
        input.selectionStart = input.selectionEnd = start + text.length;
        
        // Trigger input event for frameworks
        input.dispatchEvent(new Event('input', { bubbles: true }));
    }
});
</script>
</body>
</html>