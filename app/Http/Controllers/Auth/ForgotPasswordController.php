<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function showResetForm() {
        return view('auth.reset-password');
    }

    public function sendOtp(Request $request) {
        try {
            $request->validate(['email' => 'required|email|exists:users,email']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Wrong email address'], 422);
        }

        $otp = (string)rand(100000, 999999);
        Session::put([
            'reset_otp' => $otp,
            'reset_email' => $request->email,
            'otp_expires_at' => time() + 60, 
        ]);

        try {
            Mail::raw("Your UB Sync Password Reset OTP is: $otp. Valid for 1 minute.", function($m) use ($request){
                $m->to($request->email)->subject('Password Reset OTP');
            });
            return response()->json(['message' => 'Sent!']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Mail Error.'], 500);
        }
    }

    public function verifyAndUpdate(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp_code' => 'required|digits:6',
                'password' => 'required|min:8|confirmed',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Please fill in all fields correctly.'], 422);
        }

        $storedOtp = Session::get('reset_otp');
        $expiry = Session::get('otp_expires_at');
        $storedEmail = Session::get('reset_email');

        if (!$storedOtp || time() > $expiry) {
            return response()->json(['success' => false, 'message' => 'The verification code has expired.'], 422);
        }

        if ($request->otp_code != $storedOtp || strtolower($request->email) != strtolower($storedEmail)) {
            return response()->json(['success' => false, 'message' => 'Invalid verification code.'], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Cannot use old password.'], 422);
            }

            $user->password = Hash::make($request->password);
            $user->save();
            Session::forget(['reset_otp', 'reset_email', 'otp_expires_at']);

            return response()->json(['success' => true, 'redirect' => route('login')]);
        }
        return response()->json(['success' => false, 'message' => 'User error.'], 404);
    }
}