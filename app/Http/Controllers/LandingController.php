<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HikingTrail;

class LandingController extends Controller
{
    public function index()
    {
        // Ambil data jalur pendakian jika ada untuk ditampilkan di landing page
        $trails = HikingTrail::where('is_active', true)->get();

        return view('landing', compact('trails'));
    }
}
