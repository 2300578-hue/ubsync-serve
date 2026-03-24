<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES
|--------------------------------------------------------------------------
| Bukas para sa lahat (Customer side).
*/

Route::get('/', function () {
    return view('welcome'); 
})->name('landing');

Route::get('/menu', function () {
    return view('customer.menu'); 
})->name('customer.menu');

Route::get('/cart', function () {
    return view('customer.cart'); 
})->name('customer.cart');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES
|--------------------------------------------------------------------------
| Para lamang sa mga HINDI pa naka-login. 
| Kung naka-login na, i-re-redirect sila ng Laravel pabalik sa home.
*/

Route::middleware(['guest'])->group(function () {
    
    // Login Views & Logic
    Route::get('/login', function () { 
        return view('auth.login'); 
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.redirect'));
        }

        return back()->withErrors(['email' => 'Invalid email or password'])->withInput($request->only('email'));
    })->name('login.post');

    // Registration Views & Logic
    Route::get('/register', function () { 
        return view('auth.register'); 
    })->name('register');

    Route::post('/send-otp-register', [RegisterController::class, 'sendVerificationCode'])->name('send.otp');
    Route::post('/register-account', [RegisterController::class, 'register'])->name('register.post');

    // Forgot Password Logic
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.request');
    Route::post('/password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/password/verify-update', [ForgotPasswordController::class, 'verifyAndUpdate'])->name('password.verify-update');
});


/*
|--------------------------------------------------------------------------
| 3. PROTECTED ROUTES (Kailangan NAKA-LOGIN)
|--------------------------------------------------------------------------
| Dito nakalagay ang lahat ng kailangan ng authentication.
*/

Route::middleware(['auth'])->group(function () {
    
    // --- Dashboard Smart Redirector ---
    // Ito ang central hub pagka-login para malaman kung saan itatapon ang user
    Route::get('/dashboard-redirect', function() {
        $user = Auth::user();
        
        // Ginamitan ng strtolower para kahit "Admin" o "admin" ang nasa DB, gagana.
        return match (strtolower($user->role)) {
            'faculty', 'admin'     => redirect()->route('faculty.dashboard'),
            'cashier'              => redirect()->route('cashier.monitor'),
            'student_chef', 'chef' => redirect()->route('chef.display'),
            default => Auth::logout() ?? redirect()->route('login')->withErrors(['email' => 'Unauthorized Role.']),
        };
    })->name('dashboard.redirect');

    // --- Role-Based Dashboards ---

    // Faculty/Admin
    Route::get('/faculty', function () {
        return view('admin.faculty'); 
    })->name('faculty.dashboard');

    // Staff (Cashier)
    Route::get('/cashierstaff', function () {
        return view('staff.cashierstaff'); 
    })->name('cashier.monitor');

    // Staff (Chef)
    Route::get('/chef', function () {
        return view('staff.chef'); 
    })->name('chef.display');

    // POS & Inventory Sections
    Route::get('/service-hub', function () {
        return view('pos.service_hub');
    })->name('pos.hub');

    Route::get('/stock-vault', function () {
        return view('pos.stock_vault');
    })->name('pos.vault');

    // --- Logout Logic ---
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});