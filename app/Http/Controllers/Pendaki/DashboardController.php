<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Pendaki.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        |
        | Bisa mencari:
        |
        | - nama gunung
        | - lokasi gunung
        | - nama jalur
        |
        */

        $search = trim(
            (string) $request->query(
                'search',
                ''
            )
        );

        /*
        |--------------------------------------------------------------------------
        | DATA GUNUNG
        |--------------------------------------------------------------------------
        */

        $mountains = Mountain::query()

            /*
             * Hitung jumlah jalur aktif.
             */
            ->withCount([
                'hikingTrails as active_trails_count' => function ($query) {

                    $query->where(
                        'is_active',
                        true
                    );

                },
            ])

            /*
             * Ambil beberapa data jalur aktif.
             *
             * Nanti bisa digunakan untuk preview
             * jalur di dashboard.
             */
            ->with([
                'hikingTrails' => function ($query) {

                    $query
                        ->where(
                            'is_active',
                            true
                        )
                        ->select([
                            'id',
                            'mountain_id',
                            'name',
                            'difficulty',
                            'distance_km',
                            'estimated_time_hours',
                            'status',
                        ])
                        ->orderBy('name');

                },
            ])

            /*
             * Search.
             */
            ->when(
                $search !== '',
                function ($query) use ($search) {

                    $query->where(
                        function ($query) use ($search) {

                            /*
                             * Nama Gunung
                             */
                            $query->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            )

                            /*
                             * Lokasi
                             */
                            ->orWhere(
                                'location',
                                'like',
                                '%' . $search . '%'
                            )

                            /*
                             * Nama Jalur
                             */
                            ->orWhereHas(
                                'hikingTrails',
                                function ($trailQuery) use ($search) {

                                    $trailQuery->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    );

                                }
                            );

                        });

                }
            )

            /*
             * Urutan.
             */
            ->orderBy('name')

            /*
             * Dashboard tidak perlu terlalu panjang.
             */
            ->limit(6)

            ->get();

        /*
        |--------------------------------------------------------------------------
        | STATISTIK RINGKAS
        |--------------------------------------------------------------------------
        */

        $totalMountains = Mountain::count();

        $totalActiveTrails = Mountain::query()
            ->withCount([
                'hikingTrails as active_count' => function ($query) {

                    $query->where(
                        'is_active',
                        true
                    );

                },
            ])
            ->get()
            ->sum('active_count');

        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.dashboard',
            compact(
                'user',
                'mountains',
                'search',
                'totalMountains',
                'totalActiveTrails',
            )
        );
    }
}