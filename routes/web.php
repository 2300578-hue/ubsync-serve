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

// Payment gateway route
Route::get('/payment/gateway/{method}', function ($method) {
    return view('customer.paymentgateway', ['method' => $method]);
})->name('payment.gateway');

// Customer payment route
Route::get('/customer/payment', function () {
    return view('customer.payment'); 
})->name('customer.payment');

// Table Route (Nakabase sa folder structure mong tables/customer.blade.php)
Route::get('/table/{number}', function ($number) {
    if ($number < 1 || $number > 20) {
        abort(404, 'Table not found.');
    }
    return view('tables.customer', ['tableNumber' => $number]);
});

// Landing page
Route::get('/', function () {
    return view('welcome'); 
})->name('landing');

// Menu Route
Route::get('/menu', function (Request $request) {
    $table = $request->query('table');
    $fname = $request->query('fname');
    $lname = $request->query('lname');

    return view('customer.menu', [
        'table' => $table,
        'fname' => $fname,
        'lname' => $lname
    ]); 
})->name('customer.menu');

// Cart route
Route::get('/cart', function () {
    return view('customer.cart'); 
})->name('customer.cart');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES
|--------------------------------------------------------------------------
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
*/

Route::middleware(['auth'])->group(function () {
    
    // --- Dashboard Smart Redirector ---
    Route::get('/dashboard-redirect', function() {
        $user = Auth::user();
        
        return match (strtolower($user->role)) {
            'faculty', 'admin'     => redirect()->route('faculty.dashboard'),
            'cashier'              => redirect()->route('cashier.monitor'),
            'student_chef', 'chef' => redirect()->route('chef.display'),
            'waiter'               => redirect()->route('waiter.dashboard'), 
            default => (function() { Auth::logout();  return redirect()->route('login')->withErrors(['email' => 'Unauthorized Role.']); })(),  
        };
    })->name('dashboard.redirect');


    // --- Role-Based Dashboards ---

    // Waiter Dashboard (Inayos ang path papuntang staff/waiter/waiter.blade.php)
    Route::get('/waiter-dashboard', function () {
        return view('staff.waiter.waiter'); 
    })->name('waiter.dashboard');

    // Faculty/Admin
    Route::get('/faculty', function () {
        return view('admin.faculty'); 
    })->name('faculty.dashboard');

    // Staff (Cashier) (Inayos ang path papuntang staff/cashier/cashier.blade.php)
    Route::get('/cashier',function () {
        return view('staff.cashier.cashier'); 
    })->name('cashier.monitor');

    // Staff (Chef)
    Route::get('/chef', function () {
        return view('staff.chef.chef'); 
    })->name('chef.display');

    // POS & Inventory Sections (Nasa loob rin ng cashier folder base sa screenshot)
    Route::get('/service-hub', function () {
        return view('staff.cashier.service_hub');
    })->name('pos.hub');

    Route::get('/stock-vault', function () {
        return view('staff.cashier.stock_vault');
    })->name('pos.vault');

    // --- Logout Logic ---
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});