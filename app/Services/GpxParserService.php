<?php

namespace App\Services;

use SimpleXMLElement;
use Illuminate\Support\Facades\Storage;

class GpxParserService
{
    // Method untuk membaca dari path file storage
    public static function parse(string $filePath): array
    {
        $content = Storage::disk('public')->get($filePath);
        return self::parseFromContent($content);
    }

    // Method untuk membaca langsung dari string/content GPX
    public static function parseFromContent(string $content): array
    {
        $xml = new SimpleXMLElement($content);

        $name = (string) ($xml->trk->name ?? $xml->name ?? 'Hiking Trail');
        $coordinates = [];
        $elevations = [];

        foreach ($xml->trk->trkseg->trkpt as $pt) {
            $lat = (float) $pt['lat'];
            $lon = (float) $pt['lon'];
            $ele = isset($pt->ele) ? (float) $pt->ele : null;

            $coordinates[] = ['lat' => $lat, 'lng' => $lon, 'ele' => $ele];
            if ($ele !== null) {
                $elevations[] = $ele;
            }
        }

        $totalDistance = 0;
        for ($i = 0; $i < count($coordinates) - 1; $i++) {
            $totalDistance += self::calculateDistance(
                $coordinates[$i]['lat'], $coordinates[$i]['lng'],
                $coordinates[$i+1]['lat'], $coordinates[$i+1]['lng']
            );
        }

        return [
            'name' => $name,
            'coordinates' => $coordinates,
            'distance_km' => round($totalDistance, 2),
            'max_elevation' => !empty($elevations) ? (int) max($elevations) : null,
            'min_elevation' => !empty($elevations) ? (int) min($elevations) : null,
        ];
    }

    private static function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        return $earthRadius * (2 * atan2(sqrt($a), sqrt(1 - $a)));
    }
}