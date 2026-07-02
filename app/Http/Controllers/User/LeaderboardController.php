<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\UserStat;
use App\Models\Stage;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'global');
        $stageId = $request->get('stage_id');
        
        // ✅ BUAT CACHE KEY YANG UNIK BERDASARKAN FILTER
        // Kenapa? Karena leaderboard "global" dan "per stage" itu beda data!
        $cacheKey = "leaderboard_{$filter}_{$stageId}";
        $cacheKeyTop3 = "leaderboard_top3_{$filter}_{$stageId}";
        $cacheKeyRank = "leaderboard_rank_{$filter}_{$stageId}_{$user->id_user}";

        
        // ✅ 1. CACHE RANKINGS (10 menit = 600 detik)
        $rankings = Cache::remember($cacheKey, 600, function () use ($filter, $stageId) {
            $baseQuery = UserStat::with([
                'user:id_user,name_user',
                'currentStage:id_stage,stage_name'
            ])->select('*');

            if ($filter === 'stage' && $stageId) {
                $baseQuery->where('current_stage_id', $stageId);
            }

            return $baseQuery->orderBy('xp_total', 'desc')->paginate(15);
        });

        $rankings->getCollection()->transform(function ($stat) {

            $stat->character = \App\Models\Character::where(
                'unlock_level',
                '<=',
                $stat->current_level ?? 1
            )
            ->where('is_active', true)
            ->orderByDesc('unlock_level')
            ->first();
        
            return $stat;
        });

        // ✅ 2. CACHE TOP 3 (10 menit)
        $top3 = Cache::remember($cacheKeyTop3, 600, function () use ($filter, $stageId) {

            $baseQuery = UserStat::with([
                'user:id_user,name_user',
                'currentStage:id_stage,stage_name'
            ]);
        
            if ($filter === 'stage' && $stageId) {
                $baseQuery->where('current_stage_id', $stageId);
            }
        
            return $baseQuery
                ->orderBy('xp_total', 'desc')
                ->take(3)
                ->get();
        });

        $top3->transform(function ($stat) {

        $stat->character = \App\Models\Character::where(
            'unlock_level',
            '<=',
        $stat->current_level ?? 1
    )
    ->where('is_active', true)
    ->orderByDesc('unlock_level')
    ->first();

    return $stat;
});

        // ✅ 3. CACHE MY RANK (5 menit - lebih pendek karena user ingin lihat rank-nya cepat update)
        $myRank = Cache::remember($cacheKeyRank, 300, function () use ($user, $filter, $stageId) {
            $myXp = $user->stat->xp_total;
            $myStage = $user->stat->current_stage_id;
            
            $rankQuery = UserStat::where('xp_total', '>', $myXp);
            if ($filter === 'stage') {
                $rankQuery->where('current_stage_id', $myStage);
            }
            return $rankQuery->count() + 1;
        });

        // 4. Data stage (tidak perlu cache, datanya sedikit)
        $stages = Stage::orderBy('urutan')->get(['id_stage', 'stage_name']);

        return view('user.leaderboard.index', compact('rankings', 'top3', 'myRank', 'filter', 'stageId', 'stages'));
    }
}