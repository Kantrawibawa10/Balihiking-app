<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;
use App\Models\Simaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SimaksiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {
        $user =
            $request->user();


        $simaksis =
            Simaksi::query()
                ->where(
                    'user_id',
                    $user->id
                )
                ->with(
                    'mountain'
                )
                ->latest()
                ->get();


        $mountains =
            Mountain::query()
                ->orderBy(
                    'name'
                )
                ->get();


        return view(
            'pendaki.simaksi',
            compact(
                'simaksis',
                'mountains'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $validated =
            $request->validate([

                'mountain_id' => [
                    'nullable',
                    'integer',
                    'exists:mountains,id',
                ],

                'gunung' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'tanggal_naik' => [
                    'required',
                    'date',
                ],

                'tanggal_turun' => [
                    'required',
                    'date',
                    'after_or_equal:tanggal_naik',
                ],

                'jumlah_anggota' => [
                    'required',
                    'integer',
                    'min:1',
                    'max:100',
                ],

                'nomor_darurat' => [
                    'required',
                    'string',
                    'max:30',
                ],

            ]);


        /*
        |--------------------------------------------------------------------------
        | RESOLVE MOUNTAIN
        |--------------------------------------------------------------------------
        */

        $mountain =
            null;


        if (
            ! empty(
                $validated[
                    'mountain_id'
                ]
            )
        ) {
            $mountain =
                Mountain::query()
                    ->find(
                        $validated[
                            'mountain_id'
                        ]
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | COMPATIBILITY FRONTEND LAMA
        |--------------------------------------------------------------------------
        |
        | Frontend lama mengirim:
        |
        | gunung = "Gunung Agung"
        |--------------------------------------------------------------------------
        */

        if (
            ! $mountain
            &&
            ! empty(
                $validated[
                    'gunung'
                ]
            )
        ) {
            $mountain =
                Mountain::query()
                    ->where(
                        'name',
                        $validated[
                            'gunung'
                        ]
                    )
                    ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE CODE
        |--------------------------------------------------------------------------
        */

        do {
            $code =
                'SMK-'
                .
                now()->format(
                    'Ymd'
                )
                .
                '-'
                .
                strtoupper(
                    Str::random(
                        6
                    )
                );
        } while (
            Simaksi::query()
                ->where(
                    'code',
                    $code
                )
                ->exists()
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE SIMAKSI
        |--------------------------------------------------------------------------
        */

        Simaksi::create([

            'code' =>
                $code,

            'user_id' =>
                $request
                    ->user()
                    ->id,

            'mountain_id' =>
                $mountain?->id,

            'gunung' =>
                $mountain?->name
                ??
                (
                    $validated[
                        'gunung'
                    ]
                    ??
                    null
                ),

            'tanggal_naik' =>
                $validated[
                    'tanggal_naik'
                ],

            'tanggal_turun' =>
                $validated[
                    'tanggal_turun'
                ],

            'jumlah_anggota' =>
                $validated[
                    'jumlah_anggota'
                ],

            'nomor_darurat' =>
                $validated[
                    'nomor_darurat'
                ],

            'status' =>
                'pending',

        ]);


        return redirect()
            ->route(
                'pendaki.simaksi'
            )
            ->with(
                'success',
                'Permohonan SIMAKSI berhasil dikirim dan sedang menunggu persetujuan.'
            );
    }
}