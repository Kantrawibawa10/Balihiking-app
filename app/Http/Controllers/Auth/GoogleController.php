<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect user ke Google OAuth.
     */
    public function redirectToGoogle(Request $request)
    {
        try {
            /*
            |--------------------------------------------------------------------------
            | Bersihkan login sebelumnya
            |--------------------------------------------------------------------------
            |
            | Filament/Admin dan portal pendaki kemungkinan menggunakan guard web
            | yang sama. Jika masih ada user yang login, logout terlebih dahulu.
            |
            */

            if (Auth::check()) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            Log::info('Google OAuth Redirect Started', [
                'session_id' => $request->session()->getId(),
                'redirect_uri' => config('services.google.redirect'),
            ]);

            return Socialite::driver('google')
                ->scopes([
                    'openid',
                    'profile',
                    'email',
                ])
                ->redirect();

        } catch (Throwable $e) {

            Log::error('Google OAuth Redirect Error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Gagal terhubung ke Google. Silakan coba kembali.'
                );
        }
    }

    /**
     * Callback dari Google.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            Log::info('Google OAuth Callback Received', [
                'session_id' => $request->session()->getId(),
                'has_code' => $request->filled('code'),
                'has_state' => $request->filled('state'),
                'has_error' => $request->filled('error'),
                'google_error' => $request->input('error'),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Ambil informasi user dari Google
            |--------------------------------------------------------------------------
            */

            $googleUser = Socialite::driver('google')->user();

            Log::info('Google User Retrieved', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Pastikan Google memberikan email
            |--------------------------------------------------------------------------
            */

            $googleEmail = $googleUser->getEmail();

            if (empty($googleEmail)) {
                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun Google Anda tidak memberikan alamat email.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Normalisasi informasi Google
            |--------------------------------------------------------------------------
            */

            $googleId = $googleUser->getId();

            $googleName =
                $googleUser->getName()
                ?: $googleUser->getNickname()
                ?: 'Pendaki';

            /*
            |--------------------------------------------------------------------------
            | Cari user berdasarkan google_id
            |--------------------------------------------------------------------------
            */

            $user = User::where('google_id', $googleId)->first();

            /*
            |--------------------------------------------------------------------------
            | Jika belum ketemu, cari berdasarkan email
            |--------------------------------------------------------------------------
            */

            if (! $user) {
                $user = User::where('email', $googleEmail)->first();
            }

            /*
            |--------------------------------------------------------------------------
            | USER SUDAH ADA
            |--------------------------------------------------------------------------
            */

            if ($user) {

                /*
                |--------------------------------------------------------------------------
                | Blok akun Admin dari Portal Pendaki
                |--------------------------------------------------------------------------
                */

                if ($user->role === 'admin') {

                    Log::warning('Admin tried Google login from Pendaki Portal', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);

                    return redirect()
                        ->route('login')
                        ->with(
                            'error',
                            'Akun tersebut merupakan akun Admin. Silakan login melalui Portal Admin.'
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Hubungkan akun lama dengan Google
                |--------------------------------------------------------------------------
                */

                if (empty($user->google_id)) {
                    $user->google_id = $googleId;
                }

                /*
                |--------------------------------------------------------------------------
                | Isi nama bila sebelumnya kosong
                |--------------------------------------------------------------------------
                */

                if (empty($user->name)) {
                    $user->name = $googleName;
                }

                $user->save();

                /*
                |--------------------------------------------------------------------------
                | Login
                |--------------------------------------------------------------------------
                */

                Auth::login($user, true);

                $request->session()->regenerate();

                Log::info('Existing user login via Google successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                return redirect()
                    ->route('pendaki.dashboard')
                    ->with(
                        'success',
                        'Berhasil masuk menggunakan Google.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | USER BARU
            |--------------------------------------------------------------------------
            |
            | Jangan gunakan password => null.
            |
            | Walaupun user masuk melalui Google, database Laravel umumnya
            | mempunyai kolom password NOT NULL.
            |
            | Kita buat random password yang tidak diketahui user.
            |
            */

            $user = new User();

            $user->name = $googleName;
            $user->email = $googleEmail;
            $user->google_id = $googleId;

            /*
            |--------------------------------------------------------------------------
            | Random password
            |--------------------------------------------------------------------------
            */

            $user->password = Hash::make(
                Str::random(64)
            );

            /*
            |--------------------------------------------------------------------------
            | Role portal Pendaki
            |--------------------------------------------------------------------------
            */

            $user->role = 'user';

            $user->save();

            /*
            |--------------------------------------------------------------------------
            | Login user baru
            |--------------------------------------------------------------------------
            */

            Auth::login($user, true);

            $request->session()->regenerate();

            Log::info('New Google user registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'google_id' => $user->google_id,
            ]);

            return redirect()
                ->route('pendaki.dashboard')
                ->with(
                    'success',
                    'Selamat datang di Jalur Bali.'
                );

        } catch (InvalidStateException $e) {

            /*
            |--------------------------------------------------------------------------
            | Invalid OAuth State
            |--------------------------------------------------------------------------
            */

            Log::error('Google OAuth Invalid State', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'session_id' => $request->session()->getId(),
                'session_driver' => config('session.driver'),
                'session_domain' => config('session.domain'),
                'session_secure' => config('session.secure'),
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Sesi login Google sudah tidak valid. Silakan coba login kembali.'
                );

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Error lainnya
            |--------------------------------------------------------------------------
            */

            Log::error('Google OAuth Callback Error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    app()->environment('local')
                        ? 'Google Login Error: '.$e->getMessage()
                        : 'Gagal login menggunakan Google. Silakan coba kembali.'
                );
        }
    }
}