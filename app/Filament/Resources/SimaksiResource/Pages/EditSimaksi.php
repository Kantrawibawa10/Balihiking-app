<?php

namespace App\Filament\Resources\SimaksiResource\Pages;

use App\Filament\Resources\SimaksiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSimaksi extends EditRecord
{
    protected static string $resource =
        SimaksiResource::class;


    protected function getHeaderActions(): array
    {
        return [

            Actions\ViewAction::make(),

            Actions\DeleteAction::make()
                ->visible(
                    fn (): bool =>
                        SimaksiResource::canDelete(
                            $this->record
                        )
                ),

        ];
    }


    protected function getRedirectUrl(): string
    {
        return static::$resource
            ::getUrl(
                'index'
            );
    }
}