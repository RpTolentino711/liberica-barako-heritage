<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AdminPasswordResetController extends Controller
{
    private const SENT_MESSAGE = 'If this email belongs to a LIBÉRICA administrator, a password reset link will be sent shortly.';

    public function requestForm()
    {
        return view('admin.forgot-password');
    }

    public function sendLink(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Do not disclose account existence or administrator status.
        $email = $data['email'];
        $admin = User::where('email', $email)->where('is_admin', true)->first();

        if ($admin) {
            // Laravel broker handles secure random tokens, hashing, expiration,
            // cooldown, and delivery through the configured mail transport.
            Password::broker('users')->sendResetLink(['email' => $admin->email]);
        }

        return back()->with('status', self::SENT_MESSAGE);
    }

    public function resetForm(Request $request, string $token)
    {
        return view('admin.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', PasswordRule::min(12)->letters()->mixedCase()->numbers()],
        ]);

        $admin = User::where('email', $data['email'])->where('is_admin', true)->first();
        if (!$admin) {
            return back()->withErrors(['email' => 'This reset link is invalid or has expired.'])->onlyInput('email');
        }

        $status = Password::broker('users')->reset($data, function (User $user, string $password) {
            // Defense in depth: never reset a non-admin account here.
            if (!$user->is_admin) {
                return;
            }

            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')
                ->with('status', 'Your password has been updated. You can now sign in.');
        }

        return back()->withErrors(['email' => 'This reset link is invalid or has expired. Please request a new one.'])->onlyInput('email');
    }
}
