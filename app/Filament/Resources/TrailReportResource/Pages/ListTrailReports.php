<?php

namespace App\Filament\Resources\TrailReportResource\Pages;

use App\Filament\Resources\TrailReportResource;
use Filament\Resources\Pages\ListRecords;

class ListTrailReports extends ListRecords
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
        return 'Laporan Kondisi Jalur';
    }

    /*
    |--------------------------------------------------------------------------
    | HEADER ACTION
    |--------------------------------------------------------------------------
    |
    | Tidak ada tombol "New Trail Report"
    | karena laporan berasal dari pendaki.
    |
    */

    protected function getHeaderActions(): array
    {
        return [];
    }
}