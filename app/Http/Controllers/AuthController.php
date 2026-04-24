<?php

namespace App\Http\Controllers;

use App\Models\CatererProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // ── Client Auth ──────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try to authenticate without role restriction first
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on role
            return match($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'caterer' => redirect()->route('caterer.dashboard'),
                'client' => redirect()->route('client.dashboard'),
                default => redirect()->route('client.dashboard'),
            };
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'client',
        ]);

        Auth::login($user);

        return redirect()->route('client.dashboard');
    }

    // ── Caterer Auth ─────────────────────────────────────────────────────

    public function showCatererLogin()
    {
        return view('auth.caterer-login');
    }

    public function catererLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'caterer']))) {
            $request->session()->regenerate();

            return redirect()->route('caterer.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function showCatererRegister()
    {
        return view('auth.caterer-register');
    }

    public function catererRegister(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'barangay' => 'required|string|max:100',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'role' => 'caterer',
        ]);

        CatererProfile::create([
            'user_id' => $user->id,
            'business_name' => $data['business_name'],
            'slug' => Str::slug($data['business_name']),
            'barangay' => $data['barangay'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'profile_status' => 'incomplete',
            'is_approved' => false,
        ]);

        Auth::login($user);

        return redirect()->route('caterer.profile.settings')
            ->with('success', 'Account created! Please complete your profile to appear in the marketplace.');
    }

    // ── Logout ────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
