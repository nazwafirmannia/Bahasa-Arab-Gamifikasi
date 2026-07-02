<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStat;
use App\Models\Material;
use App\Models\UserMaterialProgress;
use App\Models\Character;
use App\Models\Stage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // 📊 Statistik Utama
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'active_today' => UserStat::whereDate('last_activity', today())->count(),
            'total_materials' => Material::count(),
            'total_characters' => Character::count(),
            'avg_completion_rate' => round(
                UserMaterialProgress::where('is_selesai', true)->count() / 
                max(1, UserMaterialProgress::count()) * 100, 
                1
            ),
        ];

        // 📈 Pertumbuhan User (7 hari terakhir)
        $userGrowth = User::where('role', 'user')
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 🎯 Distribusi Stage
        $stageDistribution = UserStat::select('current_stage_id', DB::raw('count(*) as total'))
            ->groupBy('current_stage_id')
            ->with('stage:id_stage,stage_name')
            ->get();

        // 🔥 Top 5 Materi Paling Banyak Dikerjakan
        $topMaterials = UserMaterialProgress::select('id_material', DB::raw('count(*) as attempts'))
            ->groupBy('id_material')
            ->orderByDesc('attempts')
            ->take(5)
            ->with('material:id_material,title')
            ->get();

        // 👥 User Terbaru
        $recentUsers = User::where('role', 'user')->latest()->take(5)->get();

        return view('admin.dashboard.index', compact(
            'stats', 
            'userGrowth', 
            'stageDistribution', 
            'topMaterials', 
            'recentUsers'
        ));
    } // ✅ TUTUP METHOD index()

    /**
     * Export analytics to CSV
     */
    public function exportCsv()
    {
        $users = UserStat::with(['user:id_user,name_user,email_user', 'stage:id_stage,stage_name'])
            ->get()
            ->map(fn($stat) => [
                'Nama' => $stat->user->name_user ?? '-',
                'Email' => $stat->user->email_user ?? '-',
                'Stage' => $stat->stage->stage_name ?? 'Beginner',
                'XP' => $stat->xp_total,
                'Coin' => $stat->coin_balance,
                'Streak' => $stat->streak,
                'Terakhir Aktif' => $stat->last_activity?->format('Y-m-d H:i') ?? 'Belum pernah',
            ]);

        $filename = 'analytics_users_' . now()->format('Y-m-d') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fputcsv($file, array_keys($users->first() ?? []));
            foreach ($users as $row) fputcsv($file, array_values($row));
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    } 

} 