@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('content')

<div style="max-width: 1100px; margin: 0 auto;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">👥 Manajemen Pengguna</h1>
    </div>

    <!-- Filter -->
    <form method="GET" style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; background: var(--bg-card); padding: 16px; border: 1px solid var(--border); border-radius: 12px;">
        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Cari Pengguna</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..." style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box;">
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="submit" style="padding: 10px 20px; background: var(--primary); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">🔍 Cari</button>
        </div>
    </form>

    <!-- Table -->
    <div style="background: var(--bg-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; box-shadow: var(--shadow);">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <thead style="background: var(--bg-card-hover);">
                <tr>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 30%;">Pengguna</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 15%;">Stage</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 12%;">XP</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 12%;">Coin</th>
                    <th style="padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 15%;">Terdaftar</th>
                    <th style="padding: 14px 16px; text-align: right; font-weight: 600; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; width: 16%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr style="border-bottom: 1px solid var(--border);">
                    <td style="padding: 14px 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--accent-green); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                {{ strtoupper(substr($u->name_user ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $u->name_user }}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $u->email_user }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 14px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: #DBEAFE; color: #1E40AF;">
                            {{ $u->stat?->currentStage?->stage_name ?? 'Beginner' }}
                        </span>
                    </td>
                    <td style="padding: 14px 16px; font-weight: 600; color: var(--accent-green);">{{ number_format($u->stat?->xp_total ?? 0) }}</td>
                    <td style="padding: 14px 16px; font-weight: 600; color: var(--accent-gold);">{{ number_format($u->stat?->coin_balance ?? 0) }}</td>
                    <td style="padding: 14px 16px; font-size: 0.85rem; color: var(--text-muted);">{{ $u->created_at?->format('d M Y') }}</td>
                    <td style="padding: 14px 16px; text-align: right;">
                        <button onclick="document.getElementById('modal-{{ $u->id_user }}').style.display='flex'" style="color: var(--accent-gold); background: none; border: none; cursor: pointer; font-weight: 500;">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                        
                        <!-- Modal Reset Password -->
                        <div id="modal-{{ $u->id_user }}" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); align-items: center; justify-content: center; z-index: 100;">
                            <div style="background: var(--bg-card); padding: 24px; border-radius: 16px; width: 100%; max-width: 400px; border: 1px solid var(--border);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                                    <h3 style="font-weight: 700; font-size: 1.1rem; color: var(--primary);">Reset Password</h3>
                                    <button onclick="this.closest('[id^=modal]').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer;">&times;</button>
                                </div>
                                <form method="POST" action="{{ route('admin.users.reset', $u) }}">
                                    @csrf
                                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 16px;">User: <strong>{{ $u->name_user }}</strong></p>
                                    <div style="margin-bottom: 16px;">
                                        <label style="display: block; font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Password Baru</label>
                                        <input type="password" name="new_password" style="width: 100%; padding: 10px 14px; border: 1px solid var(--border); border-radius: 8px; box-sizing: border-box;" required minlength="6" placeholder="Minimal 6 karakter">
                                    </div>
                                    <div style="display: flex; justify-content: flex-end; gap: 12px;">
                                        <button type="button" onclick="this.closest('[id^=modal]').style.display='none'" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--primary); background: var(--bg-card); cursor: pointer;">Batal</button>
                                        <button type="submit" style="padding: 10px 20px; background: var(--accent-gold); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="padding: 40px; text-align: center; color: var(--text-muted);">Belum ada pengguna ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 20px; display: flex; justify-content: center; gap: 4px;">
        {{ $users->links() }}
    </div>
</div>
@endsection