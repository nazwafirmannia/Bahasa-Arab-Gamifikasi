@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('content')

<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">📊 Dashboard Admin</h1>
    <p class="page-subtitle">Pantau progres pembelajaran dan aktivitas pengguna</p>
</div>

<!-- Stats Grid -->
<div class="dashboard-grid">
    <!-- Total Users -->
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-content">
            <p class="stat-label">Total Pengguna</p>
            <p class="stat-value">{{ $stats['total_users'] }}</p>
        </div>
    </div>
    
    <!-- Active Today -->
    <div class="stat-card">
        <div class="stat-icon icon-active"><i class="fas fa-signal"></i></div>
        <div class="stat-content">
            <p class="stat-label">Aktif Hari Ini</p>
            <p class="stat-value">{{ $stats['active_today'] }}</p>
        </div>
    </div>
    
    <!-- Total Materials -->
    <div class="stat-card">
        <div class="stat-icon icon-materials"><i class="fas fa-book"></i></div>
        <div class="stat-content">
            <p class="stat-label">Total Materi</p>
            <p class="stat-value">{{ $stats['total_materials'] }}</p>
        </div>
    </div>
    
    <!-- Total Characters -->
    <div class="stat-card">
        <div class="stat-icon icon-characters"><i class="fas fa-mask"></i></div>
        <div class="stat-content">
            <p class="stat-label">Total Karakter</p>
            <p class="stat-value">{{ $stats['total_characters'] ?? 0 }}</p>
        </div>
    </div>
    
    <!-- Avg Completion -->
    <div class="stat-card">
        <div class="stat-icon icon-completion"><i class="fas fa-chart-line"></i></div>
        <div class="stat-content">
            <p class="stat-label">Rata-rata Selesai</p>
            <p class="stat-value">{{ $stats['avg_completion_rate'] }}%</p>
        </div>
    </div>
</div>

<!-- Distribusi Stage (Full Width) -->
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">🎯 Distribusi Pengguna per Stage</h3>
    </div>
    <div class="chart-data">
        @forelse($stageDistribution as $sd)
        <div class="progress-item">
            <div class="progress-label">
                <span>{{ $sd->stage->stage_name ?? 'Unknown' }}</span>
                <span class="progress-value">{{ $sd->total }} user</span>
            </div>
            <div class="progress">
                <div class="progress-fill" style="width: {{ round($sd->total / max(1, $stats['total_users']) * 100) }}%"></div>
            </div>
        </div>
        @empty
        <p class="text-muted text-sm">Belum ada data distribusi stage.</p>
        @endforelse
    </div>
</div>

<!-- Bottom Row: Top Materi & Pengguna Terbaru -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Top Materials -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">🔥 Top 5 Materi Terbanyak Dikerjakan</h3>
        </div>
        <ul class="data-list">
            @forelse($topMaterials as $tm)
            <li class="data-item">
                <span class="item-label">{{ $tm->material->title ?? 'Materi dihapus' }}</span>
                <span class="badge badge-info">{{ $tm->attempts }}x</span>
            </li>
            @empty
            <li class="data-item text-muted">Belum ada data materi.</li>
            @endforelse
        </ul>
    </div>
    
    <!-- Recent Users -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">👥 Pengguna Terbaru</h3>
        </div>
        <ul class="data-list">
            @forelse($recentUsers as $u)
            <li class="data-item">
                <div class="item-avatar">{{ strtoupper(substr($u->name_user ?? 'U', 0, 1)) }}</div>
                <div class="item-info">
                    <p class="item-name">{{ $u->name_user ?? 'User' }}</p>
                    <p class="item-meta">{{ $u->email_user ?? '-' }}</p>
                </div>
                <span class="item-time">{{ $u->created_at?->diffForHumans() ?? '-' }}</span>
            </li>
            @empty
            <li class="data-item text-muted">Belum ada pengguna terdaftar.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection