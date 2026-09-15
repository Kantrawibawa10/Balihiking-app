<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect user Pendaki langsung ke halaman login Google.
     */
    public function redirectToGoogle(Request $request)
    {
        try {
            /*
             * PENTING:
             * Filament Admin biasanya menggunakan guard "web" yang sama.
             *
             * Kalau sebelumnya ada session Admin yang masih aktif,
             * middleware/session Laravel bisa menganggap user sudah login.
             *
             * Karena user sekarang sengaja memilih login sebagai Pendaki,
             * session web lama kita bersihkan terlebih dahulu.
             */
            if (Auth::check()) {
                Auth::logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            /*
             * Langsung menuju Google OAuth.
             * Tidak ada redirect ke /admin di method ini.
             */
            return Socialite::driver('google')
                ->scopes([
                    'openid',
                    'profile',
                    'email',
                ])
                ->redirect();

        } catch (Throwable $e) {
            Log::error('Google OAuth Redirect Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
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
            $googleUser = Socialite::driver('google')->user();

            /*
             * Pastikan Google memberikan email.
             */
            if (empty($googleUser->email)) {
                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Akun Google Anda tidak memberikan alamat email.'
                    );
            }

            /*
             * ============================================================
             * STEP 1
             * Cari berdasarkan google_id terlebih dahulu.
             * ============================================================
             */
            $user = User::where('google_id', $googleUser->id)->first();

            /*
             * ============================================================
             * STEP 2
             * Kalau google_id belum pernah tersimpan,
             * cari berdasarkan email.
             * ============================================================
             */
            if (! $user) {
                $user = User::where('email', $googleUser->email)->first();
            }

            /*
             * ============================================================
             * USER SUDAH ADA
             * ============================================================
             */
            if ($user) {

                /*
                 * Admin tidak boleh login melalui Portal Pendaki.
                 */
                if ($user->role === 'admin') {
                    return redirect()
                        ->route('login')
                        ->with(
                            'error',
                            'Akun tersebut merupakan akun Admin. Silakan login melalui Portal Admin.'
                        );
                }

                /*
                 * User sebelumnya daftar menggunakan email/password.
                 * Hubungkan akun tersebut dengan Google.
                 */
                if (empty($user->google_id)) {
                    $user->google_id = $googleUser->id;
                }

                /*
                 * Update nama hanya jika nama di database kosong.
                 */
                if (empty($user->name) && ! empty($googleUser->name)) {
                    $user->name = $googleUser->name;
                }

                $user->save();

                /*
                 * Login sebagai Pendaki.
                 */
                Auth::login($user, true);

                /*
                 * Security:
                 * regenerate session setelah login.
                 */
                $request->session()->regenerate();

                /*
                 * Jangan pakai redirect intended ke admin.
                 * Selalu arahkan ke dashboard Pendaki.
                 */
                return redirect()
                    ->route('pendaki.dashboard')
                    ->with(
                        'success',
                        'Berhasil masuk menggunakan Google.'
                    );
            }

            /*
             * ============================================================
             * USER BELUM ADA
             * Auto register sebagai Pendaki.
             * ============================================================
             */
            $user = User::create([
                'name' => $googleUser->name
                    ?: $googleUser->nickname
                    ?: 'Pendaki',

                'email' => $googleUser->email,

                'google_id' => $googleUser->id,

                /*
                 * Google login tidak membutuhkan password.
                 */
                'password' => null,

                /*
                 * WAJIB role Pendaki.
                 */
                'role' => 'user',
            ]);

            Auth::login($user, true);

            $request->session()->regenerate();

            return redirect()
                ->route('pendaki.dashboard')
                ->with(
                    'success',
                    'Selamat datang di Jalur Bali.'
                );

        } catch (Throwable $e) {
            Log::error('Google OAuth Callback Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            /*
             * INI YANG PENTING:
             *
             * Kalau Google gagal, kembali ke LOGIN PENDAKI.
             * JANGAN ke Filament Admin.
             */
            return redirect()
                ->route('login')
                ->with(
                    'error',
                    'Gagal login menggunakan Google. Silakan coba kembali.'
                );
        }
    }
}