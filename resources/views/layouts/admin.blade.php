<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ArabicQuest Admin')</title>
    
    <!-- ✅ ANTI-FOUC: Hide body until CSS loaded -->
    <style>
        body { visibility: hidden; opacity: 0; }
        body.loaded { visibility: visible; opacity: 1; transition: opacity 0.15s ease-in; }
    </style>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Merriweather:wght@400;700&family=Cinzel:wght@700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- ✅ CUSTOM CSS ONLY (NO TAILWIND CDN) -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    @stack('styles')
</head>

<body>

    <div class="app-wrapper">
        <!-- SIDEBAR -->
        <aside id="adminSidebar" class="admin-sidebar">
            
            <!-- Sidebar Header -->
            <div class="sidebar-header">
                <span class="logo-text">
                    Admin Panel
                </span>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="sidebar-nav">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.placement.index') }}" class="nav-link {{ request()->routeIs('admin.placement.index') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span class="nav-text">Placement</span>
                </a>

                <a href="{{ route('admin.stages.index') }}" class="nav-link {{ request()->routeIs('admin.stages.*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i>
                    <span class="nav-text">Stages</span>
                </a>
                <a href="{{ route('admin.levels.index') }}" class="nav-link {{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">
                    <i class="fas fa-list-ol"></i>
                    <span class="nav-text">Levels</span>
                </a>
                <a href="{{ route('admin.materials.index') }}" class="nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}">
                    <i class="fas fa-book-open"></i>
                    <span class="nav-text">Materi</span>
                </a>
                
                <a href="{{ route('admin.quizzes.index') }}" class="nav-link {{ request()->routeIs('admin.quizzes.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>
                    <span class="nav-text">Quiz Level</span>
                </a>

                    
                <a href="{{ route('admin.quiz-items.index') }}" class="nav-link {{ request()->routeIs('admin.quiz-items.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span class="nav-text">Soal Quiz</span>
                </a>

                <a href="{{ route('admin.questions.index') }}" class="nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                    <i class="fas fa-question-circle"></i>
                    <span class="nav-text">Soal Latihan</span>
                </a>
                
                <a href="{{ route('admin.characters.index') }}" class="nav-link {{ request()->routeIs('admin.characters.*') ? 'active' : '' }}">
                    <i class="fas fa-mask"></i>
                    <span class="nav-text">Karakter</span>
                </a>
                
                <!-- Divider -->
                <div class="nav-divider"></div>
                
                <!-- User Management -->
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Pengguna</span>
                </a>
            </nav>
            
            <!-- ✅ Sidebar Footer: Profile + Logout (SEMUA DI DALAMINI) -->
            <div class="sidebar-footer">
                
                <!-- Admin Profile -->
                <div class="admin-profile">
                    <div class="admin-avatar">
                        {{ strtoupper(substr(Auth::user()->name_user ?? 'A', 0, 1)) }}
                    </div>
                    <div class="admin-info">
                        <p class="admin-name">{{ Auth::user()->name_user ?? 'Admin' }}</p>
                        <p class="admin-role">Administrator</p>
                    </div>
                </div>
                
                <!-- ✅ Logout Button (MASIH DI DALAM sidebar-footer) -->
                <form method="POST" action="{{ route('logout') }}" class="logout-form-sidebar">
                    @csrf
                    <button type="submit" class="nav-link logout-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="nav-text">Logout</span>
                    </button>
                </form>
                
            </div> <!-- ✅ TUTUP sidebar-footer -->
            
        </aside> <!-- ✅ TUTUP aside -->

        <!-- MAIN CONTENT -->
        <div class="main-content">
            
            <main class="page-content">
                @if(session('success'))
                <div class="toast success animate-fade-in">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
                @endif
                
                @if($errors->any())
                <div class="toast error animate-fade-in">
                    <ul class="error-list">
                        @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                    </ul>
                </div>
                @endif
                
                @yield('content')
            </main>
            
        </div> <!-- ✅ TUTUP main-content -->
        
    </div> <!-- ✅ TUTUP app-wrapper -->
    
    @stack('scripts')
    
    <script>
    function toggleSidebar() {
        document.getElementById('adminSidebar').classList.toggle('open');
    }
    
    // ✅ SHOW BODY AFTER CSS LOADED (Anti-FOUC)
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.body.classList.add('loaded');
        }, 50);
    });
    </script>
</body>
</html>