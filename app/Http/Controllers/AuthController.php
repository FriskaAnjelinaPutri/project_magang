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
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;
            if ($role === 'admin') {
                return redirect()->intended('/dashboard/admin');
            } elseif ($role === 'kasir') {
                return redirect()->intended('/dashboard/kasir');
            } elseif ($role === 'pasien') {
                return redirect()->intended(route('pendaftaran.create'));
            }

            return redirect()->intended('/'); 
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'NIK' => ['required', 'string', 'max:16', 'unique:pasien,NIK'],
            'no_hp' => ['required', 'string', 'max:15'],
            'alamat' => ['required', 'string']
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pasien', // Default role for open registrations
        ]);

        \App\Models\Pasien::create([
            'id_user' => $user->id,
            'nama_pasien' => $validated['name'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'NIK' => $validated['NIK'],
            'no_hp' => $validated['no_hp'],
            'alamat' => $validated['alamat']
        ]);

        Auth::login($user);

        return redirect()->intended(route('pendaftaran.create'))->with('success', 'Akun berhasil dibuat. Silakan daftar antrian sekarang.');
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
