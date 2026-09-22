<?php

namespace App\Filament\Resources\HikingTrailResource\Pages;

use App\Filament\Resources\HikingTrailResource;
use Filament\Resources\Pages\CreateRecord;

class CreateHikingTrail extends CreateRecord
{
    protected static string $resource =
        HikingTrailResource::class;


    /*
    |--------------------------------------------------------------------------
    | AFTER CREATE
    |--------------------------------------------------------------------------
    |
    | FileUpload sudah benar-benar dipindahkan ke storage di tahap ini.
    | Parse sekali lagi untuk menjamin coordinates masuk database.
    |--------------------------------------------------------------------------
    */

    protected function afterCreate(): void
    {
        HikingTrailResource::syncGpxFromStoredFile(
            $this->record,
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    protected function getRedirectUrl(): string
    {
        return static::$resource
            ::getUrl(
                'index'
            );
    }
}