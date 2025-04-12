<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
class OtpPasswordResetController extends Controller
{
    public function requestOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            // Generate a 5-digit OTP
            $otp = str_pad(random_int(0, 999999), 5, '0', STR_PAD_LEFT);
            
            // Store the OTP in the database
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => $otp,
                    'created_at' => now(),
                ]
            );

            // Send OTP via email in a real application
            
            return $this->success(
                ['email' => $request->email, 'otp' => $otp], // Remove otp in production
                'OTP has been sent to your email'
            );
        } catch (\Exception $e) {
            return $this->error('Failed to send OTP', null, 400);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
                'otp' => 'required|string|size:5',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Verify OTP
            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->otp)
                ->first();

            if (!$resetRecord) {
                return $this->error('Invalid OTP', null, 422);
            }

            // Check if OTP is expired (15 minutes)
            if (now()->diffInMinutes($resetRecord->created_at) > 15) {
                return $this->error('OTP has expired', null, 422);
            }

            // Update the user's password
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the used OTP
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return $this->success(null, 'Password reset successful');
        } catch (\Exception $e) {
            return $this->error('Failed to reset password', null, 400);
        }
    }
}