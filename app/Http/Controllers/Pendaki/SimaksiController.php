<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\Mountain;
use App\Models\Simaksi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class SimaksiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    private const STATUS_PENDING = [
        'pending',
    ];

    private const STATUS_APPROVED = [
        'approved',
        'disetujui',
        'accepted',
    ];

    private const STATUS_REJECTED = [
        'rejected',
        'ditolak',
        'declined',
    ];

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

        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        $status =
            strtolower(
                trim(
                    (string) $request->query(
                        'status',
                        'all'
                    )
                )
            );

        $allowedStatuses = [
            'all',
            'pending',
            'approved',
            'rejected',
        ];

        if (
            ! in_array(
                $status,
                $allowedStatuses,
                true
            )
        ) {
            $status =
                'all';
        }

        /*
        |--------------------------------------------------------------------------
        | BASE QUERY
        |--------------------------------------------------------------------------
        */

        $baseQuery =
            Simaksi::query()
                ->where(
                    'user_id',
                    $user->id
                );

        /*
        |--------------------------------------------------------------------------
        | STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalSimaksi =
            (clone $baseQuery)
                ->count();

        $totalPending =
            (clone $baseQuery)
                ->whereIn(
                    'status',
                    self::STATUS_PENDING
                )
                ->count();

        $totalApproved =
            (clone $baseQuery)
                ->whereIn(
                    'status',
                    self::STATUS_APPROVED
                )
                ->count();

        $totalRejected =
            (clone $baseQuery)
                ->whereIn(
                    'status',
                    self::STATUS_REJECTED
                )
                ->count();

        $totalDitolak =
            $totalRejected;

        /*
        |--------------------------------------------------------------------------
        | SIMAKSI QUERY
        |--------------------------------------------------------------------------
        */

        $simaksiQuery =
            Simaksi::query()
                ->where(
                    'user_id',
                    $user->id
                );

        /*
        |--------------------------------------------------------------------------
        | LOAD MOUNTAIN RELATION ONLY IF COLUMN EXISTS
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn(
                'simaksis',
                'mountain_id'
            )
        ) {
            $simaksiQuery
                ->with([
                    'mountain',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | APPLY FILTER
        |--------------------------------------------------------------------------
        */

        $this->applyStatusFilter(
            $simaksiQuery,
            $status
        );

        /*
        |--------------------------------------------------------------------------
        | PAGINATION
        |--------------------------------------------------------------------------
        */

        $simaksis =
            $simaksiQuery
                ->latest()
                ->paginate(
                    10
                )
                ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | MOUNTAINS
        |--------------------------------------------------------------------------
        */

        $mountains =
            Mountain::query()
                ->orderBy(
                    'name'
                )
                ->get();

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'pendaki.simaksi',
            [
                'simaksis' =>
                    $simaksis,

                'mountains' =>
                    $mountains,

                'status' =>
                    $status,

                'totalSimaksi' =>
                    $totalSimaksi,

                'totalPending' =>
                    $totalPending,

                'totalApproved' =>
                    $totalApproved,

                'totalRejected' =>
                    $totalRejected,

                'totalDitolak' =>
                    $totalDitolak,
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse|JsonResponse {
        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

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
                    'required_without:mountain_id',
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

        try {
            /*
            |--------------------------------------------------------------------------
            | CURRENT USER
            |--------------------------------------------------------------------------
            */

            $user =
                $request->user();

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
            | LEGACY FRONTEND
            |--------------------------------------------------------------------------
            |
            | Frontend sekarang masih mengirim:
            |
            | gunung = "Gunung Agung"
            |
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
            | MOUNTAIN NAME
            |--------------------------------------------------------------------------
            */

            $mountainName =
                $mountain?->name
                ??
                (
                    $validated[
                        'gunung'
                    ]
                    ??
                    null
                );

            /*
            |--------------------------------------------------------------------------
            | NEW SIMAKSI
            |--------------------------------------------------------------------------
            |
            | Sengaja TIDAK menggunakan:
            |
            | Simaksi::create([...])
            |
            | agar tidak terkena MassAssignmentException.
            |
            */

            $simaksi =
                new Simaksi();

            /*
            |--------------------------------------------------------------------------
            | REQUIRED / LEGACY COLUMNS
            |--------------------------------------------------------------------------
            */

            $simaksi->user_id =
                $user->id;

            $simaksi->gunung =
                $mountainName;

            $simaksi->tanggal_naik =
                $validated[
                    'tanggal_naik'
                ];

            $simaksi->tanggal_turun =
                $validated[
                    'tanggal_turun'
                ];

            $simaksi->jumlah_anggota =
                $validated[
                    'jumlah_anggota'
                ];

            $simaksi->nomor_darurat =
                $validated[
                    'nomor_darurat'
                ];

            $simaksi->status =
                'pending';

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL: MOUNTAIN ID
            |--------------------------------------------------------------------------
            |
            | Hanya set jika kolom memang ada.
            |
            */

            if (
                Schema::hasColumn(
                    'simaksis',
                    'mountain_id'
                )
            ) {
                $simaksi->mountain_id =
                    $mountain?->id;
            }

            /*
            |--------------------------------------------------------------------------
            | OPTIONAL: CODE
            |--------------------------------------------------------------------------
            |
            | Hanya generate jika tabel memang memiliki kolom code.
            |
            */

            if (
                Schema::hasColumn(
                    'simaksis',
                    'code'
                )
            ) {
                $simaksi->code =
                    $this->generateUniqueCode();
            }

            /*
            |--------------------------------------------------------------------------
            | SAVE
            |--------------------------------------------------------------------------
            */

            $simaksi->save();

            /*
            |--------------------------------------------------------------------------
            | AJAX RESPONSE
            |--------------------------------------------------------------------------
            */

            if (
                $request->expectsJson()
                ||
                $request->ajax()
            ) {
                return response()->json([
                    'success' =>
                        true,

                    'message' =>
                        'Permohonan SIMAKSI berhasil dikirim dan sedang menunggu persetujuan.',

                    'data' => [
                        'id' =>
                            $simaksi->id,

                        'status' =>
                            $simaksi->status,

                        'gunung' =>
                            $simaksi->gunung,

                        'tanggal_naik' =>
                            $simaksi->tanggal_naik,

                        'tanggal_turun' =>
                            $simaksi->tanggal_turun,
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL RESPONSE
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route(
                    'pendaki.simaksi'
                )
                ->with(
                    'success',
                    'Permohonan SIMAKSI berhasil dikirim dan sedang menunggu persetujuan.'
                );

        } catch (Throwable $exception) {
            /*
            |--------------------------------------------------------------------------
            | LOG REAL ERROR
            |--------------------------------------------------------------------------
            */

            Log::error(
                'SIMAKSI STORE ERROR',
                [
                    'user_id' =>
                        $request->user()?->id,

                    'message' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),

                    'trace' =>
                        $exception->getTraceAsString(),

                    'payload' =>
                        $request->except([
                            '_token',
                        ]),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | AJAX ERROR RESPONSE
            |--------------------------------------------------------------------------
            */

            if (
                $request->expectsJson()
                ||
                $request->ajax()
            ) {
                $response = [
                    'success' =>
                        false,

                    'message' =>
                        'Permohonan SIMAKSI gagal disimpan.',
                ];

                /*
                |--------------------------------------------------------------------------
                | LOCAL DEBUG
                |--------------------------------------------------------------------------
                |
                | Saat APP_DEBUG=true, frontend akan langsung menampilkan
                | error Laravel sebenarnya.
                |
                */

                if (
                    config(
                        'app.debug'
                    )
                ) {
                    $response[
                        'debug'
                    ] = [
                        'message' =>
                            $exception->getMessage(),

                        'file' =>
                            $exception->getFile(),

                        'line' =>
                            $exception->getLine(),
                    ];
                }

                return response()->json(
                    $response,
                    500
                );
            }

            /*
            |--------------------------------------------------------------------------
            | NORMAL RESPONSE
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'simaksi' =>
                        'Permohonan SIMAKSI gagal disimpan.',
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | GENERATE UNIQUE CODE
    |--------------------------------------------------------------------------
    */

    private function generateUniqueCode(): string
    {
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

            $exists =
                Simaksi::query()
                    ->where(
                        'code',
                        $code
                    )
                    ->exists();

        } while (
            $exists
        );

        return $code;
    }

    /*
    |--------------------------------------------------------------------------
    | APPLY STATUS FILTER
    |--------------------------------------------------------------------------
    */

    private function applyStatusFilter(
        Builder $query,
        string $status
    ): void {
        switch (
            $status
        ) {
            case 'pending':

                $query->whereIn(
                    'status',
                    self::STATUS_PENDING
                );

                break;

            case 'approved':

                $query->whereIn(
                    'status',
                    self::STATUS_APPROVED
                );

                break;

            case 'rejected':

                $query->whereIn(
                    'status',
                    self::STATUS_REJECTED
                );

                break;

            case 'all':

            default:

                break;
        }
    }
}