<?php

namespace App\Providers\Filament;

use App\Filament\Pages\LiveTracking;
use App\Filament\Widgets\AdminStatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel

            /*
            |--------------------------------------------------------------------------
            | PANEL
            |--------------------------------------------------------------------------
            */

            ->default()

            ->id('admin')

            ->path('admin')

            ->login()

            /*
            |--------------------------------------------------------------------------
            | BRAND
            |--------------------------------------------------------------------------
            */

            ->brandName('Jalur Bali')

            /*
            |--------------------------------------------------------------------------
            | COLOR
            |--------------------------------------------------------------------------
            */

            ->colors([
                'primary' => Color::Emerald,
            ])

            /*
            |--------------------------------------------------------------------------
            | RESOURCES
            |--------------------------------------------------------------------------
            */

            ->discoverResources(
                in: app_path('Filament/Resources'),
                for: 'App\\Filament\\Resources'
            )

            /*
            |--------------------------------------------------------------------------
            | PAGES
            |--------------------------------------------------------------------------
            */

            ->discoverPages(
                in: app_path('Filament/Pages'),
                for: 'App\\Filament\\Pages'
            )

            ->pages([
                Pages\Dashboard::class,
                LiveTracking::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | WIDGET DISCOVERY
            |--------------------------------------------------------------------------
            */

            ->discoverWidgets(
                in: app_path('Filament/Widgets'),
                for: 'App\\Filament\\Widgets'
            )

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD WIDGET
            |--------------------------------------------------------------------------
            |
            | Widget default Filament sengaja tidak dipakai.
            |
            | AccountWidget dan FilamentInfoWidget dihilangkan
            | agar dashboard terlihat sebagai aplikasi Jalur Bali,
            | bukan dashboard default Filament.
            |
            */

            ->widgets([
                AdminStatsOverview::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | PWA SERVICE WORKER
            |--------------------------------------------------------------------------
            */

            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => Blade::render('
                    <script>
                        if ("serviceWorker" in navigator) {
                            window.addEventListener("load", () => {
                                navigator.serviceWorker
                                    .register("/sw.js")
                                    .then((registration) => {
                                        console.log(
                                            "Service Worker terdaftar:",
                                            registration.scope
                                        );
                                    })
                                    .catch((error) => {
                                        console.error(
                                            "Service Worker gagal:",
                                            error
                                        );
                                    });
                            });
                        }
                    </script>
                ')
            )

            /*
            |--------------------------------------------------------------------------
            | MIDDLEWARE
            |--------------------------------------------------------------------------
            */

            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])

            /*
            |--------------------------------------------------------------------------
            | AUTH MIDDLEWARE
            |--------------------------------------------------------------------------
            */

            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}