<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStat;

// ===== LANDING PAGE (PUBLIC) =====
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin' 
            ? redirect()->route('admin.dashboard') 
            : redirect()->route('user.dashboard');
    }
    return view('landing.index');
})->name('landing');

// Login
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::post('/login', function (Request $request) {
    $request->validate(['email' => 'required|email', 'password' => 'required']);
    $user = User::where('email_user', $request->email)->first();
    
    if ($user && Hash::check($request->password, $user->password_user)) {
        Auth::login($user);
        $request->session()->regenerate();
        
        // ✅ FIX: Normalisasi role sebelum dibandingkan
        $role = trim(strtolower($user->role));
        
        return $role === 'admin' 
            ? redirect('/admin/dashboard') 
            : redirect('/learning/dashboard');
    }
    return back()->withErrors(['email' => 'Email atau password salah']);
})->name('login.submit')->middleware('guest');

// Register
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:user,email_user',
        'password' => 'required|min:6|confirmed',
    ]);
    
    $user = User::create([
        'name_user' => $request->name,
        'email_user' => $request->email,
        'password_user' => Hash::make($request->password),
        'role' => 'user',
        'has_taken_placement' => false,
    ]);
    
    UserStat::create([
        'id_user' => $user->id_user,
        'xp_total' => 0,
        'streak' => 0,
        'current_stage_id' => 1,
    ]);
    
    Auth::login($user);
    return redirect('/placement-test');
})->name('register.submit')->middleware('guest');

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout')->middleware('auth');

// ============================================
// 🆕 TAMBAHKAN 2 ROUTES INI (LUPA PASSWORD)
// ============================================
Route::get('/forgot-password', fn() => view('auth.forgot-password'))
    ->name('forgot-password')
    ->middleware('guest');

Route::post('/forgot-password', function (Request $request) {
    $request->validate([
        'email' => 'required|email|exists:user,email_user',
        'new_password' => 'required|min:6|confirmed',
    ], [
        'email.exists' => '❌ Email tidak terdaftar di sistem',
        'new_password.min' => '❌ Password minimal 6 karakter',
        'new_password.confirmed' => '❌ Konfirmasi password tidak cocok',
    ]);
    
        // Update password langsung
        $user = User::where('email_user', $request->email)->first();
        $user->update([
            'password_user' => Hash::make($request->new_password),
        ]);
        
        return redirect()->route('login')
            ->with('success', '✅ Password berhasil diubah! Silakan login dengan password baru.');
    })->name('forgot-password.reset')->middleware('guest');
    
// ===== PROTECTED ROUTES =====
Route::middleware(['auth'])->group(function () {
    
    // ===== PLACEMENT (TANPA prefix & middleware placement.completed) =====
    Route::get('/placement-test', [\App\Http\Controllers\User\PlacementController::class, 'show'])->name('placement.show');
    Route::post('/placement-test/submit', [\App\Http\Controllers\User\PlacementController::class, 'submit'])->name('placement.submit');
    Route::get('/placement/result', function () {
        if (!session()->has('stageName')) {
            return redirect()->route('user.dashboard');
        }
        return view('user.placement.result', session()->all());
    })->name('placement.result');
            Route::view('/informasi', 'user.informasi')->name('user.informasi');

    // ===== USER LEARNING ROUTES (WAJIB: prefix + name + middleware) =====
    Route::middleware(['placement.completed'])->prefix('learning')->name('user.')->group(function () {
        
        // ✅ PROFILE ROUTES (Sekarang akan jadi user.profile, user.profile.update, dll)
        Route::get('/profile', [\App\Http\Controllers\User\ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [\App\Http\Controllers\User\ProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [\App\Http\Controllers\User\ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::post('/profile/avatar/{character}', [\App\Http\Controllers\User\ProfileController::class, 'equipAvatar'])->name('profile.avatar');
        Route::get('/profile/edit', [\App\Http\Controllers\User\ProfileController::class, 'edit'])->name('user.profile.edit');
        Route::post('/profile/update-avatar', [\App\Http\Controllers\User\ProfileController::class, 'updateAvatar'])->name('user.profile.update-avatar');

        // Dashboard & Material Flow
        Route::get('/dashboard', [\App\Http\Controllers\User\LearningController::class, 'dashboard'])->name('dashboard');
        Route::get('/level/{level}', [\App\Http\Controllers\User\LearningController::class, 'showLevel'])->name('level');
        Route::get('/material/{material}', [\App\Http\Controllers\User\LearningController::class, 'showMaterial'])->name('material');
        Route::post('/material/{material}/complete', [\App\Http\Controllers\User\LearningController::class, 'completeMaterial'])->name('material.complete');
        
        // ✅ NEW: Route untuk lanjut ke materi berikutnya
        Route::get('/material/{material}/next', [\App\Http\Controllers\User\LearningController::class, 'nextMaterial'])->name('material.next');
    
        // Practice & Hint
        Route::get('/material/{material}/practice', [\App\Http\Controllers\User\LearningController::class, 'practice'])->name('practice');
        Route::post('/material/{material}/practice/submit', [\App\Http\Controllers\User\LearningController::class, 'submitPractice'])->name('practice.submit');
        Route::get('/material/{material}/practice/result', [\App\Http\Controllers\User\LearningController::class, 'showPracticeResult'])->name('practice.result');
        Route::post('/hint/{question}', [\App\Http\Controllers\User\LearningController::class, 'buyHint'])->name('hint.buy');
        
        // Level Quiz Routes
        Route::get('/level/{levelId}/quiz/{quizId}', [\App\Http\Controllers\User\LearningController::class, 'showLevelQuiz'])->name('level.quiz');
        Route::post('/level/{levelId}/quiz/{quizId}/submit', [\App\Http\Controllers\User\LearningController::class, 'submitLevelQuiz'])->name('level.quiz.submit');
        Route::get('/level/{levelId}/quiz/{quizId}/result', [\App\Http\Controllers\User\LearningController::class, 'showLevelQuizResult'])->name('level.result');
        
        // Quiz Evaluasi (jika ada fitur terpisah)
        Route::get('/quiz/{quiz}', [\App\Http\Controllers\User\QuizController::class, 'show'])->name('quiz.show');
        Route::post('/quiz/{quiz}/submit', [\App\Http\Controllers\User\QuizController::class, 'submit'])->name('quiz.submit');
        
        // Shop & Leaderboard
        Route::get('/shop', [\App\Http\Controllers\User\ShopController::class, 'index'])->name('shop.index');
        Route::post('/shop/buy/{character}', [\App\Http\Controllers\User\ShopController::class, 'buyCharacter'])->name('shop.buy');
        Route::post('/shop/equip/{character}', [\App\Http\Controllers\User\ShopController::class, 'equipCharacter'])->name('shop.equip');
        Route::get('/leaderboard', [\App\Http\Controllers\User\LeaderboardController::class, 'index'])->name('leaderboard');

        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\User\NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/{id}/read', [\App\Http\Controllers\User\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [\App\Http\Controllers\User\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('/notifications/{id}', [\App\Http\Controllers\User\NotificationController::class, 'destroy'])->name('notifications.destroy');
    });
    
    // ===== ADMIN ROUTES =====
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
        // Dashboard & Analytics
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/analytics/export', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'exportCsv'])->name('analytics.export');
    
        // Resource CRUD (Otomatis generate: index, create, store, show, edit, update, destroy)
        Route::resource('stages', \App\Http\Controllers\Admin\StageController::class);
        Route::resource('levels', \App\Http\Controllers\Admin\LevelController::class);
        Route::resource('materials', \App\Http\Controllers\Admin\MaterialController::class);
        Route::resource('quizzes', \App\Http\Controllers\Admin\QuizController::class);
        Route::resource('quiz-items', \App\Http\Controllers\Admin\QuizItemController::class)->parameters(['quiz-items' => 'quiz_item']);
        Route::resource('questions', \App\Http\Controllers\Admin\QuestionController::class);
        Route::resource('characters', \App\Http\Controllers\Admin\CharacterController::class);
    
        Route::resource('placement', \App\Http\Controllers\Admin\PlacementQuestionController::class);

        // User Management (Bonus)
        Route::get('/users', [\App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
        Route::put('/users/{user}/reset-password', [\App\Http\Controllers\Admin\UserManagementController::class, 'resetPassword'])->name('users.reset');
    });
});