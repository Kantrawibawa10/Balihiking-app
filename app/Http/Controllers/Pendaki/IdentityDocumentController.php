<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use App\Models\IdentityDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IdentityDocumentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ): View {

        $documents = IdentityDocument::query()
            ->where(
                'user_id',
                $request->user()->id
            )
            ->latest()
            ->get()
            ->keyBy(
                'document_type'
            );


        return view(
            'pendaki.profile.documents',
            [
                'user' =>
                    $request->user(),

                'documents' =>
                    $documents,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store / Replace Document
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {

        $validated = $request->validate([
            'document_type' => [
                'required',
                'in:ktp,sim',
            ],

            'document_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'document_file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ], [
            'document_type.required' =>
                'Jenis dokumen wajib dipilih.',

            'document_file.required' =>
                'File dokumen wajib dipilih.',

            'document_file.mimes' =>
                'Dokumen harus berupa JPG, JPEG, PNG, atau PDF.',

            'document_file.max' =>
                'Ukuran dokumen maksimal 5 MB.',
        ]);


        $user =
            $request->user();


        $file =
            $request->file(
                'document_file'
            );


        /*
        |--------------------------------------------------------------------------
        | Existing
        |--------------------------------------------------------------------------
        */

        $existing = IdentityDocument::query()
            ->where(
                'user_id',
                $user->id
            )
            ->where(
                'document_type',
                $validated['document_type']
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | File name random
        |--------------------------------------------------------------------------
        */

        $extension =
            strtolower(
                $file->getClientOriginalExtension()
            );


        $filename =
            Str::uuid()
            . '.'
            . $extension;


        $folder =
            'identity-documents/'
            . $user->id;


        /*
        |--------------------------------------------------------------------------
        | Simpan PRIVATE
        |--------------------------------------------------------------------------
        */

        $path = $file->storeAs(
            $folder,
            $filename,
            'local'
        );


        if (!$path) {

            return back()
                ->withErrors([
                    'document_file' =>
                        'Dokumen gagal disimpan.',
                ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Hapus file lama setelah file baru berhasil ditulis
        |--------------------------------------------------------------------------
        */

        if (
            $existing
            &&
            $existing->file_path
            &&
            Storage::disk('local')
                ->exists(
                    $existing->file_path
                )
        ) {

            Storage::disk('local')
                ->delete(
                    $existing->file_path
                );

        }


        IdentityDocument::updateOrCreate(
            [
                'user_id' =>
                    $user->id,

                'document_type' =>
                    $validated['document_type'],
            ],
            [
                'document_number' =>
                    $validated['document_number']
                    ?? null,

                'file_path' =>
                    $path,

                'original_name' =>
                    $file->getClientOriginalName(),

                'mime_type' =>
                    $file->getMimeType(),

                'file_size' =>
                    $file->getSize(),

                /*
                |--------------------------------------------------------------------------
                | Upload ulang harus diverifikasi lagi
                |--------------------------------------------------------------------------
                */

                'status' =>
                    'pending',

                'verification_note' =>
                    null,

                'verified_at' =>
                    null,

                'verified_by' =>
                    null,
            ]
        );


        return back()->with(
            'success',
            strtoupper(
                $validated['document_type']
            )
            .
            ' berhasil disimpan dan menunggu verifikasi.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Authorized View
    |--------------------------------------------------------------------------
    */

    public function view(
        Request $request,
        IdentityDocument $identityDocument
    ): BinaryFileResponse {

        abort_unless(
            $identityDocument->user_id ===
            $request->user()->id,
            403
        );


        abort_unless(
            Storage::disk('local')
                ->exists(
                    $identityDocument->file_path
                ),
            404
        );


        return response()->file(
            Storage::disk('local')
                ->path(
                    $identityDocument->file_path
                ),
            [
                'Content-Type' =>
                    $identityDocument->mime_type
                    ??
                    'application/octet-stream',

                'Cache-Control' =>
                    'private, no-store, max-age=0',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        IdentityDocument $identityDocument
    ): RedirectResponse {

        abort_unless(
            $identityDocument->user_id ===
            $request->user()->id,
            403
        );


        if (
            Storage::disk('local')
                ->exists(
                    $identityDocument->file_path
                )
        ) {

            Storage::disk('local')
                ->delete(
                    $identityDocument->file_path
                );

        }


        $identityDocument->delete();


        return back()->with(
            'success',
            'Dokumen identitas berhasil dihapus.'
        );
    }
}