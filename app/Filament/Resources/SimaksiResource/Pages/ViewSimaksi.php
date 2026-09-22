<?php

namespace App\Filament\Resources\SimaksiResource\Pages;

use App\Filament\Resources\SimaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSimaksi extends ViewRecord
{
    protected static string $resource =
        SimaksiResource::class;


    protected function getHeaderActions(): array
    {
        return [

            Actions\EditAction::make()
                ->visible(
                    fn (): bool =>
                        SimaksiResource::canEdit(
                            $this->record
                        )
                ),

        ];
    }
}