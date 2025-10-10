<?php

namespace App\Http\Controllers;

use App\Mail\TwoFactorCodeMail;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * 🔹 Show Login Form
     */
    public function showLogin()
    {
        
        return view('auth.login');
    }

    /**
     * 🔹 Handle Login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if user exists and is not locked
        if ($user && $user->isLocked()) {
            return back()->with('error', 'Account is temporarily locked. Please try again later.');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user is active
            if (!$user->isActive()) {
                Auth::logout();
                return back()->with('error', 'Your account is inactive. Please contact administrator.');
            }

            // Reset login attempts and update last login
            $user->login_attempts = 0;
            $user->locked_until = null;
            $user->last_login_at = now();
            $user->two_factor_attempts = 0;
            $user->save();

            // 🔐 Force password change
            if ($user->force_password_change || $user->first_login) {
                return redirect()->route('change.password.get')
                    ->with('info', 'Please change your password on first login.');
            }

            // 🔐 If 2FA enabled → send OTP
            if ($user->two_factor_enabled) {
                $user->two_factor_code = rand(100000, 999999);
                $user->two_factor_expires_at = Carbon::now()->addMinutes(5); // 5 min expiry
                $user->save();

                // Send OTP mail
                Mail::to($user->email)->send(new TwoFactorCodeMail($user->two_factor_code));

                return redirect()->route('twofactor.get')
                    ->with('info', 'OTP sent to your email. Please enter the code.');
            }

            return redirect()->intended('dashboard')
                ->with('success', 'Login successful!');
        }

        // Handle failed login attempts
        if ($user) {
            $user->increment('login_attempts');
            
            // Lock account after 5 failed attempts for 30 minutes
            if ($user->login_attempts >= 5) {
                $user->locked_until = Carbon::now()->addMinutes(30);
                $user->save();
                return back()->with('error', 'Too many failed attempts. Account locked for 30 minutes.');
            }
        }

        return back()->with('error', 'Invalid credentials provided.');
    }

    /**
     * 🔹 Show Register Form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * 🔹 Handle Register
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => Role::where('name', 'Staff')->first()->id ?? null,
                'force_password_change' => true, // first login me force change
            ]);

            Auth::login($user);

            return redirect()->route('change.password.get')
                ->with('success', 'Account created successfully! Please change your password.');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Show Change Password
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * 🔹 Update Password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        try {
            $user = Auth::user();
            $user->password = Hash::make($request->password);
            $user->force_password_change = false;
            $user->first_login = false; // Mark as not first login anymore
            $user->save();

            return redirect()->intended('dashboard')
                ->with('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }

    /**
     * 🔹 Show Two Factor Page
     */
    public function showTwoFactor()
    {
        return view('auth.twofactor');
    }

    /**
     * 🔹 Verify Two Factor Code
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate(['code' => 'required|numeric']);

        $user = Auth::user();

        // Check max attempts
        if ($user->two_factor_attempts >= 3) {
            Auth::logout();

            return redirect()->route('login')->with('error',
                'Too many invalid attempts. Please login again.');
        }

        // Check expiry
        if ($user->two_factor_expires_at->isPast()) {
            return back()->with('error', 'OTP expired. Please request a new one.');
        }

        // Check OTP
        if ($user->two_factor_code === $request->code) {
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->two_factor_attempts = 0;
            $user->save();

            $request->session()->put('two_factor_verified', true);

            return redirect()->intended('/dashboard')->with('success', 'Two-factor verified successfully!');
        }

        // Wrong code → increase attempts
        $user->increment('two_factor_attempts');

        return back()->with('error', 'Invalid OTP. Try again.');
    }

    /**
     * 🔹 Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
