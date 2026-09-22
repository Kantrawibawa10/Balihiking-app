<?php

namespace Database\Seeders;

use App\Models\Mountain;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class BaliMountainCoordinatesSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | KOORDINAT GUNUNG BALI
        |--------------------------------------------------------------------------
        |
        | Digunakan sebagai titik prakiraan cuaca.
        |
        | Jangan seed suhu / hujan di sini karena cuaca harus tetap berasal
        | dari provider cuaca.
        |--------------------------------------------------------------------------
        */

        $mountains = [

            'Gunung Agung' => [
                'latitude' => -8.3420,
                'longitude' => 115.5080,
            ],

            'Gunung Batur' => [
                'latitude' => -8.2420,
                'longitude' => 115.3750,
            ],

            'Gunung Abang' => [
                'latitude' => -8.2780,
                'longitude' => 115.4210,
            ],

            'Bukit Trunyan' => [
                'latitude' => -8.2560,
                'longitude' => 115.4420,
            ],

            'Gunung Batukaru' => [
                'latitude' => -8.3710,
                'longitude' => 115.1020,
            ],

            'Gunung Batu Karu' => [
                'latitude' => -8.3710,
                'longitude' => 115.1020,
            ],

            'Gunung Catur' => [
                'latitude' => -8.2600,
                'longitude' => 115.1770,
            ],

            'Gunung Lesung' => [
                'latitude' => -8.2830,
                'longitude' => 115.1100,
            ],

            'Gunung Pohen' => [
                'latitude' => -8.3210,
                'longitude' => 115.1290,
            ],

            'Gunung Tapak' => [
                'latitude' => -8.2660,
                'longitude' => 115.1440,
            ],

            'Gunung Adeng' => [
                'latitude' => -8.3040,
                'longitude' => 115.1350,
            ],

        ];


        foreach ($mountains as $name => $coordinate) {

            /*
            |--------------------------------------------------------------------------
            | EXACT MATCH
            |--------------------------------------------------------------------------
            */

            $mountain =
                Mountain::query()
                    ->whereRaw(
                        'LOWER(name) = ?',
                        [
                            mb_strtolower($name),
                        ]
                    )
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | FALLBACK MATCH
            |--------------------------------------------------------------------------
            */

            if (! $mountain) {
                $simpleName =
                    trim(
                        preg_replace(
                            '/^(Gunung|Bukit)\s+/i',
                            '',
                            $name
                        )
                    );


                $mountain =
                    Mountain::query()
                        ->where(
                            'name',
                            'like',
                            '%' . $simpleName . '%'
                        )
                        ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | NOT FOUND
            |--------------------------------------------------------------------------
            */

            if (! $mountain) {
                $this->command?->warn(
                    "Gunung tidak ditemukan: {$name}"
                );

                Log::warning(
                    'BaliHiking coordinate seeder mountain not found',
                    [
                        'mountain' => $name,
                    ]
                );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE
            |--------------------------------------------------------------------------
            */

            $mountain->forceFill([
                'latitude' =>
                    $coordinate['latitude'],

                'longitude' =>
                    $coordinate['longitude'],
            ]);

            $mountain->save();


            $this->command?->info(
                sprintf(
                    '%s -> %.7f, %.7f',
                    $mountain->name,
                    $coordinate['latitude'],
                    $coordinate['longitude']
                )
            );
        }
    }
}