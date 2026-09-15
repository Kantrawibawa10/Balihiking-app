<?php

namespace App\Filament\Resources\HikingTrailResource\Pages;

use App\Filament\Resources\HikingTrailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHikingTrails extends ListRecords
{
    protected static string $resource = HikingTrailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
