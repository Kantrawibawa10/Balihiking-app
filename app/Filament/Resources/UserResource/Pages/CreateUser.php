<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource =
        UserResource::class;


    /*
    |--------------------------------------------------------------------------
    | AFTER CREATE
    |--------------------------------------------------------------------------
    */

    protected function afterCreate(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Mountain assignment hanya untuk Pengelola Pendakian.
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


    protected function getRedirectUrl(): string
    {
        return static::$resource
            ::getUrl(
                'index'
            );
    }
}