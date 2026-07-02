@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', '🔔 Notifikasi')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- ✅ Header - Tema Coklat, TANPA HOVER/ACTIVE -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-[#2C1810]">Notifikasi</h2>
        <div class="flex gap-2">
            <!-- Filter: Semua -->
            <a href="{{ route('user.notifications', ['filter' => 'all']) }}" 
               class="px-3 py-1.5 text-sm rounded-lg cursor-pointer select-none outline-none
                      {{ $filter === 'all' ? 'bg-[#D9C9AA] text-[#2C1810] font-bold' : 'bg-[#F4EFE6] text-[#5C4838]' }}
                      hover:bg-[#F4EFE6] hover:text-[#5C4838] active:bg-[#F4EFE6] focus:bg-[#F4EFE6] focus:outline-none">
                Semua
            </a>
            <!-- Filter: Belum Dibaca -->
            <a href="{{ route('user.notifications', ['filter' => 'unread']) }}" 
               class="px-3 py-1.5 text-sm rounded-lg cursor-pointer select-none outline-none
                      {{ $filter === 'unread' ? 'bg-[#D9C9AA] text-[#2C1810] font-bold' : 'bg-[#F4EFE6] text-[#5C4838]' }}
                      hover:bg-[#F4EFE6] hover:text-[#5C4838] active:bg-[#F4EFE6] focus:bg-[#F4EFE6] focus:outline-none">
                Belum Dibaca
            </a>
            <!-- Tandai Semua Dibaca -->
            @if(Auth::user()->unread_count > 0)
            <button onclick="markAllAsRead()" 
                    class="px-3 py-1.5 text-sm bg-[#C9A961] text-[#2C1810] rounded-lg font-bold cursor-pointer select-none outline-none
                           hover:bg-[#C9A961] active:bg-[#C9A961] focus:bg-[#C9A961] focus:outline-none">
                Tandai Semua Dibaca
            </button>
            @endif
        </div>
    </div>

    <!-- ✅ Notification List - TANPA HOVER/ACTIVE/FOKUS -->
    <div class="space-y-3">
        @forelse($notifications as $notif)
        <div class="bg-[#F4EFE6] rounded-xl border {{ $notif->read_at ? 'border-[#C4B59A]' : 'border-l-4 border-[#775537]' }} p-4 select-none">
            <div class="flex items-start gap-4">
                
                <!-- ✅ Icon -->
                <div class="text-2xl flex-shrink-0 {{ $notif->read_at ? 'opacity-50' : '' }}">
                    {{ $notif->icon }}
                </div>
                
                <!-- ✅ Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <!-- Title: Bold if unread, muted if read -->
                            <h4 class="font-bold text-[#2C1810] {{ $notif->read_at ? 'text-[#5C4838]' : '' }}">
                                {{ $notif->title }}
                            </h4>
                            <!-- Message -->
                            <p class="text-sm text-[#5C4838] mt-1 {!! $notif->read_at ? 'line-clamp-2' : '' !!}">
                                {!! nl2br(e($notif->message)) !!}
                            </p>
                        </div>
                        
                        <!-- ✅ Actions - TANPA HOVER/ACTIVE -->
                        <div class="flex items-center gap-2">
                            @if(!$notif->read_at)
                            <button onclick="markAsRead({{ $notif->id_notification }})" 
                                    class="text-xs text-[#775537] font-medium cursor-pointer select-none outline-none
                                           hover:text-[#775537] active:text-[#775537] focus:text-[#775537] focus:outline-none">
                                Tandai Dibaca
                            </button>
                            @endif
                            <form method="POST" action="{{ route('user.notifications.destroy', $notif->id_notification) }}" class="inline" onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-[#A85D4A] font-medium cursor-pointer select-none outline-none
                                               hover:text-[#A85D4A] active:text-[#A85D4A] focus:text-[#A85D4A] focus:outline-none">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- ✅ Meta - Waktu & Link Aksi - TANPA HOVER -->
                    <div class="flex items-center gap-3 mt-2 text-xs text-[#5C4838]">
                        <span>{{ $notif->created_at->diffForHumans() }}</span>
                        @if($notif->action_url)
                        <a href="{{ $notif->action_url }}" 
                           class="text-[#775537] font-medium flex items-center gap-1 cursor-pointer select-none outline-none
                                  hover:text-[#775537] active:text-[#775537] focus:text-[#775537] focus:outline-none no-underline">
                            Buka →
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- ✅ Empty State -->
        <div class="text-center py-12 bg-[#F4EFE6] rounded-xl border border-[#C4B59A] select-none">
            <p class="text-4xl mb-3">🔕</p>
            <p class="text-[#5C4838]">
                @if($filter === 'unread')
                Tidak ada notifikasi belum dibaca.
                @else
                Tidak ada notifikasi.
                @endif
            </p>
            @if($filter === 'unread')
            <a href="{{ route('user.notifications') }}" 
               class="text-[#775537] font-medium mt-2 inline-block cursor-pointer select-none outline-none
                      hover:text-[#775537] active:text-[#775537] focus:text-[#775537] focus:outline-none no-underline">
                Lihat Semua
            </a>
            @endif
        </div>
        @endforelse
    </div>
    
    <!-- ✅ Pagination - Override Tailwind Default -->
    <div class="mt-6">
        <style>
            .pagination a,
            .pagination span,
            .pagination button {
                background-color: #F4EFE6 !important;
                border: 1px solid #C4B59A !important;
                color: #2C1810 !important;
                border-radius: 8px !important;
                padding: 6px 12px !important;
                font-weight: 500 !important;
                transition: none !important;
                outline: none !important;
            }
            .pagination a:hover,
            .pagination a:active,
            .pagination a:focus,
            .pagination span:hover,
            .pagination span:active,
            .pagination span:focus {
                background-color: #F4EFE6 !important;
                color: #2C1810 !important;
                border-color: #C4B59A !important;
                outline: none !important;
            }
            .pagination .active span {
                background-color: #775537 !important;
                border-color: #775537 !important;
                color: #FFFFFF !important;
            }
            .pagination .active span:hover,
            .pagination .active span:active,
            .pagination .active span:focus {
                background-color: #775537 !important;
                color: #FFFFFF !important;
            }
        </style>
        {{ $notifications->links('pagination::tailwind') }}
    </div>
</div>

@push('scripts')
<script>
// Disable default focus outline for all interactive elements on this page
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a, button, [tabindex]').forEach(el => {
        el.addEventListener('focus', (e) => e.target.blur());
    });
});

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
            const badge = document.querySelector('.notification-badge');
            if (badge && data.unread_count === 0) {
                badge.remove();
            } else if (badge) {
                badge.textContent = data.unread_count;
            }
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
            const badge = document.querySelector('.notification-badge');
            if (badge) badge.remove();
            location.reload();
        }
    });
}
</script>
@endpush
@endsection