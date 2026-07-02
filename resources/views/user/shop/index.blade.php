@extends('layouts.app')
@section('title', 'Toko Karakter')
@section('page-title', 'Toko Karakter')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header Shop - UPDATED GRADIENT -->
    <div class="shop-header bg-gradient-to-r from-primary via-primary-light to-accent-blue rounded-2xl p-6 text-white mb-8 shadow-xl">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold mb-2" style="color: #FFFFFF !important;">🎭 Koleksi Karakter</h2>
                <p class="text-white/90">Beli karakter unik dengan Coin yang kamu kumpulkan!</p>
            </div>
            <div class="text-center md:text-right">
                <div class="text-3xl font-bold text-accent-yellow">🪙 {{ number_format(Auth::user()->stat->coin_balance) }}</div>
                <p class="text-sm text-white/80">Coin Tersedia</p>
            </div>
        </div>
    </div>

    <!-- Filter & Sort - UPDATED STYLING -->
    <div class="filter-container bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-wrap gap-4 items-center">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-gray-600">Filter:</span>
            <a href="{{ route('user.shop.index') }}" 
               class="px-4 py-2 text-sm rounded-lg transition {{ !request('filter') ? 'bg-primary/10 text-primary font-semibold border border-primary' : 'bg-gray-100 text-gray-600' }}">
                Semua
            </a>
            <a href="{{ route('user.shop.index', ['filter' => 'owned']) }}" 
               class="px-4 py-2 text-sm rounded-lg transition {{ request('filter') === 'owned' ? 'bg-primary/10 text-primary font-semibold border border-primary' : 'bg-gray-100 text-gray-600' }}">
                Dimiliki
            </a>
        </div>
        
        <div class="flex items-center gap-2 ml-auto">
            <span class="text-sm font-semibold text-gray-600">Urutkan:</span>
            <select onchange="window.location.href=this.value" 
                    class="px-4 py-2 text-sm border rounded-lg bg-white focus:ring-2 focus:ring-primary cursor-pointer">
                <option value="{{ route('user.shop.index', array_merge(request()->query(), ['sort' => 'popularity'])) }}" 
                        {{ request('sort') === 'popularity' || !request('sort') ? 'selected' : '' }}>
                    🔥 Populer
                </option>
                <option value="{{ route('user.shop.index', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" 
                        {{ request('sort') === 'price_asc' ? 'selected' : '' }}>
                    💰 Harga: Rendah ke Tinggi
                </option>
                <option value="{{ route('user.shop.index', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" 
                        {{ request('sort') === 'price_desc' ? 'selected' : '' }}>
                    💰 Harga: Tinggi ke Rendah
                </option>
                <option value="{{ route('user.shop.index', array_merge(request()->query(), ['sort' => 'name'])) }}" 
                        {{ request('sort') === 'name' ? 'selected' : '' }}>
                    🔤 Nama A-Z
                </option>
            </select>
        </div>
    </div>

    <!-- Character Grid (tetap sama seperti sebelumnya) -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($characters as $char)
        @php
            $isOwned = in_array($char->id_character, $ownedCharacterIds);
            $isEquipped = $equippedCharacter?->id_character === $char->id_character;
            $canAfford = Auth::user()->stat->coin_balance >= $char->price_coin;
            
            $rarityBorder = match($char->rarity ?? 'common') {
                'legendary' => 'border-accent-yellow shadow-accent-yellow/20',
                'epic' => 'border-purple-400 shadow-purple-200',
                'rare' => 'border-accent-blue shadow-accent-blue/20',
                default => 'border-gray-200 shadow-gray-100'
            };
        @endphp
        
        <div class="relative group bg-white rounded-2xl shadow-sm border {{ $isEquipped ? 'border-primary ring-2 ring-accent-yellow/50' : $rarityBorder }} overflow-hidden card-hover">
            
            <!-- Badge -->
            <div class="absolute top-3 right-3 z-10">
                @if($isEquipped)
                <span class="px-2 py-1 bg-primary text-white text-xs font-bold rounded-full shadow animate-pulse-soft">✓ Dipakai</span>
                @elseif($isOwned)
                <span class="px-2 py-1 bg-accent-blue text-primary-dark text-xs font-bold rounded-full">Dimiliki</span>
                @else
                <span class="px-2 py-1 bg-gray-800 text-white text-xs font-bold rounded-full">🔒</span>
                @endif
            </div>
            
            <!-- Image -->
            <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center p-4">
                <img src="{{ $char->image && !filter_var($char->image, FILTER_VALIDATE_URL) ? Storage::url($char->image) : $char->image }}" 
                     alt="{{ $char->name }}" 
                     class="w-full h-full object-contain transition-transform group-hover:scale-110"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($char->name) }}&background=random'">
            </div>
            
            <!-- Info -->
            <div class="p-4">
                <h4 class="font-bold text-neutral-text mb-1">{{ $char->name }}</h4>
                <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $char->description ?? 'Karakter unik untuk petualangan belajarmu!' }}</p>
                
                <!-- Bonus Tooltip -->
                @php
                $bonusStat = is_string($char->bonus_stat) ? json_decode($char->bonus_stat, true) : $char->bonus_stat;
                $xpBonus = $bonusStat['xp_multiplier'] ?? 1.0;
                $hasBonus = $xpBonus > 1.0;
                @endphp

                @if($hasBonus)
                <div class="mb-3 p-2 bg-primary/5 rounded-lg text-center" title="Bonus saat memakai karakter ini">
                    <span class="text-xs font-bold text-primary">✨ +{{ round(($xpBonus - 1) * 100) }}% XP Bonus</span>
                </div>
                @endif
                
                <!-- Price & Action -->
                <div class="flex items-center justify-between">
                    <span class="font-bold text-accent-yellow">🪙 {{ number_format($char->price_coin) }}</span>
                    
                    @if($isEquipped)
                    <span class="px-3 py-1.5 bg-primary/10 text-primary text-xs font-bold rounded-lg cursor-default">✓ Aktif</span>
                    @elseif($isOwned)
                    <form method="POST" action="{{ route('user.shop.equip', $char->id_character) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-accent-blue hover:bg-accent-blue/80 text-primary-dark text-xs font-bold rounded-lg transition">Pakai</button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('user.shop.buy', $char->id_character) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-3 py-1.5 {{ $canAfford ? 'bg-primary hover:bg-primary-dark' : 'bg-gray-300 cursor-not-allowed' }} text-white text-xs font-bold rounded-lg transition"
                                {{ !$canAfford ? 'disabled title="Coin tidak cukup"' : '' }}>
                            {{ $canAfford ? 'Beli' : 'Kurang Coin' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            
        
        </div>
        @endforeach
    </div>
    
    <!-- Empty State -->
    @if($characters->isEmpty())
    <div class="text-center py-12 bg-white rounded-xl border border-gray-100">
        <p class="text-4xl mb-3">🔍</p>
        <p class="text-gray-500">Tidak ada karakter yang sesuai filter.</p>
        <a href="{{ route('user.shop.index') }}" class="text-primary hover:underline mt-2 inline-block">Reset Filter</a>
    </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if(session('coin_animation'))
    const coinData = @json(session('coin_animation'));
    const coinAnim = document.createElement('div');
    coinAnim.innerHTML = `
        <div class="fixed top-20 right-6 z-50 animate-bounce">
            <div class="bg-accent-yellow border-2 border-amber-500 rounded-full p-3 shadow-lg">
                <span class="text-2xl">🪙</span>
                <span class="block text-xs font-bold text-primary-dark">${coinData.amount}</span>
            </div>
            <p class="text-xs text-center text-primary-dark mt-1">${coinData.character}!</p>
        </div>
    `;
    document.body.appendChild(coinAnim);
    setTimeout(() => { coinAnim.remove(); }, 3000);
    @endif
});
</script>
@endpush
@endsection