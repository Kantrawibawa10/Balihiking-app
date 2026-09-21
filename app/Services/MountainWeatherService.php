<?php

namespace App\Services;

use App\Models\Mountain;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class MountainWeatherService
{
    /*
    |--------------------------------------------------------------------------
    | ENDPOINT
    |--------------------------------------------------------------------------
    */

    private const GEOCODING_URL =
        'https://geocoding-api.open-meteo.com/v1/search';

    private const WEATHER_URL =
        'https://api.open-meteo.com/v1/forecast';


    /*
    |--------------------------------------------------------------------------
    | CACHE
    |--------------------------------------------------------------------------
    */

    private const WEATHER_CACHE_MINUTES =
        30;

    private const COORDINATE_CACHE_DAYS =
        30;

    private const STALE_CACHE_DAYS =
        7;


    /*
    |--------------------------------------------------------------------------
    | GET WEATHER
    |--------------------------------------------------------------------------
    */

    public function getWeather(
        Mountain $mountain,
        bool $forceRefresh = false
    ): ?array {
        $weatherCacheKey =
            $this->weatherCacheKey(
                $mountain
            );

        $staleCacheKey =
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
                    $weatherCacheKey
                );

            if (
                is_array(
                    $cached
                )
            ) {
                $cached['source'] =
                    $cached['source']
                    ??
                    'cache';

                $cached['cached'] =
                    true;

                return $cached;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RESOLVE COORDINATES
        |--------------------------------------------------------------------------
        */

        $coordinates =
            $this->resolveCoordinates(
                $mountain,
                $forceRefresh
            );


        if (! $coordinates) {
            Log::warning(
                'BaliHiking weather: coordinate unavailable',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'mountain_name' =>
                        $mountain->name,

                    'location' =>
                        $this->getMountainLocation(
                            $mountain
                        ),
                ]
            );

            return $this->getStaleWeather(
                $staleCacheKey
            );
        }


        /*
        |--------------------------------------------------------------------------
        | REQUEST WEATHER
        |--------------------------------------------------------------------------
        */

        try {
            $response =
                Http::acceptJson()
                    ->connectTimeout(10)
                    ->timeout(20)
                    ->retry(
                        2,
                        750,
                        throw: false
                    )
                    ->get(
                        self::WEATHER_URL,
                        [
                            'latitude' =>
                                $coordinates['latitude'],

                            'longitude' =>
                                $coordinates['longitude'],

                            'timezone' =>
                                'Asia/Makassar',

                            'forecast_days' =>
                                5,

                            /*
                            |--------------------------------------------------------------------------
                            | CURRENT
                            |--------------------------------------------------------------------------
                            */

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

                            /*
                            |--------------------------------------------------------------------------
                            | DAILY
                            |--------------------------------------------------------------------------
                            */

                            'daily' =>
                                implode(
                                    ',',
                                    [
                                        'weather_code',
                                        'temperature_2m_max',
                                        'temperature_2m_min',
                                        'apparent_temperature_max',
                                        'apparent_temperature_min',
                                        'sunrise',
                                        'sunset',
                                        'precipitation_sum',
                                        'precipitation_probability_max',
                                        'wind_speed_10m_max',
                                        'wind_direction_10m_dominant',
                                    ]
                                ),
                        ]
                    );


            if (! $response->successful()) {
                $this->logHttpFailure(
                    'forecast',
                    $response,
                    $mountain
                );

                return $this->getStaleWeather(
                    $staleCacheKey
                );
            }


            $raw =
                $response->json();


            if (
                ! is_array(
                    $raw
                )
                ||
                empty(
                    $raw['current']
                )
                ||
                empty(
                    $raw['daily']
                )
            ) {
                Log::warning(
                    'BaliHiking weather: invalid forecast response',
                    [
                        'mountain_id' =>
                            $mountain->id,

                        'response' =>
                            $raw,
                    ]
                );

                return $this->getStaleWeather(
                    $staleCacheKey
                );
            }


            /*
            |--------------------------------------------------------------------------
            | NORMALIZE
            |--------------------------------------------------------------------------
            */

            $weather =
                $this->normalizeWeather(
                    $mountain,
                    $coordinates,
                    $raw
                );


            /*
            |--------------------------------------------------------------------------
            | NORMAL CACHE
            |--------------------------------------------------------------------------
            */

            Cache::put(
                $weatherCacheKey,
                $weather,
                now()->addMinutes(
                    self::WEATHER_CACHE_MINUTES
                )
            );


            /*
            |--------------------------------------------------------------------------
            | STALE CACHE
            |--------------------------------------------------------------------------
            |
            | Digunakan jika API gagal di kemudian hari.
            |
            */

            Cache::put(
                $staleCacheKey,
                $weather,
                now()->addDays(
                    self::STALE_CACHE_DAYS
                )
            );


            return $weather;

        } catch (ConnectionException $exception) {
            $this->logException(
                'forecast connection',
                $exception,
                $mountain
            );

            return $this->getStaleWeather(
                $staleCacheKey
            );

        } catch (Throwable $exception) {
            $this->logException(
                'forecast',
                $exception,
                $mountain
            );

            return $this->getStaleWeather(
                $staleCacheKey
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE COORDINATES
    |--------------------------------------------------------------------------
    */

    public function resolveCoordinates(
        Mountain $mountain,
        bool $forceRefresh = false
    ): ?array {
        /*
        |--------------------------------------------------------------------------
        | 1. DATABASE COORDINATES
        |--------------------------------------------------------------------------
        |
        | Aman walaupun kolom latitude/longitude belum ada pada tabel.
        |
        */

        $latitude =
            $mountain->getAttribute(
                'latitude'
            );

        $longitude =
            $mountain->getAttribute(
                'longitude'
            );


        if (
            is_numeric(
                $latitude
            )
            &&
            is_numeric(
                $longitude
            )
        ) {
            return [
                'latitude' =>
                    (float)
                    $latitude,

                'longitude' =>
                    (float)
                    $longitude,

                'name' =>
                    $mountain->name,

                'admin1' =>
                    null,

                'country' =>
                    'Indonesia',

                'source' =>
                    'database',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | 2. COORDINATE CACHE
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
                is_array(
                    $cached
                )
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
        | 3. GEOCODING FALLBACK
        |--------------------------------------------------------------------------
        */

        $queries =
            $this->buildGeocodingQueries(
                $mountain
            );


        foreach (
            $queries
            as
            $query
        ) {
            $result =
                $this->geocode(
                    $query,
                    $mountain
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


            return $result;
        }


        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD GEOCODING QUERIES
    |--------------------------------------------------------------------------
    */

    private function buildGeocodingQueries(
        Mountain $mountain
    ): array {
        $name =
            trim(
                (string)
                $mountain->name
            );


        $location =
            trim(
                $this->getMountainLocation(
                    $mountain
                )
            );


        $queries = [];


        /*
        |--------------------------------------------------------------------------
        | Nama + lokasi
        |--------------------------------------------------------------------------
        */

        if (
            $name !== ''
            &&
            $location !== ''
        ) {
            $queries[] =
                "{$name}, {$location}";
        }


        /*
        |--------------------------------------------------------------------------
        | Nama + Bali
        |--------------------------------------------------------------------------
        */

        if (
            $name !== ''
        ) {
            $queries[] =
                "{$name}, Bali, Indonesia";

            $queries[] =
                "{$name}, Indonesia";

            $queries[] =
                $name;
        }


        /*
        |--------------------------------------------------------------------------
        | Lokasi saja
        |--------------------------------------------------------------------------
        */

        if (
            $location !== ''
        ) {
            $queries[] =
                "{$location}, Bali, Indonesia";

            $queries[] =
                $location;
        }


        return array_values(
            array_unique(
                array_filter(
                    $queries
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
        string $query,
        Mountain $mountain
    ): ?array {
        try {
            Log::info(
                'BaliHiking weather geocoding request',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'query' =>
                        $query,
                ]
            );


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
                $this->logHttpFailure(
                    'geocoding',
                    $response,
                    $mountain,
                    [
                        'query' =>
                            $query,
                    ]
                );

                return null;
            }


            $results =
                $response->json(
                    'results',
                    []
                );


            if (
                ! is_array(
                    $results
                )
                ||
                count(
                    $results
                )
                ===
                0
            ) {
                Log::info(
                    'BaliHiking weather geocoding empty',
                    [
                        'mountain_id' =>
                            $mountain->id,

                        'query' =>
                            $query,
                    ]
                );

                return null;
            }


            /*
            |--------------------------------------------------------------------------
            | PRIORITIZE BALI / INDONESIA
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
                            $score =
                                0;


                            $countryCode =
                                strtoupper(
                                    (string)
                                    (
                                        $item['country_code']
                                        ??
                                        ''
                                    )
                                );


                            $admin1 =
                                strtolower(
                                    (string)
                                    (
                                        $item['admin1']
                                        ??
                                        ''
                                    )
                                );


                            $timezone =
                                strtolower(
                                    (string)
                                    (
                                        $item['timezone']
                                        ??
                                        ''
                                    )
                                );


                            if (
                                $countryCode
                                ===
                                'ID'
                            ) {
                                $score +=
                                    100;
                            }


                            if (
                                str_contains(
                                    $admin1,
                                    'bali'
                                )
                            ) {
                                $score +=
                                    100;
                            }


                            if (
                                str_contains(
                                    $timezone,
                                    'makassar'
                                )
                            ) {
                                $score +=
                                    20;
                            }


                            if (
                                isset(
                                    $item['population']
                                )
                            ) {
                                $score +=
                                    1;
                            }


                            return $score;
                        }
                    )
                    ->first();


            if (
                ! is_array(
                    $selected
                )
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
                    $selected['latitude'],

                'longitude' =>
                    (float)
                    $selected['longitude'],

                'name' =>
                    $selected['name']
                    ??
                    $query,

                'admin1' =>
                    $selected['admin1']
                    ??
                    null,

                'admin2' =>
                    $selected['admin2']
                    ??
                    null,

                'country' =>
                    $selected['country']
                    ??
                    'Indonesia',

                'timezone' =>
                    $selected['timezone']
                    ??
                    'Asia/Makassar',

                'source' =>
                    'geocoding',
            ];

        } catch (Throwable $exception) {
            $this->logException(
                'geocoding',
                $exception,
                $mountain,
                [
                    'query' =>
                        $query,
                ]
            );

            return null;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE WEATHER
    |--------------------------------------------------------------------------
    */

    private function normalizeWeather(
        Mountain $mountain,
        array $coordinates,
        array $raw
    ): array {
        $current =
            $raw['current']
            ??
            [];


        $daily =
            $raw['daily']
            ??
            [];


        $currentCode =
            isset(
                $current['weather_code']
            )
                ?
                (int)
                $current['weather_code']
                :
                null;


        $forecast =
            [];


        $dates =
            $daily['time']
            ??
            [];


        foreach (
            $dates
            as
            $index =>
            $date
        ) {
            $weatherCode =
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
                    $weatherCode,

                'condition' =>
                    $this->weatherLabel(
                        $weatherCode
                    ),

                'icon' =>
                    $this->weatherIcon(
                        $weatherCode
                    ),

                'temperature_max' =>
                    $this->numberOrNull(
                        $daily[
                            'temperature_2m_max'
                        ][$index]
                        ??
                        null
                    ),

                'temperature_min' =>
                    $this->numberOrNull(
                        $daily[
                            'temperature_2m_min'
                        ][$index]
                        ??
                        null
                    ),

                'apparent_temperature_max' =>
                    $this->numberOrNull(
                        $daily[
                            'apparent_temperature_max'
                        ][$index]
                        ??
                        null
                    ),

                'apparent_temperature_min' =>
                    $this->numberOrNull(
                        $daily[
                            'apparent_temperature_min'
                        ][$index]
                        ??
                        null
                    ),

                'precipitation_sum' =>
                    $this->numberOrNull(
                        $daily[
                            'precipitation_sum'
                        ][$index]
                        ??
                        null
                    ),

                'precipitation_probability' =>
                    $this->numberOrNull(
                        $daily[
                            'precipitation_probability_max'
                        ][$index]
                        ??
                        null
                    ),

                'wind_speed_max' =>
                    $this->numberOrNull(
                        $daily[
                            'wind_speed_10m_max'
                        ][$index]
                        ??
                        null
                    ),

                'wind_direction' =>
                    $this->numberOrNull(
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
                $this->getMountainLocation(
                    $mountain
                ),

            'coordinates' => [
                'latitude' =>
                    $coordinates['latitude'],

                'longitude' =>
                    $coordinates['longitude'],

                'source' =>
                    $coordinates['source']
                    ??
                    null,

                'resolved_name' =>
                    $coordinates['name']
                    ??
                    null,

                'admin1' =>
                    $coordinates['admin1']
                    ??
                    null,
            ],

            'current' => [
                'time' =>
                    $current['time']
                    ??
                    null,

                'temperature' =>
                    $this->numberOrNull(
                        $current[
                            'temperature_2m'
                        ]
                        ??
                        null
                    ),

                'apparent_temperature' =>
                    $this->numberOrNull(
                        $current[
                            'apparent_temperature'
                        ]
                        ??
                        null
                    ),

                'humidity' =>
                    $this->numberOrNull(
                        $current[
                            'relative_humidity_2m'
                        ]
                        ??
                        null
                    ),

                'precipitation' =>
                    $this->numberOrNull(
                        $current[
                            'precipitation'
                        ]
                        ??
                        null
                    ),

                'cloud_cover' =>
                    $this->numberOrNull(
                        $current[
                            'cloud_cover'
                        ]
                        ??
                        null
                    ),

                'wind_speed' =>
                    $this->numberOrNull(
                        $current[
                            'wind_speed_10m'
                        ]
                        ??
                        null
                    ),

                'wind_direction' =>
                    $this->numberOrNull(
                        $current[
                            'wind_direction_10m'
                        ]
                        ??
                        null
                    ),

                'weather_code' =>
                    $currentCode,

                'condition' =>
                    $this->weatherLabel(
                        $currentCode
                    ),

                'icon' =>
                    $this->weatherIcon(
                        $currentCode
                    ),
            ],

            'forecast' =>
                $forecast,

            'timezone' =>
                $raw['timezone']
                ??
                'Asia/Makassar',

            'timezone_abbreviation' =>
                $raw[
                    'timezone_abbreviation'
                ]
                ??
                'WITA',

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

            'source' =>
                'open-meteo',

            'cached' =>
                false,

            'stale' =>
                false,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | STALE WEATHER
    |--------------------------------------------------------------------------
    */

    private function getStaleWeather(
        string $cacheKey
    ): ?array {
        $weather =
            Cache::get(
                $cacheKey
            );


        if (
            ! is_array(
                $weather
            )
        ) {
            return null;
        }


        $weather['cached'] =
            true;

        $weather['stale'] =
            true;

        $weather['source'] =
            'stale-cache';


        return $weather;
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER LABEL
    |--------------------------------------------------------------------------
    */

    public function weatherLabel(
        ?int $code
    ): string {
        if (
            $code ===
            null
        ) {
            return 'Tidak diketahui';
        }


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
            75 =>
                'Salju',

            77 =>
                'Butiran Salju',

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
        if (
            $code ===
            null
        ) {
            return '🌥️';
        }


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
    | LOCATION FIELD FALLBACK
    |--------------------------------------------------------------------------
    |
    | Mendukung beberapa kemungkinan nama kolom yang sebelumnya digunakan.
    |--------------------------------------------------------------------------
    */

    private function getMountainLocation(
        Mountain $mountain
    ): string {
        $possibleFields = [
            'location',
            'address',
            'alamat',
            'lokasi',
        ];


        foreach (
            $possibleFields
            as
            $field
        ) {
            $value =
                $mountain->getAttribute(
                    $field
                );


            if (
                is_string(
                    $value
                )
                &&
                trim(
                    $value
                )
                !==
                ''
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
            'balihiking:weather:coordinates:'
            .
            $mountain->id;
    }


    /*
    |--------------------------------------------------------------------------
    | NUMBER
    |--------------------------------------------------------------------------
    */

    private function numberOrNull(
        mixed $value
    ): ?float {
        if (
            ! is_numeric(
                $value
            )
        ) {
            return null;
        }


        return round(
            (float)
            $value,
            1
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOG HTTP ERROR
    |--------------------------------------------------------------------------
    */

    private function logHttpFailure(
        string $context,
        Response $response,
        Mountain $mountain,
        array $extra = []
    ): void {
        Log::error(
            "BaliHiking weather {$context} HTTP error",
            array_merge(
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
                            2000
                        ),
                ],
                $extra
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LOG EXCEPTION
    |--------------------------------------------------------------------------
    */

    private function logException(
        string $context,
        Throwable $exception,
        Mountain $mountain,
        array $extra = []
    ): void {
        Log::error(
            "BaliHiking weather {$context} exception",
            array_merge(
                [
                    'mountain_id' =>
                        $mountain->id,

                    'mountain_name' =>
                        $mountain->name,

                    'exception' =>
                        get_class(
                            $exception
                        ),

                    'message' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),
                ],
                $extra
            )
        );
    }
}
