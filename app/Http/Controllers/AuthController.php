<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /* =====================================================
     |  VIEW
     ===================================================== */
    public function loginPage()
    {
        return view('auth.login');
    }

    public function registerPage()
    {
        return view('auth.register');
    }

    /* =====================================================
    |  REGISTER (MANUAL)
     ===================================================== */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        // Kirim email verifikasi
       //$user->sendEmailVerificationNotification();

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil. Silakan cek email untuk verifikasi.');
    }

    /* =====================================================
    |  LOGIN (MANUAL)
     ===================================================== */
    public function login(Request $request, GamificationService $game)
    {
         // 1️⃣ Validasi input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

         // 2️⃣ Coba login
        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Email atau password salah.');
        }

         // 3️⃣ Ambil user login
        $user = Auth::user();

         // 4️⃣ 🔥 INISIALISASI GAMIFIKASI (INI WAJIB)
        $game->initUser($user);

         // 5️⃣ Redirect sesuai role
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }

    /* =====================================================
    |  GOOGLE OAUTH (USER ONLY)
     ===================================================== */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Admin tidak boleh login via Google
            if ($user->role !== 'user') {
                abort(403, 'Akun ini tidak diizinkan login via Google.');
            }
        } else {
            // Buat user baru dari Google
            $user = User::create([
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'email_verified_at' => now(),
                'password'          => null,
                'role'              => 'user',
            ]);
        }

        Auth::login($user);
        return redirect()->route('user.dashboard');
    }

    /* =====================================================
     |  LOGOUT
     ===================================================== */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('landing');
    }
}