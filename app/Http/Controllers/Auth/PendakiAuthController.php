<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class PendakiAuthController extends Controller
{
    /**
     * Halaman login Pendaki.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Halaman register Pendaki.
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Proses login manual Pendaki.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'string',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
         * Login menggunakan Laravel Auth.
         */
        if (! Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ])
                ->onlyInput('email');
        }

        /*
         * Regenerate session setelah berhasil login.
         */
        $request->session()->regenerate();

        $user = Auth::user();

        /*
         * Admin dilarang masuk melalui Portal Pendaki.
         */
        if ($user->role === 'admin') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Admin wajib login melalui Portal Admin Filament.',
                ]);
        }

        /*
         * Pastikan hanya role user/Pendaki.
         */
        if ($user->role !== 'user') {

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Akun Anda tidak memiliki akses ke Portal Pendaki.',
                ]);
        }

        /*
         * Jangan gunakan intended agar tidak terlempar
         * ke URL admin yang mungkin tersimpan di session.
         */
        return redirect()
            ->route('pendaki.dashboard');
    }

    /**
     * Proses register Pendaki.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults(),
            ],
        ]);

        $user = User::create([
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            /*
             * Semua register Portal Pendaki
             * otomatis role user.
             */
            'role' => 'user',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('pendaki.dashboard')
            ->with(
                'success',
                'Pendaftaran berhasil. Selamat datang di Jalur Bali.'
            );
    }

    /**
     * Logout Pendaki.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login');
    }
}