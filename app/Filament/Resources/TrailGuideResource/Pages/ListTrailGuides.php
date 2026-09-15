<?php

namespace App\Filament\Resources\TrailGuideResource\Pages;

use App\Filament\Resources\TrailGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrailGuides extends ListRecords
{
    protected static string $resource = TrailGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
