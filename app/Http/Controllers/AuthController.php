<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identifier = $credentials['username'];
        $password = $credentials['password'];

        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL) !== false;

        $attemptCredentials = $isEmail
            ? ['email' => $identifier, 'password' => $password]
            : ['username' => $identifier, 'password' => $password];

        if (Auth::attempt($attemptCredentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // Enforce: pasien login via NIK (username), admin/kasir login via email
            if ($isEmail && $role === 'pasien') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Pasien harus login menggunakan NIK (bukan email).',
                ])->onlyInput('username');
            }

            if (!$isEmail && in_array($role, ['admin', 'kasir', 'dokter'], true)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Admin/Kasir/Dokter harus login menggunakan email.',
                ])->onlyInput('username');
            }

            if ($role === 'admin') {
                return redirect()->intended('/dashboard/admin');
            } elseif ($role === 'kasir') {
                return redirect()->intended('/dashboard/kasir');
            } elseif ($role === 'dokter') {
                return redirect()->intended('/dashboard/dokter');
            } elseif ($role === 'pasien') {
                return redirect()->intended(route('reservasi.create'));
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    /**
     * Display the registration view.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function registerStore(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'username' => ['required', 'string', 'size:16', 'unique:users,username'],
        ]);

        // Email is required by schema, but pasien registration uses NIK as identity.
        // Generate a unique email from the NIK.
        $baseEmailLocal = $validated['username'];
        $generatedEmail = $baseEmailLocal . '@pasien.local';
        $suffix = 0;
        while (User::where('email', $generatedEmail)->exists()) {
            $suffix++;
            $generatedEmail = $baseEmailLocal . '+' . $suffix . '@pasien.local';
        }

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $generatedEmail,
            'password' => Hash::make($validated['password']),
            'role' => 'pasien', // Default role for open registrations
        ]);

        Auth::login($user);

        return redirect()
            ->route('pasien.complete_profile')
            ->with('success', 'Akun berhasil dibuat. Silakan lengkapi profil Anda terlebih dahulu.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
