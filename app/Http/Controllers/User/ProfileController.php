<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Character;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profile
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $currentLevel = $user->stat->current_level ?? 1;
        
        $avatarCharacter = Character::where('is_active', 1)
            ->where('unlock_level', '<=', $currentLevel)
            ->orderByDesc('unlock_level')
            ->first();
    
            return view('user.profile.index', compact('user','avatarCharacter'));
    }

    /**
     * Update data profile
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name_user' => 'required|string|max:100',
            'email_user' => [
                'required',
                'email',
                'max:255',
                Rule::unique('user', 'email_user')
                    ->ignore($user->id_user, 'id_user'),
            ],
        ], [
            'name_user.required' => 'Nama lengkap wajib diisi',
            'email_user.required' => 'Email wajib diisi',
            'email_user.unique' => 'Email sudah digunakan user lain',
        ]);

        $user->update([
            'name_user' => $validated['name_user'],
            'email_user' => $validated['email_user'],
        ]);

        return back()->with(
            'success',
            '✅ Profil berhasil diperbarui!'
        );
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'new_password.min' => 'Password baru minimal 6 karakter',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check(
            $request->current_password,
            $user->password_user
        )) {
            return back()
                ->withErrors([
                    'current_password' => 'Password lama tidak sesuai'
                ])
                ->withInput();
        }

        $user->password_user = Hash::make(
            $request->new_password
        );

        $user->save();

        return back()->with(
            'success',
            '✅ Password berhasil diubah!'
        );
    }

    /**
     * Halaman edit profile
     */
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
    
        $currentLevel = $user->stat->current_level ?? 1;
    
        if ($level >= 31) {
            $avatarCharacter = Character::find(5);
        } elseif ($level >= 21) {
            $avatarCharacter = Character::find(4);
        } elseif ($level >= 11) {
            $avatarCharacter = Character::find(3);
        } elseif ($level >= 6) {
            $avatarCharacter = Character::find(2);
        } else {
            $avatarCharacter = Character::find(1);
        }
    
        return view(
            'user.profile.edit',
            compact(
                'user',
                'avatarCharacter'
            )
        );
    }
}