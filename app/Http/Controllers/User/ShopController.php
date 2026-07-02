<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\UserCharacter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ShopController extends Controller
{
    /**
     * ✅ HELPER: Hapus semua variasi cache shop untuk user tertentu
     * Karena cache key bervariasi (filter & sort), kita hapus semua kemungkinan
     */
    private function clearShopCache($userId)
    {
        $filters = [null, 'owned', 'locked'];
        $sorts = [null, 'price_asc', 'price_desc', 'name'];
        
        foreach ($filters as $filter) {
            foreach ($sorts as $sort) {
                $cacheKey = "shop_" . $userId . "_" . $filter . "_" . $sort;
                Cache::forget($cacheKey);
            }
        }
    }

    /**
     * Tampilkan halaman shop dengan filter (DENGAN CACHE)
     */

public function index(Request $request)
{
    $user = Auth::user();
    $cacheKey = "shop_" . $user->id_user . "_" . $request->filter . "_" . $request->sort;
    
    // ✅ Cache HANYA data query-nya
    $data = Cache::remember($cacheKey, 600, function() use ($user, $request) {
        $ownedCharacterIds = UserCharacter::where('id_user', $user->id_user)
            ->pluck('id_character')
            ->toArray();
        
        $query = Character::query()->where('is_active', true);
        
        if ($request->filled('filter')) {
            if ($request->filter === 'owned') {
                $query->whereIn('id_character', $ownedCharacterIds);
            } elseif ($request->filter === 'locked') {
                $query->whereNotIn('id_character', $ownedCharacterIds);
            }
        }
        
        if ($request->filled('sort')) {
            match($request->sort) {
                'price_asc' => $query->orderBy('price_coin', 'asc'),
                'price_desc' => $query->orderBy('price_coin', 'desc'),
                'name' => $query->orderBy('name', 'asc'),
                default => $query->orderByDesc('id_character'),
            };
        } else {
            $query->orderByDesc('id_character');
        }
        
        $characters = $query->get();
        
        $equippedCharacter = null;
        $equipped = UserCharacter::where('id_user', $user->id_user)
            ->where('is_equipped', true)
            ->first();
            
        if ($equipped) {
            $equippedCharacter = Character::find($equipped->id_character);
        }
        
        // ✅ RETURN DATA SAJA (array), BUKAN view()!
        return [
            'characters' => $characters,
            'ownedCharacterIds' => $ownedCharacterIds,
            'equippedCharacter' => $equippedCharacter,
        ];
    });
    
    // ✅ View di LUAR Cache::remember()
    return view('user.shop.index', $data);
}

    /**
     * Beli karakter dengan validasi saldo
     */
    public function buyCharacter(Request $request, $characterId)
    {
        $user = Auth::user();
        $character = Character::findOrFail($characterId);
        
        // ✅ Validasi: sudah dimiliki? (query langsung ke user_character)
        $alreadyOwned = UserCharacter::where('user_character.id_user', $user->id_user)
        ->where('user_character.id_character', $character->id_character)
        ->exists();
            
        if ($alreadyOwned) {
            return back()->with('error', '❌ Kamu sudah memiliki karakter ini!');
        }
        
        // ✅ Validasi: saldo cukup?
        if ($user->stat->coin_balance < $character->price_coin) {
            return back()->with('error', "💰 Coin tidak cukup! Butuh {$character->price_coin} Coin.");
        }
        
        DB::transaction(function() use ($user, $character) {
            // 1. Potong coin
            $user->stat->addCoin(-$character->price_coin);
            
            // 2. Catat pembelian
            \App\Models\CoinUsage::create([
                'id_user' => $user->id_user,
                'amount' => $character->price_coin,
                'type' => 'buy_character',
                'reference_id' => $character->id_character
            ]);
            
            // 3. Tambahkan ke koleksi user
            UserCharacter::create([
                'id_user' => $user->id_user,
                'id_character' => $character->id_character,
                'purchased_at' => now(),
                'is_equipped' => false
            ]);
            
            // 4. ✅ AUTO-EQUIP JIKA INI KARAKTER PERTAMA
            $totalOwned = UserCharacter::where('id_user', $user->id_user)->count();
            if ($totalOwned === 1) {
                UserCharacter::where('id_user', $user->id_user)
                    ->where('id_character', $character->id_character)
                    ->update(['is_equipped' => true]);
            }
        });
        
        // ✅ HAPUS SEMUA CACHE SHOP UNTUK USER INI
        $this->clearShopCache($user->id_user);
        
        return back()->with('success', 
            "🎉 Karakter **{$character->name}** berhasil dibeli! -{$character->price_coin} Coin")
            ->with('coin_animation', [
                'amount' => -$character->price_coin,
                'character' => $character->name
            ]);
    }

    /**
     * Equip/Unequip karakter
     */
    public function equipCharacter(Request $request, $characterId)
{
    $user = Auth::user();
    $character = Character::findOrFail($characterId);
    
    // ✅ BENAR: Prefix dengan nama tabel
    $isOwned = UserCharacter::where('user_character.id_user', $user->id_user)
        ->where('user_character.id_character', $character->id_character)
        ->exists();
    
    if (!$isOwned) {
        return back()->with('error', '❌ Kamu belum memiliki karakter ini. Beli dulu di shop!');
    }
    
    DB::transaction(function() use ($user, $characterId) {
        // Nonaktifkan semua karakter yang sedang equipped
        UserCharacter::where('id_user', $user->id_user)
            ->update(['is_equipped' => false]);
        
        // Equip karakter yang dipilih
        UserCharacter::where('id_user', $user->id_user)
            ->where('id_character', $characterId)
            ->update(['is_equipped' => true]);
    });
    
    // ✅ Clear cache setelah equip
    $this->clearShopCache($user->id_user);
    
    return back()->with('success', "🎨 Karakter **{$character->name}** berhasil dipakai!");
}
}