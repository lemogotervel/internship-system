<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    // Step 1: Show forgot-password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }
 
    // Step 2: Validate identity and send token (stored in DB, no email needed for MVP)
    public function sendResetToken(Request $request)
    {
        $request->validate([
            'role'     => 'required|in:student,academic_supervisor,professional_supervisor,coordinator',
            'identity' => 'required|string', // username OR email depending on role
        ]);
 
        $role     = $request->input('role');
        $identity = $request->input('identity');
 
        // Find user by role + identity field
        $user = match($role) {
            'student', 'coordinator' => User::where('username', $identity)->where('role', $role)->first(),
            default                  => User::where('email', $identity)->where('role', $role)->first(),
        };
 
        if (!$user) {
            return back()->withErrors(['identity' => 'Aucun compte trouvé avec ces informations.'])->withInput();
        }
 
        // Generate a simple 6-digit code
        $token = strtoupper(Str::random(6));
 
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );
 
        // In a real app you'd email this — for MVP, redirect to reset form with token visible
        // In production: Mail::to($user->email)->send(new ResetPasswordMail($token));
        return redirect()->route('password.reset.form', ['email' => $user->email])
            ->with('reset_token', $token)
            ->with('info', 'Code de réinitialisation généré. En production il serait envoyé par email.');
    }
 
    // Step 3: Show reset form (pre-filled with email)
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'email' => $request->query('email', ''),
            'token' => session('reset_token', ''),
        ]);
    }
 
    // Step 4: Apply new password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'token'                 => 'required|string',
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string',
        ]);
 
        // Find the token record
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();
 
        if (!$record) {
            return back()->withErrors(['token' => 'Code invalide ou expiré.']);
        }
 
        // Token expires after 30 minutes
        if (now()->diffInMinutes($record->created_at) > 30) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['token' => 'Le code a expiré. Recommencez.']);
        }
 
        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['token' => 'Code de réinitialisation incorrect.']);
        }
 
        // Update password
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);
 
        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
 
        return redirect()->route('login')->with('success', 'Mot de passe modifié avec succès. Vous pouvez vous connecter.');
    }
}
