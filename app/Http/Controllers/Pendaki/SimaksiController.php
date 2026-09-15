<?php

namespace App\Http\Controllers\Pendaki;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SimaksiController extends Controller
{
    public function index()
    {
        return view('pendaki.simaksi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gunung' => 'required',
            'tanggal_naik' => 'required|date',
            'tanggal_turun' => 'required|date|after_or_equal:tanggal_naik',
            'jumlah_anggota' => 'required|numeric|min:1',
            'nomor_darurat' => 'required',
        ]);

        // Simpan data simaksi ke database di sini...

        return back()->with('success', 'Pendaftaran SIMAKSI berhasil diajukan! Menunggu verifikasi petugas.');
    }
}