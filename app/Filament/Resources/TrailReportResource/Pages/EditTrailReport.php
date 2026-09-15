<?php

namespace App\Filament\Resources\TrailReportResource\Pages;

use App\Filament\Resources\TrailReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrailReport extends EditRecord
{
    protected static string $resource = TrailReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
