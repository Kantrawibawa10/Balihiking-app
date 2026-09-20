<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LiveTracking extends Page
{
    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static ?string $navigationIcon =
        'lucide-radar';

    protected static ?string $navigationLabel =
        'Live Tracking Pendaki';

    protected static ?string $title =
        'Live Tracking Pendaki';

    protected static ?string $navigationGroup =
        'Manajemen Pendakian';

    protected static ?int $navigationSort =
        4;


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    protected static string $view =
        'filament.pages.live-tracking';


    /*
    |--------------------------------------------------------------------------
    | ACCESS
    |--------------------------------------------------------------------------
    |
    | Admin:
    | - selalu boleh mengakses Live Tracking.
    |
    | Pengelola Lokasi / role lain:
    | - wajib memiliki permission live_tracking.view.
    |
    */

    public static function canAccess(): bool
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | BELUM LOGIN
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        if (
            method_exists(
                $user,
                'hasRole'
            )
            &&
            $user->hasRole('admin')
        ) {
            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | PERMISSION
        |--------------------------------------------------------------------------
        */

        return $user->can(
            'live_tracking.view'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER NAVIGATION
    |--------------------------------------------------------------------------
    |
    | Menu Live Tracking hanya muncul jika user memang mempunyai akses.
    |
    */

    public static function shouldRegisterNavigation(): bool
    {
        return static::canAccess();
    }


    /*
    |--------------------------------------------------------------------------
    | FULL WIDTH
    |--------------------------------------------------------------------------
    */

    public function getMaxContentWidth(): ?string
    {
        return 'full';
    }
}