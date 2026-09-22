<?php

namespace App\Filament\Resources\SimaksiResource\Pages;

use App\Filament\Resources\SimaksiResource;
use Filament\Resources\Pages\ListRecords;

class ListSimaksis extends ListRecords
{
    protected static string $resource =
        SimaksiResource::class;


    protected function getHeaderActions(): array
    {
        return [];
    }
}