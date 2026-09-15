<?php

use App\Filament\Pages\LiveTracking;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\PendakiAuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Pendaki\DashboardController;
use App\Http\Controllers\Pendaki\LiveTrackController;
use App\Http\Controllers\Pendaki\MountainController;
use App\Http\Controllers\Pendaki\ProfileController;
use App\Http\Controllers\Pendaki\RiwayatController;
use App\Http\Controllers\Pendaki\SimaksiController;
use App\Http\Controllers\Pendaki\TrailController;
use App\Http\Controllers\Pendaki\TrailReportController;
use Illuminate\Support\Facades\Route;

Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),

        'user' => auth()->check()
            ? [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'role' => auth()->user()->role,
            ]
            : null,
    ]);
});

/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [LandingController::class, 'index']
)->name('home');

/*
|--------------------------------------------------------------------------
| GOOGLE OAUTH PENDAKI
|--------------------------------------------------------------------------
*/

Route::get(
    '/auth/google',
    [GoogleController::class, 'redirectToGoogle']
)->name('pendaki.google.redirect');

Route::get(
    '/auth/google/callback',
    [GoogleController::class, 'handleGoogleCallback']
)->name('pendaki.google.callback');

/*
|--------------------------------------------------------------------------
| AUTH PENDAKI
|--------------------------------------------------------------------------
*/

Route::middleware('guest')
    ->group(function () {

        Route::get(
            '/login',
            [PendakiAuthController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [PendakiAuthController::class, 'login']
        )->name('pendaki.login');

        Route::get(
            '/register',
            [PendakiAuthController::class, 'showRegister']
        )->name('register');

        Route::post(
            '/register',
            [PendakiAuthController::class, 'register']
        )->name('pendaki.register');
    });

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [PendakiAuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN LIVE TRACKING DATA
|--------------------------------------------------------------------------
*/

Route::get(
    '/api/live-tracking-data',
    function () {
        return response()->json(
            LiveTracking::getLocationsData()
        );
    }
)
    ->middleware('auth')
    ->name('api.live-tracking-data');

/*
|--------------------------------------------------------------------------
| PORTAL PENDAKI
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('pendaki')
    ->name('pendaki.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/',
            [DashboardController::class, 'index']
        )->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | GUNUNG
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/gunung/{mountain}',
            [MountainController::class, 'show']
        )
            ->whereNumber('mountain')
            ->name('mountain.show');

        /*
        |--------------------------------------------------------------------------
        | PETA JALUR
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/jalur/{trail}',
            [TrailController::class, 'show']
        )
            ->whereNumber('trail')
            ->name('trail.show');

        /*
        |--------------------------------------------------------------------------
        | LIVE TRACKING
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/live-track',
            [LiveTrackController::class, 'index']
        )->name('live-track');

        Route::post(
            '/live-track/location',
            [LiveTrackController::class, 'storeLocation']
        )->name('live-track.location');

        Route::post(
            '/live-track/complete',
            [LiveTrackController::class, 'complete']
        )->name('live-track.complete');

        Route::post(
            '/live-track/sos',
            [LiveTrackController::class, 'sendSos']
        )->name('sos');

        /*
        |--------------------------------------------------------------------------
        | SIMAKSI
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/simaksi',
            [SimaksiController::class, 'index']
        )->name('simaksi');

        Route::post(
            '/simaksi',
            [SimaksiController::class, 'store']
        )->name('simaksi.store');

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/riwayat',
            [RiwayatController::class, 'index']
        )->name('riwayat');

        /*
        |--------------------------------------------------------------------------
        | PROFILE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profil',
            [ProfileController::class, 'index']
        )->name('profil');

        /*
|--------------------------------------------------------------------------
| DETAIL JALUR
|--------------------------------------------------------------------------
*/

        Route::get(
            '/jalur/{trail}',
            [TrailController::class, 'show']
        )
            ->whereNumber('trail')
            ->name('trail.show');

        /*
        |--------------------------------------------------------------------------
        | DOWNLOAD PETA OFFLINE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/jalur/{trail}/offline',
            [TrailController::class, 'downloadOffline']
        )
            ->whereNumber('trail')
            ->name('trail.offline');

        /*
        |--------------------------------------------------------------------------
        | FEEDBACK KONDISI JALUR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/jalur/{trail}/feedback',
            [TrailReportController::class, 'store']
        )
            ->whereNumber('trail')
            ->name('trail.feedback.store');
            
    });

/*
|--------------------------------------------------------------------------
| SMART REDIRECT
|--------------------------------------------------------------------------
*/

Route::get(
    '/home',
    function () {
        $user = auth()->user();

        if (! $user) {
            return redirect()
                ->route('login');
        }

        if (
            $user->role === 'admin'
        ) {
            return redirect(
                '/admin'
            );
        }

        return redirect()
            ->route(
                'pendaki.dashboard'
            );
    }
)
    ->middleware('auth')
    ->name('home.redirect');
