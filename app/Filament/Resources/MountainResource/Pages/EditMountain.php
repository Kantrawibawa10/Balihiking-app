<?php

namespace App\Filament\Resources\MountainResource\Pages;

use App\Filament\Resources\MountainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMountain extends EditRecord
{
    protected static string $resource = MountainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
