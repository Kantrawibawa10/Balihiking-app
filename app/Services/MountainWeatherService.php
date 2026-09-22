<?php

namespace App\Services;

use App\Models\Mountain;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class MountainWeatherService
{
    /*
    |--------------------------------------------------------------------------
    | OPEN METEO
    |--------------------------------------------------------------------------
    */

    private const FORECAST_URL =
        'https://api.open-meteo.com/v1/forecast';


    /*
    |--------------------------------------------------------------------------
    | CACHE
    |--------------------------------------------------------------------------
    */

    private const CACHE_MINUTES =
        30;


    /*
    |--------------------------------------------------------------------------
    | KOORDINAT GUNUNG BALI
    |--------------------------------------------------------------------------
    |
    | Digunakan jika latitude / longitude database masih kosong.
    |--------------------------------------------------------------------------
    */

    private const COORDINATES = [

        'gunung agung' => [
            -8.3420,
            115.5080,
        ],

        'gunung batur' => [
            -8.2420,
            115.3750,
        ],

        'gunung abang' => [
            -8.2780,
            115.4210,
        ],

        'bukit trunyan' => [
            -8.2560,
            115.4420,
        ],

        'gunung batukaru' => [
            -8.3710,
            115.1020,
        ],

        'gunung batu karu' => [
            -8.3710,
            115.1020,
        ],

        'gunung catur' => [
            -8.2600,
            115.1770,
        ],

        'gunung lesung' => [
            -8.2830,
            115.1100,
        ],

        'gunung pohen' => [
            -8.3210,
            115.1290,
        ],

        'gunung tapak' => [
            -8.2660,
            115.1440,
        ],

        'gunung adeng' => [
            -8.3040,
            115.1350,
        ],

    ];


    /*
    |--------------------------------------------------------------------------
    | FALLBACK WEATHER DEMO
    |--------------------------------------------------------------------------
    |
    | PERHATIAN:
    |
    | Nilai ini bukan cuaca aktual.
    |
    | Dipakai hanya apabila API tidak dapat dijangkau.
    |--------------------------------------------------------------------------
    */

    private const OFFLINE_PRESETS = [

        'gunung agung' => [
            'temperature' => 18,
            'apparent_temperature' => 17,
            'humidity' => 82,
            'precipitation' => 0.2,
            'cloud_cover' => 58,
            'wind_speed' => 12.4,
            'wind_direction' => 105,
            'weather_code' => 2,
        ],

        'gunung batur' => [
            'temperature' => 20,
            'apparent_temperature' => 19,
            'humidity' => 76,
            'precipitation' => 0,
            'cloud_cover' => 38,
            'wind_speed' => 9.7,
            'wind_direction' => 92,
            'weather_code' => 1,
        ],

        'gunung abang' => [
            'temperature' => 17,
            'apparent_temperature' => 16,
            'humidity' => 86,
            'precipitation' => 0.4,
            'cloud_cover' => 71,
            'wind_speed' => 10.2,
            'wind_direction' => 120,
            'weather_code' => 3,
        ],

        'bukit trunyan' => [
            'temperature' => 21,
            'apparent_temperature' => 21,
            'humidity' => 73,
            'precipitation' => 0,
            'cloud_cover' => 32,
            'wind_speed' => 8.3,
            'wind_direction' => 87,
            'weather_code' => 1,
        ],

        'gunung batukaru' => [
            'temperature' => 16,
            'apparent_temperature' => 15,
            'humidity' => 89,
            'precipitation' => 1.2,
            'cloud_cover' => 81,
            'wind_speed' => 7.6,
            'wind_direction' => 145,
            'weather_code' => 61,
        ],

        'gunung batu karu' => [
            'temperature' => 16,
            'apparent_temperature' => 15,
            'humidity' => 89,
            'precipitation' => 1.2,
            'cloud_cover' => 81,
            'wind_speed' => 7.6,
            'wind_direction' => 145,
            'weather_code' => 61,
        ],

        'gunung catur' => [
            'temperature' => 17,
            'apparent_temperature' => 16,
            'humidity' => 84,
            'precipitation' => 0.3,
            'cloud_cover' => 65,
            'wind_speed' => 8.9,
            'wind_direction' => 132,
            'weather_code' => 2,
        ],

        'gunung lesung' => [
            'temperature' => 18,
            'apparent_temperature' => 17,
            'humidity' => 83,
            'precipitation' => 0.2,
            'cloud_cover' => 57,
            'wind_speed' => 7.8,
            'wind_direction' => 121,
            'weather_code' => 2,
        ],

    ];


    /*
    |--------------------------------------------------------------------------
    | GET WEATHER
    |--------------------------------------------------------------------------
    */

    public function getWeather(
        Mountain $mountain,
        bool $forceRefresh = false
    ): array {
        $cacheKey =
            'balihiking:weather:'
            .
            $mountain->id;


        /*
        |--------------------------------------------------------------------------
        | CACHE
        |--------------------------------------------------------------------------
        */

        if (! $forceRefresh) {

            $cached =
                Cache::get(
                    $cacheKey
                );


            if (
                is_array(
                    $cached
                )
            ) {
                return $cached;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | COORDINATES
        |--------------------------------------------------------------------------
        */

        $coordinates =
            $this->resolveCoordinates(
                $mountain
            );


        /*
        |--------------------------------------------------------------------------
        | TRY LIVE WEATHER
        |--------------------------------------------------------------------------
        */

        try {

            $response =
                Http::acceptJson()
                    ->connectTimeout(
                        8
                    )
                    ->timeout(
                        15
                    )
                    ->get(
                        self::FORECAST_URL,
                        [

                            'latitude' =>
                                $coordinates[
                                    'latitude'
                                ],

                            'longitude' =>
                                $coordinates[
                                    'longitude'
                                ],

                            'timezone' =>
                                'Asia/Makassar',

                            'forecast_days' =>
                                5,

                            'current' =>
                                implode(
                                    ',',
                                    [

                                        'temperature_2m',

                                        'relative_humidity_2m',

                                        'apparent_temperature',

                                        'precipitation',

                                        'weather_code',

                                        'cloud_cover',

                                        'wind_speed_10m',

                                        'wind_direction_10m',

                                    ]
                                ),

                            'daily' =>
                                implode(
                                    ',',
                                    [

                                        'weather_code',

                                        'temperature_2m_max',

                                        'temperature_2m_min',

                                        'precipitation_probability_max',

                                        'precipitation_sum',

                                        'wind_speed_10m_max',

                                    ]
                                ),

                        ]
                    );


            /*
            |--------------------------------------------------------------------------
            | API SUCCESS
            |--------------------------------------------------------------------------
            */

            if (
                $response->successful()
                &&
                is_array(
                    $response->json(
                        'current'
                    )
                )
            ) {

                $weather =
                    $this->normalizeLiveWeather(
                        $mountain,
                        $coordinates,
                        $response->json()
                    );


                Cache::put(
                    $cacheKey,
                    $weather,
                    now()->addMinutes(
                        self::CACHE_MINUTES
                    )
                );


                return $weather;
            }


            /*
            |--------------------------------------------------------------------------
            | HTTP ERROR
            |--------------------------------------------------------------------------
            */

            Log::warning(
                'BaliHiking weather API unavailable',
                [

                    'mountain_id' =>
                        $mountain->id,

                    'status' =>
                        $response->status(),

                ]
            );

        } catch (
            Throwable $exception
        ) {

            /*
            |--------------------------------------------------------------------------
            | SSL / INTERNET / DNS ERROR
            |--------------------------------------------------------------------------
            */

            Log::warning(
                'BaliHiking weather API connection failed',
                [

                    'mountain_id' =>
                        $mountain->id,

                    'mountain' =>
                        $mountain->name,

                    'exception' =>
                        get_class(
                            $exception
                        ),

                    'message' =>
                        $exception
                            ->getMessage(),

                ]
            );

        }


        /*
        |--------------------------------------------------------------------------
        | OFFLINE FALLBACK
        |--------------------------------------------------------------------------
        */

        $fallback =
            $this->offlineWeather(
                $mountain,
                $coordinates
            );


        Cache::put(
            $cacheKey,
            $fallback,
            now()->addMinutes(
                10
            )
        );


        return $fallback;
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE COORDINATES
    |--------------------------------------------------------------------------
    */

    private function resolveCoordinates(
        Mountain $mountain
    ): array {
        /*
        |--------------------------------------------------------------------------
        | DATABASE FIRST
        |--------------------------------------------------------------------------
        */

        if (
            is_numeric(
                $mountain->latitude
            )
            &&
            is_numeric(
                $mountain->longitude
            )
        ) {

            return [

                'latitude' =>
                    (float)
                    $mountain->latitude,

                'longitude' =>
                    (float)
                    $mountain->longitude,

                'source' =>
                    'database',

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | PRESET
        |--------------------------------------------------------------------------
        */

        $key =
            $this->mountainKey(
                $mountain->name
            );


        $preset =
            self::COORDINATES[
                $key
            ]
            ??
            null;


        if (
            is_array(
                $preset
            )
        ) {

            return [

                'latitude' =>
                    $preset[0],

                'longitude' =>
                    $preset[1],

                'source' =>
                    'bali-preset',

            ];
        }


        /*
        |--------------------------------------------------------------------------
        | GENERIC BALI
        |--------------------------------------------------------------------------
        |
        | Gunung baru tetap tidak menyebabkan 503.
        |--------------------------------------------------------------------------
        */

        return [

            'latitude' =>
                -8.4095,

            'longitude' =>
                115.1889,

            'source' =>
                'bali-default',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE LIVE WEATHER
    |--------------------------------------------------------------------------
    */

    private function normalizeLiveWeather(
        Mountain $mountain,
        array $coordinates,
        array $raw
    ): array {
        $current =
            $raw['current']
            ??
            [];


        $weatherCode =
            isset(
                $current[
                    'weather_code'
                ]
            )
                ?
                (int)
                $current[
                    'weather_code'
                ]
                :
                null;


        return [

            'success' =>
                true,

            /*
            |--------------------------------------------------------------------------
            | REAL DATA
            |--------------------------------------------------------------------------
            */

            'is_demo' =>
                false,

            'source' =>
                'open-meteo',

            'source_label' =>
                'Data cuaca aktual',

            'mountain_id' =>
                $mountain->id,

            'mountain_name' =>
                $mountain->name,

            'location' =>
                $this->location(
                    $mountain
                ),

            'coordinates' =>
                $coordinates,

            'current' => [

                'temperature' =>
                    $this->number(
                        $current[
                            'temperature_2m'
                        ]
                        ??
                        null
                    ),

                'apparent_temperature' =>
                    $this->number(
                        $current[
                            'apparent_temperature'
                        ]
                        ??
                        null
                    ),

                'humidity' =>
                    $this->number(
                        $current[
                            'relative_humidity_2m'
                        ]
                        ??
                        null
                    ),

                'precipitation' =>
                    $this->number(
                        $current[
                            'precipitation'
                        ]
                        ??
                        null
                    ),

                'cloud_cover' =>
                    $this->number(
                        $current[
                            'cloud_cover'
                        ]
                        ??
                        null
                    ),

                'wind_speed' =>
                    $this->number(
                        $current[
                            'wind_speed_10m'
                        ]
                        ??
                        null
                    ),

                'wind_direction' =>
                    $this->number(
                        $current[
                            'wind_direction_10m'
                        ]
                        ??
                        null
                    ),

                'weather_code' =>
                    $weatherCode,

                'condition' =>
                    $this->weatherLabel(
                        $weatherCode
                    ),

                /*
                |--------------------------------------------------------------------------
                | GAMBAR SVG
                |--------------------------------------------------------------------------
                */

                'icon_url' =>
                    $this->weatherImage(
                        $weatherCode
                    ),

            ],

            'forecast' =>
                $this->normalizeForecast(
                    $raw[
                        'daily'
                    ]
                    ??
                    []
                ),

            'updated_at_label' =>
                now()
                    ->timezone(
                        'Asia/Makassar'
                    )
                    ->format(
                        'd M Y, H:i'
                    ),

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OFFLINE WEATHER
    |--------------------------------------------------------------------------
    */

    private function offlineWeather(
        Mountain $mountain,
        array $coordinates
    ): array {
        $key =
            $this->mountainKey(
                $mountain->name
            );


        $preset =
            self::OFFLINE_PRESETS[
                $key
            ]
            ??
            [

                'temperature' =>
                    19,

                'apparent_temperature' =>
                    18,

                'humidity' =>
                    80,

                'precipitation' =>
                    0,

                'cloud_cover' =>
                    50,

                'wind_speed' =>
                    8,

                'wind_direction' =>
                    110,

                'weather_code' =>
                    2,

            ];


        $code =
            (int)
            $preset[
                'weather_code'
            ];


        return [

            'success' =>
                true,

            /*
            |--------------------------------------------------------------------------
            | DEMO MARKER
            |--------------------------------------------------------------------------
            */

            'is_demo' =>
                true,

            'source' =>
                'offline-demo',

            'source_label' =>
                'Mode Offline / Data Demo',

            'mountain_id' =>
                $mountain->id,

            'mountain_name' =>
                $mountain->name,

            'location' =>
                $this->location(
                    $mountain
                ),

            'coordinates' =>
                $coordinates,

            'current' => [

                'temperature' =>
                    $preset[
                        'temperature'
                    ],

                'apparent_temperature' =>
                    $preset[
                        'apparent_temperature'
                    ],

                'humidity' =>
                    $preset[
                        'humidity'
                    ],

                'precipitation' =>
                    $preset[
                        'precipitation'
                    ],

                'cloud_cover' =>
                    $preset[
                        'cloud_cover'
                    ],

                'wind_speed' =>
                    $preset[
                        'wind_speed'
                    ],

                'wind_direction' =>
                    $preset[
                        'wind_direction'
                    ],

                'weather_code' =>
                    $code,

                'condition' =>
                    $this->weatherLabel(
                        $code
                    ),

                /*
                |--------------------------------------------------------------------------
                | LOCAL IMAGE
                |--------------------------------------------------------------------------
                */

                'icon_url' =>
                    $this->weatherImage(
                        $code
                    ),

            ],

            /*
            |--------------------------------------------------------------------------
            | DEMO FORECAST 5 DAYS
            |--------------------------------------------------------------------------
            */

            'forecast' =>
                $this->offlineForecast(
                    $preset
                ),

            'updated_at_label' =>
                'Mode Offline',

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | OFFLINE FORECAST
    |--------------------------------------------------------------------------
    */

    private function offlineForecast(
        array $current
    ): array {
        $codes = [

            $current[
                'weather_code'
            ],

            2,

            61,

            1,

            3,

        ];


        $forecast = [];


        for (
            $i = 0;
            $i < 5;
            $i++
        ) {

            $code =
                (int)
                $codes[$i];


            $temperature =
                (float)
                $current[
                    'temperature'
                ];


            $forecast[] = [

                'date' =>
                    now()
                        ->timezone(
                            'Asia/Makassar'
                        )
                        ->addDays(
                            $i
                        )
                        ->format(
                            'Y-m-d'
                        ),

                'weather_code' =>
                    $code,

                'condition' =>
                    $this->weatherLabel(
                        $code
                    ),

                'icon_url' =>
                    $this->weatherImage(
                        $code
                    ),

                'temperature_max' =>
                    round(
                        $temperature
                        +
                        2
                        +
                        ($i % 2),
                        1
                    ),

                'temperature_min' =>
                    round(
                        $temperature
                        -
                        2
                        -
                        ($i % 2),
                        1
                    ),

                'precipitation_probability' =>
                    match (
                        $code
                    ) {

                        61,
                        63,
                        65 =>
                            70,

                        3 =>
                            35,

                        2 =>
                            25,

                        default =>
                            10,

                    },

                'precipitation_sum' =>
                    match (
                        $code
                    ) {

                        61 =>
                            1.5,

                        63 =>
                            3.0,

                        65 =>
                            6.0,

                        default =>
                            0,

                    },

            ];
        }


        return $forecast;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE LIVE FORECAST
    |--------------------------------------------------------------------------
    */

    private function normalizeForecast(
        array $daily
    ): array {
        $result = [];


        foreach (
            $daily[
                'time'
            ]
            ??
            []
            as
            $index =>
            $date
        ) {

            $code =
                isset(
                    $daily[
                        'weather_code'
                    ][$index]
                )
                    ?
                    (int)
                    $daily[
                        'weather_code'
                    ][$index]
                    :
                    null;


            $result[] = [

                'date' =>
                    $date,

                'weather_code' =>
                    $code,

                'condition' =>
                    $this->weatherLabel(
                        $code
                    ),

                'icon_url' =>
                    $this->weatherImage(
                        $code
                    ),

                'temperature_max' =>
                    $this->number(
                        $daily[
                            'temperature_2m_max'
                        ][$index]
                        ??
                        null
                    ),

                'temperature_min' =>
                    $this->number(
                        $daily[
                            'temperature_2m_min'
                        ][$index]
                        ??
                        null
                    ),

                'precipitation_probability' =>
                    $this->number(
                        $daily[
                            'precipitation_probability_max'
                        ][$index]
                        ??
                        null
                    ),

                'precipitation_sum' =>
                    $this->number(
                        $daily[
                            'precipitation_sum'
                        ][$index]
                        ??
                        null
                    ),

            ];
        }


        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER LABEL
    |--------------------------------------------------------------------------
    */

    private function weatherLabel(
        ?int $code
    ): string {
        return match (
            $code
        ) {

            0 =>
                'Cerah',

            1 =>
                'Cerah Berawan',

            2 =>
                'Berawan Sebagian',

            3 =>
                'Berawan',

            45,
            48 =>
                'Berkabut',

            51,
            53,
            55 =>
                'Gerimis',

            61 =>
                'Hujan Ringan',

            63 =>
                'Hujan Sedang',

            65 =>
                'Hujan Lebat',

            80,
            81,
            82 =>
                'Hujan Lokal',

            95,
            96,
            99 =>
                'Badai Petir',

            default =>
                'Cuaca Pegunungan',

        };
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER IMAGE
    |--------------------------------------------------------------------------
    |
    | Menghasilkan gambar SVG data URI.
    |
    | Tidak membutuhkan:
    | - CDN
    | - API image
    | - file PNG
    | - internet
    |--------------------------------------------------------------------------
    */

    private function weatherImage(
        ?int $code
    ): string {
        $type =
            match (
                $code
            ) {

                0 =>
                    'sunny',

                1,
                2 =>
                    'partly',

                3,
                45,
                48 =>
                    'cloud',

                51,
                53,
                55,
                61,
                63,
                65,
                80,
                81,
                82 =>
                    'rain',

                95,
                96,
                99 =>
                    'storm',

                default =>
                    'partly',

            };


        $svg =
            match (
                $type
            ) {

                'sunny' =>
                    $this->sunnySvg(),

                'cloud' =>
                    $this->cloudSvg(),

                'rain' =>
                    $this->rainSvg(),

                'storm' =>
                    $this->stormSvg(),

                default =>
                    $this->partlyCloudySvg(),

            };


        return
            'data:image/svg+xml;base64,'
            .
            base64_encode(
                $svg
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SVG SUNNY
    |--------------------------------------------------------------------------
    */

    private function sunnySvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">
    <defs>
        <linearGradient id="sun" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#ffd54f"/>
            <stop offset="1" stop-color="#ff9800"/>
        </linearGradient>
    </defs>

    <circle cx="80" cy="80" r="34" fill="url(#sun)"/>

    <g stroke="#ffb300" stroke-width="7" stroke-linecap="round">
        <path d="M80 18v14"/>
        <path d="M80 128v14"/>
        <path d="M18 80h14"/>
        <path d="M128 80h14"/>
        <path d="M36 36l10 10"/>
        <path d="M114 114l10 10"/>
        <path d="M124 36l-10 10"/>
        <path d="M46 114l-10 10"/>
    </g>
</svg>
SVG;
    }


    /*
    |--------------------------------------------------------------------------
    | SVG CLOUD
    |--------------------------------------------------------------------------
    */

    private function cloudSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">
    <defs>
        <linearGradient id="cloud" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#eef5f8"/>
            <stop offset="1" stop-color="#aebec7"/>
        </linearGradient>
    </defs>

    <path
        d="M45 112h72c18 0 29-11 29-27
           0-15-11-27-26-28
           -5-19-21-31-41-31
           -24 0-43 17-45 40
           -15 3-24 13-24 25
           0 12 10 21 35 21z"
        fill="url(#cloud)"
    />
</svg>
SVG;
    }


    /*
    |--------------------------------------------------------------------------
    | SVG PARTLY CLOUDY
    |--------------------------------------------------------------------------
    */

    private function partlyCloudySvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">

    <circle
        cx="58"
        cy="55"
        r="28"
        fill="#ffca28"
    />

    <g stroke="#ffb300" stroke-width="5" stroke-linecap="round">
        <path d="M58 12v10"/>
        <path d="M58 88v10"/>
        <path d="M16 55h10"/>
        <path d="M90 55h10"/>
    </g>

    <defs>
        <linearGradient id="cloud" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0" stop-color="#f4f8fa"/>
            <stop offset="1" stop-color="#b0bec5"/>
        </linearGradient>
    </defs>

    <path
        d="M49 119h70c17 0 28-10 28-25
           0-14-11-25-25-26
           -6-18-21-29-39-29
           -23 0-40 16-43 37
           -14 3-23 12-23 23
           0 12 10 20 32 20z"
        fill="url(#cloud)"
    />

</svg>
SVG;
    }


    /*
    |--------------------------------------------------------------------------
    | SVG RAIN
    |--------------------------------------------------------------------------
    */

    private function rainSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">

    <path
        d="M45 94h72c18 0 29-11 29-27
           0-15-11-27-26-28
           -5-19-21-31-41-31
           -24 0-43 17-45 40
           -15 3-24 13-24 25
           0 12 10 21 35 21z"
        fill="#b0bec5"
    />

    <g
        stroke="#29b6f6"
        stroke-width="7"
        stroke-linecap="round"
    >

        <path d="M48 110l-7 18"/>

        <path d="M78 110l-7 18"/>

        <path d="M108 110l-7 18"/>

    </g>

</svg>
SVG;
    }


    /*
    |--------------------------------------------------------------------------
    | SVG STORM
    |--------------------------------------------------------------------------
    */

    private function stormSvg(): string
    {
        return <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160">

    <path
        d="M45 92h72c18 0 29-11 29-27
           0-15-11-27-26-28
           -5-19-21-31-41-31
           -24 0-43 17-45 40
           -15 3-24 13-24 25
           0 12 10 21 35 21z"
        fill="#78909c"
    />

    <path
        d="M82 93
           L62 124
           H79
           L69 151
           L108 111
           H89
           L101 93
           Z"
        fill="#ffc107"
    />

</svg>
SVG;
    }


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    private function location(
        Mountain $mountain
    ): string {
        return
            filled(
                $mountain->location
            )

                ?

                $mountain->location

                :

                'Bali, Indonesia';
    }


    /*
    |--------------------------------------------------------------------------
    | MOUNTAIN KEY
    |--------------------------------------------------------------------------
    */

    private function mountainKey(
        string $name
    ): string {
        return Str::of(
            $name
        )
            ->lower()
            ->ascii()
            ->squish()
            ->toString();
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    private function number(
        mixed $value
    ): ?float {
        return
            is_numeric(
                $value
            )

                ?

                round(
                    (float)
                    $value,
                    1
                )

                :

                null;
    }
}