<?php

namespace App\Services;

use App\Models\Mountain;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Throwable;

class MountainWeatherService
{
    private const FORECAST_URL =
        'https://api.open-meteo.com/v1/forecast';

    private const GEOCODING_URL =
        'https://geocoding-api.open-meteo.com/v1/search';

    /*
    |--------------------------------------------------------------------------
    | CACHE
    |--------------------------------------------------------------------------
    */

    private const WEATHER_CACHE_MINUTES = 30;

    private const COORDINATE_CACHE_DAYS = 30;

    private const STALE_WEATHER_DAYS = 7;


    /*
    |--------------------------------------------------------------------------
    | KOORDINAT FALLBACK GUNUNG BALI
    |--------------------------------------------------------------------------
    |
    | Digunakan jika database belum mempunyai latitude/longitude.
    |
    | Koordinat ini cukup untuk pengambilan prakiraan cuaca regional gunung.
    |
    */

    private const BALI_MOUNTAINS = [

        'gunung agung' => [
            'latitude' => -8.3420,
            'longitude' => 115.5080,
        ],

        'agung' => [
            'latitude' => -8.3420,
            'longitude' => 115.5080,
        ],

        'gunung batur' => [
            'latitude' => -8.2420,
            'longitude' => 115.3750,
        ],

        'batur' => [
            'latitude' => -8.2420,
            'longitude' => 115.3750,
        ],

        'gunung abang' => [
            'latitude' => -8.2780,
            'longitude' => 115.4210,
        ],

        'abang' => [
            'latitude' => -8.2780,
            'longitude' => 115.4210,
        ],

        'gunung batukaru' => [
            'latitude' => -8.3710,
            'longitude' => 115.1020,
        ],

        'batukaru' => [
            'latitude' => -8.3710,
            'longitude' => 115.1020,
        ],

        'gunung batu karu' => [
            'latitude' => -8.3710,
            'longitude' => 115.1020,
        ],

        'batu karu' => [
            'latitude' => -8.3710,
            'longitude' => 115.1020,
        ],

        'gunung catur' => [
            'latitude' => -8.2600,
            'longitude' => 115.1770,
        ],

        'catur' => [
            'latitude' => -8.2600,
            'longitude' => 115.1770,
        ],

        'gunung lesung' => [
            'latitude' => -8.2830,
            'longitude' => 115.1100,
        ],

        'lesung' => [
            'latitude' => -8.2830,
            'longitude' => 115.1100,
        ],

        'gunung pohen' => [
            'latitude' => -8.3210,
            'longitude' => 115.1290,
        ],

        'pohen' => [
            'latitude' => -8.3210,
            'longitude' => 115.1290,
        ],

        'gunung tapak' => [
            'latitude' => -8.2660,
            'longitude' => 115.1440,
        ],

        'tapak' => [
            'latitude' => -8.2660,
            'longitude' => 115.1440,
        ],

        'gunung adeng' => [
            'latitude' => -8.3040,
            'longitude' => 115.1350,
        ],

        'adeng' => [
            'latitude' => -8.3040,
            'longitude' => 115.1350,
        ],

        'bukit trunyan' => [
            'latitude' => -8.2560,
            'longitude' => 115.4420,
        ],

        'trunyan' => [
            'latitude' => -8.2560,
            'longitude' => 115.4420,
        ],

        'bukit trunyan bali' => [
            'latitude' => -8.2560,
            'longitude' => 115.4420,
        ],

    ];


    /*
    |--------------------------------------------------------------------------
    | PUBLIC WEATHER
    |--------------------------------------------------------------------------
    */

    public function getWeather(
        Mountain $mountain,
        bool $forceRefresh = false
    ): ?array {
        $cacheKey =
            $this->weatherCacheKey(
                $mountain
            );

        $staleKey =
            $this->staleWeatherCacheKey(
                $mountain
            );


        /*
        |--------------------------------------------------------------------------
        | NORMAL CACHE
        |--------------------------------------------------------------------------
        */

        if (! $forceRefresh) {
            $cached =
                Cache::get(
                    $cacheKey
                );

            if (is_array($cached)) {
                $cached['cached'] =
                    true;

                return $cached;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | COORDINATE
        |--------------------------------------------------------------------------
        */

        $coordinate =
            $this->resolveCoordinates(
                $mountain,
                $forceRefresh
            );


        if (! $coordinate) {
            Log::warning(
                'BaliHiking weather coordinate unavailable',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'mountain_name' =>
                        $mountain->name,

                    'location' =>
                        $this->location(
                            $mountain
                        ),
                ]
            );

            return $this->stale(
                $staleKey
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST OPEN METEO
        |--------------------------------------------------------------------------
        */

        try {
            $response =
                Http::acceptJson()
                    ->connectTimeout(10)
                    ->timeout(20)
                    ->retry(
                        2,
                        500,
                        throw: false
                    )
                    ->get(
                        self::FORECAST_URL,
                        [
                            'latitude' =>
                                $coordinate['latitude'],

                            'longitude' =>
                                $coordinate['longitude'],

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
                                        'wind_direction_10m_dominant',
                                        'sunrise',
                                        'sunset',
                                    ]
                                ),
                        ]
                    );


            if (! $response->successful()) {
                $this->logHttpError(
                    'forecast',
                    $response,
                    $mountain
                );

                return $this->stale(
                    $staleKey
                );
            }


            $json =
                $response->json();


            if (
                ! is_array($json)
                ||
                empty($json['current'])
                ||
                empty($json['daily'])
            ) {
                Log::warning(
                    'BaliHiking invalid weather response',
                    [
                        'mountain_id' =>
                            $mountain->id,

                        'response' =>
                            $json,
                    ]
                );

                return $this->stale(
                    $staleKey
                );
            }


            $weather =
                $this->normalize(
                    $mountain,
                    $coordinate,
                    $json
                );


            /*
            |--------------------------------------------------------------------------
            | CACHE TERBARU
            |--------------------------------------------------------------------------
            */

            Cache::put(
                $cacheKey,
                $weather,
                now()->addMinutes(
                    self::WEATHER_CACHE_MINUTES
                )
            );


            /*
            |--------------------------------------------------------------------------
            | CACHE CADANGAN
            |--------------------------------------------------------------------------
            */

            Cache::put(
                $staleKey,
                $weather,
                now()->addDays(
                    self::STALE_WEATHER_DAYS
                )
            );


            return $weather;

        } catch (Throwable $e) {
            Log::error(
                'BaliHiking forecast exception',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'mountain_name' =>
                        $mountain->name,

                    'message' =>
                        $e->getMessage(),

                    'class' =>
                        get_class($e),
                ]
            );


            return $this->stale(
                $staleKey
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE COORDINATE
    |--------------------------------------------------------------------------
    */

    public function resolveCoordinates(
        Mountain $mountain,
        bool $forceRefresh = false
    ): ?array {
        /*
        |--------------------------------------------------------------------------
        | 1. DATABASE
        |--------------------------------------------------------------------------
        */

        $databaseCoordinate =
            $this->databaseCoordinates(
                $mountain
            );


        if ($databaseCoordinate) {
            return $databaseCoordinate;
        }


        /*
        |--------------------------------------------------------------------------
        | 2. PRESET BALI
        |--------------------------------------------------------------------------
        */

        $preset =
            $this->presetCoordinates(
                $mountain
            );


        if ($preset) {
            /*
            |--------------------------------------------------------------------------
            | Opsional simpan hasil ke database jika kolom tersedia.
            |--------------------------------------------------------------------------
            */

            $this->saveCoordinatesIfAvailable(
                $mountain,
                $preset
            );


            return $preset;
        }


        /*
        |--------------------------------------------------------------------------
        | 3. CACHE GEOCODING
        |--------------------------------------------------------------------------
        */

        $cacheKey =
            $this->coordinateCacheKey(
                $mountain
            );


        if (! $forceRefresh) {
            $cached =
                Cache::get(
                    $cacheKey
                );


            if (
                is_array($cached)
                &&
                isset(
                    $cached['latitude'],
                    $cached['longitude']
                )
            ) {
                return $cached;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | 4. GEOCODING
        |--------------------------------------------------------------------------
        */

        foreach (
            $this->geocodingQueries(
                $mountain
            )
            as
            $query
        ) {
            $result =
                $this->geocode(
                    $mountain,
                    $query
                );


            if (! $result) {
                continue;
            }


            Cache::put(
                $cacheKey,
                $result,
                now()->addDays(
                    self::COORDINATE_CACHE_DAYS
                )
            );


            $this->saveCoordinatesIfAvailable(
                $mountain,
                $result
            );


            return $result;
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | DATABASE COORDINATE
    |--------------------------------------------------------------------------
    */

    private function databaseCoordinates(
        Mountain $mountain
    ): ?array {
        if (
            ! Schema::hasColumn(
                $mountain->getTable(),
                'latitude'
            )
            ||
            ! Schema::hasColumn(
                $mountain->getTable(),
                'longitude'
            )
        ) {
            return null;
        }


        $latitude =
            $mountain->getAttribute(
                'latitude'
            );

        $longitude =
            $mountain->getAttribute(
                'longitude'
            );


        if (
            ! is_numeric($latitude)
            ||
            ! is_numeric($longitude)
        ) {
            return null;
        }


        return [
            'latitude' =>
                (float) $latitude,

            'longitude' =>
                (float) $longitude,

            'source' =>
                'database',

            'resolved_name' =>
                $mountain->name,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PRESET
    |--------------------------------------------------------------------------
    */

    private function presetCoordinates(
        Mountain $mountain
    ): ?array {
        $name =
            $this->normalizeName(
                $mountain->name
            );


        /*
        |--------------------------------------------------------------------------
        | EXACT
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                self::BALI_MOUNTAINS[
                    $name
                ]
            )
        ) {
            return array_merge(
                self::BALI_MOUNTAINS[
                    $name
                ],
                [
                    'source' =>
                        'bali-preset',

                    'resolved_name' =>
                        $mountain->name,
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE PREFIX
        |--------------------------------------------------------------------------
        */

        $withoutPrefix =
            trim(
                preg_replace(
                    '/^(gunung|bukit)\s+/i',
                    '',
                    $name
                )
                ??
                $name
            );


        foreach (
            self::BALI_MOUNTAINS
            as
            $key =>
            $coordinate
        ) {
            $keyWithoutPrefix =
                trim(
                    preg_replace(
                        '/^(gunung|bukit)\s+/i',
                        '',
                        $key
                    )
                    ??
                    $key
                );


            if (
                $withoutPrefix
                ===
                $keyWithoutPrefix
            ) {
                return array_merge(
                    $coordinate,
                    [
                        'source' =>
                            'bali-preset',

                        'resolved_name' =>
                            $mountain->name,
                    ]
                );
            }
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE NAME
    |--------------------------------------------------------------------------
    */

    private function normalizeName(
        mixed $value
    ): string {
        return Str::of(
            (string) $value
        )
            ->lower()
            ->ascii()
            ->replaceMatches(
                '/[^a-z0-9\s]/',
                ' '
            )
            ->squish()
            ->toString();
    }


    /*
    |--------------------------------------------------------------------------
    | GEOCODING QUERY
    |--------------------------------------------------------------------------
    */

    private function geocodingQueries(
        Mountain $mountain
    ): array {
        $name =
            trim(
                (string)
                $mountain->name
            );


        $location =
            $this->location(
                $mountain
            );


        $simpleName =
            trim(
                preg_replace(
                    '/^(gunung|bukit)\s+/i',
                    '',
                    $name
                )
                ??
                $name
            );


        $queries = [

            "{$name}, {$location}",

            "{$name}, Bali",

            "{$name}, Bali, Indonesia",

            "{$simpleName}, Bali",

            "{$simpleName}, Indonesia",

            $name,

            $simpleName,

        ];


        return array_values(
            array_unique(
                array_filter(
                    array_map(
                        'trim',
                        $queries
                    )
                )
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GEOCODE
    |--------------------------------------------------------------------------
    */

    private function geocode(
        Mountain $mountain,
        string $query
    ): ?array {
        try {
            $response =
                Http::acceptJson()
                    ->connectTimeout(8)
                    ->timeout(15)
                    ->retry(
                        1,
                        350,
                        throw: false
                    )
                    ->get(
                        self::GEOCODING_URL,
                        [
                            'name' =>
                                $query,

                            'count' =>
                                10,

                            'language' =>
                                'id',

                            'format' =>
                                'json',

                            'countryCode' =>
                                'ID',
                        ]
                    );


            if (! $response->successful()) {
                return null;
            }


            $results =
                $response->json(
                    'results',
                    []
                );


            if (
                ! is_array($results)
                ||
                empty($results)
            ) {
                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | Prioritaskan Bali
            |--------------------------------------------------------------------------
            */

            $selected =
                collect(
                    $results
                )
                    ->sortByDesc(
                        function (
                            array $item
                        ): int {
                            $score = 0;


                            $countryCode =
                                strtoupper(
                                    (string)
                                    (
                                        $item[
                                            'country_code'
                                        ]
                                        ??
                                        ''
                                    )
                                );


                            $admin =
                                strtolower(
                                    (
                                        $item[
                                            'admin1'
                                        ]
                                        ??
                                        ''
                                    )
                                    .
                                    ' '
                                    .
                                    (
                                        $item[
                                            'admin2'
                                        ]
                                        ??
                                        ''
                                    )
                                );


                            if (
                                $countryCode
                                ===
                                'ID'
                            ) {
                                $score += 100;
                            }


                            if (
                                str_contains(
                                    $admin,
                                    'bali'
                                )
                            ) {
                                $score += 200;
                            }


                            return $score;
                        }
                    )
                    ->first();


            if (
                ! is_array($selected)
                ||
                ! isset(
                    $selected['latitude'],
                    $selected['longitude']
                )
            ) {
                return null;
            }


            return [
                'latitude' =>
                    (float)
                    $selected[
                        'latitude'
                    ],

                'longitude' =>
                    (float)
                    $selected[
                        'longitude'
                    ],

                'source' =>
                    'geocoding',

                'resolved_name' =>
                    $selected['name']
                    ??
                    $query,
            ];

        } catch (Throwable $e) {
            Log::warning(
                'BaliHiking geocoding failed',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'query' =>
                        $query,

                    'message' =>
                        $e->getMessage(),
                ]
            );


            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SAVE COORDINATE
    |--------------------------------------------------------------------------
    */

    private function saveCoordinatesIfAvailable(
        Mountain $mountain,
        array $coordinate
    ): void {
        try {
            if (
                ! Schema::hasColumn(
                    $mountain->getTable(),
                    'latitude'
                )
                ||
                ! Schema::hasColumn(
                    $mountain->getTable(),
                    'longitude'
                )
            ) {
                return;
            }


            if (
                is_numeric(
                    $mountain->latitude
                )
                &&
                is_numeric(
                    $mountain->longitude
                )
            ) {
                return;
            }


            $mountain->forceFill([
                'latitude' =>
                    $coordinate[
                        'latitude'
                    ],

                'longitude' =>
                    $coordinate[
                        'longitude'
                    ],
            ]);


            $mountain->saveQuietly();

        } catch (Throwable $e) {
            Log::warning(
                'Cannot save mountain coordinate',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'message' =>
                        $e->getMessage(),
                ]
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE WEATHER
    |--------------------------------------------------------------------------
    */

    private function normalize(
        Mountain $mountain,
        array $coordinate,
        array $json
    ): array {
        $current =
            $json['current']
            ??
            [];


        $daily =
            $json['daily']
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


        $forecast = [];


        foreach (
            $daily['time']
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


            $forecast[] = [

                'date' =>
                    $date,

                'weather_code' =>
                    $code,

                'condition' =>
                    $this->weatherLabel(
                        $code
                    ),

                'icon' =>
                    $this->weatherIcon(
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

                'wind_speed_max' =>
                    $this->number(
                        $daily[
                            'wind_speed_10m_max'
                        ][$index]
                        ??
                        null
                    ),

                'wind_direction' =>
                    $this->number(
                        $daily[
                            'wind_direction_10m_dominant'
                        ][$index]
                        ??
                        null
                    ),

                'sunrise' =>
                    $daily[
                        'sunrise'
                    ][$index]
                    ??
                    null,

                'sunset' =>
                    $daily[
                        'sunset'
                    ][$index]
                    ??
                    null,

            ];
        }


        return [

            'success' =>
                true,

            'mountain_id' =>
                $mountain->id,

            'mountain_name' =>
                $mountain->name,

            'location' =>
                $this->location(
                    $mountain
                ),

            'coordinates' => [

                'latitude' =>
                    $coordinate[
                        'latitude'
                    ],

                'longitude' =>
                    $coordinate[
                        'longitude'
                    ],

                'source' =>
                    $coordinate[
                        'source'
                    ]
                    ??
                    'unknown',

            ],

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

                'icon' =>
                    $this->weatherIcon(
                        $weatherCode
                    ),

            ],

            'forecast' =>
                $forecast,

            'updated_at' =>
                now()
                    ->timezone(
                        'Asia/Makassar'
                    )
                    ->toIso8601String(),

            'updated_at_label' =>
                now()
                    ->timezone(
                        'Asia/Makassar'
                    )
                    ->format(
                        'd M Y, H:i'
                    ),

            'cached' =>
                false,

            'stale' =>
                false,

        ];
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER LABEL
    |--------------------------------------------------------------------------
    */

    public function weatherLabel(
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

            56,
            57 =>
                'Gerimis Beku',

            61 =>
                'Hujan Ringan',

            63 =>
                'Hujan Sedang',

            65 =>
                'Hujan Lebat',

            66,
            67 =>
                'Hujan Beku',

            71,
            73,
            75,
            77 =>
                'Salju',

            80 =>
                'Hujan Lokal Ringan',

            81 =>
                'Hujan Lokal Sedang',

            82 =>
                'Hujan Lokal Lebat',

            85,
            86 =>
                'Hujan Salju',

            95 =>
                'Badai Petir',

            96,
            99 =>
                'Badai Petir & Hujan Es',

            default =>
                'Cuaca Tidak Diketahui',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER ICON
    |--------------------------------------------------------------------------
    */

    public function weatherIcon(
        ?int $code
    ): string {
        return match (
            $code
        ) {
            0 =>
                '☀️',

            1,
            2 =>
                '🌤️',

            3 =>
                '☁️',

            45,
            48 =>
                '🌫️',

            51,
            53,
            55,
            56,
            57 =>
                '🌦️',

            61,
            63,
            65,
            66,
            67,
            80,
            81,
            82 =>
                '🌧️',

            71,
            73,
            75,
            77,
            85,
            86 =>
                '❄️',

            95,
            96,
            99 =>
                '⛈️',

            default =>
                '🌥️',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | LOCATION
    |--------------------------------------------------------------------------
    */

    private function location(
        Mountain $mountain
    ): string {
        foreach (
            [
                'location',
                'address',
                'alamat',
                'lokasi',
            ]
            as
            $attribute
        ) {
            $value =
                $mountain->getAttribute(
                    $attribute
                );


            if (
                is_string($value)
                &&
                trim($value) !== ''
            ) {
                return trim(
                    $value
                );
            }
        }


        return 'Bali, Indonesia';
    }


    /*
    |--------------------------------------------------------------------------
    | CACHE KEYS
    |--------------------------------------------------------------------------
    */

    private function weatherCacheKey(
        Mountain $mountain
    ): string {
        return
            'balihiking:weather:'
            .
            $mountain->id;
    }


    private function staleWeatherCacheKey(
        Mountain $mountain
    ): string {
        return
            'balihiking:weather:stale:'
            .
            $mountain->id;
    }


    private function coordinateCacheKey(
        Mountain $mountain
    ): string {
        return
            'balihiking:weather:coordinate:'
            .
            $mountain->id;
    }


    /*
    |--------------------------------------------------------------------------
    | STALE
    |--------------------------------------------------------------------------
    */

    private function stale(
        string $key
    ): ?array {
        $cached =
            Cache::get(
                $key
            );


        if (! is_array($cached)) {
            return null;
        }


        $cached['cached'] =
            true;

        $cached['stale'] =
            true;


        return $cached;
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    private function number(
        mixed $value
    ): ?float {
        if (! is_numeric($value)) {
            return null;
        }


        return round(
            (float) $value,
            1
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOG HTTP
    |--------------------------------------------------------------------------
    */

    private function logHttpError(
        string $context,
        Response $response,
        Mountain $mountain
    ): void {
        Log::error(
            'BaliHiking weather '
            .
            $context
            .
            ' error',
            [
                'mountain_id' =>
                    $mountain->id,

                'mountain_name' =>
                    $mountain->name,

                'status' =>
                    $response->status(),

                'body' =>
                    mb_substr(
                        $response->body(),
                        0,
                        1000
                    ),
            ]
        );
    }
}