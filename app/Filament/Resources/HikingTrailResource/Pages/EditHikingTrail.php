<?php

namespace App\Filament\Resources\HikingTrailResource\Pages;

use App\Filament\Resources\HikingTrailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHikingTrail extends EditRecord
{
    protected static string $resource =
        HikingTrailResource::class;


    /*
    |--------------------------------------------------------------------------
    | BEFORE FORM FILLED
    |--------------------------------------------------------------------------
    |
    | Ini memperbaiki data lama:
    |
    | GPX file ada
    | coordinates kosong
    |
    | → otomatis parse ulang.
    |--------------------------------------------------------------------------
    */

    protected function mutateFormDataBeforeFill(
        array $data
    ): array {
        if (
            ! empty(
                $data[
                    'gpx_file_path'
                ]
            )
            &&
            (
                empty(
                    $data[
                        'coordinates'
                    ]
                    ??
                    null
                )
                ||
                empty(
                    $data[
                        'map_geojson'
                    ]
                    ??
                    null
                )
            )
        ) {
            $record =
                HikingTrailResource::syncGpxFromStoredFile(
                    $this->record,
                    true
                );


            $data[
                'coordinates'
            ] =
                $record->coordinates;


            $data[
                'map_geojson'
            ] =
                $record
                    ->map_geojson;


            $data[
                'distance_km'
            ] =
                $record
                    ->distance_km;


            $data[
                'max_elevation'
            ] =
                $record
                    ->max_elevation;


            $data[
                'min_elevation'
            ] =
                $record
                    ->min_elevation;
        }


        return $data;
    }


    /*
    |--------------------------------------------------------------------------
    | AFTER SAVE
    |--------------------------------------------------------------------------
    */

    protected function afterSave(): void
    {
        HikingTrailResource::syncGpxFromStoredFile(
            $this->record,
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER ACTION
    |--------------------------------------------------------------------------
    */

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make(),

        ];
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