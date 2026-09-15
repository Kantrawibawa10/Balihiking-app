<?php

namespace App\Filament\Resources\TrailGuideResource\Pages;

use App\Filament\Resources\TrailGuideResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrailGuide extends EditRecord
{
    protected static string $resource = TrailGuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
