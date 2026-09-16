<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect pendaki ke halaman autentikasi Google.
     */
    public function redirectToGoogle(
        Request $request
    ): RedirectResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | PENTING
            |--------------------------------------------------------------------------
            |
            | Login Google ini khusus Portal Pendaki.
            |
            | Jika sebelumnya ada session admin/pengelola yang aktif,
            | kita logout terlebih dahulu supaya tidak bercampur dengan
            | autentikasi Portal Pendaki.
            |
            */

            if (
                Auth::check()
                &&
                in_array(
                    Auth::user()?->role,
                    [
                        'admin',
                        'pengelola_jalur',
                    ],
                    true
                )
            ) {
                Auth::logout();

                $request
                    ->session()
                    ->invalidate();

                $request
                    ->session()
                    ->regenerateToken();
            }

            $provider = $this->googleProvider();

            return $provider
                ->scopes([
                    'openid',
                    'profile',
                    'email',
                ])
                ->redirect();

        } catch (Throwable $e) {

            Log::error(
                'Google OAuth Redirect Error',
                [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]
            );

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    app()->isLocal()
                        ? 'Google Login Error: ' . $e->getMessage()
                        : 'Tidak dapat terhubung ke Google. Silakan coba kembali.'
                );
        }
    }

    /**
     * Callback setelah login Google.
     */
    public function handleGoogleCallback(
        Request $request
    ): RedirectResponse {
        try {

            /*
            |--------------------------------------------------------------------------
            | GOOGLE PROVIDER
            |--------------------------------------------------------------------------
            |
            | Di sinilah request ke:
            |
            | https://www.googleapis.com/oauth2/v4/token
            |
            | dilakukan.
            |
            | Pada localhost, jika PHP tidak memiliki CA Certificate yang
            | benar, Guzzle akan menghasilkan cURL error 60.
            |
            */

            $provider = $this->googleProvider();

            $googleUser = $provider->user();

            /*
            |--------------------------------------------------------------------------
            | VALIDASI EMAIL GOOGLE
            |--------------------------------------------------------------------------
            */

            if (
                empty($googleUser->getEmail())
            ) {
                return redirect()
                    ->route('login')
                    ->with(
                        'error',
                        'Google tidak memberikan alamat email untuk akun ini.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | CARI USER
            |--------------------------------------------------------------------------
            |
            | Prioritas:
            |
            | 1. google_id
            | 2. email
            |
            */

            $existingUser = User::query()
                ->where(
                    'google_id',
                    $googleUser->getId()
                )
                ->orWhere(
                    'email',
                    $googleUser->getEmail()
                )
                ->first();

            /*
            |--------------------------------------------------------------------------
            | USER SUDAH ADA
            |--------------------------------------------------------------------------
            */

            if ($existingUser) {

                /*
                |--------------------------------------------------------------------------
                | BLOK ADMIN DAN PENGELOLA JALUR
                |--------------------------------------------------------------------------
                |
                | Akun backend tidak boleh masuk dari login Google Pendaki.
                |
                */

                if (
                    in_array(
                        $existingUser->role,
                        [
                            'admin',
                            'pengelola_jalur',
                        ],
                        true
                    )
                ) {

                    Auth::logout();

                    $request
                        ->session()
                        ->invalidate();

                    $request
                        ->session()
                        ->regenerateToken();

                    $adminLoginUrl =
                        Route::has(
                            'filament.admin.auth.login'
                        )
                            ? route(
                                'filament.admin.auth.login'
                            )
                            : url('/admin/login');

                    return redirect(
                        $adminLoginUrl
                    )
                        ->with(
                            'error',
                            'Akun administrator dan pengelola jalur wajib login melalui Portal Admin.'
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | HUBUNGKAN GOOGLE ID
                |--------------------------------------------------------------------------
                |
                | Jika user sebelumnya daftar manual menggunakan email yang sama,
                | google_id akan disimpan ketika pertama login Google.
                |
                */

                $updateData = [];

                if (
                    empty(
                        $existingUser->google_id
                    )
                ) {
                    $updateData['google_id'] =
                        $googleUser->getId();
                }

                /*
                 * Sinkronkan nama hanya jika nama user sebelumnya kosong.
                 */
                if (
                    empty(
                        $existingUser->name
                    )
                    &&
                    ! empty(
                        $googleUser->getName()
                    )
                ) {
                    $updateData['name'] =
                        $googleUser->getName();
                }

                if (
                    ! empty(
                        $updateData
                    )
                ) {
                    $existingUser->update(
                        $updateData
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | LOGIN
                |--------------------------------------------------------------------------
                */

                Auth::login(
                    $existingUser,
                    true
                );

                $request
                    ->session()
                    ->regenerate();

                return redirect()
                    ->intended(
                        route(
                            'pendaki.dashboard'
                        )
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | USER BARU
            |--------------------------------------------------------------------------
            */

            $newUser = User::create([
                'name' =>
                    $googleUser->getName()
                    ?: 'Pendaki',

                'email' =>
                    $googleUser->getEmail(),

                'google_id' =>
                    $googleUser->getId(),

                /*
                 * Google login tidak membutuhkan password lokal.
                 */
                'password' =>
                    null,

                /*
                 * Google login selalu menghasilkan akun Pendaki.
                 */
                'role' =>
                    'user',
            ]);

            /*
            |--------------------------------------------------------------------------
            | LOGIN USER BARU
            |--------------------------------------------------------------------------
            */

            Auth::login(
                $newUser,
                true
            );

            $request
                ->session()
                ->regenerate();

            return redirect()
                ->route(
                    'pendaki.dashboard'
                );

        } catch (Throwable $e) {

            Log::error(
                'Google OAuth Callback Error',
                [
                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine(),

                    'url' =>
                        $request->fullUrl(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | LOCAL DEBUG
            |--------------------------------------------------------------------------
            |
            | Di localhost tampilkan pesan asli agar mudah debugging.
            | Di production jangan tampilkan exception asli.
            |
            */

            $message =
                app()->isLocal()
                    ? 'Google Login Error: '
                        . $e->getMessage()
                    : 'Gagal login menggunakan Google. Silakan coba kembali.';

            return redirect()
                ->route('login')
                ->with(
                    'error',
                    $message
                );
        }
    }

    /**
     * Membuat Socialite Google Provider.
     *
     * Pada localhost kita bisa menonaktifkan SSL certificate verification
     * melalui GOOGLE_OAUTH_SSL_VERIFY=false.
     *
     * Production WAJIB menggunakan SSL verification.
     */
    private function googleProvider(): Provider
    {
        $provider =
            Socialite::driver(
                'google'
            );

        /*
        |--------------------------------------------------------------------------
        | SSL VERIFICATION
        |--------------------------------------------------------------------------
        */

        $verifySsl =
            config(
                'services.google.ssl_verify',
                true
            );

        /*
        |--------------------------------------------------------------------------
        | SAFETY
        |--------------------------------------------------------------------------
        |
        | verify=false hanya diperbolehkan ketika APP_ENV=local.
        |
        | Walaupun .env production salah mengatur false,
        | kode ini tetap memaksa verify=true di production.
        |
        */

        if (! app()->isLocal()) {
            $verifySsl = true;
        }

        /*
        |--------------------------------------------------------------------------
        | CUSTOM GUZZLE CLIENT
        |--------------------------------------------------------------------------
        */

        $httpClient =
            new HttpClient([
                'verify' =>
                    $verifySsl,

                'timeout' =>
                    15,

                'connect_timeout' =>
                    10,

                'http_errors' =>
                    true,
            ]);

        $provider->setHttpClient(
            $httpClient
        );

        return $provider;
    }
}