<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\UserLocation;
use Illuminate\Support\Facades\DB;

class LiveTracking extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-signal';
    protected static ?string $navigationLabel = 'Live Tracking Pendaki';
    protected static ?string $navigationGroup = 'Manajemen Pendakian';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.live-tracking';

    // Helper method statis untuk mengambil data JSON lokasi
    public static function getLocationsData(): array
    {
        return UserLocation::with(['user', 'hikingTrail'])
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('user_locations')
                    ->groupBy('user_id');
            })
            ->where('recorded_at', '>=', now()->subHours(12))
            ->get()
            ->map(function ($loc) {
                return [
                    'id' => $loc->id,
                    'user_name' => $loc->user->name ?? 'Pendaki Anonim',
                    'trail_name' => $loc->hikingTrail->name ?? 'Jalur Tidak Terdaftar',
                    'lat' => (float) $loc->latitude,
                    'lng' => (float) $loc->longitude,
                    'altitude' => $loc->altitude_m ?? '-',
                    'battery' => $loc->battery_level ?? '-',
                    'status' => $loc->status ?? 'normal',
                    'updated_ago' => $loc->recorded_at ? $loc->recorded_at->diffForHumans() : '-',
                ];
            })
            ->toArray();
    }
}