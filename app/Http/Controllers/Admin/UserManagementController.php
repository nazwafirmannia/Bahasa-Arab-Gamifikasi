<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')->with('stat.stage');
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name_user', 'like', '%' . $request->search . '%')
                  ->orWhere('email_user', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user->update([
            'password_user' => Hash::make($request->new_password),
        ]);

        return back()->with('success', '🔐 Password user berhasil direset!');
    }
}