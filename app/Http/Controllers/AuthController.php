<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('signin.index');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Mail::to($request->email)->send(new RegisterMail($user));

        return redirect()->route('registered', ['id' => $user->id])
            ->with('status', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('login.index');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.'])->withInput();
        }

        if (!$user->email_verified_at) {
            return back()->withErrors(['email' => 'Verify your email first!'])->withInput();
        }

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:8'
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login successful');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function registered($id)
    {
        $user = User::findOrFail($id);
        return view('signin.registered', compact('user'));
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);
        if ($user->email_verified_at) {
            return redirect()->route('login')->with('status', 'Email is already verified.');
        }
        $user->update(['email_verified_at' => now()]);
        return redirect()->route('verified')->with('success', 'Email verified successfully! You can now log in.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('status', 'Logged out successfully.');
    }

    // Forgot Password Methods
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Password reset link sent!')
            : back()->withErrors(['email' => 'Failed to send reset link.']);
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password reset successfully!')
            : back()->withErrors(['email' => 'Failed to reset password.']);
    }
}
