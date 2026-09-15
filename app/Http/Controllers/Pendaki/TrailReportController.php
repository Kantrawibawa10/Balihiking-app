<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\HikingTrail;
use App\Models\TrailReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TrailReportController extends Controller
{
    /**
     * Menyimpan feedback kondisi jalur.
     */
    public function store(
        Request $request,
        HikingTrail $trail
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | TRAIL HARUS AKTIF
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $trail->is_active,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'status' => [
                    'required',
                    'string',

                    Rule::in([
                        'aman',
                        'licin',
                        'berlumpur',
                        'longsor',
                        'pohon_tumbang',
                        'jalur_tertutup',
                        'jembatan_rusak',
                        'lainnya',
                    ]),
                ],

                'condition_note' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'status.required' =>
                    'Silakan pilih kondisi jalur.',

                'status.in' =>
                    'Kondisi jalur yang dipilih tidak valid.',

                'condition_note.required' =>
                    'Silakan masukkan keterangan kondisi jalur.',

                'condition_note.min' =>
                    'Keterangan kondisi jalur minimal 5 karakter.',

                'condition_note.max' =>
                    'Keterangan kondisi jalur maksimal 1000 karakter.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | USER LOGIN
        |--------------------------------------------------------------------------
        */

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | SAVE REPORT
        |--------------------------------------------------------------------------
        */

        TrailReport::create([
            'hiking_trail_id' =>
                $trail->id,

            'user_id' =>
                $user->id,

            'status' =>
                $validated['status'],

            'condition_note' =>
                trim(
                    $validated['condition_note']
                ),

            'report_date' =>
                now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'pendaki.trail.show',
                $trail
            )
            ->with(
                'success',
                'Terima kasih. Laporan kondisi jalur berhasil dikirim.'
            );
    }
}