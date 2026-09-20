<?php

namespace App\Services;

use App\Models\Mountain;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MountainWeatherService
{
    private const FORECAST_URL =
        'https://api.open-meteo.com/v1/forecast';

    private const GEOCODING_URL =
        'https://geocoding-api.open-meteo.com/v1/search';

    private const TIMEZONE =
        'Asia/Makassar';

    private const FRESH_CACHE_MINUTES =
        30;

    private const STALE_CACHE_DAYS =
        7;

    private const GEOCODING_CACHE_DAYS =
        30;


    /*
    |--------------------------------------------------------------------------
    | GET FORECAST
    |--------------------------------------------------------------------------
    */

    public function getForecast(
        Mountain $mountain
    ): ?array {

        $coordinates =
            $this->resolveCoordinates(
                $mountain
            );


        if (!$coordinates) {

            return $this->getStaleForecast(
                $mountain
            );

        }


        $latitude =
            (float)
            $coordinates['latitude'];


        $longitude =
            (float)
            $coordinates['longitude'];


        $freshCacheKey =
            $this->freshCacheKey(
                $mountain,
                $latitude,
                $longitude
            );


        /*
        |--------------------------------------------------------------------------
        | CACHE 30 MENIT
        |--------------------------------------------------------------------------
        */

        $cached =
            Cache::get(
                $freshCacheKey
            );


        if (
            is_array(
                $cached
            )
        ) {

            return $cached;

        }


        try {

            $response =
                Http::acceptJson()
                    ->timeout(10)
                    ->retry(
                        2,
                        500,
                        throw: false
                    )
                    ->get(
                        self::FORECAST_URL,
                        [
                            'latitude' =>
                                $latitude,

                            'longitude' =>
                                $longitude,

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
                                        'precipitation_probability_max',
                                        'wind_speed_10m_max',
                                        'sunrise',
                                        'sunset',
                                    ]
                                ),

                            'timezone' =>
                                self::TIMEZONE,

                            'forecast_days' =>
                                5,
                        ]
                    );


            if (
                !$response->successful()
            ) {

                Log::warning(
                    'BaliHiking weather API failed',
                    [
                        'mountain_id' =>
                            $mountain->id,

                        'status' =>
                            $response->status(),
                    ]
                );


                return $this->getStaleForecast(
                    $mountain
                );

            }


            $apiData =
                $response->json();


            if (
                !is_array(
                    $apiData
                )
            ) {

                return $this->getStaleForecast(
                    $mountain
                );

            }


            $weather =
                $this->transformWeather(
                    $mountain,
                    $coordinates,
                    $apiData
                );


            if (!$weather) {

                return $this->getStaleForecast(
                    $mountain
                );

            }


            /*
            |--------------------------------------------------------------------------
            | FRESH CACHE
            |--------------------------------------------------------------------------
            */

            Cache::put(
                $freshCacheKey,
                $weather,
                now()->addMinutes(
                    self::FRESH_CACHE_MINUTES
                )
            );


            /*
            |--------------------------------------------------------------------------
            | STALE CACHE
            |--------------------------------------------------------------------------
            |
            | Jika API cuaca down, data terakhir masih bisa digunakan.
            |
            */

            Cache::put(
                $this->staleCacheKey(
                    $mountain
                ),
                $weather,
                now()->addDays(
                    self::STALE_CACHE_DAYS
                )
            );


            return $weather;


        } catch (\Throwable $exception) {

            Log::warning(
                'BaliHiking weather exception',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'message' =>
                        $exception->getMessage(),
                ]
            );


            return $this->getStaleForecast(
                $mountain
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | RESOLVE COORDINATES
    |--------------------------------------------------------------------------
    |
    | Prioritas:
    |
    | 1. latitude / longitude di tabel mountains
    | 2. nama gunung + location
    | 3. location saja
    |
    |--------------------------------------------------------------------------
    */

    private function resolveCoordinates(
        Mountain $mountain
    ): ?array {

        /*
        |--------------------------------------------------------------------------
        | DATABASE COORDINATE
        |--------------------------------------------------------------------------
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

                'source' =>
                    'database',
            ];

        }


        /*
        |--------------------------------------------------------------------------
        | GEOCODING CACHE
        |--------------------------------------------------------------------------
        */

        $geoCacheKey =
            $this->geocodingCacheKey(
                $mountain
            );


        $cached =
            Cache::get(
                $geoCacheKey
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


        /*
        |--------------------------------------------------------------------------
        | QUERY 1
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | Gunung Abang, Bangli, Bali, Indonesia
        |
        |--------------------------------------------------------------------------
        */

        $queries = [];


        $queries[] =
            trim(
                implode(
                    ', ',
                    array_filter(
                        [
                            $mountain->name,
                            $mountain->location,
                            'Bali',
                            'Indonesia',
                        ]
                    )
                )
            );


        /*
        |--------------------------------------------------------------------------
        | QUERY 2
        |--------------------------------------------------------------------------
        */

        $queries[] =
            trim(
                (string)
                $mountain->name
            );


        /*
        |--------------------------------------------------------------------------
        | QUERY 3
        |--------------------------------------------------------------------------
        */

        if (
            $mountain->location
        ) {

            $queries[] =
                trim(
                    $mountain->location
                    .
                    ', Bali, Indonesia'
                );

        }


        foreach (
            array_unique(
                array_filter(
                    $queries
                )
            ) as $query
        ) {

            $coordinates =
                $this->geocode(
                    $query,
                    $mountain
                );


            if (
                $coordinates
            ) {

                Cache::put(
                    $geoCacheKey,
                    $coordinates,
                    now()->addDays(
                        self::GEOCODING_CACHE_DAYS
                    )
                );


                return $coordinates;

            }

        }


        return null;
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

            $response =
                Http::acceptJson()
                    ->timeout(10)
                    ->retry(
                        2,
                        400,
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
                        ]
                    );


            if (
                !$response->successful()
            ) {

                return null;

            }


            $results =
                $response->json(
                    'results',
                    []
                );


            if (
                !is_array(
                    $results
                )
                ||
                count(
                    $results
                ) ===
                0
            ) {

                return null;

            }


            /*
            |--------------------------------------------------------------------------
            | Pilih hasil terbaik untuk Bali, Indonesia
            |--------------------------------------------------------------------------
            */

            $mountainLocation =
                mb_strtolower(
                    (string)
                    $mountain->location
                );


            $mountainName =
                mb_strtolower(
                    str_replace(
                        'gunung',
                        '',
                        $mountain->name
                    )
                );


            $best =
                collect(
                    $results
                )
                ->sortByDesc(
                    function (
                        array $result
                    ) use (
                        $mountainLocation,
                        $mountainName
                    ) {

                        $score =
                            0;


                        $country =
                            mb_strtolower(
                                (string)
                                (
                                    $result['country']
                                    ??
                                    ''
                                )
                            );


                        $countryCode =
                            mb_strtolower(
                                (string)
                                (
                                    $result['country_code']
                                    ??
                                    ''
                                )
                            );


                        $admin1 =
                            mb_strtolower(
                                (string)
                                (
                                    $result['admin1']
                                    ??
                                    ''
                                )
                            );


                        $admin2 =
                            mb_strtolower(
                                (string)
                                (
                                    $result['admin2']
                                    ??
                                    ''
                                )
                            );


                        $name =
                            mb_strtolower(
                                (string)
                                (
                                    $result['name']
                                    ??
                                    ''
                                )
                            );


                        if (
                            $countryCode ===
                            'id'
                        ) {

                            $score +=
                                100;

                        }


                        if (
                            str_contains(
                                $country,
                                'indonesia'
                            )
                        ) {

                            $score +=
                                50;

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


                        $mountainName =
                            trim(
                                $mountainName
                            );


                        if (
                            $mountainName !==
                            ''
                            &&
                            str_contains(
                                $name,
                                $mountainName
                            )
                        ) {

                            $score +=
                                75;

                        }


                        if (
                            $mountainLocation !==
                            ''
                        ) {

                            $words =
                                preg_split(
                                    '/[\s,]+/',
                                    $mountainLocation
                                );


                            foreach (
                                $words
                                as $word
                            ) {

                                $word =
                                    trim(
                                        mb_strtolower(
                                            $word
                                        )
                                    );


                                if (
                                    mb_strlen(
                                        $word
                                    )
                                    <
                                    3
                                ) {

                                    continue;

                                }


                                if (
                                    str_contains(
                                        $name,
                                        $word
                                    )
                                    ||
                                    str_contains(
                                        $admin1,
                                        $word
                                    )
                                    ||
                                    str_contains(
                                        $admin2,
                                        $word
                                    )
                                ) {

                                    $score +=
                                        15;

                                }

                            }

                        }


                        return $score;

                    }
                )
                ->first();


            if (
                !$best
                ||
                !isset(
                    $best['latitude'],
                    $best['longitude']
                )
            ) {

                return null;

            }


            return [
                'latitude' =>
                    (float)
                    $best['latitude'],

                'longitude' =>
                    (float)
                    $best['longitude'],

                'source' =>
                    'geocoding',

                'resolved_name' =>
                    $best['name']
                    ??
                    null,

                'resolved_admin1' =>
                    $best['admin1']
                    ??
                    null,

                'resolved_admin2' =>
                    $best['admin2']
                    ??
                    null,

                'resolved_country' =>
                    $best['country']
                    ??
                    null,
            ];


        } catch (\Throwable $exception) {

            Log::warning(
                'BaliHiking geocoding failed',
                [
                    'mountain_id' =>
                        $mountain->id,

                    'query' =>
                        $query,

                    'message' =>
                        $exception->getMessage(),
                ]
            );


            return null;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | TRANSFORM WEATHER
    |--------------------------------------------------------------------------
    */

    private function transformWeather(
        Mountain $mountain,
        array $coordinates,
        array $data
    ): ?array {

        $current =
            $data['current']
            ??
            null;


        $daily =
            $data['daily']
            ??
            null;


        if (
            !is_array(
                $current
            )
        ) {

            return null;

        }


        $currentCode =
            (int)
            (
                $current['weather_code']
                ??
                -1
            );


        $currentCondition =
            $this->weatherCondition(
                $currentCode
            );


        /*
        |--------------------------------------------------------------------------
        | DAILY FORECAST
        |--------------------------------------------------------------------------
        */

        $dailyForecast =
            [];


        if (
            is_array(
                $daily
            )
            &&
            isset(
                $daily['time']
            )
            &&
            is_array(
                $daily['time']
            )
        ) {

            foreach (
                $daily['time']
                as $index =>
                $date
            ) {

                $weatherCode =
                    (int)
                    (
                        $daily['weather_code'][$index]
                        ??
                        -1
                    );


                $condition =
                    $this->weatherCondition(
                        $weatherCode
                    );


                $dailyForecast[] = [

                    'date' =>
                        $date,

                    'day_name' =>
                        Carbon::parse(
                            $date,
                            self::TIMEZONE
                        )
                        ->locale('id')
                        ->translatedFormat(
                            'D'
                        ),

                    'day_full' =>
                        Carbon::parse(
                            $date,
                            self::TIMEZONE
                        )
                        ->locale('id')
                        ->translatedFormat(
                            'l, d M'
                        ),

                    'weather_code' =>
                        $weatherCode,

                    'condition' =>
                        $condition['label'],

                    'icon' =>
                        $condition['icon'],

                    'temperature_max' =>
                        $this->number(
                            $daily['temperature_2m_max'][$index]
                            ??
                            null
                        ),

                    'temperature_min' =>
                        $this->number(
                            $daily['temperature_2m_min'][$index]
                            ??
                            null
                        ),

                    'rain_probability' =>
                        $this->integer(
                            $daily['precipitation_probability_max'][$index]
                            ??
                            null
                        ),

                    'wind_max' =>
                        $this->number(
                            $daily['wind_speed_10m_max'][$index]
                            ??
                            null,
                            1
                        ),

                    'sunrise' =>
                        $this->formatTime(
                            $daily['sunrise'][$index]
                            ??
                            null
                        ),

                    'sunset' =>
                        $this->formatTime(
                            $daily['sunset'][$index]
                            ??
                            null
                        ),

                ];

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FINAL PAYLOAD
        |--------------------------------------------------------------------------
        */

        return [

            'mountain_id' =>
                $mountain->id,

            'mountain_name' =>
                $mountain->name,

            'location' =>
                $mountain->location,

            'latitude' =>
                $coordinates['latitude'],

            'longitude' =>
                $coordinates['longitude'],

            'coordinate_source' =>
                $coordinates['source']
                ??
                null,

            'timezone' =>
                self::TIMEZONE,

            'updated_at' =>
                now(
                    self::TIMEZONE
                )
                ->toIso8601String(),

            'updated_label' =>
                now(
                    self::TIMEZONE
                )
                ->locale('id')
                ->translatedFormat(
                    'd M Y, H:i'
                ),

            'stale' =>
                false,

            'current' => [

                'temperature' =>
                    $this->number(
                        $current['temperature_2m']
                        ??
                        null
                    ),

                'apparent_temperature' =>
                    $this->number(
                        $current['apparent_temperature']
                        ??
                        null
                    ),

                'humidity' =>
                    $this->integer(
                        $current['relative_humidity_2m']
                        ??
                        null
                    ),

                'precipitation' =>
                    $this->number(
                        $current['precipitation']
                        ??
                        null,
                        1
                    ),

                'weather_code' =>
                    $currentCode,

                'condition' =>
                    $currentCondition['label'],

                'icon' =>
                    $currentCondition['icon'],

                'cloud_cover' =>
                    $this->integer(
                        $current['cloud_cover']
                        ??
                        null
                    ),

                'wind_speed' =>
                    $this->number(
                        $current['wind_speed_10m']
                        ??
                        null,
                        1
                    ),

                'wind_direction' =>
                    $this->integer(
                        $current['wind_direction_10m']
                        ??
                        null
                    ),
            ],

            'daily' =>
                $dailyForecast,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | WEATHER CONDITION
    |--------------------------------------------------------------------------
    */

    private function weatherCondition(
        int $code
    ): array {

        return match (true) {

            $code === 0 => [
                'label' =>
                    'Cerah',

                'icon' =>
                    'sun',
            ],

            in_array(
                $code,
                [
                    1,
                    2,
                ],
                true
            ) => [
                'label' =>
                    'Cerah Berawan',

                'icon' =>
                    'cloud-sun',
            ],

            $code === 3 => [
                'label' =>
                    'Berawan',

                'icon' =>
                    'cloud',
            ],

            in_array(
                $code,
                [
                    45,
                    48,
                ],
                true
            ) => [
                'label' =>
                    'Berkabut',

                'icon' =>
                    'fog',
            ],

            in_array(
                $code,
                [
                    51,
                    53,
                    55,
                    56,
                    57,
                ],
                true
            ) => [
                'label' =>
                    'Gerimis',

                'icon' =>
                    'drizzle',
            ],

            in_array(
                $code,
                [
                    61,
                    63,
                    65,
                    66,
                    67,
                    80,
                    81,
                    82,
                ],
                true
            ) => [
                'label' =>
                    'Hujan',

                'icon' =>
                    'rain',
            ],

            in_array(
                $code,
                [
                    71,
                    73,
                    75,
                    77,
                    85,
                    86,
                ],
                true
            ) => [
                'label' =>
                    'Presipitasi Dingin',

                'icon' =>
                    'rain',
            ],

            in_array(
                $code,
                [
                    95,
                    96,
                    99,
                ],
                true
            ) => [
                'label' =>
                    'Hujan Petir',

                'icon' =>
                    'storm',
            ],

            default => [
                'label' =>
                    'Cuaca Berubah',

                'icon' =>
                    'cloud',
            ],
        };
    }


    /*
    |--------------------------------------------------------------------------
    | STALE CACHE
    |--------------------------------------------------------------------------
    */

    private function getStaleForecast(
        Mountain $mountain
    ): ?array {

        $weather =
            Cache::get(
                $this->staleCacheKey(
                    $mountain
                )
            );


        if (
            !is_array(
                $weather
            )
        ) {

            return null;

        }


        $weather['stale'] =
            true;


        return $weather;
    }


    /*
    |--------------------------------------------------------------------------
    | CACHE KEYS
    |--------------------------------------------------------------------------
    */

    private function freshCacheKey(
        Mountain $mountain,
        float $latitude,
        float $longitude
    ): string {

        return sprintf(
            'balihiking_weather_fresh_%s_%s_%s',
            $mountain->id,
            round(
                $latitude,
                5
            ),
            round(
                $longitude,
                5
            )
        );
    }


    private function staleCacheKey(
        Mountain $mountain
    ): string {

        return 'balihiking_weather_stale_'
            .
            $mountain->id;
    }


    private function geocodingCacheKey(
        Mountain $mountain
    ): string {

        return 'balihiking_weather_geo_'
            .
            $mountain->id;
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    private function number(
        mixed $value,
        int $precision = 0
    ): int|float|null {

        if (
            !is_numeric(
                $value
            )
        ) {

            return null;

        }


        return round(
            (float)
            $value,
            $precision
        );
    }


    private function integer(
        mixed $value
    ): ?int {

        if (
            !is_numeric(
                $value
            )
        ) {

            return null;

        }


        return (int)
        round(
            (float)
            $value
        );
    }


    private function formatTime(
        mixed $value
    ): ?string {

        if (!$value) {

            return null;

        }


        try {

            return Carbon::parse(
                $value,
                self::TIMEZONE
            )
            ->format(
                'H:i'
            );


        } catch (\Throwable) {

            return null;

        }
    }
}