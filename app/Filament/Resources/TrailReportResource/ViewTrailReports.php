<?php

namespace App\Filament\Resources\TrailReportResource\Pages;

use App\Filament\Resources\TrailReportResource;
use Filament\Resources\Pages\ViewRecord;

class ViewTrailReport extends ViewRecord
{
    protected static string $resource =
        TrailReportResource::class;

    /*
    |--------------------------------------------------------------------------
    | TITLE
    |--------------------------------------------------------------------------
    */

    public function getTitle(): string
    {
        return 'Detail Laporan Kondisi Jalur';
    }

    /*
    |--------------------------------------------------------------------------
    | BREADCRUMB
    |--------------------------------------------------------------------------
    */

    public function getBreadcrumb(): string
    {
        return 'Detail';
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER ACTIONS
    |--------------------------------------------------------------------------
    |
    | Tidak ada Edit / Delete.
    |
    */

    protected function getHeaderActions(): array
    {
        return [];
    }
}