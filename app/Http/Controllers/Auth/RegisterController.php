<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function sendVerificationCode(Request $request)
    {
        // Paggamit ng Validator para makapag-return ng JSON error imbes na Redirect
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => "The email has already been taken or is invalid."
            ], 422);
        }

        $currentTime = time();

        if (Session::has('reg_last_otp_sent')) {
            $lastSent = Session::get('reg_last_otp_sent');
            if (($currentTime - $lastSent) < 60) {
                $wait = 60 - ($currentTime - $lastSent);
                return response()->json([
                    'error' => "Please wait $wait seconds before requesting another code."
                ], 429);
            }
        }

        $code = (string)rand(100000, 999999);
        
        Session::put('register_email', $request->email);
        Session::put('verification_code', $code);
        Session::put('reg_last_otp_sent', $currentTime); 
        Session::put('code_expires_at', $currentTime + 60); 
        Session::save();

        Mail::raw("Your UB Sync registration code is: $code. Valid for 1 minute.", function ($message) use ($request) {
            $message->to($request->email)->subject('Verification Code - UB Sync');
        });

        return response()->json(['success' => 'Code sent!']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8|confirmed',
            'role'       => 'required|in:admin,cashier,chef,waiter',
            'otp_code'   => 'required|digits:6'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $expiry = Session::get('code_expires_at');
        if (!$expiry || time() > $expiry) {
            return response()->json(['success' => false, 'message' => 'The verification code has expired.'], 422);
        }

        $sessionCode = Session::get('verification_code');
        $sessionEmail = Session::get('register_email');

        // Added trim and strtolower check for extra security
        if (trim($request->otp_code) != $sessionCode || strtolower($request->email) != strtolower($sessionEmail)) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code.'], 422);
        }

        User::create([
            'name'      => $request->first_name . ' ' . $request->last_name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'email_verified_at' => now(),
        ]);

        // Clean up session after success
        Session::forget(['verification_code', 'register_email', 'code_expires_at', 'reg_last_otp_sent']);
        Session::save();

        return response()->json([
            'success' => true,
            'redirect' => route('login'), // Siguraduhing may route('login') ka
            'message' => 'Account successfully created!'
        ]);
    }
}