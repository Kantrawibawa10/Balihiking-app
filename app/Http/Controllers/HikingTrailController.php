<?php

namespace App\Http\Controllers;

use App\Models\HikingTrail;
use Illuminate\Http\Request;

class HikingTrailController extends Controller
{
    public function show($id)
    {
        $trail = HikingTrail::with('checkpoints')->findOrFail($id);

        return view('trails.show', compact('trail'));
    }
}
