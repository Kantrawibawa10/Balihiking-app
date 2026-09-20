<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class LiveTracking extends Page
{
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

    protected static string $view =
        'filament.pages.live-tracking';

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