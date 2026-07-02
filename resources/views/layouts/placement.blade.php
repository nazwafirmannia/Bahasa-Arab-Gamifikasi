<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Placement Test')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; }
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-emerald-50 via-white to-teal-50 min-h-screen flex flex-col">
    
    <!-- Minimal Header -->
    <header class="w-full h-16 px-8 flex items-center justify-between bg-[#6E4B2F] shadow-mg">
        <div class="h-20 flex items-center justify-center px-2">
            <h1 class="text-2xl font-extrabold tracking-wider" 
                style="font-family: 'Cinzel', serif !important; color: #FFFFFF !important; text-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;">
                ARABIC QUEST
            </h1>
        </div>
        @auth
        <div class="text-sm text-white">
            {{ Auth::user()->name_user }}
        </div>
        @endauth
    </header>

    <!-- Main Content (Centered) -->

      
            @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-r-lg">
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r-lg">
                {{ session('error') }}
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Simple Footer -->
    <footer class="py-4 text-center text-gray-400 text-sm">
        &copy; {{ date('Y') }} ArabicQuest Placement System
    </footer>

    @stack('scripts')
</body>
</html>