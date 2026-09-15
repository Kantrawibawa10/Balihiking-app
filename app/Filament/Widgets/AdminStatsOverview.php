<?php

namespace App\Filament\Widgets;

use App\Models\HikingTrail;
use App\Models\Mountain;
use App\Models\TrailReport;
use App\Models\UserLocation;
use App\Models\UserRoute;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    /**
     * Urutan widget pada dashboard.
     */
    protected static ?int $sort = 1;

    /**
     * Widget memenuhi lebar dashboard.
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Interval refresh.
     *
     * Karena ada data live tracking dan SOS,
     * widget diperbarui otomatis.
     */
    protected static ?string $pollingInterval = '15s';

    /**
     * Statistik dashboard admin.
     */
    protected function getStats(): array
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL GUNUNG
        |--------------------------------------------------------------------------
        */

        $totalMountains = Mountain::query()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL JALUR AKTIF
        |--------------------------------------------------------------------------
        */

        $activeTrails = HikingTrail::query()
            ->where('is_active', true)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PENDAKI AKTIF
        |--------------------------------------------------------------------------
        |
        | User dianggap aktif jika:
        |
        | - memiliki sesi user_routes yang belum completed
        | - dan/atau mengirim lokasi dalam 2 menit terakhir.
        |
        */

        $activeHikers = UserLocation::query()
            ->where(
                'recorded_at',
                '>=',
                now()->subMinutes(2)
            )
            ->whereIn(
                'status',
                [
                    'tracking',
                    'off_route',
                    'gps_low_accuracy',
                ]
            )
            ->distinct('user_id')
            ->count('user_id');

        /*
        |--------------------------------------------------------------------------
        | SESI PENDAKIAN BERLANGSUNG
        |--------------------------------------------------------------------------
        */

        $ongoingRoutes = UserRoute::query()
            ->whereNull('completed_at')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | LAPORAN KONDISI TERBARU
        |--------------------------------------------------------------------------
        */

        $todayReports = TrailReport::query()
            ->whereDate(
                'report_date',
                today()
            )
            ->count();

        /*
        |--------------------------------------------------------------------------
        | SOS AKTIF
        |--------------------------------------------------------------------------
        |
        | Karena tabel user_locations sekarang menyimpan
        | status=sos, kita hitung SOS terbaru.
        |
        | Untuk sementara SOS 24 jam terakhir dianggap
        | perlu perhatian admin.
        |
        */

        $activeSos = UserLocation::query()
            ->where('status', 'sos')
            ->where(
                'recorded_at',
                '>=',
                now()->subHours(24)
            )
            ->distinct('user_id')
            ->count('user_id');

        /*
        |--------------------------------------------------------------------------
        | RETURN STATS
        |--------------------------------------------------------------------------
        */

        return [
            Stat::make(
                'Total Gunung',
                number_format($totalMountains)
            )
                ->description(
                    'Data gunung pada sistem'
                )
                ->descriptionIcon(
                    'heroicon-m-map'
                )
                ->color('primary'),

            Stat::make(
                'Jalur Aktif',
                number_format($activeTrails)
            )
                ->description(
                    'Jalur pendakian yang tersedia'
                )
                ->descriptionIcon(
                    'heroicon-m-map-pin'
                )
                ->color('success'),

            Stat::make(
                'Pendaki Aktif',
                number_format($activeHikers)
            )
                ->description(
                    $ongoingRoutes . ' sesi pendakian berlangsung'
                )
                ->descriptionIcon(
                    'heroicon-m-signal'
                )
                ->color(
                    $activeHikers > 0
                        ? 'success'
                        : 'gray'
                ),

            Stat::make(
                'Laporan Hari Ini',
                number_format($todayReports)
            )
                ->description(
                    'Feedback kondisi jalur'
                )
                ->descriptionIcon(
                    'heroicon-m-chat-bubble-left-right'
                )
                ->color(
                    $todayReports > 0
                        ? 'warning'
                        : 'gray'
                ),

            Stat::make(
                'SOS',
                number_format($activeSos)
            )
                ->description(
                    $activeSos > 0
                        ? 'Membutuhkan perhatian'
                        : 'Tidak ada SOS aktif'
                )
                ->descriptionIcon(
                    'heroicon-m-exclamation-triangle'
                )
                ->color(
                    $activeSos > 0
                        ? 'danger'
                        : 'success'
                ),
        ];
    }
}