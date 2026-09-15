<?php

namespace App\Http\Controllers;

use App\Models\HikingTrail;
use Illuminate\View\View;

class TrailDetailController extends Controller
{
    public function show($id): View
    {
        // Load jalur pendakian beserta relasi gunung dan checkpoints-nya
        $trail = HikingTrail::with(['mountain', 'checkpoints'])->findOrFail($id);

        return view('trails.show', compact('trail'));
    }
}
