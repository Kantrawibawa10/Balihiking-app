<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\UserRoute;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RiwayatController extends Controller
{
    /**
     * Menampilkan riwayat pendakian
     * milik user yang sedang login.
     */
    public function index(Request $request): View
    {
        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        |
        | all
        | completed
        | ongoing
        |
        */

        $status = $request->query(
            'status',
            'all'
        );

        /*
        |--------------------------------------------------------------------------
        | RIWAYAT PENDAKIAN
        |--------------------------------------------------------------------------
        */

        $riwayat = UserRoute::query()

            /*
             * Hanya riwayat milik user login.
             */
            ->where(
                'user_id',
                $user->id
            )

            /*
             * Ambil jalur + gunung.
             */
            ->with([
                'hikingTrail' => function ($query) {
                    $query->select([
                        'id',
                        'mountain_id',
                        'name',
                        'difficulty',
                        'distance_km',
                        'estimated_time_hours',
                    ]);
                },

                'hikingTrail.mountain' => function ($query) {
                    $query->select([
                        'id',
                        'name',
                        'location',
                        'elevation_m',
                        'cover_image',
                    ]);
                },
            ])

            /*
             * Filter selesai.
             */
            ->when(
                $status === 'completed',
                function ($query) {
                    $query->whereNotNull(
                        'completed_at'
                    );
                }
            )

            /*
             * Filter berlangsung.
             */
            ->when(
                $status === 'ongoing',
                function ($query) {
                    $query->whereNull(
                        'completed_at'
                    );
                }
            )

            /*
             * Terbaru paling atas.
             */
            ->latest('created_at')

            /*
             * Pagination.
             */
            ->paginate(10)

            /*
             * Pertahankan query filter.
             */
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */

        $totalRiwayat = UserRoute::query()
            ->where(
                'user_id',
                $user->id
            )
            ->count();


        $totalSelesai = UserRoute::query()
            ->where(
                'user_id',
                $user->id
            )
            ->whereNotNull(
                'completed_at'
            )
            ->count();


        $totalBerlangsung = UserRoute::query()
            ->where(
                'user_id',
                $user->id
            )
            ->whereNull(
                'completed_at'
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.riwayat',
            compact(
                'user',
                'riwayat',
                'status',
                'totalRiwayat',
                'totalSelesai',
                'totalBerlangsung'
            )
        );
    }
}