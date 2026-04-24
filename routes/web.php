<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;

/*
|--------------------------------------------------------------------------
| 1. tesing lang hindi na need mag login
|--------------------------------------------------------------------------
*/

// Dashboards - Inilabas dito para ma-type mo sa localhost nang walang login
Route::get('/waiter', function () {
    return view('staff.waiter.waiter'); 
})->name('waiter.dashboard');

Route::get('/faculty', function () {
    return view('admin.faculty'); 
})->name('faculty.dashboard');

Route::get('/cashier', function () {
    return view('staff.cashier.cashier'); 
})->name('cashier.monitor');

Route::get('/chef', function () {
    return view('staff.chef.chef'); 
})->name('chef.display');

Route::get('/service-hub', function () {
    return view('staff.cashier.service_hub');
})->name('pos.hub');

Route::get('/stock-vault', function () {
    return view('staff.cashier.stock_vault');
})->name('pos.vault');

// Customer side
Route::get('/payment/gateway/{method}', function ($method) {
    return view('customer.paymentgateway', ['method' => $method]);
})->name('payment.gateway');

Route::get('/customer/payment', function () {
    return view('customer.payment'); 
})->name('customer.payment');

Route::get('/table/{number}', function ($number) {
    if ($number < 1 || $number > 20) {
        abort(404, 'Table not found.');
    }
    return view('tables.customer', ['tableNumber' => $number]);
});

Route::get('/reservation', function () {
    return view('reservation.book');
});

Route::get('/', function () {
    return view('welcome'); 
})->name('landing');

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

Route::get('/cart', function () {
    return view('customer.cart'); 
})->name('customer.cart');


/*
|--------------------------------------------------------------------------
| 2. GUEST ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['guest'])->group(function () {
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

    Route::get('/register', function () { 
        return view('auth.register'); 
    })->name('register');

    Route::post('/send-otp-register', [RegisterController::class, 'sendVerificationCode'])->name('send.otp');
    Route::post('/register-account', [RegisterController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.request');
    Route::post('/password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('password.send-otp');
    Route::post('/password/verify-update', [ForgotPasswordController::class, 'verifyAndUpdate'])->name('password.verify-update');
});


/*
|--------------------------------------------------------------------------
| 3. PROTECTED ROUTES (Logics na lang na kailangan ng Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard-redirect', function() {
        $user = Auth::user();
        
        return match (strtolower($user->role)) {
            'faculty', 'admin'     => redirect()->route('faculty.dashboard'),
            'cashier'              => redirect()->route('cashier.monitor'),
            'chef',                 => redirect()->route('chef.display'),
            'waiter'               => redirect()->route('waiter.dashboard'), 
            default => (function() { Auth::logout();  return redirect()->route('login')->withErrors(['email' => 'Unauthorized Role.']); })(),  
        };
    })->name('dashboard.redirect');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});