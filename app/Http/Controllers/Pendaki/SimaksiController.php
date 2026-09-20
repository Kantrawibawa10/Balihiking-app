<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Simaksi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

        $status =
            $request->string('status')
                ->toString();

        if (
            !in_array(
                $status,
                [
                    'all',
                    'pending',
                    'approved',
                    'rejected',
                ],
                true
            )
        ) {

            $status = 'all';

        }


        $query = Simaksi::query()
            ->where(
                'user_id',
                $request->user()->id
            );


        /*
        |--------------------------------------------------------------------------
        | FILTER STATUS
        |--------------------------------------------------------------------------
        */

        if (
            $status !== 'all'
        ) {

            $query->where(
                'status',
                $status
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $simaksis = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | SUMMARY
        |--------------------------------------------------------------------------
        */

        $totalSimaksi = Simaksi::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->count();


        $totalPending = Simaksi::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'status',
                'pending'
            )
            ->count();


        $totalApproved = Simaksi::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'status',
                'approved'
            )
            ->count();


        $totalRejected = Simaksi::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'status',
                'rejected'
            )
            ->count();


        return view(
            'pendaki.simaksi',
            compact(
                'simaksis',
                'status',
                'totalSimaksi',
                'totalPending',
                'totalApproved',
                'totalRejected',
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
    ): JsonResponse|RedirectResponse {

        $validated = $request->validate(
            [
                'gunung' => [
                    'required',
                    'string',
                    'max:150',

                    Rule::in([
                        'Gunung Agung',
                        'Gunung Batur',
                        'Gunung Abang',
                    ]),
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
                    'regex:/^[0-9+\-\s]+$/',
                ],
            ],
            [
                'gunung.required' =>
                    'Gunung wajib dipilih.',

                'gunung.in' =>
                    'Gunung yang dipilih tidak valid.',

                'tanggal_naik.required' =>
                    'Tanggal naik wajib dipilih.',

                'tanggal_turun.required' =>
                    'Tanggal turun wajib dipilih.',

                'tanggal_turun.after_or_equal' =>
                    'Tanggal turun tidak boleh lebih awal dari tanggal naik.',

                'jumlah_anggota.required' =>
                    'Jumlah anggota wajib diisi.',

                'jumlah_anggota.min' =>
                    'Jumlah anggota minimal 1 orang.',

                'jumlah_anggota.max' =>
                    'Jumlah anggota maksimal 100 orang.',

                'nomor_darurat.required' =>
                    'Nomor darurat wajib diisi.',

                'nomor_darurat.regex' =>
                    'Format nomor darurat tidak valid.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | OPTIONAL DUPLICATE CHECK
        |--------------------------------------------------------------------------
        */

        $duplicate = Simaksi::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->where(
                'gunung',
                $validated['gunung']
            )
            ->whereDate(
                'tanggal_naik',
                $validated['tanggal_naik']
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'approved'
                ]
            )
            ->exists();


        if (
            $duplicate
        ) {

            if (
                $request->expectsJson()
            ) {

                return response()->json(
                    [
                        'message' =>
                            'Anda sudah memiliki permohonan SIMAKSI untuk gunung dan tanggal tersebut.',
                    ],
                    422
                );

            }


            return back()
                ->withInput()
                ->withErrors([
                    'gunung' =>
                        'Permohonan SIMAKSI untuk gunung dan tanggal tersebut sudah ada.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        $simaksi = Simaksi::create([
            'user_id' =>
                $request->user()->id,

            'gunung' =>
                $validated['gunung'],

            'tanggal_naik' =>
                $validated['tanggal_naik'],

            'tanggal_turun' =>
                $validated['tanggal_turun'],

            'jumlah_anggota' =>
                $validated['jumlah_anggota'],

            'nomor_darurat' =>
                $validated['nomor_darurat'],

            'status' =>
                'pending',
        ]);


        /*
        |--------------------------------------------------------------------------
        | AJAX / OFFLINE SYNC
        |--------------------------------------------------------------------------
        */

        if (
            $request->expectsJson()
        ) {

            return response()->json(
                [
                    'success' =>
                        true,

                    'message' =>
                        'Permohonan SIMAKSI berhasil dikirim.',

                    'data' => [
                        'id' =>
                            $simaksi->id,

                        'status' =>
                            $simaksi->status,

                        'status_label' =>
                            $simaksi->status_label,
                    ],
                ],
                201
            );

        }


        return redirect()
            ->route(
                'pendaki.simaksi'
            )
            ->with(
                'success',
                'Permohonan SIMAKSI berhasil dikirim dan sedang menunggu verifikasi.'
            );
    }
}