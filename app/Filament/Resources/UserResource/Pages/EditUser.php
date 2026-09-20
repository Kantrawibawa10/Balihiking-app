<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource =
        UserResource::class;


    /*
    |--------------------------------------------------------------------------
    | AFTER SAVE
    |--------------------------------------------------------------------------
    */

    protected function afterSave(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Jika role berubah dari pengelola menjadi Pendaki/Admin,
        | assignment lokasi dibersihkan.
        |--------------------------------------------------------------------------
        */

        if (
            ! $this->record->hasRole(
                'pengelola_lokasi'
            )
        ) {
            $this->record
                ->mountains()
                ->detach();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER ACTION
    |--------------------------------------------------------------------------
    */

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make()
                ->label(
                    'Hapus User'
                )
                ->visible(
                    fn (): bool =>
                        UserResource::canDelete(
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